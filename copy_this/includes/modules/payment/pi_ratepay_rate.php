<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  PayIntelligent
 * @package   PayIntelligent_Ratepay
 * @copyright (C) 2010 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license   GPLv2
 * */
class pi_ratepay_rate {

    //Begin default
    var $code;
    var $title;
    var $public_title;
    var $description;
    var $enabled;
    var $_check;
    //End default

    var $profileId;
    var $securityCode;
    var $sandbox;
    var $logs;
    var $gtcURL;
    var $privacyURL;
    var $merchantPrivacyURL;
    var $merchantName;
    var $bankName;
    var $sortCode;
    var $accountNr;
    var $swift;
    var $iban;
    var $email;
    var $extraField;
    var $min;
    var $max;
    var $owner;
    var $hr;
    var $court;
    var $fon;
    var $fax;
    var $street;
    var $plz;
    var $descriptor;
    var $transId;
    var $transShortId;
    var $ust;
    var $debtholder;

    /**
     * This constructor set's all properties for the pi_ratepay_rate object
     */
    function pi_ratepay_rate() {
        global $order;
        //Begin default
        $this->code = 'pi_ratepay_rate';
        $this->title = MODULE_PAYMENT_PI_RATEPAY_RATE_TEXT;
        $this->public_title = MODULE_PAYMENT_PI_RATEPAY_RATE_TEXT_TITLE;
        $this->description = utf8_decode(MODULE_PAYMENT_PI_RATEPAY_RATE_TEXT_DESCRIPTION);
        $this->enabled = ((MODULE_PAYMENT_PI_RATEPAY_RATE_STATUS == 'True') ? true : false);
        $this->signature = "pi_ratepay|pi_ratepay_rechnung|1.2.1|1.2.1";
        //End default
        //Begin custom
        $this->profileId = MODULE_PAYMENT_PI_RATEPAY_RATE_PROFILE_ID;
        $this->securityCode = MODULE_PAYMENT_PI_RATEPAY_RATE_SECURITY_CODE;
        $this->sandbox = ((MODULE_PAYMENT_PI_RATEPAY_RATE_SANDBOX == 'True') ? true : false);
        $this->logs = ((MODULE_PAYMENT_PI_RATEPAY_RATE_LOGS == 'True') ? true : false);
        $this->gtcURL = MODULE_PAYMENT_PI_RATEPAY_RATE_GTC;
        $this->privacyURL = MODULE_PAYMENT_PI_RATEPAY_RATE_PRIVACY;
        $this->merchantPrivacyURL = MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_PRIVACY;
        $this->merchantName = MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_NAME;
        $this->bankName = MODULE_PAYMENT_PI_RATEPAY_RATE_BANK_NAME;
        $this->sortCode = MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_CODE;
        $this->accountNr = MODULE_PAYMENT_PI_RATEPAY_RATE_ACCOUNT_NR;
        $this->swift = MODULE_PAYMENT_PI_RATEPAY_RATE_SWIFT;
        $this->iban = MODULE_PAYMENT_PI_RATEPAY_RATE_IBAN;
        $this->email = MODULE_PAYMENT_PI_RATEPAY_RATE_EMAIL;
        $this->extraField = MODULE_PAYMENT_PI_RATEPAY_RATE_EXTRA_FIELD;
        $this->min = MODULE_PAYMENT_PI_RATEPAY_RATE_MIN;
        $this->max = MODULE_PAYMENT_PI_RATEPAY_RATE_MAX;

        $this->owner = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_OWNER;
        $this->hr = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_HR;
        $this->court = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_COURT;
        $this->fon = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FON;
        $this->fax = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FAX;
        $this->street = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_STREET;
        $this->plz = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_PLZ;
        $this->ust = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_UST;
        $this->debtholder = MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_DEBT_HOLDER;
        //End custom

        $this->sort_order = MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_ORDER;

        if ((int) MODULE_PAYMENT_PI_RATEPAY_RATE_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_PI_RATEPAY_RATE_ORDER_STATUS_ID;
        }
        $this->check();
        if (is_object($order)) {
            $this->update_status();
        }
    }

    /**
     * Updates the Status
     */
    function update_status() {
        global $order;
        if (($this->enabled == true) && ((int) MODULE_PAYMENT_PI_RATEPAY_RATE_ZONE > 0)) {
            $check_flag = false;
            $check_query = tep_db_query("SELECT zone_id from "
                    . TABLE_ZONES_TO_GEO_ZONES . " WHERE geo_zone_id = '"
                    . MODULE_PAYMENT_PI_RATEPAY_RATE_ZONE . "' and zone_country_id = '"
                    . tep_db_input($order->billing['country']['id']) . "' order by zone_id");

            while ($check = tep_db_fetch_array($check_query)) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check['zone_id'] == $order->billing['zone_id']) {
                    $check_flag = true;
                    break;
                }
            }

            if ($check_flag == false) {
                $this->enabled = false;
            }
        }
    }

    /*
     * Javascript Validation
     *
     * @return boolean
     *
     */

    function javascript_validation() {
        return false;
    }

    /**
     * This function checks whether to display RatePAY Rate or not
     *
     * @return boolean
     */
    function selection() {
        global $order, $currency;
        unset($_SESSION['pi']['confirm']);
        $customerId = $_SESSION['customer_id'];

        $query = tep_db_query("SELECT customers_gender, customers_dob, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " WHERE customers_id ='" . tep_db_input($customerId) . "' ");
        $customer = tep_db_fetch_array($query);

        $fieldsBool = false;

        $fields = array();

        if ($customer['customers_telephone'] == '' || !preg_match('/^[0-9\/\-+() ]*$/', $customer['customers_telephone'])) {
            $fieldsBool = true;
            $fields[] = array('title' => 'Telefon', 'field' => tep_draw_input_field('pi_phone_rate', ''));
        }

        if ($customer['customers_dob'] == '0000-00-00 00:00:00') {
            $fieldsBool = true;
            $fields[] = array('title' => 'Geburtstag', 'field' => tep_draw_input_field('pi_birthdate_rate', '') . " " . PI_RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE_FORMAT);
        }

        if (($order->customer['company'] != '' || $order->billing['company'] != '')) {
            $fieldsBool = true;
            $fields[] = array('title' => PI_RATEPAY_RATE_VIEW_PAYMENT_VATID, 'field' => tep_draw_input_field('pi_vatid_rate', ''));
        }

        if ($fieldsBool) {
            $display = array('id' => $this->code, 'module' => tep_image(DIR_WS_IMAGES . '/pi_ratepay_rate_checkout_logo.png'), 'fields' => $fields);
        } else {
            $display = array('id' => $this->code, 'module' => tep_image(DIR_WS_IMAGES . '/pi_ratepay_rate_checkout_logo.png'));
        }

        $customer_country = $order->customer['country']['iso_code_2'];

        $currency = $_SESSION ['currency'];

        if ($customer_country != "DE") {
            $display = null;
        }

        //Check allowed currency
        if (strtoupper($currency) != "EUR") {
            $display = null;
        }
        //Compare billing and delivery address
        if (sizeof($order->delivery) != sizeof($order->billing)) {
            $display = null;
        } else {
            if (is_array($order->billing)) {
                foreach ($order->billing as $key => $val) {
                    if ($order->billing[$key] != $order->delivery[$key]) {
                        $display = null;
                    }
                }
            }
        }
        if (isset($_SESSION['disable'])) {
            if ($_SESSION['disable'] == true) {
                $display = null;
            }
        }

        if ($customer['customers_dob'] != '0000-00-00 00:00:00') {
            $geb = strval($customer['customers_dob']);
            $gebtag = explode("-", $geb);
            // explode day form time (14 00:00:00)
            $birthDay = explode(" ", $gebtag[2]);

            $stampBirth = mktime(0, 0, 0, $gebtag[1], $birthDay[0], $gebtag[0]);
            $result['stampBirth'] = $stampBirth;
            // fetch the current date (minus 18 years)
            $today['day'] = date('d');
            $today['month'] = date('m');
            $today['year'] = date('Y') - 18;

            // generate todays timestamp
            $stampToday = mktime(0, 0, 0, $today['month'], $today['day'], $today['year']);
            $result['$stampToday'] = $stampToday;
            $flag = false;
            if ($stampBirth > $stampToday) {
                $display = null;
            }
        }

        $min_order = $this->min;
        $max_order = $this->max;
        //Check minimum order size and maximum order size
        if ((floatval($this->getOrderTotal($order)) < floatval($min_order)) || (floatval($this->getOrderTotal($order)) > floatval($max_order))) {
            $display = null;
        }
        $_SESSION['discount_applied'] = false;
        return $display;
    }

    /**
     * Check if the customer is over 18 years or redirect with an error message
     *
     * @param string $dateStr
     */
    function verifyAge($dateStr) {
        $today = array();
        $geb = strval($dateStr);

        $gebtag = explode(".", $geb);
        $birthDay = explode(" ", $gebtag[2]);

        $stampBirth = mktime(0, 0, 0, $gebtag[1], $gebtag[0], $birthDay[0]);
        $today['day'] = date('d');
        $today['month'] = date('m');
        $today['year'] = date('Y') - 18;
        $stampToday = mktime(0, 0, 0, $today['month'], $today['day'], $today['year']);

        if ($stampBirth > $stampToday) {
            $errorStr = urlencode(PI_RATEPAY_RATE_ERROR_AGE);
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
        }
    }

    /**
     * Checks if all needed Data is set and initializes RatePAY Payment
     *
     * @return boolean
     */
    function pre_confirmation_check() {
        global $order, $coupon;
        $_SESSION['pi_ratepay_rate_order_total'] = $this->getOrderTotal($order, true);
        $_SESSION['discount_applied'] = true;
        if (isset($_SESSION['pi_ratepay_rate_conditions']) && $_SESSION['pi_ratepay_rate_conditions'] == true) {
            unset($_SESSION['pi_ratepay_rate_conditions']);
            $response = $this->paymentInit($order);
            if ($response) {
                if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "350") {
                    
                    return false;
                } else {
                    $errorStr = urlencode(PI_RATEPAY_RATE_ERROR_GATEWAY);
                    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
                }
            } else {
                $_SESSION['disable'] = true;
                $errorStr = urlencode(PI_RATEPAY_RATE_ERROR);
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
            }
        } else {
            if ($this->getOrderTotal($order) < floatval($this->min) || $this->getOrderTotal($order) > floatval($this->max)) {
                $errorStr = urlencode(sprintf(PI_RATEPAY_RATE_ERROR_AMOUNT, $this->min, $this->max));
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT,'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
            }
            
            $successFon = false;
            $successDate = false;
            $inputNeededFon = false;
            $inputNeededBirthdate = false;
            if (isset($_POST['pi_phone_rate']) && isset($_POST['pi_birthdate_rate'])) {

                $inputNeededFon = true;
                $inputNeededBirthdate = true;

                if ($_POST['pi_phone_rate'] != '' && preg_match('/^[0-9\/\-+() ]*$/', $_POST['pi_phone_rate'])) {
                    $successFon = true;
                    $customerId = $_SESSION['customer_id'];
                    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_telephone = '" . tep_db_prepare_input($_POST['pi_phone_rate']) . "' WHERE customers_id ='" . tep_db_input($customerId) . "' ");
                }

                $dob = tep_db_prepare_input($_POST['pi_birthdate_rate']);
                if (is_numeric(substr(tep_date_raw($dob), 4, 2)) && is_numeric(substr(tep_date_raw($dob), 6, 2)) && is_numeric(substr(tep_date_raw($dob), 0, 4))) {
                    if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
                        $successDate = true;
                        $customerId = $_SESSION['customer_id'];
                        $dateStr = substr(tep_date_raw($dob), 6, 2) . "." . substr(tep_date_raw($dob), 4, 2) . "." . substr(tep_date_raw($dob), 0, 4) . " 00:00:00";
                        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_dob = '" . tep_date_raw($dateStr) . "' WHERE customers_id ='" . tep_db_input($customerId) . "' ");
                        $this->verifyAge($dateStr);
                    }
                }
            } else if (isset($_POST['pi_phone_rate'])) {
                $inputNeededFon = true;

                if ($_POST['pi_phone_rate'] != '' && preg_match('/^[0-9\/\-+() ]*$/', $_POST['pi_phone_rate'])) {
                    $successFon = true;
                    $customerId = $_SESSION['customer_id'];
                    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_telephone = '" . tep_db_prepare_input($_POST['pi_phone_rate']) . "' WHERE customers_id ='" . tep_db_input($customerId) . "' ");
                }
            } else if (isset($_POST['pi_birthdate_rate'])) {
                $inputNeededBirthdate = true;

                $dob = tep_db_prepare_input($_POST['pi_birthdate_rate']);

                if (is_numeric(substr(tep_date_raw($dob), 4, 2)) && is_numeric(substr(tep_date_raw($dob), 6, 2)) && is_numeric(substr(tep_date_raw($dob), 0, 4))) {
                    if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
                        $successDate = true;
                        $customerId = $_SESSION['customer_id'];
                        $dateStr = substr(tep_date_raw($dob), 6, 2) . "." . substr(tep_date_raw($dob), 4, 2) . "." . substr(tep_date_raw($dob), 0, 4) . " 00:00:00";
                        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_dob = '" . tep_date_raw($dateStr) . "' WHERE customers_id ='" . tep_db_input($customerId) . "' ");
                        $this->verifyAge($dateStr);
                    }
                }
            }

            $customerId = $_SESSION['customer_id'];
            $query = tep_db_query("SELECT customers_gender, customers_dob, customers_email_address, customers_telephone, customers_fax, customers_default_address_id from " . TABLE_CUSTOMERS . " WHERE customers_id ='" . tep_db_input($customerId) . "' ");
            $customerXTC = tep_db_fetch_array($query);

            if (($order->customer['company'] != '' || $order->billing['company'] != '') && $_POST['pi_vatid_rate'] == '') {
                $errorStr = urlencode(PI_RATEPAY_RATE_ERROR_VATID_ERROR);
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
            } else if (($order->customer['company'] != '' || $order->billing['company'] != '') && $_POST['pi_vatid_rate'] != '') {
                $_SESSION['pi']['vatid'] = $_POST['pi_vatid_rate'];
            }

            if ($inputNeededFon == true && $inputNeededBirthdate == true) {
                if ($successDate) {
                    $this->verifyAge($dateStr);
                }

                if ($successFon == true && $successDate == true) {
                    if ($this->is_osc231()) {
                        tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms_osc231.php", "coupon=" . $coupon, 'SSL', true));
                    } else {
                        tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms.php?osCsid=" . tep_session_id() . "&coupon=" . $coupon.'&', '', 'SSL'));
                    }
                } else {
                    if ($successFon == false && $successDate == false) {
                        $errorStr = urlencode(PI_RATEPAY_RATE_ERROR_PHONE_AND_BIRTH);
                        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
                    } else if ($successDate == false) {
                        $errorStr = urlencode(PI_RATEPAY_RATE_ERROR_BIRTH);
                        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
                    } else if ($successFon == false) {
                        $errorStr = urlencode(PI_RATEPAY_RATE_ERROR_PHONE);
                        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
                    }
                }
            } else if ($inputNeededFon) {
                if ($successFon) {
                    if ($this->is_osc231()) {
                        tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms_osc231.php", "coupon=" . $coupon, 'SSL', true));
                    } else {
                        tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms.php?osCsid=" . tep_session_id() . "&coupon=" . $coupon.'&', '', 'SSL'));
                    }
                } else {
                    $errorStr = urlencode(PI_RATEPAY_RATE_ERROR_PHONE);
                    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
                }
            } else if ($inputNeededBirthdate) {
                if ($successDate) {
                    if ($this->is_osc231()) {
                        tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms_osc231.php", "coupon=" . $coupon, 'SSL', true));
                    } else {
                        tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms.php?osCsid=" . tep_session_id() . "&coupon=" . $coupon.'&', '', 'SSL'));
                    }
                } else {
                    $errorStr = rawurlencode(PI_RATEPAY_RATE_ERROR_BIRTH);
                    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
                }
            } else {
                if ($this->is_osc231()) {
                    tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms_osc231.php", "coupon=" . $coupon, 'SSL', true));
                } else {
                    tep_redirect(tep_href_link("checkout_pi_ratepay_rate_terms.php?osCsid=" . tep_session_id() . "&coupon=" . $coupon.'&', '', 'SSL'));
                }
            }
        }
        return false;
    }

    function confirmation() {
        return false;
    }

    /*
     * This method creates the String for the process button
     *
     * @return String
     */

    function process_button() {
        global $HTTP_POST_VARS, $order;

        $payment_type = 'RATEPAY';

        if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1) {
            $this->amount = $order->info['total'] + $order->info['tax'];
        } else {
            $this->amount = $order->info['total'];
        }

        $this->amount = number_format($this->amount, 2, '.', '');

        $process_button_string = tep_draw_hidden_field('paymentType', $payment_type);


        return $process_button_string;
    }

    /*
     * Requests the Payment requests and handles the response.
     *
     * @return boolean
     */

    function before_process() {
        global $HTTP_POST_VARS, $order, $currency, $customer_id;
        global $language;
        $orderId = $_SESSION['success_order_id'];
        if ($orderId == '') {
            $orderId = 'n/a';
        }
        $transactionId = $_SESSION['pi']['tid'];

        $return = $this->paymentRequest($order);

        $request = $return[0];
        $response = $return[1];
        $first_name = $this->removeSpecialChars($order->delivery['firstname']);
        $last_name = $this->removeSpecialChars($order->delivery['lastname']);

        if ($response) {
            $this->piRatepayLog($orderId, $transactionId, 'PAYMENT_REQUEST', 'n/a', $request, $response, $first_name, $last_name);
            if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "402") {
                $this->descriptor = (string) $response->content->payment->descriptor;
                $this->transId = (string) $response->head->{'transaction-id'};
                $this->transShortId = (string) $response->head->{'transaction-short-id'};
            } else {
                $_SESSION['disable'] = true;
                $errorStr = urlencode(PI_RATEPAY_RATE_ERROR);
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
            }
        } else {
            $_SESSION['disable'] = true;
            $this->piRatepayLog($orderId, $transactionId, 'PAYMENT_REQUEST', 'n/a', $request, false, $first_name, $last_name);
            $errorStr = urlencode(PI_RATEPAY_RATE_ERROR);
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
        }
        return false;
    }

    /**
     * Confirm of the order and requesting RatePAY Confirm and handles the Response and saving all necessary Data to DB
     *
     */
    function after_process() {
        $vat_id = $_SESSION['pi']['vatid'];
        unset($_SESSION['pi']['company']);
        unset($_SESSION['pi']['vatid']);
        global $HTTP_POST_VARS, $order, $insert_id;
        global $language;
        include_once(DIR_WS_CLASSES . 'pi_order.php');
        include_once(DIR_WS_CLASSES . 'order.php');
        $order = new order($insert_id);
        $neworder = new pi_order($insert_id);
        $return = $this->paymentConfirm($insert_id);
        $customerId = $_SESSION ['customer_id'];
        $query = tep_db_query("SELECT customers_gender, customers_dob, customers_email_address, customers_telephone, customers_fax, customers_default_address_id from " . TABLE_CUSTOMERS . " WHERE customers_id ='" . tep_db_input($customerId) . "' ");
        $customerXTC = tep_db_fetch_array($query);
        $request = $return[0];
        $response = $return[1];
        $orderId = $insert_id;

        $name = explode(' ', $order->delivery['name']);
        $first_name = $name[0];
        $last_name = '';
        for ($i = 1; $i < sizeof($name);$i++) {
            $last_name .= $name[$i];
        }

        if ($response) {
            $transactionId = $this->transId;
            $transactionShortId = $this->transShortId;
            $this->piRatepayLog($orderId, $transactionId, 'PAYMENT_CONFIRM', 'n/a', $request, $response, $first_name, $last_name);

            if ((string) $response->head->processing->status->attributes()->code == "OK" && (string) $response->head->processing->result->attributes()->code == "400") {

                $id = $insert_id;

                $sql = "INSERT INTO pi_ratepay_rate_orders (order_number, transaction_id, transaction_short_id, descriptor, first_name, last_name, dob, gender, vat_id, coupon_code)
                                                        VALUES ('" . tep_db_input($id) . "', '" . tep_db_input($transactionId) . "', '" . tep_db_input($transactionShortId)
                        . "','" . tep_db_input($this->descriptor) . "', '" . tep_db_input($first_name) . "', '" . tep_db_input($last_name)
                        . "', '" . tep_db_input($customerXTC['customers_dob']) . "', '" . tep_db_input($customerXTC['customers_gender'])
                        . "', '" . tep_db_input($vat_id) . "', '" . $order->coupon->coupon['coupons_id'] . "')";

                tep_db_query($sql);

                for ($i = 0; $i <= sizeof($neworder->products); $i++) {

                    $attributes = "";

                    if (isset($neworder->products[$i]['attributes'])) {
                        foreach ($neworder->products[$i]['attributes'] as $attr) {
                            $attributes = $attributes . ", " . $attr['option'] . ": " .
                                    $attr['value'];
                        }
                    }
                    $name = strip_tags($neworder->products[$i]['name'] . $attributes);
                    $name = mysql_real_escape_string($name);
                    $price = ($neworder->products[$i]['final_price'] * $neworder->info['currency_value']) * ((100 + $neworder->products[$i]['tax']) / 100);
                    $qty = intval($neworder->products[$i]['qty']);
                    if ($price > 0) {
                        $sql = "INSERT INTO pi_ratepay_rate_orderdetails (order_number,article_number, real_article_number, article_name,ordered,article_netUnitPrice)
										VALUES ('" . tep_db_input($id) . "', '" . tep_db_input($neworder->products[$i]['opid']) . "', '" . tep_db_input(tep_get_prid($neworder->products[$i]['id'])) . "','" . tep_db_input($name) . "', " . tep_db_input($qty) . ", " . number_format($price, 2) . ")";

                        tep_db_query($sql);
                    }
                }
                
                if (isset($_SESSION['pi']['discount'])) {
                    foreach ($_SESSION['pi']['discount'] as $discount) {
                        if (number_format($discount['amount'], 2, ".", "") != 0) {
                            $sql = "select count(*) as nr from pi_ratepay_rate_orderdetails where article_number like 'DISCOUNT%'";
                            $query = tep_db_query($sql);
                            $nr = tep_db_fetch_array($query);
                            $sql = "INSERT INTO pi_ratepay_rate_orderdetails (order_number,article_number, real_article_number, article_name, ordered, article_netUnitPrice, tax)
                                                                                    VALUES ('" . tep_db_input($id) . "', '" . tep_db_input('DISCOUNT' . $nr['nr']) . "', '" . tep_db_input('DISCOUNT') . "','" . tep_db_input($discount['name']) . "', " . tep_db_input(1) . ", " . number_format($discount['amount'], 2) . ", " . number_format($discount['tax'], 2) . ")";

                            tep_db_query($sql);
                        }
                    }
                }

                if (isset($_SESSION['pi_ratepay']['shipping'])) {
                    $shippingCost = $_SESSION['pi_ratepay']['shipping'];
                    $sql = "INSERT INTO pi_ratepay_rate_orderdetails (order_number,article_number,real_article_number,article_name,ordered,article_netUnitPrice, tax)
									VALUES ('" . tep_db_input($id) . "', 'SHIPPING', 'SHIPPING', 'Versand', 1, " . number_format($shippingCost, 2, ".", "") . ", " . number_format($_SESSION['pi_ratepay']['shipping_tax'], 2, ".", "") . ")";
                    tep_db_query($sql);
                    unset($_SESSION['pi_ratepay']['shipping']);
                }

                $total_amount = $_SESSION['pi_ratepay_rate_total_amount'];
                $amount = $_SESSION['pi_ratepay_rate_amount'];
                $interest_amount = $_SESSION['pi_ratepay_rate_interest_amount'];
                $service_charge = $_SESSION['pi_ratepay_rate_service_charge'];
                $annual_percentage_rate = $_SESSION['pi_ratepay_rate_annual_percentage_rate'];
                $monthly_debit_interest = $_SESSION['pi_ratepay_rate_monthly_debit_interest'];
                $number_of_rates = $_SESSION['pi_ratepay_rate_number_of_rates'];
                $rate = $_SESSION['pi_ratepay_rate_rate'];
                $last_rate = $_SESSION['pi_ratepay_rate_last_rate'];
                tep_db_query("DELETE FROM `pi_ratepay_rate_details` where orderid = '" . tep_db_input($id) . "'");
                tep_db_query("INSERT INTO `pi_ratepay_rate_details` (`orderid`,`totalamount`, `amount`, `interestamount`, `servicecharge`, `annualpercentagerate`, `monthlydebitinterest`, `numberofrates`, `rate`, `lastrate`) VALUES ('" . tep_db_input($id) . "','" . tep_db_input($total_amount) . "', '" . tep_db_input($amount) . "', '" . tep_db_input($interest_amount) . "', '" . tep_db_input($service_charge) . "', '" . tep_db_input($annual_percentage_rate) . "', '" . tep_db_input($monthly_debit_interest) . "', '" . tep_db_input($number_of_rates) . "','" . tep_db_input($rate) . "', '" . tep_db_input($last_rate) . "')");
            } else {
                $_SESSION['disable'] = true;
                $errorStr = urlencode(PI_RATEPAY_RATE_ERROR);
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
            }
        } else {
            $_SESSION['disable'] = true;
            $errorStr = urlencode(PI_RATEPAY_RATE_ERROR);
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $errorStr, 'SSL'));
            $this->piRatepayLog($orderId, $transactionId, 'PAYMENT_CONFIRM', 'n/a', $request, false, $first_name, $last_name);
        }

        if ($this->order_status) {
            tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status='" . tep_db_input($this->order_status) . "' WHERE orders_id='" . tep_db_input($insert_id) . "'");
        }
    }

    /**
     * Getting the Error
     *
     * @return array
     */
    function get_error() {
        $errorstr = html_entity_decode(urldecode($_GET['error']));
        if (empty($errorstr)) {
            $errorstr = utf8_encode(html_entity_decode(urldecode($_GET['amp;error'])));
        }
        $error = array('title' => '', 'error' => $errorstr);
        return $error;
    }

    /*
     * Checks if RatePAY Rate is enabled.
     *
     * @return boolean
     */

    function check() {
        if (!isset($this->_check)) {
            $check_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PI_RATEPAY_RATE_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        }
        return $this->_check;
    }

    /**
     * This method create's all necessary Database entries for RatePAY Rate
     */
    function install() {
        global $language;
        @include(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/payment/pi_ratepay_rate.php');

        $check_query = tep_db_query("SHOW TABLES LIKE 'pi_ratepay_rate_orders'");
        if (tep_db_num_rows($check_query) == 0) {
            tep_db_query(
                    "CREATE TABLE `pi_ratepay_rate_orders`(
						`id` int(11) NOT NULL auto_increment,
						`order_number` varchar(32) character set latin1 collate latin1_general_ci NOT NULL,
						`transaction_id` varchar(64) NOT NULL,
						`transaction_short_id` varchar(20) NOT NULL,
						`return_amount` decimal(9,2) NOT NULL DEFAULT '0.00',
						`descriptor` varchar(20),
                                                `first_name` varchar(64),
                                                `last_name` varchar(64),
                                                `dob` date,
                                                `gender` varchar(64),
                                                `vat_id` varchar(64),
                                                `coupon_code` varchar(64),
						PRIMARY KEY  (`id`)
						) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }
        $check_query = tep_db_query("SHOW TABLES LIKE 'pi_ratepay_rate_orderdetails'");
        if (tep_db_num_rows($check_query) == 0) {
            tep_db_query(
                    "CREATE TABLE `pi_ratepay_rate_orderdetails` (
						  `id` INT NOT NULL AUTO_INCREMENT,
						  `order_number` VARCHAR( 255 ) NOT NULL ,
						  `article_number` VARCHAR( 255 ) NOT NULL ,
						  `real_article_number` VARCHAR( 255 ) NOT NULL ,
						  `article_name` VARCHAR(255) NOT NULL,
						  `ordered` INT NOT NULL DEFAULT '1',
						  `shipped` INT NOT NULL DEFAULT '0',
						  `cancelled` INT NOT NULL DEFAULT '0',
						  `article_netUnitPrice` decimal(10,2) NOT NULL DEFAULT '0',
                                                  `tax` decimal(10,2) NOT NULL DEFAULT '0',
						  `returned` INT NOT NULL DEFAULT '0',
						   PRIMARY KEY  (`id`)
						) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }
        $check_query = tep_db_query("SHOW TABLES LIKE 'pi_ratepay_rate_history'");
        if (tep_db_num_rows($check_query) == 0) {
            tep_db_query(
                    "CREATE TABLE `pi_ratepay_rate_history` (
						  `id` INT NOT NULL AUTO_INCREMENT,
						  `order_number` VARCHAR( 255 ) NOT NULL ,
						  `article_number` VARCHAR( 255 ) NOT NULL ,
						  `quantity` INT NOT NULL,
						  `method` VARCHAR( 40 ) NOT NULL,
						  `submethod` VARCHAR( 40 ) NOT NULL DEFAULT '',
						  `date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						   PRIMARY KEY  (`id`)
						) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }
        $check_query = tep_db_query("SHOW TABLES LIKE 'pi_ratepay_log'");
        if (tep_db_num_rows($check_query) == 0) {
            tep_db_query(
                    "CREATE TABLE `pi_ratepay_log` (
						  `id` INT NOT NULL AUTO_INCREMENT,
						  `order_number` VARCHAR( 255 ) NOT NULL,
						  `transaction_id` VARCHAR( 255 ) NOT NULL,
						  `payment_method` VARCHAR( 40 ) NOT NULL,
						  `payment_type` VARCHAR( 40 ) NOT NULL,
						  `payment_subtype` VARCHAR( 40 ) NOT NULL,
						  `result` VARCHAR( 40 ) NOT NULL,
						  `request` MEDIUMTEXT NOT NULL,
						  `response` MEDIUMTEXT NOT NULL,
						  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						  `result_code` VARCHAR( 10 ) NOT NULL,
                                                  `first_name` VARCHAR( 40 ) NOT NULL DEFAULT '',
                                                  `last_name` VARCHAR( 40 ) NOT NULL DEFAULT '',
                                                  `reason` VARCHAR( 255 ) NOT NULL DEFAULT '',
						   PRIMARY KEY  (`id`)
						) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }

        $check_query = tep_db_query("SHOW TABLES LIKE 'pi_ratepay_rate_details'");
        if (tep_db_num_rows($check_query) == 0) {
            tep_db_query(
                    "CREATE TABLE `pi_ratepay_rate_details` (
						`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
						`orderid` VARCHAR(255) NOT NULL ,
						`totalamount` DOUBLE NOT NULL ,
						`amount` DOUBLE NOT NULL ,
						`interestamount` DOUBLE NOT NULL ,
						`servicecharge` DOUBLE NOT NULL ,
						`annualpercentagerate` DOUBLE NOT NULL ,
						`monthlydebitinterest` DOUBLE NOT NULL ,
						`numberofrates` DOUBLE NOT NULL ,
						`rate` DOUBLE NOT NULL ,
						`lastrate` DOUBLE NOT NULL,
						`checkouttype` VARCHAR(255) DEFAULT '',
						`owner` VARCHAR(255) DEFAULT '',
						`bankaccountnumber` VARCHAR(255) DEFAULT '',
						`bankcode` VARCHAR(255) DEFAULT '',
						`bankname` VARCHAR(255) DEFAULT '',
						`iban` VARCHAR(255) DEFAULT '',
						`bicswift` VARCHAR(255) DEFAULT ''
						) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
            );
        }

        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_STATUS_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_STATUS', 'True', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_STATUS_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_PROFILE_ID_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_PROFILE_ID', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_PROFILE_ID_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SECURITY_CODE_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SECURITY_CODE', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SECURITY_CODE_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SANDBOX_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SANDBOX', 'False', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SANDBOX_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_LOGS_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_LOGS', 'False', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_LOGS_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_GTC_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_GTC', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_GTC_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_PRIVACY_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_PRIVACY', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_PRIVACY_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_PRIVACY_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_PRIVACY', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_PRIVACY_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_NAME_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_NAME', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_NAME_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_BANK_NAME_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_BANK_NAME', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_BANK_NAME_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_CODE_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_CODE', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_CODE_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_ACCOUNT_NR_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_ACCOUNT_NR', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_ACCOUNT_NR_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SWIFT_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SWIFT', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SWIFT_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_IBAN_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_IBAN', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_IBAN_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_MAX_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_MAX', '0', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_MAX_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_MIN_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_MIN', '0', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_MIN_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_EXTRA_FIELD_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_EXTRA_FIELD', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_EXTRA_FIELD_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_ORDER_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_ORDER', '0', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_ORDER_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_ZONE_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_ZONE', '0', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_ZONE_DESC . "', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_ORDER_STATUS_ID_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_ORDER_STATUS_ID', '0', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_ORDER_STATUS_ID_DESC . "', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_ALLOWED_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_ALLOWED', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_ALLOWED_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_DEBT_HOLDER_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_DEBT_HOLDER', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_DEBT_HOLDER_DESC . "', '6', '1', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_UST_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_UST', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_UST_DESC . "', '6', '1', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_OWNER_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_OWNER', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_OWNER_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_HR_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_HR', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_HR_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FON_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FON', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FON_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FAX_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FAX', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FAX_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_PLZ_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_PLZ', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_PLZ_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_STREET_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_STREET', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_STREET_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_COURT_TITLE . "', 'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_COURT', '', '" . MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_COURT_DESC . "', '6', '3', now())");

    }

    /*
     * Removes all RatePAY Rate DB Entries
     */

    function remove() {
        tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
        //tep_db_query("DROP TABLE `pi_ratepay_rate_history`, `pi_ratepay_rate_orderdetails`, `pi_ratepay_rate_orders`,`pi_ratepay_rate_details`");
    }

    /*
     * Setting all the RatePAY Rate Keys for Configuration
     *
     * @return array
     */

    function keys() {
        return array(
            'MODULE_PAYMENT_PI_RATEPAY_RATE_STATUS',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_PROFILE_ID',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SECURITY_CODE',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_MIN',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_MAX',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SANDBOX',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_LOGS',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_GTC',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_PRIVACY',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_PRIVACY',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_OWNER',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FON',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_FAX',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_STREET',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_PLZ',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_COURT',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_HR',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_MERCHANT_NAME',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_BANK_NAME',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_CODE',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_ACCOUNT_NR',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SWIFT',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_IBAN',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_UST',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SHOP_DEBT_HOLDER',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_EXTRA_FIELD',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_SORT_ORDER',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_ALLOWED',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_ZONE',
            'MODULE_PAYMENT_PI_RATEPAY_RATE_ORDER_STATUS_ID'
        );
    }

    /**
     * This method send's the PAYMENT_INIT request to the RatePAY API
     *
     * @return SimpleXML
     */
    function paymentInit($order) {
        include('ratepay_webservice/Ratepay_XML.php');

        $systemId = $_SERVER['SERVER_ADDR'];
        $operation = 'PAYMENT_INIT';
        $payment_subtype = 'n/a';

        //PAYMENT_INIT
        $ratepay = new Ratepay_XML;
        $ratepay->live = $this->testOrLive();

        $request = $ratepay->getXMLObject();

        $head = $request->addChild('head');
        $head->addChild('system-id', $systemId);
        $head->addChild('operation', $operation);

        $credential = $head->addChild('credential');
        $credential->addChild('profile-id', $this->profileId);
        $credential->addChild('securitycode', $this->securityCode);

        $response = $ratepay->paymentOperation($request);

        $transactionId = (string) $response->head->{'transaction-id'};
        $transactionShortId = (string) $response->head->{'transaction-short-id'};

        $_SESSION['pi']['tid'] = $transactionId;
        $_SESSION['pi']['tsid'] = $transactionShortId;

        $orderId = 'n/a';

        $first_name = $order->delivery['firstname'];
        $last_name = $order->delivery['lastname'];
        if ($response) {
            $this->piRatepayLog($orderId, $transactionId, $operation, $payment_subtype, $request, $response, $first_name, $last_name);
        } else {
            $this->piRatepayLog($orderId, $transactionId, $operation, $payment_subtype, $request, false, $first_name, $last_name);
        }
        return $response;
    }

    /**
     * This method send's the PAYMENT_REQUEST request to the RatePAY API
     *
     * @return array
     */
    function paymentRequest($order) {
        include('ratepay_webservice/Ratepay_XML.php');

        $systemId = $_SERVER['SERVER_ADDR'];
        $operation = 'PAYMENT_REQUEST';
        $payment_subtype = 'n/a';
        $tid = $_SESSION['pi']['tid'];
        $tsid = $_SESSION['pi']['tsid'];
        $customerId = $_SESSION ['customer_id'];
        $currency = $_SESSION ['currency'];

        $query = tep_db_query("SELECT customers_gender, DATE_FORMAT(customers_dob, '%Y-%m-%d') as customers_dob, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " WHERE customers_id ='" . tep_db_input($customerId) . "' ");
        $customerXTC = tep_db_fetch_array($query);

        $total = $this->getOrderTotal($order);

        $ratepay = new Ratepay_XML;
        $ratepay->live = $this->testOrLive();

        $request = $ratepay->getXMLObject();

        $head = $request->addChild('head');
        $head->addChild('system-id', $systemId);
        $head->addChild('transaction-id', $tid);
        $head->addChild('transaction-short-id', $tsid);
        $head->addChild('operation', $operation);

        $credential = $head->addChild('credential');
        $credential->addChild('profile-id', $this->profileId);
        $credential->addChild('securitycode', $this->securityCode);

        $customerDevice = $head->addChild('customer-device');

        $httpHeaderList = $customerDevice->addChild('http-header-list');

        $header = $httpHeaderList->addChild('header', 'text/xml');
        $header->addAttribute('name', 'Accept');
        $header = $httpHeaderList->addChild('header', 'utf-8');
        $header->addAttribute('name', 'Accept-Charset');
        $header = $httpHeaderList->addChild('header', 'x86');
        $header->addAttribute('name', 'UA-CPU');

        $request->addChild('content');
        $content = $request->content;
        $content->addChild('customer');

        $customer = $content->customer;

        if (strtoupper($customerXTC['customers_gender']) == "F") {
            $gender = "F";
        } else if (strtoupper($customerXTC['customers_gender']) == "M") {
            $gender = "M";
        } else {
            $gender = "U";
        }
        $customer->addCDataChild('first-name', $this->removeSpecialChars($order->delivery['firstname']));
        $customer->addCDataChild('last-name', $this->removeSpecialChars($order->delivery['lastname']));
        $customer->addChild('gender', $gender);
        $customer->addChild('date-of-birth', $customerXTC['customers_dob']);
        $customer->addChild('ip-address', $this->getRatepayCustomerIpAddress());
        if ($order->customer['company'] != '') {
            $customer->addChild('company-name', $order->customer['company']);
            $customer->addChild('vat-id', $_SESSION['pi']['vatid']);
        }

        $customer->addChild('contacts');
        $contacts = $customer->contacts;
        $contacts->addChild('email', $customerXTC['customers_email_address']);
        $contacts->addChild('phone');

        $phone = $contacts->phone;
        $phone->addChild('direct-dial', $customerXTC['customers_telephone']);

        if ($customerXTC['customers_fax'] != "") {
            $contacts->addChild('fax');
            $fax = $contacts->fax;
            $fax->addChild('direct-dial', $customerXTC['customers_fax']);
        }

        $customer->addChild('addresses');
        $addresses = $customer->addresses;
        $addresses->addChild('address');
        $addresses->addChild('address');

        $billingAddress = $addresses->address[0];
        $shippingAddress = $addresses->address[1];

        $billingAddress->addAttribute('type', 'BILLING');
        $shippingAddress->addAttribute('type', 'DELIVERY');

        $billingAddress->addCDataChild('street', $this->removeSpecialChars($order->delivery['street_address']));
        $billingAddress->addChild('zip-code', $order->delivery['postcode']);
        $billingAddress->addCDataChild('city', $this->removeSpecialChars($order->delivery['city']));
        $billingAddress->addChild('country-code', $order->delivery['country']['iso_code_2']);

        $shippingAddress->addCDataChild('street', $this->removeSpecialChars($order->delivery['street_address']));
        $shippingAddress->addChild('zip-code', $order->delivery['postcode']);
        $shippingAddress->addCDataChild('city', $this->removeSpecialChars($order->delivery['city']));
        $shippingAddress->addChild('country-code', $order->delivery['country']['iso_code_2']);

        $customer->addChild('nationality', $order->delivery['country']['iso_code_2']);
        $customer->addChild('customer-allow-credit-inquiry', 'yes');

        $content->addChild('shopping-basket');
        $shoppingBasket = $content->{'shopping-basket'};
        $shoppingBasket->addAttribute('amount', number_format($total, 2, ".", ""));
        $shoppingBasket->addAttribute('currency', 'EUR');

        $shoppingBasket->addChild('items');

        $items = $shoppingBasket->items;
        for ($i = 0; $i < sizeof($order->products); $i++) {

            $price = $order->products[$i]['final_price'] * $order->info['currency_value'];
            $qty = intval($order->products[$i]['qty']);
            if ($price > 0) {
                $items->addCDataChild('item', $this->removeSpecialChars($order->products[$i]['name']));
                $items->item[$i]->addAttribute('article-number', tep_get_prid($order->products[$i]['id']));
                $items->item[$i]->addAttribute('quantity', $qty);
                $items->item[$i]->addAttribute('unit-price', number_format($price, 2, ".", ""));
                $items->item[$i]->addAttribute('total-price', number_format($price * $qty, 2, ".", ""));
                $items->item[$i]->addAttribute('tax', number_format($qty * ($price / 100 * $order->products[$i]['tax']), 2, ".", ""));
            }
        }

        $shippingCost = number_format($order->info['shipping_cost'] * $order->info['currency_value'], 2);

        if ($shippingCost > 0) {
            $this->isPricesInclTax() ? $shippingCost = $shippingCost - $this->getShippingTaxAmount($order) : $shippingCost = $shippingCost;
            $_SESSION['pi_ratepay']['shipping'] = $shippingCost;
            $_SESSION['pi_ratepay']['shipping_tax'] = $this->getShippingTaxAmount($order);
            $items->addChild('item', 'Versand');
            $items->item[$i]->addAttribute('article-number', 'SHIPPING');
            $items->item[$i]->addAttribute('quantity', '1');
            $items->item[$i]->addAttribute('unit-price', number_format($shippingCost, 2, ".", ""));
            $items->item[$i]->addAttribute('total-price', number_format($shippingCost, 2, ".", ""));
            $items->item[$i]->addAttribute('tax', number_format($this->getShippingTaxAmount($order), 2, ".", ""));
            $i++;
        }
        
        $discounts = $this->getDiscounts($order);
        if (!empty($discounts)) {
            $_SESSION['pi']['discount'] = $discounts;
            foreach ($discounts as $discount) {
                if (number_format($discount['amount'], 2, ".", "") != 0) {
                    $items->addChild('item', 'Discount - ' . $discount['name']);
                    $items->item[$i]->addAttribute('article-number', 'DISCOUNT');
                    $items->item[$i]->addAttribute('quantity', '1');
                    $items->item[$i]->addAttribute('unit-price', number_format($discount['amount'], 2, ".", ""));
                    $items->item[$i]->addAttribute('total-price', number_format($discount['amount'], 2, ".", ""));
                    $items->item[$i]->addAttribute('tax', number_format($discount['tax'],2,".",""));
                    $i++;
                }
            }
        }
        
        $content->addChild('payment');
        $payment = $content->payment;
        $payment->addAttribute('method', 'INSTALLMENT');
        $payment->addAttribute('currency', 'EUR');
        $payment->addChild('amount', number_format($_SESSION['pi_ratepay_rate_total_amount'], 2, ".", ""));
        $installment = $payment->addChild('installment-details');
        $installment->addChild('installment-number', $_SESSION['pi_ratepay_rate_number_of_rates']);
        $installment->addChild('installment-amount', $_SESSION['pi_ratepay_rate_rate']);
        $installment->addChild('last-installment-amount', $_SESSION['pi_ratepay_rate_last_rate']);
        $installment->addChild('interest-rate', $_SESSION['pi_ratepay_rate_interest_rate']);
        $payment->addChild('usage', $this->testOrLiveUsage());
        $payment->addChild('debit-pay-type', 'BANK-TRANSFER');

        $response = $ratepay->paymentOperation($request);
        $return = array($request, $response);

        return $return;
    }
    
    function getDiscounts($order)
    {
        $discounts = array();
        if (!empty($order->coupon->coupon['coupons_id'])) {
            require_once('includes/classes/discount_coupon.php');
            $discount = new discount_coupon($order->coupon->coupon['coupons_id'], $order->delivery);
            $i = 0;
            foreach ($order->products as $product) {
                $applied_discount = $discount->calculate_discount($product, $i);
                if($applied_discount > 0) $i++;
            }

            foreach ($discount->applied_discount as $key => $value) {
                $amount = $value * $order->info['currency_value'];
                preg_match("/\d+/",$key,$result);
                $taxPercent = $result[0];

                if ($this->isPricesInclTax() == 'true') {
                    $taxAmount = $amount/($taxPercent+100) * $taxPercent;
                } else {
                    $taxAmount = $amount * ($taxPercent/100);
                }
                
                if ($amount > 0) $amount = $amount * -1;
                if ($taxAmount > 0) $taxAmount = $taxAmount * -1;
                $discountData = array(
                    'amount' => $this->isPricesInclTax() == 'true' ? $amount = $amount - $taxAmount : $amount = $amount,
                    'tax' => $taxAmount,
                    'name' => $key
                );
                array_push($discounts, $discountData);
            }
        }
        return $discounts;
    }
    
    public function getOrderTotal($order, $rateCalculator = false)
    {
        $total = $order->info['total'] * $order->info['currency_value'];
        if ($rateCalculator == true) {
            $total = $total + $this->getShippingTaxAmount($order, $rateCalculator);
        }
        return $total;
    }
    
    
    /**
     * This method send's the PAYMENT_CONFIRM request to the RatePAY API
     *
     * @return array $return
     */
    function paymentConfirm($orderId) {
        $ratepay = new Ratepay_XML;
        $ratepay->live = $this->testOrLive();

        $request = $ratepay->getXMLObject();
        $tid = $_SESSION['pi']['tid'];
        $tsid = $_SESSION['pi']['tsid'];
        $operation = 'PAYMENT_CONFIRM';
        $systemId = $_SERVER['SERVER_ADDR'];
        $head = $request->addChild('head');
        $head->addChild('system-id', $systemId);
        $head->addChild('transaction-id', $tid);
        $head->addChild('transaction-short-id', $tsid);
        $head->addChild('operation', $operation);

        $credential = $head->addChild('credential');
        $credential->addChild('profile-id', $this->profileId);
        $credential->addChild('securitycode', $this->securityCode);

        $external = $head->addChild('external');

        $external->addChild('order-id', $orderId);

        $response = $ratepay->paymentOperation($request);
        $return = array($request, $response);

        return $return;
    }

    /**
     * This method save's all necessary request and response informations in the database
     *
     * @param string $orderId
     * @param string $transactionId
     * @param string $payment_type
     * @param string $payment_subtype
     * @param string $request
     * @param string $response
     * @param string $first_name
     * @param string $last_name
     */
    function piRatepayLog($orderId, $transactionId, $payment_type, $payment_subtype, $request, $response = false, $first_name = '', $last_name = '') {
        $logging = $this->logs;
        if ($logging == true) {
            $responseXML = '';
            $reasonText = '';
            $result = '';
            $resultCode = '';
            if ($response) {
                $responseXML = utf8_decode($response->asXML());
                $result = (string) $response->head->processing->result;
                $resultCode = (string) $response->head->processing->result->attributes()->code;
                $reasonText = (string) $response->head->processing->reason;
            } else {
                $result = "Service unavaible.";
                $resultCode = "Service unavaible.";
            }

            $requestXML = utf8_decode($request->asXML());

            $sql = "INSERT INTO pi_ratepay_log (order_number, transaction_id, payment_method, payment_type,  payment_subtype, result, request, response, result_code, first_name, last_name, reason) VALUES ('" . tep_db_input($orderId) . "', '" . tep_db_input($transactionId) . "', 'INSTALLMENT','" . tep_db_input($payment_type) . "', '" . tep_db_input($payment_subtype) . "', '" . tep_db_input($result) . "','" . tep_db_input($requestXML) . "','" . tep_db_input($responseXML) . "','" . tep_db_input($resultCode) . "','" . tep_db_input($first_name) . "','" . tep_db_input($last_name) . "','" . tep_db_input($reasonText) . "')";

            tep_db_query($sql);

            if ($payment_type == "PAYMENT_CONFIRM") {
                $sql = "UPDATE pi_ratepay_log set order_number = '" . tep_db_input($orderId) . "' where transaction_id = '" . tep_db_input($transactionId) . "';";
                tep_db_query($sql);
            }
        }
    }

    /**
     * This method check's if it's live or test
     *
     * @return string
     */
    function testOrLiveUsage() {
        if (($this->sandbox == false)) {
            $usage = 'Produktionskauf';
        } else {
            $usage = 'Testeinkauf';
        }
        return $usage;
    }

    /**
     * This method check's if it's live or test
     *
     * @return boolean
     */
    function testOrLive() {
        if (($this->sandbox == false)) {
            $usage = true;
        } else {
            $usage = false;
        }
        return $usage;
    }

    /*
     * This method returns the IP of the Customer
     *
     * @return string
     */

    function getRatepayCustomerIpAddress() {
        $systemId = "";
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $systemId = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $systemId = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $systemId = $_SERVER['REMOTE_ADDR'];
        }
        return $systemId;
    }

    /*
     * This method removes some special chars
     *
     * @return string
     */

    function removeSpecialChars($str) {
        $search = array("–", "´", "‹", "›", "‘", "’", "‚", "“", "”", "„", "‟", "•", "‒", "―", "—", "™", "¼", "½", "¾");
        $replace = array("-", "'", "<", ">", "'", "'", ",", '"', '"', '"', '"', "-", "-", "-", "-", "TM", "1/4", "1/2", "3/4");
        return $this->removeSpecialChar($search, $replace, $str);
    }

    /*
     * This method removes some special chars
     *
     * @return string
     */

    function removeSpecialChar($search, $replace, $subject) {
        $str = str_replace($search, $replace, $subject);
        if ($this->is_osc231()) {
            return $str;
        }
        return utf8_encode($str);
    }

    /**
     * Add the shipping tax to the order object
     *
     * @param order $order
     * @return float
     */
    function getShippingTaxAmount($order, $rateCalculator = false) {
        $taxPercent = $this->getShippingTaxRate($order);
        if ($this->isPricesInclTax() == 'true' && $rateCalculator == false) {
            $shippingTaxAmount = ($order->info['shipping_cost'] * $order->info['currency_value']) / ($taxPercent+100) * $taxPercent;

        } else if ($this->isPricesInclTax() == 'true' && $rateCalculator == true){
            $shippingTaxAmount = ($order->info['shipping_cost'] * $order->info['currency_value']) * ($taxPercent / 100);
        } else {
            $shippingTaxAmount = ($order->info['shipping_cost'] * $order->info['currency_value']) * ($taxPercent / 100);
        }

        return $shippingTaxAmount;
    }

    /**
     * Retrieve the shipping tax rate
     *
     * @param order $order
     * @return float
     */
    function getShippingTaxRate($order) {
        global $shipping;
        $shipping_class_array = explode("_", $shipping['id']);
        $shipping_class = strtoupper($shipping_class_array[0]);
        if (empty($shipping_class)) {
            $shipping_tax_rate = 0;
        } else {
            $const = 'MODULE_SHIPPING_' . $shipping_class . '_TAX_CLASS';

            if (defined($const)) {
                $shipping_tax_rate = tep_get_tax_rate(constant($const));
            } else {
                $shipping_tax_rate = 0;
            }
        }

        return $shipping_tax_rate;
    }

    /**
     * Is osc v. 2.3.1
     * 
     * @return boolean 
     */
    function is_osc231() {
        $filename = 'includes/template_top.php';
        if (file_exists($filename)) {
            return true;
        } else {
            return false;
        }
    }
    
    function isPricesInclTax()
    {
        $sql = 'SELECT configuration_value from configuration where configuration_key = "DISPLAY_PRICE_WITH_TAX"';
        $query = tep_db_query($sql);
        $data = tep_db_fetch_array($query);
        
        return $data['configuration_value'];
    }


}

?>