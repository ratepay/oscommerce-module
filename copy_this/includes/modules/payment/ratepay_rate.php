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
 * RatePAY Rate payment class
 */
class ratepay_rate extends ratepay_abstract
{

    /**
     * Payment code
     * 
     * @var string
     */
    public $code;
    
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
     * Payment active flag
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
    public $profileIdDe;
    
    /**
     * Config entry "RatePAY Securitycode"
     * 
     * @var string
     */
    public $securityCodeDe;
    
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
     * Minimal order amount
     * 
     * @var float
     */
    public $minDe;
    
    /**
     * Maximal order amount
     * 
     * @var float
     */
    public $maxDe;
    
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
     * RatePAY privacy url
     * 
     * @var string
     */
    public $ratepayPrivacyUrl;
    
    /**
     * Payment firstday
     * 
     * @var int
     */
    public $paymentFirstDay;

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
     * This constructor set's all properties for the ratepay_rate object
     */
    public function __construct() 
    {
        global $order;
        
        $this->code                 = 'ratepay_rate';
        $this->version              = '2.1.2';
        $this->shopVersion          = str_replace(' ','',str_replace("xt:Commerce v", "", PROJECT_VERSION));
        $this->shopSystem           = 'xt:Commerce';
        $this->title                = MODULE_PAYMENT_RATEPAY_RATE_TEXT . " (" . $this->version . ")";
        $this->public_title         = $this->code;
        $this->description          = utf8_decode(MODULE_PAYMENT_RATEPAY_RATE_TEXT_DESCRIPTION);
        $this->enabled              = (MODULE_PAYMENT_RATEPAY_RATE_STATUS == 'True') ? true : false;
        $this->enabledDe            = true;
        $this->enabledAt            = false;
        $this->enabledCh            = false;
        $this->profileId            = MODULE_PAYMENT_RATEPAY_RATE_PROFILE_ID;
        $this->securityCode         = MODULE_PAYMENT_RATEPAY_RATE_SECURITY_CODE;
        $this->minDe                = (float) MODULE_PAYMENT_RATEPAY_RATE_MIN;
        $this->maxDe                = (float) MODULE_PAYMENT_RATEPAY_RATE_MAX;
        $this->merchantGtcUrl       = MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_GTC_URL;
        $this->merchantPrivacyUrlDe = MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_PRIVACY_URL;
        $this->ratepayPrivacyUrlDe  = MODULE_PAYMENT_RATEPAY_RATE_RATEPAY_PRIVACY_URL;
        $this->paymentFirstDay      = (MODULE_PAYMENT_RATEPAY_RATE_PAYMENT_FIRSTDAY == 'True') ? true : false;
        $this->sandbox              = (MODULE_PAYMENT_RATEPAY_RATE_SANDBOX == 'True') ? true : false;
        $this->logging              = (MODULE_PAYMENT_RATEPAY_RATE_LOGGING == 'True') ? true : false;
        $this->shopOwner            = MODULE_PAYMENT_RATEPAY_RATE_SHOP_OWNER;
        $this->shopHr               = MODULE_PAYMENT_RATEPAY_RATE_SHOP_HR;
        $this->shopPhone            = MODULE_PAYMENT_RATEPAY_RATE_SHOP_FON;
        $this->shopFax              = MODULE_PAYMENT_RATEPAY_RATE_SHOP_FAX;
        $this->shopZipCode          = MODULE_PAYMENT_RATEPAY_RATE_SHOP_PLZ;
        $this->shopStreet           = MODULE_PAYMENT_RATEPAY_RATE_SHOP_STREET;
        $this->shopCourt            = MODULE_PAYMENT_RATEPAY_RATE_SHOP_COURT;
        $this->shopBankName         = MODULE_PAYMENT_RATEPAY_RATE_BANK_NAME;
        $this->shopSortCode         = MODULE_PAYMENT_RATEPAY_RATE_SORT_CODE;
        $this->shopAccountNumber    = MODULE_PAYMENT_RATEPAY_RATE_ACCOUNT_NR;
        $this->shopSwift            = MODULE_PAYMENT_RATEPAY_RATE_SWIFT;
        $this->shopIban             = MODULE_PAYMENT_RATEPAY_RATE_IBAN;
        $this->extraInvoiceField    = MODULE_PAYMENT_RATEPAY_RATE_EXTRA_FIELD;
        $this->sort_order           = MODULE_PAYMENT_RATEPAY_RATE_SORT_ORDER;

        $this->country = $this->_getCountry();

        if ((int) MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID;
        }
        
        if (is_object($order)) {
            $this->update_status();
        }
    }
    
    /**
     * Updates the payment status status
     */
    public function update_status() 
    {
        global $order;
        if (($this->enabled == true) && ((int) MODULE_PAYMENT_RATEPAY_RATE_ZONE > 0)) {
            $check_flag = false;
            $check_query = tep_db_query("SELECT zone_id from "
                    . TABLE_ZONES_TO_GEO_ZONES . " WHERE geo_zone_id = '"
                    . MODULE_PAYMENT_RATEPAY_RATE_ZONE . "' and zone_country_id = '"
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
    
    /*public function pre_confirmation_check()
    {
        parent::pre_confirmation_check();
        
        //if ($this->isInfoVisited()) {
        //    $this->rateCalculatorCheck();
        //}
    }*/

    public function getRateCalculatorError()
    {
        $error = Session::getRpSessionEntry('calculator_error');
        if (!Data::betterEmpty($error)) {
            $error = '<div class="payment-error">' . $error . '</div>';
            Session::setRpSessionEntry('calculator_error', null);
        }
        return $error;
    }
    
    /**
     * Is called when the checkout_confirmation.php page is called
     */
    public function rateCalculatorCheck() 
    {
        $checking = true;
        
        if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_total_amount'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_amount'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_interest_amount'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_service_charge'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_annual_percentage_rate'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_monthly_debit_interest'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_number_of_rates'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_rate'))) {
            $checking = false;
        } else if (Data::betterEmpty(Session::getRpSessionEntry('ratepay_rate_last_rate'))) {
            $checking = false;
        }

        if (!$checking) {
            Session::setRpSessionEntry('calculator_error', RATEPAY_RATE_RATE_CALCULATOR_ERROR);
            tep_redirect(tep_href_link("ratepay_rate_checkout_details.php", 'conditions=1', 'SSL'));
        }
    }
    
    /**
     * Redeclare _saveRpOrder() rate detail saving added
     * 
     * @param order $order
     * @param int $orderId
     */
    protected function _saveRpOrder(order $order, $orderId)
    {
        parent::_saveRpOrder($order, $orderId);
        $this->_saveRateDetails($orderId);
    }

    /**
     * Save rate details to the db
     * 
     * @param int$orderId
     */
    protected function _saveRateDetails($orderId)
    {
        $data = array(
            'order_number' => $orderId,
            'total_amount' => Session::getRpSessionEntry('ratepay_rate_total_amount'),
            'amount' => Session::getRpSessionEntry('ratepay_rate_amount'),
            'interest_amount' => Session::getRpSessionEntry('ratepay_rate_interest_amount'),
            'service_charge' => Session::getRpSessionEntry('ratepay_rate_service_charge'),
            'annual_percentage_rate' => Session::getRpSessionEntry('ratepay_rate_annual_percentage_rate'),
            'monthly_debit_interest' => Session::getRpSessionEntry('ratepay_rate_monthly_debit_interest'),
            'number_of_rates' => Session::getRpSessionEntry('ratepay_rate_number_of_rates'),
            'rate' => Session::getRpSessionEntry('ratepay_rate_rate'),
            'last_rate' => Session::getRpSessionEntry('ratepay_rate_last_rate')
        );
        
        Db::setRatepayRateDetails($data);
    }
    
    /**
     * Checks if RatePAY Rate is enabled.
     *
     * @return boolean
     */
    public function check() 
    {
        if (!isset($this->check)) {
            $check_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_RATEPAY_RATE_STATUS'");
            $this->check = tep_db_num_rows($check_query);
        }
        
        return $this->check;
    }

    /**
     * Install routine, inserts all module entrys
     */
    public function install() 
    {
        global $language;
        @include(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/payment/ratepay_rate.php');
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_rate_orders'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_rate_orders`("
                  . " `id` int(11) NOT NULL auto_increment,"
                  . " `order_number` varchar(32) NOT NULL,"
                  . " `transaction_id` varchar(64) NOT NULL,"
                  . " `transaction_short_id` varchar(64) NOT NULL,"
                  . " `customers_birth` varchar(64) NOT NULL,"
                  . " `firstname` varchar(64) NOT NULL,"
                  . " `lastname` varchar(64) NOT NULL,"
                  . " `ip_address` varchar(64) NOT NULL,"
                  . " `billing_country_code` varchar(64) NOT NULL,"
                  . " `shipping_country_code` varchar(64) NOT NULL,"
                  . " `fax` varchar(64) NOT NULL,"
                  . " `gender` varchar(1) NOT NULL,"
                  . " `customers_country_code` varchar(2) NOT NULL,"
                  . " `descriptor` varchar(20),"
                  . " PRIMARY KEY  (`id`)"
                  . " ) ENGINE=MyISAM AUTO_INCREMENT=1;"
            );
        }
        
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_rate_items'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_rate_items` ("
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
        
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_rate_history'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_rate_history` ("
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
        
        if (tep_db_num_rows(tep_db_query("SHOW TABLES LIKE 'ratepay_rate_details'")) == 0) {
            tep_db_query(
                    "CREATE TABLE `ratepay_rate_details` ("
                  . " `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,"
                  . " `order_number` VARCHAR(255) NOT NULL ,"
                  . " `total_amount` DOUBLE NOT NULL ,"
                  . " `amount` DOUBLE NOT NULL ,"
                  . " `interest_amount` DOUBLE NOT NULL ,"
                  . " `service_charge` DOUBLE NOT NULL ,"
                  . " `annual_percentage_rate` DOUBLE NOT NULL ,"
                  . " `monthly_debit_interest` DOUBLE NOT NULL ,"
                  . " `number_of_rates` DOUBLE NOT NULL ,"
                  . " `rate` DOUBLE NOT NULL ,"
                  . " `payment_firstday` VARCHAR( 4 ) NOT NULL DEFAULT '',"
                  . " `last_rate` DOUBLE NOT NULL"
                  . " ) ENGINE = MYISAM AUTO_INCREMENT=1;"
            );
        }
        
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_STATUS_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_STATUS', 'True', '" . MODULE_PAYMENT_RATEPAY_RATE_STATUS_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SANDBOX_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SANDBOX', 'True', '" . MODULE_PAYMENT_RATEPAY_RATE_SANDBOX_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_LOGGING_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_LOGGING', 'False', '" . MODULE_PAYMENT_RATEPAY_RATE_LOGGING_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_PROFILE_ID_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_PROFILE_ID', '', '" . MODULE_PAYMENT_RATEPAY_RATE_PROFILE_ID_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SECURITY_CODE_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SECURITY_CODE', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SECURITY_CODE_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_MIN_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_MIN', '', '" . MODULE_PAYMENT_RATEPAY_RATE_MIN_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_MAX_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_MAX', '', '" . MODULE_PAYMENT_RATEPAY_RATE_MAX_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_GTC_URL_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_GTC_URL', '', '" . MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_GTC_URL_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_PRIVACY_URL_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_PRIVACY_URL', '', '" . MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_PRIVACY_URL_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_RATEPAY_PRIVACY_URL_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_RATEPAY_PRIVACY_URL', '', '" . MODULE_PAYMENT_RATEPAY_RATE_RATEPAY_PRIVACY_URL_DESC . "', '6', '3', NOW())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_PAYMENT_FIRSTDAY_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_PAYMENT_FIRSTDAY', 'False', '" . MODULE_PAYMENT_RATEPAY_RATE_PAYMENT_FIRSTDAY_DESC . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_OWNER_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SHOP_OWNER', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_OWNER_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_HR_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SHOP_HR', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_HR_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_FON_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SHOP_FON', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_FON_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_FAX_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SHOP_FAX', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_FAX_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_PLZ_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SHOP_PLZ', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_PLZ_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_STREET_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SHOP_STREET', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_STREET_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_COURT_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SHOP_COURT', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SHOP_COURT_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_BANK_NAME_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_BANK_NAME', '', '" . MODULE_PAYMENT_RATEPAY_RATE_BANK_NAME_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SORT_CODE_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SORT_CODE', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SORT_CODE_DESC . "', '6', '3', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_ACCOUNT_NR_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_ACCOUNT_NR', '', '" . MODULE_PAYMENT_RATEPAY_RATE_ACCOUNT_NR_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SWIFT_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SWIFT', '', '" . MODULE_PAYMENT_RATEPAY_RATE_SWIFT_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_IBAN_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_IBAN', '', '" . MODULE_PAYMENT_RATEPAY_RATE_IBAN_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_EXTRA_FIELD_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_EXTRA_FIELD', '', '" . MODULE_PAYMENT_RATEPAY_RATE_EXTRA_FIELD_DESC . "',  '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_ZONE_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_ZONE', '0', '" . MODULE_PAYMENT_RATEPAY_RATE_ZONE_DESC . "', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_ALLOWED_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_ALLOWED', '', '" . MODULE_PAYMENT_RATEPAY_RATE_ALLOWED_DESC . "', '6', '0', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID', '0', '" . MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID_DESC . "', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('" . MODULE_PAYMENT_RATEPAY_RATE_SORT_ORDER_TITLE . "', 'MODULE_PAYMENT_RATEPAY_RATE_SORT_ORDER', '0', '" . MODULE_PAYMENT_RATEPAY_RATE_SORT_ORDER_DESC . "', '6', '0', now())");
    }

    /**
     * Removes all RatePAY Rate module entrys
     */
    public function remove() 
    {
        tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    /**
     * All RatePAY Rate module keys
     *
     * @return array
     */
    public function keys() 
    {
        return array (
            'MODULE_PAYMENT_RATEPAY_RATE_STATUS',
            'MODULE_PAYMENT_RATEPAY_RATE_SANDBOX',
            'MODULE_PAYMENT_RATEPAY_RATE_LOGGING',
            'MODULE_PAYMENT_RATEPAY_RATE_PROFILE_ID',
            'MODULE_PAYMENT_RATEPAY_RATE_SECURITY_CODE',
            'MODULE_PAYMENT_RATEPAY_RATE_MIN',
            'MODULE_PAYMENT_RATEPAY_RATE_MAX',
            'MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_GTC_URL',
            'MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_PRIVACY_URL',
            'MODULE_PAYMENT_RATEPAY_RATE_RATEPAY_PRIVACY_URL',
            'MODULE_PAYMENT_RATEPAY_RATE_PAYMENT_FIRSTDAY',
            'MODULE_PAYMENT_RATEPAY_RATE_SHOP_OWNER',
            'MODULE_PAYMENT_RATEPAY_RATE_SHOP_HR',
            'MODULE_PAYMENT_RATEPAY_RATE_SHOP_FON',
            'MODULE_PAYMENT_RATEPAY_RATE_SHOP_FAX',
            'MODULE_PAYMENT_RATEPAY_RATE_SHOP_PLZ',
            'MODULE_PAYMENT_RATEPAY_RATE_SHOP_STREET',
            'MODULE_PAYMENT_RATEPAY_RATE_SHOP_COURT',
            'MODULE_PAYMENT_RATEPAY_RATE_BANK_NAME',
            'MODULE_PAYMENT_RATEPAY_RATE_SORT_CODE',
            'MODULE_PAYMENT_RATEPAY_RATE_ACCOUNT_NR',
            'MODULE_PAYMENT_RATEPAY_RATE_SWIFT',
            'MODULE_PAYMENT_RATEPAY_RATE_IBAN',
            'MODULE_PAYMENT_RATEPAY_RATE_EXTRA_FIELD',
            'MODULE_PAYMENT_RATEPAY_RATE_ALLOWED',
            'MODULE_PAYMENT_RATEPAY_RATE_ZONE',
            'MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID',
            'MODULE_PAYMENT_RATEPAY_RATE_SORT_ORDER'
        );
    }
}
