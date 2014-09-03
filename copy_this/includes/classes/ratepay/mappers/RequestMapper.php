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
 * @package   ratepay
 * @copyright (C) 2012 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license   GPLv2
 */

require_once(dirname(__FILE__) . '/../../../classes/ratepay/models/HeadInfo.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/models/CustomerInfo/AddressInfo.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/models/CustomerInfo/BankInfo.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/models/CustomerInfo.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/models/BasketInfo.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/models/BasketInfo/ItemInfo.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/models/PaymentInfo.php');

require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Data.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Db.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Session.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Loader.php');

/**
 * RequestMapper class, fills all models which are needed for the ratepay calls
 */
class RequestMapper
{
    /**
     * Retrieve a headInfo model, filled with the merchant crdentials and some transaction data
     * 
     * @param order $order
     * @param string $transactionId
     * @param string $transactionShortId 
     * @param int $orderId
     * @param string $subtype
     * @return HeadInfo
     */
    public static function getHeadInfoModel(order $order, $transactionId = null, $transactionShortId = null, $orderId = null, $subtype = null)
    {
        Session::setRpSessionEntry('orderId', $orderId);
        $payment = Loader::getRatepayPayment($order->info['payment_method']);

        $headInfo = new HeadInfo();
        $headInfo->setOrderId($orderId)
                ->setProfileId($payment->profileId)
                ->setSecurityCode($payment->securityCode)
                ->setSubtype($subtype)
                ->setTransactionId($transactionId)
                ->setTransactionShortId($transactionShortId)
                ->setShopSystem($payment->shopSystem)
                ->setShopVersion($payment->shopVersion)
                ->setModuleVersion($payment->version);

        return $headInfo;
    }

    /**
     * Retrieve a customerInfo model filled with the addresses and the customer data
     * 
     * @param order $order
     * @param int $orderId
     * @return CustomerInfo
     */
    public static function getCustomerInfoModel(order $order, $orderId = null)
    {
        $customerInfo = new CustomerInfo();
        $customerInfo->setBillingAddressInfo(self::getBillingAdressInfo($order, $orderId))
                ->setShippingAddressInfo(self::getShippingAdressInfo($order, $orderId));

        if ($order->info['payment_method'] === 'ratepay_lastschrift' && is_null($orderId)) {
            $payment = Loader::getRatepayPayment($order->info['payment_method']);
            $bankAccount = $payment->getBankData();
            $bankAccountInfo = new BankInfo();
            $bankAccountInfo->setOwner($bankAccount['owner']);
            if (!empty($bankAccount['iban'])) {
                $bankAccountInfo->setIban($bankAccount['iban']);
                if (!empty($bankAccount['bic-swift'])) {
                    $bankAccountInfo->setBic($bankAccount['bic-swift']);
                }
            } else {
                $bankAccountInfo->setAccountNumber($bankAccount['bank-account-number']);
                $bankAccountInfo->setBankCode($bankAccount['bank-code']);
            }
            $bankAccountInfo->setBankName($bankAccount['bank-name']);

            $customerInfo->setBankAccount($bankAccountInfo);
        }

        $customerInfo->setCreditInquiry('yes')
                ->setDateOfBirth(Db::getCustomersDob($orderId, Session::getSessionEntry('customer_id')))
                ->setEmail($order->customer['email_address'])
                ->setFax(Db::getCustomersFax($orderId, Session::getSessionEntry('customer_id')))
                ->setPhone($order->customer['telephone'])
                ->setFirstName(is_null($orderId) ? $order->customer['firstname'] : Db::getRatepayOrderDataEntry($orderId, 'firstname'))
                ->setGender(is_null($orderId) ? $order->customer['gender'] : Db::getRatepayOrderDataEntry($orderId, 'gender'))
                ->setIp(is_null($orderId) ? Data::getCustomerIp() : Db::getRatepayOrderDataEntry($orderId, 'ip_address'))
                ->setLastName(is_null($orderId) ? $order->customer['lastname'] : Db::getRatepayOrderDataEntry($orderId, 'lastname'))
                ->setNationality(is_array($order->customer['country']) ? $order->customer['country']['iso_code_2'] : Db::getRatepayOrderDataEntry($orderId, 'customers_country_code'));
        $vatId = Db::getCustomersVatId($orderId, Session::getSessionEntry('customer_id'));
        if (!empty($order->customer['company']) && !empty($vatId)) {
            $customerInfo->setCompany($order->customer['company'])->setVatId($vatId);
        }

        return $customerInfo;
    }

    /**
     * Retrieve a billingAddressInfo model
     * 
     * @param order $order
     * @param int $orderId
     * @return AddressInfo
     */
    private static function getBillingAdressInfo(order $order, $orderId)
    {
        $countryId = is_array($order->billing['country']) ? $order->billing['country']['iso_code_2'] : Db::getRatepayOrderDataEntry($orderId, 'billing_country_code');
        $addressInfo = new AddressInfo();
        $addressInfo->setCity($order->billing['city'])
                ->setCountryId($countryId)
                ->setStreet($order->billing['street_address'])
                ->setZip($order->billing['postcode']);
        $addressInfo->setType('BILLING');
        return $addressInfo;
    }

    /**
     * Retrieve a shippingAddressInfo model
     * 
     * @param order $order
     * @param int $orderId
     * @return AddressInfo
     */
    private static function getShippingAdressInfo(order $order, $orderId)
    {
        $countryId = is_array($order->delivery['country']) ? $order->delivery['country']['iso_code_2'] : Db::getRatepayOrderDataEntry($orderId, 'shipping_country_code');
        $addressInfo = new AddressInfo();
        $addressInfo->setCity($order->delivery['city'])
                ->setCountryId($countryId)
                ->setStreet($order->delivery['street_address'])
                ->setZip($order->delivery['postcode']);
        $addressInfo->setType('DELIVERY');
        return $addressInfo;
    }

    /**
     * Retrieve a bankInfo model
     *
     * @param order $order
     * @param int $orderId
     * @return BankInfo
     */
    private static function getBankInfo(order $order)
    {
        $bankInfo = new BankInfo();
        $bankInfo->setOwner($order->customer['bankAccount']['owner'])
            ->setBankAccountNumber($order->customer['bankAccount']['bank-account-number'])
            ->setBankCode($order->customer['bankAccount']['bank-code'])
            ->setBankName($order->customer['bankAccount']['bank-name']);
        return $bankInfo;
    }

    /**
     * Retrieve a basketInfo model, filled with the items and basket data
     * 
     * @param order $order
     * @param int $orderId
     * @param array $post
     * @return BasketInfo
     */
    public static function getBasketInfoModel(order $order, $orderId = null, array $post = array())
    {
        $basketInfo = new BasketInfo();
        $basketInfo->setAmount(Data::getBasketAmount($order, $orderId, $post))
                ->setCurrency($order->info['currency'])
                ->setItems(self::getItems($order, $post, $orderId));
        return $basketInfo;
    }

    /**
     * Retrieve the items from the order
     * 
     * @param order $order
     * @return array
     */
    private static function getItemsByOrder(order $order)
    {
        $items = array();
        foreach ($order->products as $product) {
            $items[] = self::getItem(Data::getItemData($product, $order));
        }

        $shipping = Data::getShippingData($order);
        if (!empty($shipping)) {
            $items[] = self::getItem($shipping);
        }

        foreach (Data::getDiscounts() as $discountData) {
            $discount = Data::getDiscountData($discountData, $order);
            if (!empty($discount)) {
                $items[] = self::getItem($discount);
            }
        }

        return $items;
    }

    /**
     * Retrieve the items for the basketInfo model
     * 
     * @param order $order
     * @param array $post
     * @param int $orderId
     * @return array
     */
    private static function getItems(order $order, array $post, $orderId = null)
    {
        if (is_null($orderId)) {
            $items = self::getItemsByOrder($order);
        } else {
            $items = self::getItemInfoByTable($orderId, $post);
        }
        
        return $items;
    }

    /**
     * Retrieve the items by the ratepay tables
     * 
     * @param int $orderId
     * @param array $post
     * @return array
     */
    private static function getItemInfoByTable($orderId, array $post)
    {
        $itemInfos = array();;
        $items = Db::getItemsByTable($orderId, $post);
        foreach ($items as $item) {
            if ($item['qty'] > 0) {
                $itemInfos[] = self::getItem($item);
            }
        }
        return $itemInfos;
    }

    /**
     * Retrieve itemInfo model
     * 
     * @param array $itemData
     * @return ItemInfo
     */
    private static function getItem(array $itemData)
    {
        $item = new ItemInfo();
        $item->setArticleName($itemData['name'])
                ->setArticleNumber($itemData['id'])
                ->setQuantity($itemData['qty'])
                ->setTax($itemData['tax'])
                ->setTotalPrice($itemData['totalPrice'])
                ->setUnitPrice($itemData['unitPrice']);
        return $item;
    }

    /**
     * Retrieve paymentInfo model 
     * 
     * @param order $order
     * @param int $orderId
     * @return PaymentInfo
     */
    public static function getPaymentInfoModel(order $order, $orderId = null)
    {
        $paymentInfo = new PaymentInfo();
        $paymentInfo->setAmount(Data::getPaymentAmount($order, $orderId))->setCurrency($order->info['currency'])
                ->setMethod(Data::getRpPaymentMethod($order->info['payment_method']));
        if ($order->info['payment_method'] == 'ratepay_rate') {
            if (is_null($orderId)) {
                $paymentInfo->setDebitType('BANK-TRANSFER')
                    ->setInstallmentAmount(Session::getRpSessionEntry('ratepay_rate_rate'))
                    ->setInstallmentNumber(Session::getRpSessionEntry('ratepay_rate_number_of_rates'))
                    ->setInterestRate(Session::getRpSessionEntry('ratepay_rate_interest_rate'))
                    ->setLastInstallmentAmount(Session::getRpSessionEntry('ratepay_rate_last_rate'))
                    ->setPaymentFirstDay(Session::getRpSessionEntry('ratepay_payment_firstday'));
            } else {
                $details = Db::getRatepayRateDetails($orderId);
                $paymentInfo->setDebitType('BANK-TRANSFER')
                    ->setInstallmentAmount($details['rate'])
                    ->setInstallmentNumber($details['number_of_rates'])
                    ->setInterestRate($details['interest_amount'])
                    ->setLastInstallmentAmount($details['last_rate'])
                    ->setPaymentFirstDay($details['payment_firstday']);
            }
        }
        
        return $paymentInfo;
    }

}
