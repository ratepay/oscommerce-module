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

require_once(dirname(__FILE__) . '/../../../classes/ratepay/mappers/RequestMapper.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/services/RequestService.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Data.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Db.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Session.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Globals.php');

abstract class ratepay_abstract
{

    /**
     * Payment code
     * 
     * @var string
     */
    public $code = 'ratepay_abstract';

    /**
     * Current customer country
     *
     * @var string
     */
    public $country = null;

    /**
     * Minimal order amount by country
     *
     * @var float
     */
    public $minDe;
    public $minAt;
    public $minCh;

    /**
     * Maximal order amount by country
     *
     * @var float
     */
    public $maxDe;
    public $maxAt;
    public $maxCh;

    /**
     * b2b de flag by country
     * @var boolan
     */
    public $b2bDe = false;
    public $b2bAt = false;
    public $b2bCh = false;

    /**
     * Max order amount
     *
     * @var float
     */
    private $_nextPage = array("ratepay_rechnung" => FILENAME_CHECKOUT_CONFIRMATION,
                               "ratepay_rate" => "ratepay_rate_checkout_details.php",
                               "ratepay_lastschrift" => FILENAME_CHECKOUT_CONFIRMATION);

    /**
     * validation errors
     *
     * @var array
     */
    public $error = array();    
    
    /**
     * Retrieve payment selection array
     * 
     * @return array
     */
    public function selection()
    {
        global $order;

        $display = array(
            'id' => $this->code, 
            'module' => tep_image(DIR_WS_IMAGES . '/' . $this->code . '_checkout_logo.png')
        );
        
        $neededFields = $this->_getNeededFields();
        $neededRpJS = $this->_getRatepayJavaSrcipt();
        if (!empty($neededFields)) {
            $display['fields'] = array_merge($neededFields, $neededRpJS);
        }

        if ($this->country != 'DE' &&
            $this->country != 'AT'&&
            $this->country != 'CH')
        {
            $display = null;
        }
        
        if (!Data::isRatepayAvailable()) {
            $display = null;
        }

        $countrySuffix = ucfirst(strtolower($this->country));
        $enabledVarName = 'enabled' . $countrySuffix;
        $minVarName = 'min' . $countrySuffix;
        $maxVarName = 'max' . $countrySuffix;
        $b2bVarName = 'b2b' . $countrySuffix;
        $alaVarName = 'ala' . $countrySuffix;

        if (!$this->$enabledVarName) {
            $display = null;
        }

        if ((floatval($order->info['total']) < floatval($this->$minVarName)) || (floatval($order->info['total']) > floatval($this->$maxVarName))) {
            $display = null;
        }

        if (!empty($order->billing['company']) && !$this->$b2bVarName) {
            $display = null;
        }
        
        if (!$this->$alaVarName) {
            if (sizeof($order->delivery) != sizeof($order->billing)) {
                $display = null;
            } else {
                if (is_array($order->billing)) {
                    foreach ($order->billing as $key => $val) {
                        if ($order->billing[$key] != $order->delivery[$key]) {
                            $display = null;
                        }
                        unset($val);
                    }
                }
            }
        }

        $dob = Db::getCustomersDob(null, Session::getSessionEntry('customer_id'));
        if (intval($dob) <> 0 && !$this->_isAdult($dob)) {
            $display = null;
        }

        $this->setInfoVisited(false);

        return $display;
    }

    /**
     * Retrieve needed form fields for payment
     * 
     * @return array
     */
    public function _getNeededFields()
    {
        global $fields;

        $fields = array();
        $phone = $this->_getPhoneField();
        if (!empty($phone)) {
            $fields[] = $phone;
        }

        $dob = $this->_getDobField();
        if (!empty($dob)) {
            $fields[] = $dob;
        }

        $company = $this->_getCompanyField();
        if (!empty($company)) {
            $fields[] = $company;
        }

        $vatId = $this->_getVatIdField();
        if (!empty($vatId)) {
            $fields[] = $vatId;
        }

        return $fields;
    }

    /**
     * Retrieve RatePAY JavaScript
     *
     * @return array
     */
    protected function _getRatepayJavaSrcipt()
    {
        $jsField = array();

        $jsFunctions = file_get_contents(DIR_FS_CATALOG . 'templates/javascript/ratepay_checkout.js');
        $js = 'window.onload = RpCheckout.ratepayOnLoad;';
        $jsField[] = array('title' => '', 'field' => sprintf('<script type="text/javascript">%s</script>', $jsFunctions));
        $jsField[] = array('title' => '', 'field' => sprintf('<script type="text/javascript">%s</script>', $js));

        return $jsField;
    }

    /**
     * Retrieve phone form field
     * 
     * @return array
     */
    protected function _getPhoneField()
    {
        global $order;
        if ($this->_isPhoneNeeded()) {
            return array(
                'title' => '<span id="' . $this->code . '_block">Telefon:</span>',
                'field' => tep_draw_input_field($this->code . '_phone', $order->customer['telephone'])
            );
        }

        return null;
    }
    
    /**
     * Retrieve dob form field
     * 
     * @return array
     */
    protected function _getDobField()
    {
        $dob = Db::getCustomersDob(null, Session::getSessionEntry('customer_id'));
        $dateStr = substr($dob, 8, 2) . "." . substr($dob, 5, 2) . "." . substr($dob, 0, 4);

        if ($this->_isDobNeeded()) {
            return array(
                'title' => '<span id="' . $this->code . '_block">Geburtstag:</span>',
                'field' => tep_draw_input_field($this->code . '_birthdate', $dateStr) . " " . constant(strtoupper($this->code) . "_VIEW_PAYMENT_BIRTHDATE_FORMAT")
            );
        }

        return null;
    }
    
    /**
     * Retrieve company form field
     * 
     * @return array
     */
    protected function _getCompanyField()
    {
        if ($this->_isCompanyNeeded()) {
            return array(
                'title' => '<span id="' . $this->code . '_block">Firma:</span>',
                'field' => tep_draw_input_field($this->code . '_company', '')
            );
        }

        return null;
    }

    /**
     * Retrieve vat id form field
     * 
     * @return array
     */
    protected function _getVatIdField()
    {
        if ($this->_isVatIdNeeded()) {
            return array(
                'title' => '<span id="' . $this->code . '_block">Umsatzsteuer ID:</span>',
                'field' => tep_draw_input_field($this->code . '_vatid', '')
            );
        }

        return null;
    }

    /**
     * Is phone needed
     * 
     * @return boolean
     */
    protected function _isPhoneNeeded()
    {
        global $order;
        $phone = $order->customer['telephone'];
        return empty($phone) || !$this->_isPhoneValid($phone);
    }
    
    /**
     * Is dob needed
     * 
     * @return boolean
     */
    protected function _isDobNeeded()
    {
        $dob = Db::getCustomersDob(null, Session::getSessionEntry('customer_id'));
        return empty($dob) ||
               intval($dob) == 0 ||
               !$this->_isDobValid($dob) ||
               !$this->_isAdult($dob);
    }
    
    /**
     * Is company needed
     * 
     * @return boolean
     */
    protected function _isCompanyNeeded()
    {
        global $order;
        $vatId = Db::getCustomersVatId(null, Session::getSessionEntry('customer_id'));
        return (empty($order->customer['company']) || empty($order->billing['company'])) && !empty($vatId);
    }
    
    /**
     * Is vat id needed
     * 
     * @return boolean
     */
    protected function _isVatIdNeeded()
    {
        global $order;
        $vatId = Db::getCustomersVatId(null, Session::getSessionEntry('customer_id'));
        return (!empty($order->customer['company']) || !empty($order->billing['company'])) && empty($vatId);
    }

    /**
     * Is account information needed
     *
     * @return boolean
     */
    protected function _isBankAccountNeeded()
    {
        return ($this->code == 'ratepay_lastschrift');
    }

    /**
     * Is dob valid
     * 
     * @param string $dob
     * @return boolean
     */
    protected function _isDobValid($dob)
    {
        $intDob = str_replace(array('-', '.'), '', $dob);
        return is_numeric($intDob) && intval($intDob) > 0 && strlen($intDob) == 8;
    }
    
    /**
     * Check if the customer is over 18 years or redirect with an error message
     * 
     * @param string $dob
     * @return boolean
     */
    protected function _isAdult($dob) {
        $minBirthDate = array();
        $customerBirthDate = array();

        if (strstr($dob, '-')) {
            $dobArr = explode('-', $dob);
            $customerBirthDate['day'] = $dobArr[2];
            $customerBirthDate['month'] = $dobArr[1];
            $customerBirthDate['year'] = $dobArr[0];
        } else {
            $dobArr = explode('.', $dob);
            $customerBirthDate['day'] = $dobArr[0];
            $customerBirthDate['month'] = $dobArr[1];
            $customerBirthDate['year'] = $dobArr[2];
        }
        $customerBirthDateTS = mktime(0, 0, 0, floatval($customerBirthDate['month']), floatval($customerBirthDate['day']), floatval($customerBirthDate['year']));

        $minBirthDate['day'] = date('d');
        $minBirthDate['month'] = date('m');
        $minBirthDate['year'] = date('Y') - 18;
        $minBirthDateTS = mktime(0, 0, 0, floatval($minBirthDate['month']), floatval($minBirthDate['day']), floatval($minBirthDate['year']));

        if ($customerBirthDateTS > $minBirthDateTS) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if the phone number is valid
     *
     * @param string $phone
     * @return boolean
     */
    protected function _isPhoneValid($phone) {
        $valid = "<^((\\+|00)[1-9]\\d{0,3}|0 ?[1-9]|\\(00? ?[1-9][\\d ]*\\))[\\d\\-/ ]*$>";
        if (strlen(trim($phone)) >= 6 && preg_match($valid, trim($phone))) {
            return true;
        }
        return false;
    }

    /**
     * Is called after checkout_payment.php is confirmed,
     * checks if all needed customer data available or 
     * redirect the customer to the checkout_payment.php
     * with a error message otherwise the user get to the
     * ratepay terms page
     * 
     * @global order $order
     */
    public function pre_confirmation_check()
    {
        global $order;
        if (!$this->isInfoVisited()) {
            if ($this->_isPhoneNeeded()) {
                if (Globals::hasPostEntry($this->code . '_phone') && !Data::betterEmpty(Globals::getPostEntry($this->code . '_phone'))) {
                    $phone = Globals::getPostEntry($this->code . '_phone');
                    if ($this->_isPhoneValid($phone)) {
                        Db::setXtCustomerEntry(Session::getSessionEntry('customer_id'), 'customers_telephone', $phone);
                        $order->customer['telephone'] = $phone;
                    } else {
                        $this->error['PHONE'] = 'INVALID';
                    }
                } else {
                    $this->error['PHONE'] = 'MISSING';
                }
            }

            if ($this->_isDobNeeded()) {
                if (Globals::hasPostEntry($this->code . '_birthdate') && !Data::betterEmpty(Globals::getPostEntry($this->code . '_birthdate'))) {
                    $dob = Globals::getPostEntry($this->code . '_birthdate');
                    if (!$this->_isDobValid($dob)) {
                        $this->error['DOB'] = 'INVALID';
                    } elseif (!$this->_isAdult($dob)) {
                        $this->error['DOB'] = 'YOUNGER';
                    }else {
                        $dobArr = explode('.', $dob);
                        $dateStr = $dobArr[2] . "-" . $dobArr[1] . "-" . $dobArr[0] . " 00:00:00";
                        Db::setXtCustomerEntry(Session::getSessionEntry('customer_id'), 'customers_dob', $dateStr);
                    }
                } else {
                    $this->error['DOB'] = 'MISSING';
                }
            }
            
            if ($this->_isCompanyNeeded()) {
                if (Globals::hasPostEntry($this->code . '_company') && !Data::betterEmpty(Globals::getPostEntry($this->code . '_company'))) {
                    $company = Globals::getPostEntry($this->code . '_company');
                    $order->customer['company'] = $company;
                    $order->billing['company']  = $company;
                    $dbInput = tep_db_input(Db::getXtCustomerEntry(Session::getSessionEntry('customer_id'), 'customers_default_address_id'));
                    tep_db_query("UPDATE " . TABLE_ADDRESS_BOOK . " "
                               . "SET entry_company = '" . tep_db_prepare_input($company) . "' "
                               . "WHERE address_book_id = '" . $dbInput . "'"
                    );

                } else {
                    $this->error['VATID'] = 'MISSING';
                }
            }

            if ($this->_isVatIdNeeded()) {
                if (Globals::hasPostEntry($this->code . '_vatid') && !Data::betterEmpty(Globals::getPostEntry($this->code . '_vatid'))) {
                    Db::setXtCustomerEntry(Session::getSessionEntry('customer_id'), 'customers_vat_id', Globals::getPostEntry($this->code . '_vatid'));
                } else {
                    $this->error['VATID'] = 'MISSING';
                }
            }

            if (empty($this->error)) {
                $this->setInfoVisited(true);
                Session::setRpSessionEntry('basketAmount', Data::getBasketAmount($order));
                //$url = tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL');
                $url = tep_href_link($this->_getNextStepPayment(), '', 'SSL');
            } else {
                $this->error = urlencode($this->_getErrorString($this->error));
                $url = tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . $this->error, 'SSL');
            }

            tep_redirect($url);

        }
    }

    /**
     * Place to put some JS validation for extra form field at checkout_payment.php
     */
    public function javascript_validation()
    {
        return false;
    }
    
    public function confirmation()
    {
        return false;
    }
    
    /**
     * Is called when the user clicks the "process button" but before the order is saved
     * here we send the PAYMENT_INIT and PAYMENT_REQUEST call to RatePAY in case of an 
     * we redirect the user to the checkout_payment.php with an error message
     */
    public function before_process() 
    {
        global $order;
        $result = $this->_paymentInit();
        if (!array_key_exists('error', $result) && array_key_exists('transactionId', $result)) {
            Session::setRpSessionEntry('transactionId', $result['transactionId']);
            Session::setRpSessionEntry('transactionShortId', $result['transactionShortId']);
            $result = $this->_paymentRequest($result['transactionId'], $result['transactionShortId']);
            if (array_key_exists('error', $result) && !array_key_exists('transactionId', $result)) {
                Session::cleanRpSession();
                $error = urlencode(constant(strtoupper($this->code) . '_ERROR'));
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . $error, 'SSL'));
            } else {
                Session::setRpSessionEntry('customers_country_code', $order->customer['country']['iso_code_2']);
                Session::setRpSessionEntry('descriptor', $result['descriptor']);
            }
        } else {
            $error = urlencode(constant(strtoupper($this->code) . '_ERROR_GATEWAY'));
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . $error, 'SSL'));
        }
    }

    /**
     * Is called when the order is saved to the db
     * here we send the PAYMENT_CONFIRM call to RatePAY
     */
    public function after_process() 
    {
        global $insert_id, $order;
        $transactionId = Session::getRpSessionEntry('transactionId');
        $transactionShortId = Session::getRpSessionEntry('transactionShortId');
        if (!empty($transactionId)) {
            $result = $this->_paymentConfirm($transactionId, $transactionShortId, $insert_id);
            if (!array_key_exists('error', $result)) {
                $this->_saveRpOrder($order, $insert_id);
                Session::cleanRpSession();
            } else {
                Session::cleanRpSession();
                $error = urlencode(constant(strtoupper($this->code) . '_ERROR'));
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . $error, 'SSL'));
            }
        }
    }
    
    /**
     * Is called to render the process button
     */
    public function process_button() 
    {
        global $ot_coupon;
        Session::setRpSessionEntry('coupon', $ot_coupon->output);
    }
    
    /**
     * Retrieve any payment error
     *
     * @return array
     */
    function get_error()
    {
        global $_GET;

        return array (
                'title' => 'RatePAY Error',
                'error' => urldecode($_GET['error_message'])
        );
    }
    
    /**
     * Save ratepay order wrapper
     * 
     * @param order $order
     * @param int $orderId
     */
    protected function _saveRpOrder(order $order, $orderId)
    {
        Db::setRatepayOrderData($order, $orderId);
    }

    /**
     * Build the constant for the error string 
     * and retrieve the error string
     * 
     * @param array $error
     * @return string
     */
    protected function _getErrorString(array $error)
    {
        $message = '';
        foreach ($error as $key => $value) {
            $message .= constant(strtoupper($this->code . '_' . $key) . '_IS_' . strtoupper($value));
        }
        
        return $message;
    }

    /**
     * Call payment init
     * 
     * @global order $order
     * @return array
     */
    protected function _paymentInit()
    {
        global $order;
        $data = array(
            'HeadInfo' => RequestMapper::getHeadInfoModel($order)
        );
        $requestService = new RequestService($this->sandbox, $data);
        $result = $requestService->callPaymentInit();
        Db::xmlLog($order, $requestService->getRequest(), 'N/A', $requestService->getResponse());
        return $result;
    }

    /**
     * Call PAYMENT_REQUEST request
     * 
     * @global order $order
     * @param string $transactionId
     * @param string$transactionShortId
     * @return array
     */
    protected function _paymentRequest($transactionId, $transactionShortId)
    {
        global $order;
        $data = array(
            'HeadInfo' => RequestMapper::getHeadInfoModel($order, $transactionId, $transactionShortId),
            'CustomerInfo' => RequestMapper::getCustomerInfoModel($order),
            'BasketInfo' => RequestMapper::getBasketInfoModel($order),
            'PaymentInfo' => RequestMapper::getPaymentInfoModel($order)
        );
        $requestService = new RequestService($this->sandbox, $data);
        $result = $requestService->callPaymentRequest();
        Db::xmlLog($order, $requestService->getRequest(), 'N/A', $requestService->getResponse());
        return $result;
    }

    /**
     * Call PAYMENT_CONFIRM request
     * 
     * @global order $order
     * @param string $transactionId
     * @param string $transactionShortId
     * @param int $orderId
     * @return array
     */
    protected function _paymentConfirm($transactionId, $transactionShortId, $orderId)
    {
        global $order;
        $data = array(
            'HeadInfo' => RequestMapper::getHeadInfoModel($order, $transactionId, $transactionShortId, $orderId)
        );
        $requestService = new RequestService($this->sandbox, $data);
        $result = $requestService->callPaymentConfirm();
        Db::xmlLog($order, $requestService->getRequest(), $orderId, $requestService->getResponse());
        return $result;
    }

    protected function _setCredentials($country)
    {
        switch (strtoupper($country)) {
            case 'DE':
                $this->profileId = constant('MODULE_PAYMENT_' . strtoupper($this->code) . '_PROFILE_ID_DE');
                $this->securityCode = constant('MODULE_PAYMENT_' . strtoupper($this->code) . '_SECURITY_CODE_DE');
                break;
            case 'AT':
                $this->profileId = constant('MODULE_PAYMENT_' . strtoupper($this->code) . '_PROFILE_ID_AT');
                $this->securityCode = constant('MODULE_PAYMENT_' . strtoupper($this->code) . '_SECURITY_CODE_AT');
                break;
            case 'CH':
                $this->profileId = constant('MODULE_PAYMENT_' . strtoupper($this->code) . '_PROFILE_ID_CH');
                $this->securityCode = constant('MODULE_PAYMENT_' . strtoupper($this->code) . '_SECURITY_CODE_CH');
                break;
            default:
                $this->profileId = null;
                $this->securityCode = null;
                break;
        }
    }

    /**
     * Set info page visited
     * 
     * @param boolean $visited
     */
    public function setInfoVisited($visited)
    {
        Session::setRpSessionEntry('infoVisited', $visited);
    }

    /**
     * is info page visited 
     * 
     * @return boolean
     */
    protected function isInfoVisited()
    {
        return Session::getRpSessionEntry('infoVisited');
    }

    /**
     * get the next step after choosing payment method dependig on method
     *
     * @return string $page
     */
    protected function _getNextStepPayment()
    {
        return $this->_nextPage[$this->code];
    }

    /**
     * get the current customer country
     *
     * @return string $country
     */
    protected function _getCountry() {
        global $order;

        if (is_array($order->billing['country'])) {
            $country = $order->billing['country']['iso_code_2'];
        } elseif (Session::getRpSessionEntry('orderId')) {
            $country = Db::getRatepayOrderDataEntry(Session::getRpSessionEntry('orderId'), 'customers_country_code');
        } elseif (Session::getRpSessionEntry('country')) {
            $country = Session::getRpSessionEntry('country');
        } else {
            $country = "DE";
        }

        Session::setRpSessionEntry('country', $country);

        return strtoupper($country);
    }

}
