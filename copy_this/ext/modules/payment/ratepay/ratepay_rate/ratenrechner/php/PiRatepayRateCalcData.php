<?php

/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */
include_once 'includes/application_top.php';
include_once 'includes/modules/payment/ratepay_rate.php';
include_once 'includes/classes/ratepay/helpers/Session.php';
include_once "PiRatepayRateCalcDataInterface.php";

class PiRatepayRateCalcData implements PiRatepayRateCalcDataInterface
{

    /**
     * This method get the RatePAY profile-id and has to be rewritten
     * @return string
     */
    public function getProfileId()
    {
        $ratepay = new ratepay_rate();
        return $ratepay->profileId;
    }

    /**
     * This method get the RatePAY security-code and has to be rewritten
     * If you only have the hashed security-code, return an empty string.
     * @return string
     */
    public function getSecurityCode()
    {

        return '';
    }

    /**
     * This method get the security-code md5 hashed and has to be rewritten
     * If you only have the non hashed security-code, return an empty string.
     * @return string
     */
    public function getSecurityCodeHashed()
    {
        $ratepay = new ratepay_rate();
        $securityCode = $ratepay->securityCode;
        return $securityCode;
    }

    /**
     * This method get the status live or sandbox and has to be rewritten
     * @return boolean
     */
    public function isLive()
    {
        $pi_ratepay = new ratepay_rate();
        return !$pi_ratepay->sandbox;
    }

    /**
     * This method get the transaction-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */
    public function getTransactionId()
    {
        return '';
    }

    /**
     * This method get the transaction-short-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */
    public function getTransactionShortId()
    {
        return '';
    }

    /**
     * This method get the order-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */
    public function getOrderId()
    {
        return '';
    }

    /**
     * This method get the merchant-consumer-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */
    public function getMerchantConsumerId()
    {
        return '';
    }

    /**
     * This method get the merchant-cosnumer-classification and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */
    public function getMerchantConsumerClassification()
    {
        return '';
    }

    /**
     * This method get the total basket amount and has to be rewritten
     * @return string
     */
    public function getAmount()
    {
        return Session::getRpSessionEntry('basketAmount');
    }

    /**
     * This method get the selected languange and has to be rewritten
     * return DE for German Calculator. Everything else will be English.
     * @return string
     */
    public function getLanguage()
    {
        if (Session::getSessionEntry('language') == 'german') {
            return 'DE';
        } else {
            return 'EN';
        }
    }

    /**
     * This method get the interest rate and has to be rewritten
     * return '' for default.
     * @return string
     */
    public function getInterestRate()
    {
        return '';
    }

    /**
     * This method set all needed data and has to be rewritten
     * It is for internal Shop usage, like saving the variables in the DB or session etc.
     * @param string $total_amount
     * @param string $amount
     * @param string $interest_amount
     * @param string $service_charge
     * @param string $annual_percentage_rate
     * @param string $monthly_debit_interest
     * @param string $number_of_rates
     * @param string $rate
     * @param string $last_rate
     */
    public function setData($total_amount, $amount, $interest_rate, $interest_amount, $service_charge, $annual_percentage_rate, $monthly_debit_interest, $number_of_rates, $rate, $last_rate, $payment_firstday)
    {
        Session::setRpSessionEntry('ratepay_rate_total_amount', $total_amount);
        Session::setRpSessionEntry('ratepay_rate_amount', $amount);
        Session::setRpSessionEntry('ratepay_rate_interest_rate', $interest_rate);
        Session::setRpSessionEntry('ratepay_rate_interest_amount', $interest_amount);
        Session::setRpSessionEntry('ratepay_rate_service_charge', $service_charge);
        Session::setRpSessionEntry('ratepay_rate_annual_percentage_rate', $annual_percentage_rate);
        Session::setRpSessionEntry('ratepay_rate_monthly_debit_interest', $monthly_debit_interest);
        Session::setRpSessionEntry('ratepay_rate_number_of_rates', $number_of_rates);
        Session::setRpSessionEntry('ratepay_rate_rate', $rate);
        Session::setRpSessionEntry('ratepay_rate_last_rate', $last_rate);
        if (array_key_exists('pi_dd', $_SESSION)) {
            Session::setRpSessionEntry('ratepay_payment_firstday', $_SESSION['pi_dd']);
        } else {
            Session::setRpSessionEntry('ratepay_payment_firstday', $payment_firstday);
        }
    }

    /**
     * This method get all needed data and has to be rewritten
     * Optional - Will only be used, if you want to show the result on another page (include_result.html)
     * Needs to return an array with the indexes total_amount, amount, interest_amount, service_charge, annual_percentage_rate, monthly_debit_interest, number_of_rates , rate, lastRate
     * @return array
     */
    public function getData()
    {
        return array(
            'total_amount' => Session::getRpSessionEntry('ratepay_rate_total_amount'),
            'amount' => Session::getRpSessionEntry('ratepay_rate_amount'),
            'interest_rate' => Session::getRpSessionEntry('ratepay_rate_interest_rate'),
            'interest_amount' => Session::getRpSessionEntry('ratepay_rate_interest_amount'),
            'service_charge' => Session::getRpSessionEntry('ratepay_rate_service_charge'),
            'annual_percentage_rate' => Session::getRpSessionEntry('ratepay_rate_annual_percentage_rate'),
            'monthly_debit_interest' => Session::getRpSessionEntry('ratepay_rate_monthly_debit_interest'),
            'number_of_rates' => Session::getRpSessionEntry('ratepay_rate_number_of_rates'),
            'rate' => Session::getRpSessionEntry('ratepay_rate_rate'),
            'last_rate' => Session::getRpSessionEntry('ratepay_rate_last_rate'),
            'payment_firstday' => Session::getRpSessionEntry('ratepay_payment_firstday')
        );
    }

    /**
     * This method unset the Data and has to be rewritten
     */
    public function unsetData()
    {
        unset($_SESSION['ratepay']['ratepay_rate_total_amount']);
        unset($_SESSION['ratepay']['ratepay_rate_amount']);
        unset($_SESSION['ratepay']['ratepay_rate_interest_rate']);
        unset($_SESSION['ratepay']['ratepay_rate_interest_amount']);
        unset($_SESSION['ratepay']['ratepay_rate_service_charge']);
        unset($_SESSION['ratepay']['ratepay_rate_annual_percentage_rate']);
        unset($_SESSION['ratepay']['ratepay_rate_monthly_debit_interest']);
        unset($_SESSION['ratepay']['ratepay_rate_number_of_rates']);
        unset($_SESSION['ratepay']['ratepay_rate_rate']);
        unset($_SESSION['ratepay']['ratepay_rate_last_rate']);
        unset($_SESSION['ratepay']['ratepay_payment_firstday']);
    }

    public function getPaymentFirstdayConfig()
    {
        $ratepay = new ratepay_rate();
        return $ratepay->paymentFirstDay;
    }

}

?>