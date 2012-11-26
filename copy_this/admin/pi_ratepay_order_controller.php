<?php

require 'includes/application_top.php';
require_once '../includes/modules/payment/ratepay_webservice/Ratepay_XML.php';
$language = $_SESSION['language'];
require_once 'includes/languages/'.$language.'/modules/payment/pi_ratepay.php';

require_once(DIR_FS_DOCUMENT_ROOT . 'includes/classes/order.php');

if (!empty($_POST['oID'])) {
    $shopOrderID = $_POST['oID'];
    $query = tep_db_query("select * from orders a, orders_total b where a.orders_id = '" . tep_db_input($shopOrderID) . "' and a.orders_id = b.orders_id and class = 'ot_total'");
    $order = tep_db_fetch_array($query);
    $oID = $_POST['oID'];
    $pi_order = new order($oID);
    $paymentType = $pi_order->info['payment_method'];
    if (!empty($_POST['stornieren'])) {
        $resultArr = cancelRequest($oID, $paymentType);
    } else if (!empty($_POST['versenden'])) {
        $resultArr = deliverRequest($oID, $paymentType);
    } else if (!empty($_POST['retournieren'])) {
        $resultArr = returnRequest($oID, $paymentType);
    } else if (!empty($_POST['gutschein'])) {
        $resultArr = voucherRequest($oID, $paymentType);
    }
    require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rechnung.php');
    $pi_ratepay = new pi_ratepay_rechnung();
    $file = 'pi_ratepay_admin_osc22';
    if ($pi_ratepay->is_osc231()) {
        $file = 'pi_ratepay_admin_osc231';
    }
    $url = "$file.php?oID=" . $oID . "&result=" . urlencode($resultArr['result']) . "&message=" . urlencode($resultArr['message']);

    header("Location: $url");
}

/**
 * This functions send the CONFIRMATION_DELIVER request to the RatePAY API
 * and saves all necessary informations in the DB
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function deliverRequest($oID, $paymentType) {
    $pi_order = new order($oID);
    $operation = 'CONFIRMATION_DELIVER';
    $subOperation = 'n/a';
    $pi_ratepay = null;
    if ($paymentType == "RatePAY Rechnung") {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rechnung.php');
        $pi_ratepay = new pi_ratepay_rechnung();
        $pi_table_prefix = 'pi_ratepay_rechnung';
        $pi_payment_type = 'INVOICE';
    } else {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rate.php');
        $pi_ratepay = new pi_ratepay_rate();
        $pi_table_prefix = 'pi_ratepay_rate';
        $pi_payment_type = 'INSTALLMENT';
    }

    $profileId = $pi_ratepay->profileId;
    $securityCode = $pi_ratepay->securityCode;
    $systemId = $_SERVER['SERVER_ADDR'];

    $query = tep_db_query("select transaction_id, transaction_short_id, first_name, last_name from " . $pi_table_prefix . "_orders where order_number = '" . tep_db_input($oID) . "'");
    $orderData = tep_db_fetch_array($query);

    $ratepay = new Ratepay_XML;
    $ratepay->live = $pi_ratepay->testOrLive();

    $request = $ratepay->getXMLObject();

    $head = $request->addChild('head');
    $head->addChild('system-id', $systemId);
    $head->addChild('transaction-id', $orderData['transaction_id']);
    $head->addChild('transaction-short-id', $orderData['transaction_short_id']);
    $head->addChild('operation', $operation);

    $credential = $head->addChild('credential');
    $credential->addChild('profile-id', $profileId);
    $credential->addChild('securitycode', $securityCode);

    $external = $head->addChild('external');
    $external->addChild('order-id', $oID);

    $content = $request->addChild('content');

    $content->addChild('shopping-basket');

    $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
    $query = tep_db_query($sql);
    $price = 0;
    while ($mItem = tep_db_fetch_array($query)) {
        $arr = str_split($mItem['article_number'], 8);
        if ($_POST[$mItem['article_number']] > 0) {
            if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                $zwischenPrice = $mItem['products_price'] * $pi_order->info['currency_value'];
                $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                $price = $price + (($zwischenPrice + $zwischenTax) * $_POST[$mItem['article_number']]);
            } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                $price = $price + ( ( $mItem['article_netUnitPrice'] * $_POST[$mItem['article_number']] ));
            } else if ($mItem['article_number'] == 'SHIPPING') {
                $price = $price + $mItem['article_netUnitPrice'] + $mItem['tax'];
            } else if ($arr[0] == 'DISCOUNT') {
                $price = $price + $mItem['article_netUnitPrice'] + $mItem['tax'];
            }
        }
    }

    $shoppingBasket = $content->{'shopping-basket'};
    $shoppingBasket->addAttribute('amount', number_format($price, 2, '.', ''));
    $shoppingBasket->addAttribute('currency', 'EUR');
    $items = $shoppingBasket->addChild('items');
    $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
    $query = tep_db_query($sql);
    $i = 0;
    while ($mItem = tep_db_fetch_array($query)) {
        $qty = ($mItem['ordered'] - $mItem['shipped'] - $mItem['cancelled']);
        if ($_POST[$mItem['article_number']] > 0) {
            $arr = str_split($mItem['article_number'], 8);
            if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                $items->addCDataChild('item', removeSpecialChars(utf8_encode($mItem['article_name'])));
                $items->item[$i]->addAttribute('article-number', $mItem['products_id']);
                $items->item[$i]->addAttribute('quantity', $_POST[$mItem['article_number']]);
                $zwischenPrice = $mItem['products_price'] * $pi_order->info['currency_value'];
                $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                $items->item[$i]->addAttribute('unit-price', number_format($zwischenPrice, 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format($_POST[$mItem['article_number']] * $zwischenPrice, 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($_POST[$mItem['article_number']] * $zwischenTax, 2, '.', ''));
            } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                $items->addChild('item', PI_RATEPAY_VOUCHER);
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $_POST[$mItem['article_number']]);
                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format(0, 2, '.', ''));
            } else if ($mItem['article_number'] == 'SHIPPING') {
                $shipping_method_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_shipping'");
                $shipping_method = tep_db_fetch_array($shipping_method_query);
                $shippingPrice = number_format($shipping_method['value'] * $pi_order->info['currency_value'],2,".","");
                $items->addChild('item', utf8_encode($mItem['article_name']));
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $_POST[$mItem['article_number']]);
                $items->item[$i]->addAttribute('unit-price', number_format($shippingPrice - $mItem['tax'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format(($_POST[$mItem['article_number']] * $shippingPrice - $mItem['tax']), 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($_POST[$mItem['article_number']] * ($mItem['tax']), 2, '.', ''));
            } else if ($arr[0] == 'DISCOUNT') {
                $items->addChild('item', utf8_encode($mItem['article_name']));
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $_POST[$mItem['article_number']]);
                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format(($_POST[$mItem['article_number']] * $mItem['article_netUnitPrice']), 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($_POST[$mItem['article_number']] * $mItem['tax'], 2, '.', ''));
            }
            $i++;
        }
    }
    $response = $ratepay->paymentOperation($request);

    $query = tep_db_query("select * from orders where orders_id = '" . tep_db_input($oID) . "'");
    $order = tep_db_fetch_array($query);
    $first_name = removeSpecialChars(utf8_encode($orderData['first_name']));
    $last_name = removeSpecialChars(utf8_encode($orderData['last_name']));
    if ($response) {
        $resultCode = (string) $response->head->processing->result->attributes()->code;
        $result = (string) $response->head->processing->result;
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, $response, $first_name, $last_name);
        if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "404") {
            $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
            $query = tep_db_query($sql);
            $i = 0;
            while ($mItem = tep_db_fetch_array($query)) {
                $qty = ($mItem['ordered'] - $mItem['shipped'] - $mItem['cancelled']);
                if ($_POST[$mItem['article_number']] > 0) {
                    $arr = str_split($mItem['article_number'], 8);
                    if ($arr[0] != 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set shipped = shipped + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_number = '" . tep_db_input($mItem['article_number']) . "'";
                        tep_db_query($sql);
                    } else if ($arr[0] == 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set shipped = shipped + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_name = '" . tep_db_input($mItem['article_name']) . "'";
                        tep_db_query($sql);
                    }
                    $sql = "insert into " . $pi_table_prefix . "_history (order_number, article_number, quantity, method, submethod) values ('" . tep_db_input($oID) . "', '" . tep_db_input($mItem['article_number']) . "', '" . tep_db_input($_POST[$mItem['article_number']]) . "', 'shipped', 'shipped')";
                    tep_db_query($sql);
                }
            }
            $message = PI_RATEPAY_SUCCESSDELIVERY;
            return array('result' => 'SUCCESS', 'message' => $message);
        } else {
            $message = PI_RATEPAY_ERRORDELIVERY;
            return array('result' => 'ERROR', 'message' => $message);
        }
    } else {
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, false, $first_name, $last_name);
        $message = PI_RATEPAY_SERVICE;
        return array('result' => 'ERROR', 'message' => $message);
    }
}

/**
 * This functions calls the fullCancel($oID) or the partCancel($oID) function
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function cancelRequest($oID, $paymentType) {
    if ($paymentType == "RatePAY Rechnung") {
        $pi_table_prefix = 'pi_ratepay_rechnung';
    } else {
        $pi_table_prefix = 'pi_ratepay_rate';
    }
    $sql = "select * from " . $pi_table_prefix . "_orderdetails where order_number = '" . tep_db_input($oID) . "'";
    $query = tep_db_query($sql);
    $flag = array();
    $i = 0;
    while ($item = tep_db_fetch_array($query)) {
        $qty = $item['ordered'] - $item['cancelled'] - $_POST[$item['article_number']];
        if ($qty == 0) {
            $flag[$i] = true;
        } else if ($qty > 0) {
            $flag[$i] = false;
        }
        $i++;
    }
    $full = true;
    for ($i = 0; $i < count($flag); $i++) {
        if ($flag[$i] == false) {
            $full = false;
        }
    }
    if ($full == true) {
        return fullCancel($oID, $paymentType);
    } else if ($full == false) {
        return partCancel($oID, $paymentType);
    }
}

/**
 * This functions send a PAYMENT_CHANGE request with the sub operation full-cancelation
 * to the RatePAY API and saves all necessary informations in the DB
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function fullCancel($oID, $paymentType) {
    $operation = 'PAYMENT_CHANGE';
    $subOperation = 'full-cancellation';
    if ($paymentType == "RatePAY Rechnung") {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rechnung.php');
        $pi_ratepay = new pi_ratepay_rechnung();
        $pi_table_prefix = 'pi_ratepay_rechnung';
        $pi_payment_type = 'INVOICE';
    } else {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rate.php');
        $pi_ratepay = new pi_ratepay_rate();
        $pi_table_prefix = 'pi_ratepay_rate';
        $pi_payment_type = 'INSTALLMENT';
    }
    $shopOrder = new order($oID);
    
    $profileId = $pi_ratepay->profileId;
    $securityCode = $pi_ratepay->securityCode;
    $systemId = $_SERVER['SERVER_ADDR'];

    $query = tep_db_query("select transaction_id, transaction_short_id, first_name, last_name, gender, dob, vat_id from " . $pi_table_prefix . "_orders where order_number = '" . tep_db_input($oID) . "'");
    $orderData = tep_db_fetch_array($query);
    
    $query = tep_db_query("select * from orders where orders_id = '" . tep_db_input($oID) . "'");
    $order = tep_db_fetch_array($query);
    $ratepay = new Ratepay_XML;
    $ratepay->live = $pi_ratepay->testOrLive();
    $request = $ratepay->getXMLObject();

    $request->addChild('head');
    $head = $request->{'head'};
    $head->addChild('system-id', $systemId);
    $head->addChild('transaction-id', $orderData['transaction_id']);
    $head->addChild('transaction-short-id', $orderData['transaction_short_id']);
    $operation = $head->addChild('operation', $operation);
    $operation->addAttribute('subtype', $subOperation);

    $credential = $head->addChild('credential');
    $credential->addChild('profile-id', $profileId);
    $credential->addChild('securitycode', $securityCode);

    $external = $head->addChild('external');
    $external->addChild('order-id', $oID);

    $content = $request->addChild('content');
    $content->addChild('customer');

    if (strtoupper($orderData['gender']) == "F") {
        $gender = "F";
    } else if (strtoupper($orderData['gender']) == "M") {
        $gender = "M";
    } else {
        $gender = "U";
    }

    $customer = $content->customer;
    $customer->addCDataChild('first-name', removeSpecialChars(utf8_encode($orderData['first_name'])));
    $customer->addCDataChild('last-name', removeSpecialChars(utf8_encode($orderData['last_name'])));
    $customer->addChild('gender', $gender);
    $customer->addChild('date-of-birth', (string) utf8_encode($orderData['dob']));
    if (!empty($shopOrder->customer['company'])) {
        $customer->addChild('company', utf8_encode($shopOrder->customer['company']));
        $customer->addChild('company', utf8_encode($orderData['vat_id']));
    }
    $customer->addChild('contacts');

    $contacts = $customer->contacts;
    $contacts->addChild('email', utf8_encode($shopOrder->customer['email_address']));
    $contacts->addChild('phone');

    $phone = $contacts->phone;
    $phone->addChild('direct-dial', utf8_encode($shopOrder->customer['telephone']));

    $customer->addChild('addresses');
    $addresses = $customer->addresses;
    $addresses->addChild('address');
    $addresses->addChild('address');

    $billingAddress = $addresses->address[0];
    $shippingAddress = $addresses->address[1];

    $billingAddress->addAttribute('type', 'BILLING');
    $shippingAddress->addAttribute('type', 'DELIVERY');

    $billingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['billing_street_address'])));
    $billingAddress->addChild('zip-code', utf8_encode($order['billing_postcode']));
    $billingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['billing_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['billing_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $billingAddress->addChild('country-code',utf8_encode($country));

    $shippingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['delivery_street_address'])));
    $shippingAddress->addChild('zip-code', utf8_encode($order['delivery_postcode']));
    $shippingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['delivery_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['delivery_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $shippingAddress->addChild('country-code',utf8_encode($country));

    $customer->addChild('nationality', utf8_encode($country));
    $customer->addChild('customer-allow-credit-inquiry', 'yes');
    $content->addChild('shopping-basket');
    $shoppingBasket = $content->{'shopping-basket'};
    $shoppingBasket->addAttribute('amount', '0.00');
    $shoppingBasket->addAttribute('currency', 'EUR');
    $shoppingBasket->addChild('items');
    $content->addChild('payment');
    $payment = $content->payment;
    $payment->addAttribute('method', $pi_payment_type);
    $payment->addAttribute('currency', 'EUR');
    $payment->addChild('amount', '0.00');
    $payment->addChild('usage', utf8_encode($pi_ratepay->testOrLiveUsage()));
    if ($pi_payment_type == "INSTALLMENT") {
        $payment->addChild('installment-details');
        $payment->addChild('debit-pay-type', 'BANK-TRANSFER');
    }
    $response = $ratepay->paymentOperation($request);

    $first_name = removeSpecialChars(utf8_encode($orderData['first_name']));
    $last_name = removeSpecialChars(utf8_encode($orderData['last_name']));

    if ($response) {
        $resultCode = (string) $response->head->processing->result->attributes()->code;
        $result = (string) $response->head->processing->result;
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, $response, $first_name, $last_name);
        if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "403") {
            $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
            $query = tep_db_query($sql);
            while ($mItem = tep_db_fetch_array($query)) {
                if ($_POST[$mItem['article_number']] > 0) {
                    $arr = str_split($mItem['article_number'], 8);
                    if ($arr[0] != 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set cancelled = cancelled + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_number = '" . tep_db_input($mItem['article_number']) . "'";
                        tep_db_query($sql);
                    } else if ($arr[0] == 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set cancelled = cancelled + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_name = '" . tep_db_input($mItem['article_name']) . "'";
                        tep_db_query($sql);
                    }
                    $sql = "insert into " . $pi_table_prefix . "_history (order_number, article_number, quantity, method, submethod) values ('" . tep_db_input($oID) . "', '" . tep_db_input($mItem['article_number']) . "', '" . tep_db_input($_POST[$mItem['article_number']]) . "', 'cancelled', 'cancelled')";
                    tep_db_query($sql);
                    $sql = "select products_quantity as qty from orders_products where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                    $query1 = tep_db_query($sql);
                    $qty = tep_db_fetch_array($query1);

                    $sql = "delete from orders_products where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                    tep_db_query($sql);

                    $sql = "delete from orders_total where class NOT LIKE 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);

                    $sql = "update orders_total set  text = '<b>0,00 EUR</b>' , value = 0 where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);
                }
            }
            $message = PI_RATEPAY_SUCCESSFULLCANCELLATION;
            return array('result' => 'SUCCESS', 'message' => $message);
        }
        else {
            $message = PI_RATEPAY_ERRORFULLCANCELLATION;
            return array('result' => 'ERROR', 'message' => $message);
        }
    }
    else {
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, false, $first_name, $last_name);
        $message = PI_RATEPAY_SERVICE;
        return array('result' => 'ERROR', 'message' => $message);
    }
}

/**
 * This functions send a PAYMENT_CHANGE request with the sub operation part-cancelation
 * to the RatePAY API and saves all necessary informations in the DB
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function partCancel($oID, $paymentType) {
    $pi_order = new order($oID);
    $operation = 'PAYMENT_CHANGE';
    $subOperation = 'partial-cancellation';
    if ($paymentType == "RatePAY Rechnung") {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rechnung.php');
        $pi_ratepay = new pi_ratepay_rechnung();
        $pi_table_prefix = 'pi_ratepay_rechnung';
        $pi_payment_type = 'INVOICE';
    } else {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rate.php');
        $pi_ratepay = new pi_ratepay_rate();
        $pi_table_prefix = 'pi_ratepay_rate';
        $pi_payment_type = 'INSTALLMENT';
    }

    $profileId = $pi_ratepay->profileId;
    $securityCode = $pi_ratepay->securityCode;
    $systemId = $_SERVER['SERVER_ADDR'];

    $query = tep_db_query("select transaction_id, transaction_short_id, first_name, last_name, gender, dob, vat_id from " . $pi_table_prefix . "_orders where order_number = '" . tep_db_input($oID) . "'");
    $orderData = tep_db_fetch_array($query);
    
    $query = tep_db_query("select * from orders where orders_id = '" . tep_db_input($oID) . "'");
    $order = tep_db_fetch_array($query);
    $ratepay = new Ratepay_XML;
    $ratepay->live = $pi_ratepay->testOrLive();
    $request = $ratepay->getXMLObject();

    $request->addChild('head');
    $head = $request->{'head'};
    $head->addChild('system-id', $systemId);
    $head->addChild('transaction-id', $orderData['transaction_id']);
    $head->addChild('transaction-short-id', $orderData['transaction_short_id']);
    $operation = $head->addChild('operation', $operation);
    $operation->addAttribute('subtype', utf8_encode($subOperation));

    $credential = $head->addChild('credential');
    $credential->addChild('profile-id', $profileId);
    $credential->addChild('securitycode', $securityCode);

    $external = $head->addChild('external');
    $external->addChild('order-id', $oID);

    $content = $request->addChild('content');
    $content->addChild('customer');

    if (strtoupper($orderData['gender']) == "F") {
        $gender = "F";
    } else if (strtoupper($orderData['gender']) == "M") {
        $gender = "M";
    } else {
        $gender = "U";
    }

    $customer = $content->customer;
    $customer->addCDataChild('first-name', removeSpecialChars(utf8_encode($orderData['first_name'])));
    $customer->addCDataChild('last-name', removeSpecialChars(utf8_encode($orderData['last_name'])));
    $customer->addChild('gender', $gender);
    $customer->addChild('date-of-birth', (string) utf8_encode($orderData['dob']));
    if (!empty($pi_order->customer['company'])) {
        $customer->addChild('company', utf8_encode($pi_order->customer['company']));
        $customer->addChild('company', utf8_encode($orderData['vat_id']));
    }
    $customer->addChild('contacts');

    $contacts = $customer->contacts;
    $contacts->addChild('email', utf8_encode($pi_order->customer['email_address']));
    $contacts->addChild('phone');

    $phone = $contacts->phone;
    $phone->addChild('direct-dial', utf8_encode($pi_order->customer['telephone']));

    $customer->addChild('addresses');
    $addresses = $customer->addresses;
    $addresses->addChild('address');
    $addresses->addChild('address');

    $billingAddress = $addresses->address[0];
    $shippingAddress = $addresses->address[1];

    $billingAddress->addAttribute('type', 'BILLING');
    $shippingAddress->addAttribute('type', 'DELIVERY');

    $billingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['billing_street_address'])));
    $billingAddress->addChild('zip-code', utf8_encode($order['billing_postcode']));
    $billingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['billing_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['billing_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $billingAddress->addChild('country-code',utf8_encode($country));

    $shippingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['delivery_street_address'])));
    $shippingAddress->addChild('zip-code', utf8_encode($order['delivery_postcode']));
    $shippingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['delivery_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['delivery_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $shippingAddress->addChild('country-code',utf8_encode($country));

    $customer->addChild('nationality', utf8_encode($country));
    $customer->addChild('customer-allow-credit-inquiry', 'yes');
    $content->addChild('shopping-basket');
    $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
    $query = tep_db_query($sql);
    $i = 0;
    while ($mItem = tep_db_fetch_array($query)) {
        $qty = ($mItem['ordered'] - $mItem['returned'] - $mItem['cancelled']);
        $newQTY = $qty - $_POST[$mItem['article_number']];
        $arr = str_split($mItem['article_number'], 8);
        if ($_POST[$mItem['article_number']] < $qty) {
            if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                $zwischenPrice = $mItem['products_price'] * $pi_order->info['currency_value'];
                $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                $zwischenBrutto = $zwischenPrice + $zwischenTax;
                $price = $price + ( $zwischenBrutto * $newQTY);
            } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                $price = $price + ( ( $mItem['article_netUnitPrice'] * $newQTY ));
            } else if ($mItem['article_number'] == 'SHIPPING') {
                $price = $price + (($mItem['article_netUnitPrice'] + $mItem['tax']) * $newQTY );
            } else if ($arr[0] == 'DISCOUNT') {
                $price = $price + (($mItem['article_netUnitPrice'] + $mItem['tax']) * $newQTY );
            }
        }
    }

    $shoppingBasket = $content->{'shopping-basket'};
    $shoppingBasket->addAttribute('amount', number_format($price, 2, ".", ""));
    $shoppingBasket->addAttribute('currency', 'EUR');
    $items = $shoppingBasket->addChild('items');
    $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
    $query = tep_db_query($sql);
    $i = 0;
    while ($mItem = tep_db_fetch_array($query)) {
        $qty = ($mItem['ordered'] - $mItem['returned'] - $mItem['cancelled']);
        $newQTY = $qty - $_POST[$mItem['article_number']];
        $arr = str_split($mItem['article_number'], 8);
        if ($_POST[$mItem['article_number']] < $qty) {
            if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                $items->addCDataChild('item', removeSpecialChars(utf8_encode($mItem['article_name'])));
                $items->item[$i]->addAttribute('article-number', $mItem['products_id']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $zwischenPrice = $mItem['products_price'] * $pi_order->info['currency_value'];
                $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                $items->item[$i]->addAttribute('unit-price', number_format($zwischenPrice, 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format($zwischenPrice * $newQTY, 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($newQTY * $zwischenTax, 2, ".", ""));
            } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                $items->addChild('item', PI_RATEPAY_VOUCHER);
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format($newQTY * $mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format(0, 2, '.', ''));
            } else if ($mItem['article_number'] == 'SHIPPING') {
                $shipping_method_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_shipping'");
                $shipping_method = tep_db_fetch_array($shipping_method_query);
                $shippingPrice = number_format($shipping_method['value'] * $pi_order->info['currency_value'],2,".","");
                $items->addChild('item', utf8_encode($mItem['article_name']));
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $items->item[$i]->addAttribute('unit-price', number_format($shippingPrice - $mItem['tax'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format(($newQTY * ($shippingPrice - $mItem['tax'])), 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($newQTY * ($mItem['tax']), 2, '.', ''));
            } else if ($arr[0] == 'DISCOUNT') {
                $items->addChild('item', utf8_encode($mItem['article_name']));
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format(($newQTY * $mItem['article_netUnitPrice']), 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($newQTY * ($mItem['tax']), 2, '.', ''));
            }
            $i++;
        }
    }
    $content->addChild('payment');
    $payment = $content->payment;
    $payment->addAttribute('method', $pi_payment_type);
    $payment->addAttribute('currency', 'EUR');
    $payment->addChild('amount', number_format($price, 2, ".", ""));
    $payment->addChild('usage', utf8_encode($pi_ratepay->testOrLiveUsage()));
    if ($pi_payment_type == "INSTALLMENT") {
        $payment->addChild('installment-details');
        $payment->addChild('debit-pay-type', 'BANK-TRANSFER');
    }
    $response = $ratepay->paymentOperation($request);
    $first_name = removeSpecialChars(utf8_encode($orderData['first_name']));
    $last_name = removeSpecialChars(utf8_encode($orderData['last_name']));

    if ($response) {
        $resultCode = (string) $response->head->processing->result->attributes()->code;
        $result = (string) $response->head->processing->result;

        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, $response, $first_name, $last_name);
        if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "403") {
            $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
            $query = tep_db_query($sql);
            while ($mItem = tep_db_fetch_array($query)) {
                if ($_POST[$mItem['article_number']] > 0) {
                    $arr = str_split($mItem['article_number'], 8);
                    if ($arr[0] != 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set cancelled = cancelled + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_number = '" . tep_db_input($mItem['article_number']) . "'";
                        tep_db_query($sql);
                    } else if ($arr[0] == 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set cancelled = cancelled + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_name = '" . tep_db_input($mItem['article_name']) . "'";
                        tep_db_query($sql);
                    }
                    
                    $sql = "insert into " . $pi_table_prefix . "_history (order_number, article_number, quantity, method, submethod) values ('" . tep_db_input($oID) . "', '" . tep_db_input($mItem['article_number']) . "', '" . tep_db_input($_POST[$mItem['article_number']]) . "', 'cancelled', 'cancelled')";
                    tep_db_query($sql);
                    
                    $sql = "select products_quantity as qty from orders_products where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                    $query1 = tep_db_query($sql);
                    $qty = tep_db_fetch_array($query1);

                    if (($qty['qty'] - $_POST[$mItem['article_number']]) <= 0) {
                        $sql = "delete from orders_products where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                        tep_db_query($sql);
                    }

                    $sql = "update orders_products set products_quantity = products_quantity - " . tep_db_input($_POST[$mItem['article_number']]) . " where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                    tep_db_query($sql);
                    if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                        $zwischenPrice = $mItem['products_price'];
                        $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                        $zwischenBrutto = $zwischenPrice + $zwischenTax;
                        $zwischenTotal = $_POST[$mItem['article_number']]*$zwischenBrutto;
                        $zwischenTotalNetto = $_POST[$mItem['article_number']]*$zwischenPrice;
                        $zwischenTotalTax = $_POST[$mItem['article_number']]*$zwischenTax;
                        $sql = "update orders_total set value = (value - " . tep_db_input($zwischenTotal) . ") where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($zwischenTotalNetto) . ") where class = 'ot_subtotal' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($zwischenTotalTax) . ") where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);
                    } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                        $sql = "update orders_total set value = (value - (" . tep_db_input($_POST[$mItem['article_number']]) . " * " . tep_db_input($mItem['article_netUnitPrice']) . ")) where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - (" . tep_db_input($_POST[$mItem['article_number']]) . " * " . tep_db_input($mItem['article_netUnitPrice']) . ")) where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "select * from orders_total where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                        $gutscheinResult = tep_db_query($sql);
                        $gutscheinResultArray = tep_db_fetch_array($gutscheinResult);
                        if ($gutscheinResultArray['value'] == 0) {
                            $sql = "delete from orders_total where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                            tep_db_query($sql);
                        } else {
                            $sql = "update orders_total set text = '<font color=\"ff0000\">" . number_format($gutscheinResultArray['value'], 2, ",", "") . " EUR</font>' where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                            tep_db_query($sql);
                        }
                    } else if ($mItem['article_number'] == 'SHIPPING') {
                        $shipping_method_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_shipping'");
                        $shipping_method = tep_db_fetch_array($shipping_method_query);
                        $shippingPrice = number_format($shipping_method['value'],2,".","");
                        $shippingTax = ($mItem['article_netUnitPrice']/$pi_order->info['currency_value']) - $shippingPrice;
                        $sql = "update orders_total set value = (value - " . tep_db_input($shippingPrice + $shippingTax) . ") where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($shippingTax) . ") where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "delete from orders_total where class = 'ot_shipping' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);
                    } else if ($arr[0] == 'DISCOUNT') {
                        $sql = "update orders_total set value = (value - " . tep_db_input(($mItem['article_netUnitPrice'] + $mItem['tax'])/$pi_order->info['currency_value']) . ") where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($mItem['tax']/$pi_order->info['currency_value']) . ") where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "' and title = '" . $mItem['article_name'] . ":'";
                        tep_db_query($sql);
                        
                        $sql = 'Select configuration_value from configuration where configuration_key = "MODULE_ORDER_TOTAL_DISCOUNT_COUPON_DISPLAY_TYPE"';
                        $query = tep_db_query($sql);
                        $config = tep_db_fetch_array($query);
                        
                        $operator = '-';
                        $multiplier = '*1';
                        if ($config['configuration_value'] == 'false') {
                            $operator = '+';
                            $multiplier = '*-1';
                        }
                        
                        $sql = "update orders_total set value = (value $operator " . tep_db_input($mItem['article_netUnitPrice']/$pi_order->info['currency_value']) . "), text = '0EUR' where class = 'ot_discount_coupon' and orders_id = '" . tep_db_input($oID) . "' and FORMAT(value, 1) = FORMAT(" . $mItem['article_netUnitPrice']/$pi_order->info['currency_value'] . "$multiplier, 1)";
                        tep_db_query($sql);
                    }
                    $sql = "select value from orders_total where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    $totalq = tep_db_query($sql);
                    $total = tep_db_fetch_array($totalq);
                    $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
                    $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . "EUR</b>' where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);

                    $sql = "select value from orders_total where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "'";
                    $totalq = tep_db_query($sql);
                    while($total = tep_db_fetch_array($totalq)){
                        $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
                        $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . "EUR</b>' where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "' and title = '" . $total['title'] . "'";
                        tep_db_query($sql);
                    }
//
//                    $sql = "select value from orders_total where class = 'ot_discount_coupon' and orders_id = '" . tep_db_input($oID) . "'";
//                    $totalq = tep_db_query($sql);
//                    while($total = tep_db_fetch_array($totalq)){
//                        $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
//                        $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . "EUR</b>' where class = 'ot_discount_coupon' and orders_id = '" . tep_db_input($oID) . "'";
//                        tep_db_query($sql);
//                    }
//                    
                    $sql = "select value from orders_total where class = 'ot_subtotal' and orders_id = '" . tep_db_input($oID) . "'";
                    $totalq = tep_db_query($sql);
                    $total = tep_db_fetch_array($totalq);
                    $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
                    $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . " EUR</b>' where class = 'ot_subtotal' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);
                }
            }
            $message = PI_RATEPAY_SUCCESSPARTIALCANCELLATION;
            return array('result' => 'SUCCESS', 'message' => $message);
        }
        else {
            $message = PI_RATEPAY_ERRORPARTIALCANCELLATION;
            return array('result' => 'ERROR', 'message' => $message);
        }
    }
    else {

        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, false, $first_name, $last_name);
        $message = PI_RATEPAY_SERVICE;
        return array('result' => 'ERROR', 'message' => $message);
    }
}

/**
 * This functions calls the fullCancel($oID) or the partCancel($oID) function
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function returnRequest($oID, $paymentType) {
    $pi_order = new order($oID);
    if ($paymentType == "RatePAY Rechnung") {
        $pi_table_prefix = 'pi_ratepay_rechnung';
    } else {
        $pi_table_prefix = 'pi_ratepay_rate';
    }
    $sql = "select * from " . $pi_table_prefix . "_orderdetails where order_number = '" . tep_db_input($oID) . "'";
    $query = tep_db_query($sql);
    $flag = array();
    $i = 0;
    while ($item = tep_db_fetch_array($query)) {
        $qty = $item['ordered'] - $item['returned'] - $_POST[$item['article_number']];
        if ($qty == 0) {
            $flag[$i] = true;
        } else if ($qty > 0) {
            $flag[$i] = false;
        }
        $i++;
    }
    $full = true;
    for ($i = 0; $i < count($flag); $i++) {
        if ($flag[$i] == false) {
            $full = false;
        }
    }
    if ($full == true) {
        return fullReturn($oID, $paymentType);
    } else if ($full == false) {
        return partReturn($oID, $paymentType);
    }
}

/**
 * This functions send a PAYMENT_CHANGE request with the sub operation part-return
 * to the RatePAY API and saves all necessary informations in the DB
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function partReturn($oID, $paymentType) {
    $pi_order = new order($oID);
    // Stuff for the request
    $operation = 'PAYMENT_CHANGE';
    $subOperation = 'partial-return';
    if ($paymentType == "RatePAY Rechnung") {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rechnung.php');
        $pi_ratepay = new pi_ratepay_rechnung();
        $pi_table_prefix = 'pi_ratepay_rechnung';
        $pi_payment_type = 'INVOICE';
    } else {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rate.php');
        $pi_ratepay = new pi_ratepay_rate();
        $pi_table_prefix = 'pi_ratepay_rate';
        $pi_payment_type = 'INSTALLMENT';
    }

    $profileId = $pi_ratepay->profileId;
    $securityCode = $pi_ratepay->securityCode;
    $systemId = $_SERVER['SERVER_ADDR'];

    $query = tep_db_query("select transaction_id, transaction_short_id, first_name, last_name, gender, dob, vat_id from " . $pi_table_prefix . "_orders where order_number = '" . tep_db_input($oID) . "'");
    $orderData = tep_db_fetch_array($query);
    
    $query = tep_db_query("select * from orders where orders_id = '" . tep_db_input($oID) . "'");
    $order = tep_db_fetch_array($query);
    $ratepay = new Ratepay_XML;
    $ratepay->live = $pi_ratepay->testOrLive();
    $request = $ratepay->getXMLObject();

    $request->addChild('head');
    $head = $request->{'head'};
    $head->addChild('system-id', $systemId);
    $head->addChild('transaction-id', $orderData['transaction_id']);
    $head->addChild('transaction-short-id', $orderData['transaction_short_id']);
    $operation = $head->addChild('operation', $operation);
    $operation->addAttribute('subtype', utf8_encode($subOperation));

    $credential = $head->addChild('credential');
    $credential->addChild('profile-id', $profileId);
    $credential->addChild('securitycode', $securityCode);

    $external = $head->addChild('external');
    $external->addChild('order-id', $oID);

    $content = $request->addChild('content');
    $content->addChild('customer');

    if (strtoupper($orderData['gender']) == "F") {
        $gender = "F";
    } else if (strtoupper($orderData['gender']) == "M") {
        $gender = "M";
    } else {
        $gender = "U";
    }

    $customer = $content->customer;
    $customer->addCDataChild('first-name', removeSpecialChars(utf8_encode($orderData['first_name'])));
    $customer->addCDataChild('last-name', removeSpecialChars(utf8_encode($orderData['last_name'])));
    $customer->addChild('gender', $gender);
    $customer->addChild('date-of-birth', (string) utf8_encode($orderData['dob']));
    if (!empty($pi_order->customer['company'])) {
        $customer->addChild('company', utf8_encode($pi_order->customer['company']));
        $customer->addChild('company', utf8_encode($orderData['vat_id']));
    }
    $customer->addChild('contacts');

    $contacts = $customer->contacts;
    $contacts->addChild('email', utf8_encode($pi_order->customer['email_address']));
    $contacts->addChild('phone');

    $phone = $contacts->phone;
    $phone->addChild('direct-dial', utf8_encode($pi_order->customer['telephone']));

    $customer->addChild('addresses');
    $addresses = $customer->addresses;
    $addresses->addChild('address');
    $addresses->addChild('address');

    $billingAddress = $addresses->address[0];
    $shippingAddress = $addresses->address[1];

    $billingAddress->addAttribute('type', 'BILLING');
    $shippingAddress->addAttribute('type', 'DELIVERY');

    $billingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['billing_street_address'])));
    $billingAddress->addChild('zip-code', utf8_encode($order['billing_postcode']));
    $billingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['billing_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['billing_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $billingAddress->addChild('country-code',utf8_encode($country));

    $shippingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['delivery_street_address'])));
    $shippingAddress->addChild('zip-code', utf8_encode($order['delivery_postcode']));
    $shippingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['delivery_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['delivery_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $shippingAddress->addChild('country-code',utf8_encode($country));

    $customer->addChild('nationality', utf8_encode($country));
    $customer->addChild('customer-allow-credit-inquiry', 'yes');
    $content->addChild('shopping-basket');
    $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
    $query = tep_db_query($sql);
    $i = 0;
    while ($mItem = tep_db_fetch_array($query)) {
        $qty = $mItem['ordered'] - $mItem['cancelled'] - $mItem['returned'];
        $newQTY = $qty - $_POST[$mItem['article_number']];
        $arr = str_split($mItem['article_number'], 8);
        if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
            $zwischenPrice = $mItem['products_price'] * $pi_order->info['currency_value'];
            $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
            $zwischenBrutto = $zwischenPrice + $zwischenTax;
            $price = $price + ( $zwischenBrutto * $newQTY);
        } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
            $price = $price + ( ( $mItem['article_netUnitPrice'] * $newQTY ));
        } else if ($mItem['article_number'] == 'SHIPPING') {
            $price = $price + ( ($mItem['article_netUnitPrice'] + $mItem['tax']) * $newQTY );
        } else if ($arr[0] == 'DISCOUNT') {
            $price = $price + ( ($mItem['article_netUnitPrice'] +  $mItem['tax'])* $newQTY );
        }
    }


    $shoppingBasket = $content->{'shopping-basket'};
    $shoppingBasket->addAttribute('amount', number_format($price, 2, ".", ""));
    $shoppingBasket->addAttribute('currency', 'EUR');
    $items = $shoppingBasket->addChild('items');
    $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
    $query = tep_db_query($sql);
    $i = 0;
    while ($mItem = tep_db_fetch_array($query)) {
        $qty = ($mItem['ordered'] - $mItem['returned'] - $mItem['cancelled']);
        $newQTY = $qty - $_POST[$mItem['article_number']];
        if ($_POST[$mItem['article_number']] < $qty) {
            $arr = str_split($mItem['article_number'], 8);
            if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                $items->addCDataChild('item', removeSpecialChars(utf8_encode($mItem['article_name'])));
                $items->item[$i]->addAttribute('article-number', $mItem['products_id']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $zwischenPrice = $mItem['products_price'] * $pi_order->info['currency_value'];
                $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                $items->item[$i]->addAttribute('unit-price', number_format($zwischenPrice, 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format($zwischenPrice * $newQTY, 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($newQTY * $zwischenTax, 2, ".", ""));
            } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                $items->addChild('item', PI_RATEPAY_VOUCHER);
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format($newQTY * $mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format(0, 2, '.', ''));
            } else if ($mItem['article_number'] == 'SHIPPING') {
                $shipping_method_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_shipping'");
                $shipping_method = tep_db_fetch_array($shipping_method_query);
                $shippingPrice = number_format($shipping_method['value'],2,".","");
                $items->addChild('item', utf8_encode($mItem['article_name']));
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $items->item[$i]->addAttribute('unit-price', number_format($shippingPrice - $mItem['tax'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format(($newQTY * ($shippingPrice - $mItem['tax'])), 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($newQTY * ($mItem['tax']), 2, '.', ''));
            } else if ($arr[0] == 'DISCOUNT') {
                $items->addChild('item', utf8_encode($mItem['article_name']));
                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                $items->item[$i]->addAttribute('quantity', $newQTY);
                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                $items->item[$i]->addAttribute('total-price', number_format(($newQTY * $mItem['article_netUnitPrice']), 2, '.', ''));
                $items->item[$i]->addAttribute('tax', number_format($newQTY * ($mItem['tax']), 2, '.', ''));
            }
            $i++;
        }
    }
    $content->addChild('payment');
    $payment = $content->payment;
    $payment->addAttribute('method', $pi_payment_type);
    $payment->addAttribute('currency', 'EUR');
    $payment->addChild('amount', number_format($price, 2, ".", ""));
    $payment->addChild('usage', utf8_encode($pi_ratepay->testOrLiveUsage()));
    if ($pi_payment_type == "INSTALLMENT") {
        $payment->addChild('installment-details');
        $payment->addChild('debit-pay-type', 'BANK-TRANSFER');
    }
    $response = $ratepay->paymentOperation($request);

    $first_name = removeSpecialChars(utf8_encode($orderData['first_name']));
    $last_name = removeSpecialChars(utf8_encode($orderData['last_name']));
    if ($response) {
        $resultCode = (string) $response->head->processing->result->attributes()->code;
        $result = (string) $response->head->processing->result;
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, $response, $first_name, $last_name);
        if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "403") {
            $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
            $query = tep_db_query($sql);
            $i = 0;
            while ($mItem = tep_db_fetch_array($query)) {
                $qty = $mItem['ordered'] - $mItem['cancelled'] - $mItem['returned'];
                if ($_POST[$mItem['article_number']] > 0) {
                    $arr = str_split($mItem['article_number'], 8);
                    if ($arr[0] != 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set returned = returned + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_number = '" . tep_db_input($mItem['article_number']) . "'";
                        tep_db_query($sql);
                    } else if ($arr[0] == 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set returned = returned + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_name = '" . tep_db_input($mItem['article_name']) . "'";
                        tep_db_query($sql);
                    }
                    $sql = "insert into " . $pi_table_prefix . "_history (order_number, article_number, quantity, method, submethod) values ('" . tep_db_input($oID) . "', '" . tep_db_input($mItem['article_number']) . "', '" . tep_db_input($_POST[$mItem['article_number']]) . "', 'returned', 'returned')";
                    tep_db_query($sql);
                    $sql = "select products_quantity as qty from orders_products where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                    $query1 = tep_db_query($sql);
                    $qty = tep_db_fetch_array($query1);
                    if (($qty['qty'] - $_POST[$mItem['article_number']]) <= 0) {
                        $sql = "delete from orders_products where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                        tep_db_query($sql);
                    }

                    $sql = "update orders_products set products_quantity = products_quantity - " . tep_db_input($_POST[$mItem['article_number']]) . " where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                    tep_db_query($sql);
                    if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                        $zwischenPrice = $mItem['products_price'];
                        $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                        $zwischenBrutto = $zwischenPrice + $zwischenTax;
                        $zwischenTotal = $_POST[$mItem['article_number']]*$zwischenBrutto;
                        $zwischenTotalNetto = $_POST[$mItem['article_number']]*$zwischenPrice;
                        $zwischenTotalTax = $_POST[$mItem['article_number']]*$zwischenTax;
                        $sql = "update orders_total set value = (value - " . tep_db_input($zwischenTotal) . ") where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($zwischenTotalNetto) . ") where class = 'ot_subtotal' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($zwischenTotalTax) . ") where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);
                    } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                        $sql = "update orders_total set value = (value - (" . tep_db_input($_POST[$mItem['article_number']]) . " * " . tep_db_input($mItem['article_netUnitPrice']) . ")) where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - (" . tep_db_input($_POST[$mItem['article_number']]) . " * " . tep_db_input($mItem['article_netUnitPrice']) . ")) where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "select * from orders_total where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                        $gutscheinResult = tep_db_query($sql);
                        $gutscheinResultArray = tep_db_fetch_array($gutscheinResult);
                        if ($gutscheinResultArray['value'] == 0) {
                            $sql = "delete from orders_total where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                            tep_db_query($sql);
                        } else {
                            $sql = "update orders_total set text = '<font color=\"ff0000\">" . number_format($gutscheinResultArray['value'], 2, ",", "") . " EUR</font>' where class = 'pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                            tep_db_query($sql);
                        }
                    } else if ($mItem['article_number'] == 'SHIPPING') {
                        $shipping_method_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_shipping'");
                        $shipping_method = tep_db_fetch_array($shipping_method_query);
                        $shippingPrice = number_format($shipping_method['value'],2,".","");
                        $shippingTax = round(($mItem['article_netUnitPrice']/$pi_order->info['currency_value']) - $shippingPrice ,2);
                        $sql = "update orders_total set value = (value - " . tep_db_input($shippingPrice + $shippingTax) . ") where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($shippingTax) . ") where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "delete from orders_total where class = 'ot_shipping' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);
                    } else if ($arr[0] == 'DISCOUNT') {
                        $sql = "update orders_total set value = (value - " . tep_db_input(($mItem['article_netUnitPrice'] + $mItem['tax'])/$pi_order->info['currency_value']) . ") where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                        tep_db_query($sql);

                        $sql = "update orders_total set value = (value - " . tep_db_input($mItem['tax']/$pi_order->info['currency_value']) . ") where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "' and title = '" . $mItem['article_name'] . ":'";
                        tep_db_query($sql);
                        
                        $sql = 'Select configuration_value from configuration where configuration_key = "MODULE_ORDER_TOTAL_DISCOUNT_COUPON_DISPLAY_TYPE"';
                        $query = tep_db_query($sql);
                        $config = tep_db_fetch_array($query);
                        
                        $operator = '-';
                        $multiplier = '*1';
                        if ($config['configuration_value'] == 'false') {
                            $operator = '+';
                            $multiplier = '*-1';
                        }
                        
                        $sql = "update orders_total set value = (value $operator " . tep_db_input($mItem['article_netUnitPrice']/$pi_order->info['currency_value']) . "), text = '0EUR' where class = 'ot_discount_coupon' and orders_id = '" . tep_db_input($oID) . "' and FORMAT(value, 1) = Format(" . $mItem['article_netUnitPrice']/$pi_order->info['currency_value'] . "$multiplier, 1)";
                        tep_db_query($sql);
                    }
                    $sql = "select value from orders_total where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    $totalq = tep_db_query($sql);
                    $total = tep_db_fetch_array($totalq);
                    $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
                    $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . " EUR</b>' where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);
                    
                    $sql = "select value from orders_total where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "'";
                    $totalq = tep_db_query($sql);
                    while($total = tep_db_fetch_array($totalq)){
                        $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
                        $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . "EUR</b>' where class = 'ot_tax' and orders_id = '" . tep_db_input($oID) . "' and title = '" . $total['title'] . "'";
                        tep_db_query($sql);
                    }

//                    $sql = "select value from orders_total where class = 'ot_discount_coupon' and orders_id = '" . tep_db_input($oID) . "'";
//                    $totalq = tep_db_query($sql);
//                    while($total = tep_db_fetch_array($totalq)){
//                        $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
//                        $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . "EUR</b>' where class = 'ot_discount_coupon' and orders_id = '" . tep_db_input($oID) . "'";
//                        tep_db_query($sql);
//                    }
                    
                    $sql = "select value from orders_total where class = 'ot_subtotal' and orders_id = '" . tep_db_input($oID) . "'";
                    $totalq = tep_db_query($sql);
                    $total = tep_db_fetch_array($totalq);
                    $totalText = str_replace(",", ".", strval(number_format($total['value'] * $pi_order->info['currency_value'], 2)));
                    $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . " EUR</b>' where class = 'ot_subtotal' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);
                }
            }
            $message = PI_RATEPAY_SUCCESSPARTIALRETURN;
            return array('result' => 'SUCCESS', 'message' => $message);
        }
        else {
            $message = PI_RATEPAY_ERRORPARTIALRETURN;
            return array('result' => 'ERROR', 'message' => $message);
        }
    }
    else {
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, false, $first_name, $last_name);
        $message = PI_RATEPAY_SERVICE;
        return array('result' => 'ERROR', 'message' => $message);
    }
}

/**
 * This functions send a PAYMENT_CHANGE request with the sub operation full-return
 * to the RatePAY API and saves all necessary informations in the DB
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function fullReturn($oID, $paymentType) {
    $operation = 'PAYMENT_CHANGE';
    $subOperation = 'full-return';
    if ($paymentType == "RatePAY Rechnung") {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rechnung.php');
        $pi_ratepay = new pi_ratepay_rechnung();
        $pi_table_prefix = 'pi_ratepay_rechnung';
        $pi_payment_type = 'INVOICE';
    } else {
        require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rate.php');
        $pi_ratepay = new pi_ratepay_rate();
        $pi_table_prefix = 'pi_ratepay_rate';
        $pi_payment_type = 'INSTALLMENT';
    }
    
    $pi_order = new Order($oID);

    $profileId = $pi_ratepay->profileId;
    $securityCode = $pi_ratepay->securityCode;
    $systemId = $_SERVER['SERVER_ADDR'];

    $query = tep_db_query("select transaction_id, transaction_short_id, first_name, last_name, gender, dob, vat_id from " . $pi_table_prefix . "_orders where order_number = '" . tep_db_input($oID) . "'");
    $orderData = tep_db_fetch_array($query);
    
    $query = tep_db_query("select * from orders where orders_id = '" . tep_db_input($oID) . "'");
    $order = tep_db_fetch_array($query);
    $ratepay = new Ratepay_XML;
    $ratepay->live = $pi_ratepay->testOrLive();
    $request = $ratepay->getXMLObject();

    $request->addChild('head');
    $head = $request->{'head'};
    $head->addChild('system-id', $systemId);
    $head->addChild('transaction-id', $orderData['transaction_id']);
    $head->addChild('transaction-short-id', $orderData['transaction_short_id']);
    $operation = $head->addChild('operation', $operation);
    $operation->addAttribute('subtype', $subOperation);

    $credential = $head->addChild('credential');
    $credential->addChild('profile-id', $profileId);
    $credential->addChild('securitycode', $securityCode);

    $external = $head->addChild('external');
    $external->addChild('order-id', $oID);

    $content = $request->addChild('content');
    $content->addChild('customer');

    if (strtoupper($orderData['gender']) == "F") {
        $gender = "F";
    } else if (strtoupper($orderData['gender']) == "M") {
        $gender = "M";
    } else {
        $gender = "U";
    }

    $customer = $content->customer;
    $customer->addCDataChild('first-name', removeSpecialChars(utf8_encode($orderData['first_name'])));
    $customer->addCDataChild('last-name', removeSpecialChars(utf8_encode($orderData['last_name'])));
    $customer->addChild('gender', $gender);
    $customer->addChild('date-of-birth', (string) utf8_encode($orderData['dob']));
    if (!empty($pi_order->customer['company'])) {
        $customer->addChild('company', utf8_encode($pi_order->customer['company']));
        $customer->addChild('company', utf8_encode($orderData['vat_id']));
    }
    $customer->addChild('contacts');

    $contacts = $customer->contacts;
    $contacts->addChild('email', utf8_encode($pi_order->customer['email_address']));
    $contacts->addChild('phone');

    $phone = $contacts->phone;
    $phone->addChild('direct-dial', utf8_encode($pi_order->customer['telephone']));

    $customer->addChild('addresses');
    $addresses = $customer->addresses;
    $addresses->addChild('address');
    $addresses->addChild('address');

    $billingAddress = $addresses->address[0];
    $shippingAddress = $addresses->address[1];

    $billingAddress->addAttribute('type', 'BILLING');
    $shippingAddress->addAttribute('type', 'DELIVERY');

    $billingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['billing_street_address'])));
    $billingAddress->addChild('zip-code', utf8_encode($order['billing_postcode']));
    $billingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['billing_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['billing_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $billingAddress->addChild('country-code',utf8_encode($country));

    $shippingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['delivery_street_address'])));
    $shippingAddress->addChild('zip-code', utf8_encode($order['delivery_postcode']));
    $shippingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['delivery_city'])));
    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['delivery_country'] . "'";
    $queryCountry = tep_db_query($sqlCountry);
    $countryArray = tep_db_fetch_array($queryCountry);
    $country = $countryArray['countries_iso_code_2'];
    $shippingAddress->addChild('country-code',utf8_encode($country));

    $customer->addChild('nationality', utf8_encode($country));
    $customer->addChild('customer-allow-credit-inquiry', 'yes');
    $content->addChild('shopping-basket');
    $shoppingBasket = $content->{'shopping-basket'};
    $shoppingBasket->addAttribute('amount', '0.00');
    $shoppingBasket->addAttribute('currency', 'EUR');
    $shoppingBasket->addChild('items');
    $content->addChild('payment');
    $payment = $content->payment;
    $payment->addAttribute('method', $pi_payment_type);
    $payment->addAttribute('currency', 'EUR');
    $payment->addChild('amount', '0.00');
    $payment->addChild('usage', utf8_encode($pi_ratepay->testOrLiveUsage()));
    if ($pi_payment_type == "INSTALLMENT") {
        $payment->addChild('installment-details');
        $payment->addChild('debit-pay-type', 'BANK-TRANSFER');
    }
    $response = $ratepay->paymentOperation($request);
    $first_name = removeSpecialChars(utf8_encode($orderData['first_name']));
    $last_name = removeSpecialChars(utf8_encode($orderData['last_name']));
    if ($response) {
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, $response, $first_name, $last_name);
        if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "403") {
            $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
            $querySQL = tep_db_query($sql);
            while ($mItem = tep_db_fetch_array($querySQL)) {
                if ($_POST[$mItem['article_number']] > 0) {
                    $arr = str_split($mItem['article_number'], 8);
                    if ($arr[0] != 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set returned = returned + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_number = '" . tep_db_input($mItem['article_number']) . "'";
                        tep_db_query($sql);
                    } else if ($arr[0] == 'DISCOUNT') {
                        $sql = "update " . $pi_table_prefix . "_orderdetails set returned = returned + " . tep_db_input($_POST[$mItem['article_number']]) . " where order_number = '" . tep_db_input($oID) . "' and article_name = '" . tep_db_input($mItem['article_name']) . "'";
                        tep_db_query($sql);
                    }

                    $sql = "insert into " . $pi_table_prefix . "_history (order_number, article_number, quantity, method, submethod) values ('" . tep_db_input($oID) . "', '" . tep_db_input($mItem['article_number']) . "', '" . tep_db_input($_POST[$mItem['article_number']]) . "', 'returned', 'returned')";
                    tep_db_query($sql);

                    $sql = "delete from orders_products where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . tep_db_input($mItem['article_number']) . "'";
                    tep_db_query($sql);
                    $sql = "delete from orders_total where class NOT LIKE 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);
                    $sql = "update orders_total set  text = '<b>0,00 EUR</b>' , value = 0 where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                    tep_db_query($sql);
                }
            }
            $message = PI_RATEPAY_SUCCESSFULLRETURN;
            return array('result' => 'SUCCESS', 'message' => $message);
        }
        else {
            $message = PI_RATEPAY_ERRORFULLRETURN;
            return array('result' => 'ERROR', 'message' => $message);
        }
    }
    else {
        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, $first_name, $last_name);
        $message = PI_RATEPAY_SERVICE;
        return array('result' => 'ERROR', 'message' => $message);
    }
}

/**
 * This functions send a PAYMENT_CHANGE request with the sub operation goodwill
 * to the RatePAY API and saves all necessary informations in the DB
 * @param string $oID
 * @param string $paymentType
 *
 * @return array
 */
function voucherRequest($oID, $paymentType) {
    $pi_order = new order($oID);
    if (isset($_POST)) {
        $operation = 'PAYMENT_CHANGE';
        if ($paymentType == "RatePAY Rechnung") {
            require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rechnung.php');
            $pi_ratepay = new pi_ratepay_rechnung();
            $pi_table_prefix = 'pi_ratepay_rechnung';
            $pi_payment_type = 'INVOICE';
        } else {
            require_once(DIR_FS_DOCUMENT_ROOT . 'includes/modules/payment/pi_ratepay_rate.php');
            $pi_ratepay = new pi_ratepay_rate();
            $pi_table_prefix = 'pi_ratepay_rate';
            $pi_payment_type = 'INSTALLMENT';
        }

        $profileId = $pi_ratepay->profileId;
        $securityCode = $pi_ratepay->securityCode;
        $systemId = $_SERVER['SERVER_ADDR'];

        $subOperation = 'credit';
        
        $query = tep_db_query("select transaction_id, transaction_short_id, first_name, last_name, gender, dob, vat_id from " . $pi_table_prefix . "_orders where order_number = '" . tep_db_input($oID) . "'");
        $orderData = tep_db_fetch_array($query);
        
        $query = tep_db_query("select * from orders a, orders_total b where a.orders_id = '" . tep_db_input($oID) . "' and a.orders_id = b.orders_id and class = 'ot_total'");
        $order = tep_db_fetch_array($query);

        if (isset($_POST['voucherAmount'])) {
            if($_POST['voucherAmount'] <= 0) {
                $message = PI_RATEPAY_ERRORVOUCHER_AMOUNT_TO_LOW;
                return array('result' => 'ERROR', 'message' => $message);
            }
            if (preg_match("/^[0-9]{1,4}$/", $_POST['voucherAmount'])) {
                $piRatepayVoucher = $_POST['voucherAmount'];
                if (isset($_POST['voucherAmountKomma']) && $_POST['voucherAmountKomma'] != '') {
                    if (preg_match("/^[0-9]{2}$/", $_POST['voucherAmountKomma'])) {
                        $piRatepayVoucher = $piRatepayVoucher . "." . $_POST['voucherAmountKomma'];
                    } else if (preg_match("/^[0-9]{1}$/", $_POST['voucherAmountKomma'])) {
                        $piRatepayVoucher = $piRatepayVoucher . "." . $_POST['voucherAmountKomma'] . "0";
                    } else {
                        $piRatepayVoucher = $piRatepayVoucher . ".00";
                        $message = PI_RATEPAY_ERRORVOUCHER;
                        return array('result' => 'ERROR', 'message' => $message);
                    }
                } else {
                    $piRatepayVoucher = $piRatepayVoucher . ".00";
                    $message = PI_RATEPAY_ERRORVOUCHER;
                    return array('result' => 'ERROR', 'message' => $message);
                }
                if ($piRatepayVoucher > $order['value']) {
                    $message = PI_RATEPAY_ERRORVOUCHER;
                    return array('result' => 'ERROR', 'message' => $message);
                } else {
                    $piRatepayVoucher = $piRatepayVoucher * (-1);

                    $ratepay = new Ratepay_XML;
                    $ratepay->live = $pi_ratepay->testOrLive();
                    $request = $ratepay->getXMLObject();

                    $request->addChild('head');
                    $head = $request->{'head'};
                    $head->addChild('system-id', $systemId);
                    $head->addChild('transaction-id', $orderData['transaction_id']);
                    $head->addChild('transaction-short-id', $orderData['transaction_short_id']);
                    $operation = $head->addChild('operation', $operation);
                    $operation->addAttribute('subtype', $subOperation);

                    $credential = $head->addChild('credential');
                    $credential->addChild('profile-id', $profileId);
                    $credential->addChild('securitycode', $securityCode);

                    $external = $head->addChild('external');
                    $external->addChild('order-id', $oID);

                    $content = $request->addChild('content');
                    $content->addChild('customer');

                    if (strtoupper($orderData['gender']) == "F") {
                        $gender = "F";
                    } else if (strtoupper($orderData['gender']) == "M") {
                        $gender = "M";
                    } else {
                        $gender = "U";
                    }

                    $customer = $content->customer;
                    $customer->addCDataChild('first-name', removeSpecialChars(utf8_encode($orderData['first_name'])));
                    $customer->addCDataChild('last-name', removeSpecialChars(utf8_encode($orderData['last_name'])));
                    $customer->addChild('gender', $gender);
                    $customer->addChild('date-of-birth', (string) utf8_encode($orderData['dob']));
                    if (!empty($pi_order->customer['company'])) {
                        $customer->addChild('company', utf8_encode($pi_order->customer['company']));
                        $customer->addChild('company', utf8_encode($orderData['vat_id']));
                    }
                    $customer->addChild('contacts');

                    $contacts = $customer->contacts;
                    $contacts->addChild('email', utf8_encode($pi_order->customer['email_address']));
                    $contacts->addChild('phone');

                    $phone = $contacts->phone;
                    $phone->addChild('direct-dial', utf8_encode($pi_order->customer['telephone']));

                    $customer->addChild('addresses');
                    $addresses = $customer->addresses;
                    $addresses->addChild('address');
                    $addresses->addChild('address');

                    $billingAddress = $addresses->address[0];
                    $shippingAddress = $addresses->address[1];

                    $billingAddress->addAttribute('type', 'BILLING');
                    $shippingAddress->addAttribute('type', 'DELIVERY');

                    $billingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['billing_street_address'])));
                    $billingAddress->addChild('zip-code', utf8_encode($order['billing_postcode']));
                    $billingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['billing_city'])));
                    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['billing_country'] . "'";
                    $queryCountry = tep_db_query($sqlCountry);
                    $countryArray = tep_db_fetch_array($queryCountry);
                    $country = $countryArray['countries_iso_code_2'];
                    $billingAddress->addChild('country-code',utf8_encode($country));

                    $shippingAddress->addCDataChild('street', removeSpecialChars(utf8_encode($order['delivery_street_address'])));
                    $shippingAddress->addChild('zip-code', utf8_encode($order['delivery_postcode']));
                    $shippingAddress->addCDataChild('city', removeSpecialChars(utf8_encode($order['delivery_city'])));
                    $sqlCountry = "SELECT * FROM `countries` WHERE `countries_name` = '" . $order['delivery_country'] . "'";
                    $queryCountry = tep_db_query($sqlCountry);
                    $countryArray = tep_db_fetch_array($queryCountry);
                    $country = $countryArray['countries_iso_code_2'];
                    $shippingAddress->addChild('country-code',utf8_encode($country));

                    $customer->addChild('nationality', utf8_encode($country));
                    $customer->addChild('customer-allow-credit-inquiry', 'yes');
                    $shoppingBasket = $content->addChild('shopping-basket');
                    $shoppingBasket->addAttribute('currency', 'EUR');
                    $items = $shoppingBasket->addChild('items');
                    $sql = "select * from " . $pi_table_prefix . "_orderdetails a left join orders_products b on b.orders_id = a.order_number and a.article_number = b.orders_products_id where  a.order_number = '" . tep_db_input($oID) . "' and  article_number != ''";
                    $query = tep_db_query($sql);
                    $i = 0;
                    $shippingCost = 0;
                    $couponTax = 0;
                    while ($mItem = tep_db_fetch_array($query)) {
                        $qty = ($mItem['ordered'] - $mItem['returned'] - $mItem['canceled']);
                        if ($qty > 0) {
                            $arr = str_split($mItem['article_number'], 8);
                            if ($mItem['article_name'] != 'pi-Merchant-Voucher' && $mItem['article_number'] != 'SHIPPING' && $arr[0] != 'DISCOUNT') {
                                $items->addCDataChild('item', removeSpecialChars(utf8_encode($mItem['article_name'])));
                                $items->item[$i]->addAttribute('article-number', $mItem['products_id']);
                                $items->item[$i]->addAttribute('quantity', $qty);
                                $zwischenPrice = $mItem['products_price'] * $pi_order->info['currency_value'];
                                $zwischenTax = $zwischenPrice/100 * $mItem['products_tax'];
                                $items->item[$i]->addAttribute('unit-price', number_format($zwischenPrice, 2, '.', ''));
                                $items->item[$i]->addAttribute('total-price', number_format($zwischenPrice * $qty, 2, '.', ''));
                                $items->item[$i]->addAttribute('tax', number_format($qty * $zwischenTax, 2, '.', ''));
                            } else if ($mItem['article_name'] == 'pi-Merchant-Voucher') {
                                $items->addChild('item', PI_RATEPAY_VOUCHER);
                                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                                $items->item[$i]->addAttribute('quantity', $qty);
                                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                                $items->item[$i]->addAttribute('total-price', number_format($qty * $mItem['article_netUnitPrice'], 2, '.', ''));
                                $items->item[$i]->addAttribute('tax', number_format(0, 2, '.', ''));
                            } else if ($mItem['article_number'] == 'SHIPPING') {
                                $shipping_method_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_shipping'");
                                $shipping_method = tep_db_fetch_array($shipping_method_query);
                                $shippingPrice = number_format($shipping_method['value'] * $pi_order->info['currency_value'],2,".","");
                                $items->addChild('item', utf8_encode($mItem['article_name']));
                                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                                $items->item[$i]->addAttribute('quantity', $qty);
                                $items->item[$i]->addAttribute('unit-price', number_format($shippingPrice - $mItem['tax'], 2, '.', ''));
                                $items->item[$i]->addAttribute('total-price', number_format(($qty * ($shippingPrice - $mItem['tax'])), 2, '.', ''));
                                $items->item[$i]->addAttribute('tax', number_format($qty * ($mItem['tax']), 2, '.', ''));
                            } else if ($arr[0] == 'DISCOUNT') {
                                $items->addChild('item', utf8_encode($mItem['article_name']));
                                $items->item[$i]->addAttribute('article-number', $mItem['article_number']);
                                $items->item[$i]->addAttribute('quantity', $qty);
                                $items->item[$i]->addAttribute('unit-price', number_format($mItem['article_netUnitPrice'], 2, '.', ''));
                                $items->item[$i]->addAttribute('total-price', number_format(($qty * $mItem['article_netUnitPrice']), 2, '.', ''));
                                $items->item[$i]->addAttribute('tax', number_format($qty * ($mItem['tax']), 2, '.', ''));
                            }
                            $i++;
                        }
                    }
                    $sql = "SELECT count( * ) as nr FROM " . $pi_table_prefix . "_orderdetails WHERE article_name = 'pi-Merchant-Voucher'";
                    $query = tep_db_query($sql);
                    $nr = tep_db_fetch_array($query);
                    if (!empty($_POST['voucherAmount']) && !empty($_POST['voucherAmountKomma'])) {
                        $items->addChild('item', PI_RATEPAY_VOUCHER);
                        $items->item[$i]->addAttribute('article-number', "pi-Merchant-Voucher-" . $nr['nr']);
                        $items->item[$i]->addAttribute('quantity', '1');
                        $items->item[$i]->addAttribute('unit-price', number_format($piRatepayVoucher, 2, ".", ""));
                        $items->item[$i]->addAttribute('total-price', number_format($piRatepayVoucher, 2, ".", ""));
                        $items->item[$i]->addAttribute('tax', number_format(0, 2, ".", ""));
                    }
                    $content->addChild('payment');
                    $payment = $content->payment;
                    $payment->addAttribute('method', $pi_payment_type);
                    $payment->addAttribute('currency', 'EUR');

                    $total = ($order['value']*$pi_order->info['currency_value'] + $piRatepayVoucher);

                    // Add the shopping basket amoutn later because we need the shipping cost
                    $shoppingBasket->addAttribute('amount', number_format(($total), 2, '.', ''));
                    $payment->addChild('amount', number_format(($total), 2, '.', ''));
                    $payment->addChild('usage', utf8_encode($pi_ratepay->testOrLiveUsage()));
                    if ($pi_payment_type == "INSTALLMENT") {
                        $payment->addChild('installment-details');
                        $payment->addChild('debit-pay-type', 'BANK-TRANSFER');
                    }
                    $response = $ratepay->paymentOperation($request);
                    $first_name = removeSpecialChars(utf8_encode($orderData['first_name']));
                    $last_name = removeSpecialChars(utf8_encode($orderData['last_name']));
                    if ($response) {
                        $resultCode = (string) $response->head->processing->result->attributes()->code;
                        $result = (string) $response->head->processing->result;

                        $pi_ratepay->piRatepayLog($oID, $orderData['transaction_id'], $operation, $subOperation, $request, $response, $first_name, $last_name);
                        if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "403") {
                            $sql = "INSERT INTO " . $pi_table_prefix . "_orderdetails
										(order_number, article_number,
										article_name, ordered, article_netUnitPrice) VALUES
										('" . $oID . "', 'pi-Merchant-Voucher-" . tep_db_input($nr['nr']) . "',
										'pi-Merchant-Voucher',1," . tep_db_input($piRatepayVoucher) . ")";
                            tep_db_query($sql);
                            $sql = "INSERT INTO " . $pi_table_prefix . "_history
										(order_number, article_number,
										quantity, method, submethod) VALUES
										('" . tep_db_input($oID) . "', 'pi-Merchant-Voucher-" . tep_db_input($nr['nr']) . "',
										'1',
										'Credit created', 'added')";
                            tep_db_query($sql);

                            $discountSql = "SELECT * FROM `orders_total` WHERE class='pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                            $discountResult = tep_db_query($discountSql);
                            $discountCount = tep_db_num_rows($discountResult);
                            if ($discountCount > 0) {
                                $discountArray = tep_db_fetch_array($discountResult);
                                $value = $discountArray['value'];
                                $value = $value + $piRatepayVoucher;
                                $value = number_format($value, 4, ".", "");
                                $discountTotalUpdate = "update orders_total set value = " . tep_db_input($value) . " where class='pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                                tep_db_query($discountTotalUpdate);
                                $value = number_format($value, 2, ",", "");
                                $discountTotalUpdate = "update orders_total set text = '<font color=\"ff0000\">" . tep_db_input($value) . " EUR</font>' where class='pi_ratepay_voucher' and orders_id = '" . tep_db_input($oID) . "'";
                                tep_db_query($discountTotalUpdate);
                            } else {
                                $value = number_format($piRatepayVoucher, 4, ".", "");
                                $valueFormat = number_format($value, 2, ",", "");
                                $discountTotalInsert = "INSERT INTO `orders_total` (`orders_id`, `title`, `text`, `value`, `class`, `sort_order`) VALUES ('" . tep_db_input($oID) . "', 'Gutschein:', '<font color=\"ff0000\"> " . tep_db_input($valueFormat) . " EUR</font>', " . tep_db_input($value) . ", 'pi_ratepay_voucher', 98)";
                                tep_db_query($discountTotalInsert);
                            }

                            $sql = "update orders_total set value = value + " . ($piRatepayVoucher/$pi_order->info['currency_value']). " where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                            tep_db_query($sql);
                            $sql = "select value from orders_total where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                            $totalq = tep_db_query($sql);
                            $total = tep_db_fetch_array($totalq);
                            $totalText = number_format($total['value']*$pi_order->info['currency_value'], 2, ",", ".");
                            $sql = "update orders_total set text = '<b>" . tep_db_input($totalText) . " EUR</b>' where class = 'ot_total' and orders_id = '" . tep_db_input($oID) . "'";
                            tep_db_query($sql);

                            $message = PI_RATEPAY_SUCCESSVOUCHER;
                            return array('result' => 'SUCCESS', 'message' => $message);
                        }
                        else {
                            $message = PI_RATEPAY_ERRORVOUCHER;
                            return array('result' => 'ERROR', 'message' => $message);
                        }
                    }
                    else {
                        $message = PI_RATEPAY_SERVICE;
                        return array('result' => 'ERROR', 'message' => $message);
                    }
                }
            }
            else {
                $message = PI_RATEPAY_ERRORVOUCHER;
                return array('result' => 'ERROR', 'message' => $message);
            }
        }
    }
}

/*
 * This method removes some special chars
 *
 * @return string
 */
function removeSpecialChars($str) {
    $search = array("–", "´", "‹", "›", "‘", "’", "‚", "“", "”", "„", "‟", "•", "‒", "―", "—", "™", "¼", "½", "¾");
    $replace = array("-", "'", "<", ">", "'", "'", ",", '"', '"', '"', '"', "-", "-", "-", "-", "TM", "1/4", "1/2", "3/4");
    return removeSpecialChar($search, $replace, $str);
}

/*
 * This method removes some special chars
 *
 * @return string
 */

function removeSpecialChar($search, $replace, $subject) {
    $str = str_replace($search, $replace, $subject);
    return $str;
}

?>
