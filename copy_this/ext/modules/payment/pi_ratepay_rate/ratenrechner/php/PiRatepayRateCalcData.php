<?php

/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */
require_once "PiRatepayRateCalcDataInterface.php";
require_once 'includes/modules/payment/pi_ratepay_rate.php';
class PiRatepayRateCalcData implements PiRatepayRateCalcDataInterface 
{
    /*
     * This method get the RatePAY profile-id and has to be rewritten
     * @return string
     */

    public function getProfileId()
    {
        //$your_profile_id = 'Your RatePAY Profile-ID';
        $pi_ratepay = new pi_ratepay_rate();
        return $pi_ratepay->profileId;
    }

    /*
     * This method get the RatePAY security-code and has to be rewritten
     * If you only have the hashed security-code, return an empty string.
     * @return string
     */

    public function getSecurityCode() {
        return '';
    }

    /*
     * This method get the security-code md5 hashed and has to be rewritten
     * If you only have the non hashed security-code, return an empty string.
     * @return string
     */

    public function getSecurityCodeHashed() {
        $pi_ratepay = new pi_ratepay_rate();
        $securityCode = $pi_ratepay->securityCode;
        return $securityCode;
    }

    /*
     * This method get the status live or sandbox and has to be rewritten
     * @return boolean
     */

    public function isLive() {
        $pi_ratepay = new pi_ratepay_rate();
        $ratepay->live = $pi_ratepay->testOrLive();
        return $ratepay->live;
    }

    /*
     * This method get the transaction-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */

    public function getTransactionId() {
        return '';
    }

    /*
     * This method get the transaction-short-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */

    public function getTransactionShortId() {
        return '';
    }

    /*
     * This method get the order-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */

    public function getOrderId() {
        return '';
    }

    /*
     * This method get the merchant-consumer-id and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */

    public function getMerchantConsumerId() {
        return '';
    }

    /*
     * This method get the merchant-cosnumer-classification and has to be rewritten
     * Optional - Return Empty String - If empty String, it will not be sended to RatePAY.
     * @return string
     */

    public function getMerchantConsumerClassification() {
        return '';
    }

    /*
     * This method get the total basket amount and has to be rewritten
     * @return string
     */

    public function getAmount() {
        return $_SESSION['pi_ratepay_rate_order_total'];
    }

    /*
     * This method get the selected languange and has to be rewritten
     * return DE for German Calculator. Everything else will be English.
     * @return string
     */

    public function getLanguage() {
        if ($_SESSION['language'] == 'german') {
            return 'DE';
        } else {
            return 'EN';
        }
    }

    /*
     * This method get the interest rate and has to be rewritten
     * return '' for default.
     * @return string
     */

    public function getInterestRate() {
        return '';
    }

    /*
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

    public function setData($total_amount, $amount, $interest_rate, $interest_amount, $service_charge, $annual_percentage_rate, $monthly_debit_interest, $number_of_rates, $rate, $last_rate) {
        /* Saving Data as example in the Session */
        $_SESSION['pi_ratepay_rate_total_amount'] = $total_amount;
        $_SESSION['pi_ratepay_rate_amount'] = $amount;
        $_SESSION['pi_ratepay_rate_interest_rate'] = $interest_rate;
        $_SESSION['pi_ratepay_rate_interest_amount'] = $interest_amount;
        $_SESSION['pi_ratepay_rate_service_charge'] = $service_charge;
        $_SESSION['pi_ratepay_rate_annual_percentage_rate'] = $annual_percentage_rate;
        $_SESSION['pi_ratepay_rate_monthly_debit_interest'] = $monthly_debit_interest;
        $_SESSION['pi_ratepay_rate_number_of_rates'] = $number_of_rates;
        $_SESSION['pi_ratepay_rate_rate'] = $rate;
        $_SESSION['pi_ratepay_rate_last_rate'] = $last_rate;
    }

    /*
     * This method get all needed data and has to be rewritten
     * Optional - Will only be used, if you want to show the result on another page (include_result.html)
     * Needs to return an array with the indexes total_amount, amount, interest_amount, service_charge, annual_percentage_rate, monthly_debit_interest, number_of_rates , rate, lastRate
     * @return array
     */

    public function getData() {
        /* Getting Data as example from the Session */
        return array('total_amount' => $_SESSION['pi_ratepay_rate_total_amount'],
            'amount' => $_SESSION['pi_ratepay_rate_amount'],
            'interest_rate' => $_SESSION['pi_ratepay_rate_interest_rate'],
            'interest_amount' => $_SESSION['pi_ratepay_rate_interest_amount'],
            'service_charge' => $_SESSION['pi_ratepay_rate_service_charge'],
            'annual_percentage_rate' => $_SESSION['pi_ratepay_rate_annual_percentage_rate'],
            'monthly_debit_interest' => $_SESSION['pi_ratepay_rate_monthly_debit_interest'],
            'number_of_rates' => $_SESSION['pi_ratepay_rate_number_of_rates'],
            'rate' => $_SESSION['pi_ratepay_rate_rate'],
            'last_rate' => $_SESSION['pi_ratepay_rate_last_rate']);
    }

    /*
     * This method unset the Data and has to be rewritten
     */

    public function unsetData() {
        /* Unsetting the Session Variables as example */
        unset($_SESSION['pi_ratepay_rate_total_amount']);
        unset($_SESSION['pi_ratepay_rate_amount']);
        unset($_SESSION['pi_ratepay_rate_interest_rate']);
        unset($_SESSION['pi_ratepay_rate_interest_amount']);
        unset($_SESSION['pi_ratepay_rate_service_charge']);
        unset($_SESSION['pi_ratepay_rate_annual_percentage_rate']);
        unset($_SESSION['pi_ratepay_rate_monthly_debit_interest']);
        unset($_SESSION['pi_ratepay_rate_number_of_rates']);
        unset($_SESSION['pi_ratepay_rate_rate']);
        unset($_SESSION['pi_ratepay_rate_last_rate']);
    }

}

?>