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

require_once('abstract/ratepay_abstract.php');

/**
 * RatePAY Lastschrift payment class
 */
class ratepay_lastschrift extends ratepay_abstract
{

    /**
     * Payment code
     * 
     * @var string
     */
    public $code;

    /**
     * Current customer country
     *
     * @var string
     */
    public $country = null;

    /**
     * Payment title module admin
     * 
     * @var string
     */
    public $title;
    
    /**
     * Payment title checkout
     * 
     * @var string
     */
    public $public_title;
    
    /**
     * Payment description
     * 
     * @var string 
     */
    public $description;
    
    /**
     * Payment active flag (by country)
     * 
     * @var boolean
     */
    public $enabled;
    public $enabledDe;
    public $enabledAt;
    public $enabledCh;

    /**
     * Payment allowed flag
     * 
     * @var boolean
     */
    public $check;
    
    /**
     * Config entry "RatePAY Profile ID"
     * 
     * @var string
     */
    public $profileId;
    
    /**
     * Config entry "RatePAY Securitycode"
     * 
     * @var string
     */
    public $securityCode;
    
    /**
     * Config entry sandbox flag
     * 
     * @var boolean
     */
    public $sandbox;
    
    /**
     * Config entry logging flag
     * 
     * @var boolean
     */
    public $logging;
    
    /**
     * Module version
     * 
     * @var string
     */
    public $version;
    
    /**
     * Shop system
     * 
     * @var string
     */
    public $shopSystem;
    
    /**
     * Shop version
     * 
     * @var string
     */
    public $shopVersion;

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
     * Merchant privacy url
     * 
     * @var string
     */
    public $merchantPrivacyUrl;
    
    /**
     * Merchant gtc url
     * 
     * @var string
     */
    public $merchantGtcUrl;

    /**
     * RatePAY privacy url by Country
     *
     * @var string
     */
    public $ratepayPrivacyUrlDe;
    public $ratepayPrivacyUrlAt;
    public $ratepayPrivacyUrlCh;
    
    /**
     * Shop owner
     * 
     * @var string
     */
    public $shopOwner;
    
    /**
     * Shop HR
     * 
     * @var string
     */
    public $shopHr;
    
    /**
     * Shop phone
     * 
     * @var string
     */
    public $shopPhone;
    
    /**
     * Shop fax
     * 
     * @var string
     */
    public $shopFax;
    
    /**
     * Shop zip code
     * 
     * @var string
     */
    public $shopZipCode;
    
    /**
     * Shop street
     * 
     * @var string
     */
    public $shopStreet;
    
    /**
     * Shop court
     * 
     * @var string
     */
    public $shopCourt;
    
    /**
     * Shop bank name
     * 
     * @var string
     */
    public $shopBankName;
    
    /**
     * Shop sort code
     * 
     * @var string
     */
    public $shopSortCode;
    
    /**
     * Shop account number
     * 
     * @var string
     */
    public $shopAccountNumber;
    
    /**
     * Shop swift
     * 
     * @var string
     */
    public $shopSwift;
    
    /**
     * Shop iban
     * 
     * @var string
     */
    public $shopIban;
    
    /**
     * Shop extra field
     * 
     * @var string
     */
    public $extraInvoiceField;

    /**
     * b2b de flag by country
     * @var boolan
     */
    public $b2bDe;
    public $b2bAt;
    public $b2bCh;

    /**
     * ala flag (different delivery address) by country
     * @var boolan
     */
    public $alaDe;
    public $alaAt;
    public $alaCh;
    
    /**
     * This constructor set's all properties for the ratepay_lastschrift object
     */
    public function __construct()
    {
        global $order;
        
        $this->code                 = 'ratepay_lastschrift';
        $this->version              = '2.1.4';
        $this->shopVersion          = str_replace(' ','',str_replace("xt:Commerce v", "", PROJECT_VERSION));
        $this->shopSystem           = 'xt:Commerce';
        $this->title                = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_TEXT . " (" . $this->version . ")";
        $this->public_title         = $this->code;
        $this->description          = utf8_decode(MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_TEXT_DESCRIPTION);
        $this->enabled              = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS == 'True') ? true : false;
        $this->enabledDe            = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_DE == 'True') ? true : false;
        $this->enabledAt            = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_AT == 'True') ? true : false;
        $this->enabledCh            = false;
        $this->minDe                = (float) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_DE;
        $this->maxDe                = (float) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_DE;
        $this->minAt                = (float) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_AT;
        $this->maxAt                = (float) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_AT;
        $this->minCh                = (float) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_CH;
        $this->maxCh                = (float) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_CH;
        $this->b2bDe                = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_DE == 'True') ? true : false;
        $this->b2bAt                = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_AT == 'True') ? true : false;
        $this->b2bCh                = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_CH == 'True') ? true : false;
        $this->alaDe                = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_DE == 'True') ? true : false;
        $this->alaAt                = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_AT == 'True') ? true : false;
        $this->alaCh                = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_CH == 'True') ? true : false;
        $this->merchantGtcUrl       = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_GTC_URL;
        $this->merchantPrivacyUrl   = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_PRIVACY_URL;
        $this->ratepayPrivacyUrlDe  = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_DE;
        $this->ratepayPrivacyUrlAt  = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_AT;
        $this->ratepayPrivacyUrlCh  = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_CH;
        $this->sandbox              = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SANDBOX == 'True') ? true : false;
        $this->logging              = (MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_LOGGING == 'True') ? true : false;
        $this->shopOwner            = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_OWNER;
        $this->shopHr               = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_HR;
        $this->shopPhone            = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FON;
        $this->shopFax              = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FAX;
        $this->shopZipCode          = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_PLZ;
        $this->shopStreet           = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_STREET;
        $this->shopCourt            = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_COURT;
        $this->shopBankName         = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_BANK_NAME;
        $this->shopSortCode         = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_CODE;
        $this->shopAccountNumber    = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT_NR;
        $this->shopSwift            = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SWIFT;
        $this->shopIban             = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_IBAN;
        $this->extraInvoiceField    = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_EXTRA_FIELD;
        $this->sort_order           = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_ORDER;

        $this->country = $this->_getCountry();

        $this->_setCredentials($this->country);

        if ((int) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ORDER_STATUS_ID;
        }
        
        if (is_object($order)) {
            $this->update_status();
        }
    }

    /**
     * Retrieve needed form fields for payment
     *
     * @return array
     */
    public function _getNeededFields()
    {
        parent::_getNeededFields();
        global $fields;

        if ($this->_isBankAccountNeeded()) {
            $fields[] = $this->_getBankBlockInitial();
            $fields[] = $this->_getBankAccountOwner();
            $fields[] = $this->_getBankAccountNumber();
            $fields[] = $this->_getBankCode();
            $fields[] = $this->_getBankName();
            $fields[] = $this->_getBankMandateReference();
            $fields[] = $this->_getBankConditions();
            $fields[] = $this->_getBankText();
        }

        return $fields;
    }

    /**
     * Retrieve bank account number form field
     *
     * @return array
     */
    protected function _getBankBlockInitial()
    {
        return array('title' => '<span id="ratepay_lastschrift_block" style="font-weight: bold;">' . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT . '</span>', 'field' => '');
    }

    /**
     * Retrieve bank account number form field
     *
     * @return array
     */
    protected function _getBankAccountOwner()
    {
        global $order;
        return array('title' => MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT_OWNER,
            'field' => tep_draw_input_field('ratepay_lastschrift_bankaccountowner', $order->customer['firstname'] . ' ' . $order->customer['lastname']));
    }

    /**
     * Retrieve bank account number form field
     *
     * @return array
     */
    protected function _getBankAccountNumber()
    {
        $title = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_IBAN_TITLE;
        if ($this->country == "DE") {
            $title .= '/' . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT_NR_TITLE;
        }
        $value = (Session::getRpSessionEntry('iban')) ? Session::getRpSessionEntry('iban') : Session::getRpSessionEntry('account-number');

        return array('title' =>  $title ,
            'field' => tep_draw_input_field('ratepay_lastschrift_bankaccountnumber', $value));
    }

    /**
     * Retrieve bank code form field
     *
     * @return array
     */
    protected function _getBankCode()
    {
        $title = MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SWIFT_TITLE;
        if ($this->country == "DE") {
            $title .= '/' . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_CODE_TITLE;
        }
        $value = (Session::getRpSessionEntry('bic-swift')) ? Session::getRpSessionEntry('bic-swift') : Session::getRpSessionEntry('bank-code');
        return array('title' => $title,
            'field' => tep_draw_input_field('ratepay_lastschrift_bankcode', $value));
    }

    /**
     * Retrieve bank name form field
     *
     * @return array
     */
    protected function _getBankName()
    {
        $value = (Session::getRpSessionEntry('bank-name')) ? Session::getRpSessionEntry('bank-name') : '';
        return array('title' => MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_BANK_NAME_TITLE,
            'field' => tep_draw_input_field('ratepay_lastschrift_bankname', $value));
    }

    /**
     * Retrieve bank conditions checkbox
     *
     * @return array
     */
    protected function _getBankConditions()
    {
        return array('title' => '',
            'field' => tep_draw_checkbox_field('ratepay_lastschrift_conditions', '', false));
    }

    /**
     * Print bank mandate reference
     *
     * @return array
     */
    protected function _getBankMandateReference()
    {
        $mandateReference = RATEPAY_LASTSCHRIFT_MANDATE_1 . "<br>";
        $mandateReference .= RATEPAY_LASTSCHRIFT_MANDATE_2 . "<br>";
        $mandateReference .= RATEPAY_LASTSCHRIFT_MANDATE_3;

        return array('title' => '', 'field' => '<p style="border: 1px solid #787878; padding: 2px;">' . $mandateReference . '</p>');
    }

    /**
     * Print bank text
     *
     * @return array
     */
    protected function _getBankText()
    {
        $privacyPolicyLink = 'ratepayPrivacyUrl' . ucfirst(strtolower($this->country));

        $text = RATEPAY_LASTSCHRIFT_AGGREEMENT_1;
        $text .= "<a href=\"" . $this->$privacyPolicyLink . "\" target=\"_blank\" style=\"text-decoration: underline;\">" . RATEPAY_LASTSCHRIFT_PRIVACY_POLICY . "</a> ";
        $text .= RATEPAY_LASTSCHRIFT_AGGREEMENT_2 . "<br>";
	    $text .= "<br>";
	    $text .= "<b>" . RATEPAY_LASTSCHRIFT_NOTICE . ":</b><br>";
        $text .= RATEPAY_LASTSCHRIFT_NOTICE_1;

        return array('title' => '', 'field' => '<span class="ratepay_lastschrift_block">' . $text . '</span>');
    }

    /**
     * Set the bank data into the session
     *
     * @param array $data
     */
    public function setBankData($data)
    {
        $this->_setBankDataSession($data);
    }

    private function _setBankDataSession($data)
    {
        Session::setRpSessionEntry('owner', $data['owner']);
        if (!Data::betterEmpty($data['iban'])) {
            Session::setRpSessionEntry('iban', $data['iban']);
            if (!Data::betterEmpty($data['bic-swift'])) {
                Session::setRpSessionEntry('bic-swift', $data['bic-swift']);
            }
        } else {
            Session::setRpSessionEntry('bank-account-number', $data['bank-account-number']);
            Session::setRpSessionEntry('bank-code', $data['bank-code']);
            Session::cleanRpSessionEntry('iban');
            Session::cleanRpSessionEntry('bic-swift');
        }
        Session::setRpSessionEntry('bank-name', $data['bank-name']);
    }

    /**
     * Retrieve the bankdata
     *
     * @return array
     */
    public function getBankData()
    {
        $bankdata = array();
        $bankdata['owner'] = Session::getRpSessionEntry('owner');
        if (Session::getRpSessionEntry('iban')) {
            $bankdata['iban'] = Session::getRpSessionEntry('iban');
            if (Session::getRpSessionEntry('bic-swift')) {
                $bankdata['bic-swift'] = Session::getRpSessionEntry('bic-swift');
            }
        } else {
            $bankdata['bank-account-number'] = Session::getRpSessionEntry('bank-account-number');
            $bankdata['bank-code'] = Session::getRpSessionEntry('bank-code');
        }
        $bankdata['bank-name'] = Session::getRpSessionEntry('bank-name');

        return $bankdata;
    }

    /**
     * Updates the payment status status
     */
    public function update_status() 
    {
        global $order;
        if (($this->enabled == true) && ((int) MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ZONE > 0)) {
            $check_flag = false;
            $check_query = tep_db_query("SELECT zone_id from "
                    . TABLE_ZONES_TO_GEO_ZONES . " WHERE geo_zone_id = '"
                    . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ZONE . "' and zone_country_id = '"
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

            if (!$check_flag) {
                $this->enabled = false;
            }
        }
    }

    public function pre_confirmation_check() {

        if ($this->_isBankAccountNeeded()) {
            $accountOwner = trim(Globals::getPostEntry('ratepay_lastschrift_bankaccountowner'));
            $accountNumber = strtoupper(trim(Globals::getPostEntry('ratepay_lastschrift_bankaccountnumber')));
            $bankCode = strtoupper(trim(Globals::getPostEntry('ratepay_lastschrift_bankcode')));
            $bankName = trim(Globals::getPostEntry('ratepay_lastschrift_bankname'));
            $conditions = Globals::getPostEntry('ratepay_lastschrift_conditions');

            if ($this->_checkBankAccountOwner($accountOwner)) {
                $bankAccount['owner'] = $accountOwner;
            } else {
                $this->error['ACCOUNTOWNER'] = 'MISSING';
            }

            switch ($this->_checkBankAccountNumber($accountNumber)) {
                case 'IBAN' :
                    $bankAccount['iban'] = $accountNumber;
                    break;
                case 'ACCNR' :
                    $bankAccount['bank-account-number'] = $accountNumber;
                    break;
                case 'MISSING' :
                    $this->error['ACCOUNTNUMBER'] = 'MISSING';
                    break;
                case 'WRONG_COUNTRY' :
                    $this->error['ACCOUNTNUMBER'] = 'WRONG_COUNTRY';
                    break;
                case 'INVALID' :
                    $this->error['ACCOUNTNUMBER'] = 'INVALID';
                    break;
            }

            switch ($this->_checkBankCode($bankCode)) {
                case 'BIC' :
                    $bankAccount['bic-swift'] = $bankCode;
                    break;
                case 'BLZ' :
                    $bankAccount['bank-code'] = $bankCode;
                    break;
                case 'MISSING' :
                    if (!$bankAccount['iban'] || $this->country != 'DE') {
                        $this->error['BANKCODE'] = 'MISSING';
                    }
                    break;
                case 'INVALID' :
                    $this->error['BANKCODE'] = 'INVALID';
                    break;
            }

            if ($this->_checkBankName($bankName)) {
                $bankAccount['bank-name'] = $bankName;
            } else {
                $this->error['BANKNAME'] = 'MISSING';
            }

            if (($bankAccount['bank-account-number'] && $bankAccount['bic-swift']) ||
                ($bankAccount['iban'] && $bankAccount['bank-code'])) {
                $this->error['BANKACCOUNTS'] = 'INVALID';
            }

            if ($bankAccount) {
                $this->setBankData($bankAccount);
            }

            if (!$this->_checkBankConditions($conditions)) {
                $this->error['CONDITIONS'] = 'MISSING';
            }
        }

        parent::pre_confirmation_check();
    }

    /**
     * Is called when the checkout_confirmation.php page is called
     */
    public function confirmation() 
    {
        return false;
    }

    /**
     * Checks if RatePAY Lastschrift is enabled.
     *
     * @return boolean
     */
    public function check() 
    {
        if (!isset($this->check)) {
            $check_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS'");
            $this->check = tep_db_num_rows($check_query);
        }
        
        return $this->check;
    }

    /**
     * Is account owner valid
     *
     * @param string $accountOwner
     * @return boolean
     */
    protected function _checkBankAccountOwner($accountOwner)
    {
        return (!empty($accountOwner));
    }

    /**
     * Is account number valid and which kind of number (iban/number)
     *
     * @param string $accountNumber
     * @return string
     */
    protected function _checkBankAccountNumber($accountNumber)
    {
        if (empty($accountNumber)) {
            return 'MISSING';
        } elseif (preg_match('/^[0-9]{3,10}$/', trim($accountNumber))) {
            return 'ACCNR';
        } elseif (preg_match('/^DE\d{20}$/', trim($accountNumber))) {
            if ($this->country != "DE") {
                return 'WRONG_COUNTRY';
            }
            return 'IBAN';
        } elseif (preg_match('/^AT\d{18}$/', trim($accountNumber))) {
            if ($this->country != "AT") {
                return 'WRONG_COUNTRY';
            }
            return 'IBAN';
        } elseif (preg_match('/^CH\d{19}$/', trim($accountNumber))) {
            if ($this->country != "CH") {
                return 'WRONG_COUNTRY';
            }
            return 'INVALID'; //return 'IBAN';
        } else {
            return 'INVALID';
        }
    }

    /**
     * Is bank code valid and which kind of code (bic/blz)
     *
     * @param string $bankCode
     * @return string
     */
    protected function _checkBankCode($bankCode)
    {
        if (empty($bankCode)) {
            return 'MISSING';
        } elseif (preg_match('/^[0-9]{8}$/', trim($bankCode))) {
            return 'BLZ';
        } elseif (preg_match('/^([a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?)$/', trim($bankCode))) {
            return 'BIC';
        } else {
            return 'INVALID';
        }
    }

    /**
     * Is account name valid
     *
     * @param string $bankName
     * @return boolean
     */
    protected function _checkBankName($bankName)
    {
        return (!empty($bankName));
    }

    /**
     * Is agreement set
     *
     * @param string $conditions
     * @return boolean
     */
    protected function _checkBankConditions($conditions)
    {
        return (!empty($conditions));
    }

    /**
     * Install routine, inserts all module entrys
     */
    public function install() 
    {
        global $language;
        @include(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/payment/ratepay_lastschrift.php');
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_lastschrift_orders'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_lastschrift_orders`("
                  . " `id` int(11) NOT NULL auto_increment,"
                  . " `order_number` varchar(32) NOT NULL,"
                  . " `transaction_id` varchar(64) NOT NULL,"
                  . " `transaction_short_id` varchar(64) NOT NULL,"
                  . " `customers_birth` varchar(64) NOT NULL,"
                  . " `firstname` varchar(64) NOT NULL,"
                  . " `lastname` varchar(64) NOT NULL,"
                  . " `ip_address` varchar(64) NOT NULL,"
                  . " `billing_country_code` varchar(2) NOT NULL,"
                  . " `shipping_country_code` varchar(2) NOT NULL,"
                  . " `fax` varchar(64) NOT NULL,"
                  . " `gender` varchar(1) NOT NULL,"
                  . " `customers_country_code` varchar(2) NOT NULL,"
                  . " `descriptor` varchar(20),"
                  . " PRIMARY KEY  (`id`)"
                  . " ) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }
        
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_lastschrift_items'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_lastschrift_items` ("
                  . " `id` INT NOT NULL AUTO_INCREMENT,"
                  . " `order_number` VARCHAR( 255 ) NOT NULL ,"
                  . " `article_number` VARCHAR( 255 ) NOT NULL ,"
                  . " `article_name` VARCHAR(255) NOT NULL,"
                  . " `ordered` INT NOT NULL DEFAULT '1',"
                  . " `shipped` INT NOT NULL DEFAULT '0',"
                  . " `cancelled` INT NOT NULL DEFAULT '0',"
                  . " `returned` INT NOT NULL DEFAULT '0',"
                  . " `unit_price` decimal(10,3) NOT NULL DEFAULT '0',"
                  . " `unit_price_with_tax` decimal(10,3) NOT NULL DEFAULT '0',"
                  . " `total_price` decimal(10,3) NOT NULL DEFAULT '0',"
                  . " `total_price_with_tax` decimal(10,3) NOT NULL DEFAULT '0',"
                  . " `unit_tax` decimal(10,3) NOT NULL DEFAULT '0',"
                  . " `total_tax` decimal(10,3) NOT NULL DEFAULT '0',"
                  . " PRIMARY KEY  (`id`)"
                  . " ) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }
        
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_lastschrift_history'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_lastschrift_history` ("
                  . " `id` INT NOT NULL AUTO_INCREMENT,"
                  . " `order_number` VARCHAR( 255 ) NOT NULL ,"
                  . " `article_number` VARCHAR( 255 ) NOT NULL ,"
                  . " `article_name` VARCHAR( 255 ) NOT NULL ,"
                  . " `quantity` INT NOT NULL,"
                  . " `method` VARCHAR( 40 ) NOT NULL,"
                  . " `submethod` VARCHAR( 40 ) NOT NULL DEFAULT '',"
                  . " `date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
                  . " PRIMARY KEY  (`id`)"
                  . " ) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }
        
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_log'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_log` ("
                  . " `id` INT NOT NULL AUTO_INCREMENT,"
                  . " `order_number` VARCHAR( 255 ) NOT NULL,"
                  . " `transaction_id` VARCHAR( 255 ) NOT NULL,"
                  . " `payment_method` VARCHAR( 40 ) NOT NULL,"
                  . " `payment_type` VARCHAR( 40 ) NOT NULL,"
                  . " `payment_subtype` VARCHAR( 40 ) NOT NULL,"
                  . " `result` VARCHAR( 40 ) NOT NULL,"
                  . " `request` MEDIUMTEXT NOT NULL,"
                  . " `response` MEDIUMTEXT NOT NULL,"
                  . " `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
                  . " `result_code` VARCHAR( 10 ) NOT NULL,"
                  . " `reason` VARCHAR( 255 ) NOT NULL DEFAULT '',"
                  . " PRIMARY KEY  (`id`)"
                  . " ) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }

        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_lastschrift_details'")) == 0) {
            tep_db_query(
                "CREATE TABLE IF NOT EXISTS `ratepay_lastschrift_details` ("
                . " `id` int(11) NOT NULL AUTO_INCREMENT,"
                . " `userid` varchar(256) NOT NULL,"
                . " `owner` blob NOT NULL,"
                . " `accountnumber` blob NOT NULL,"
                . " `bankcode` blob NOT NULL,"
                . " `bankname` blob NOT NULL,"
                . " PRIMARY KEY (`id`)"
                . " ) ENGINE = MYISAM;"
            );
        }
        
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS', 'True', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SANDBOX_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SANDBOX', 'True', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SANDBOX_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_LOGGING_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_LOGGING', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_LOGGING_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_DE', 'True', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_DE_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_DE', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_DE', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_DE', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_DE', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_DE', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_DE', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_TITLE_DE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_DE', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_DESC . "', '6', '3', NOW())");

        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_AT', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_AT_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_AT', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_AT', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_AT', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_AT', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_AT', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_AT', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_TITLE_AT . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_AT', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis-at', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_DESC . "', '6', '3', NOW())");

        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_CH_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_CH', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_CH_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_TITLE_CH . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_CH', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_TITLE_CH . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_CH', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_TITLE_CH . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_CH', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_TITLE_CH . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_CH', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_TITLE_CH . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_CH', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_TITLE_CH . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_CH', 'False', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_TITLE_CH . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_CH', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis-ch', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_DESC . "', '6', '3', NOW())");
        
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_GTC_URL_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_GTC_URL', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_GTC_URL_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_PRIVACY_URL_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_PRIVACY_URL', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_PRIVACY_URL_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_OWNER_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_OWNER', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_OWNER_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_HR_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_HR', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_HR_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FON_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FON', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FON_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FAX_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FAX', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FAX_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_PLZ_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_PLZ', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_PLZ_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_STREET_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_STREET', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_STREET_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_COURT_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_COURT', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_COURT_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_BANK_NAME_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_BANK_NAME', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_BANK_NAME_DESC ."', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_CODE_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_CODE', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_CODE_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT_NR_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT_NR', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT_NR_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SWIFT_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SWIFT', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SWIFT_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_IBAN_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_IBAN', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_IBAN_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_EXTRA_FIELD_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_EXTRA_FIELD','', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_EXTRA_FIELD_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ZONE_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ZONE', '0', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ZONE_DESC . "', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALLOWED_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALLOWED', '', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALLOWED_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ORDER_STATUS_ID_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ORDER_STATUS_ID', '0', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ORDER_STATUS_ID_DESC . "', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_ORDER_TITLE . "', 'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_ORDER', '0', '" . MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_ORDER_DESC . "', '6', '0', now())");
    }

    /**
     * Removes all RatePAY Lastschrift module entrys
     */
    public function remove() 
    {
        tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    /**
     * All RatePAY Lastschrift module keys
     *
     * @return array
     */
    public function keys() 
    {
        return array (
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SANDBOX',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_LOGGING',

            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_DE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_DE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_DE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_DE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_DE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_DE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_DE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_DE',

            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_AT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_AT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_AT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_AT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_AT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_AT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_AT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_AT',

            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_STATUS_CH',
            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_PROFILE_ID_CH',
            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SECURITY_CODE_CH',
            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MIN_CH',
            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MAX_CH',
            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_B2B_CH',
            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALA_CH',
            #'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_RATEPAY_PRIVACY_URL_CH',
            
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_GTC_URL',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_MERCHANT_PRIVACY_URL',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_OWNER',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_HR',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FON',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_FAX',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_PLZ',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_STREET',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SHOP_COURT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_BANK_NAME',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_CODE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ACCOUNT_NR',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SWIFT',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_IBAN',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_EXTRA_FIELD',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ALLOWED',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ZONE',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_ORDER_STATUS_ID',
            'MODULE_PAYMENT_RATEPAY_LASTSCHRIFT_SORT_ORDER'
        );
    }
}
