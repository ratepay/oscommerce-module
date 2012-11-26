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
require_once 'ext/modules/payment/pi_ratepay_rate/ratenrechner/php/path.php';
require_once $pi_ratepay_rate_calc_path . 'php/PiRatepayRateCalc.php';
$pi_calculator = new PiRatepayRateCalc();
if (isset($_POST['calcValue']) && isset($_POST['calcMethod'])) {
    if ($_POST['calcMethod'] == "calculation-by-time" || $_POST['calcMethod'] == "calculation-by-rate") {
        if ($_POST['calcMethod'] == "calculation-by-time" && is_numeric($_POST['calcValue'])) {
            if (preg_match('/^[0-9]{1,3}$/', $_POST['calcValue'])) {
                $pi_calculator->setRequestCalculationValue($_POST['calcValue']);
                $pi_resultArray = $pi_calculator->getRatepayRateDetails($_POST['calcMethod']);
            } else {
                $pi_calculator->setErrorMsg('wrongvalue');
            }
        } else if ($_POST['calcMethod'] == "calculation-by-rate") {
            if (preg_match('/^[0-9]+(\.[0-9][0-9][0-9])?(,[0-9]{1,2})?$/', $_POST['calcValue'])) {
                $pi_value = $_POST['calcValue'];
                $pi_value = str_replace(".", "", $pi_value);
                $pi_value = str_replace(",", ".", $pi_value);
                $pi_calculator->setRequestCalculationValue($pi_value);
                $pi_resultArray = $pi_calculator->getRatepayRateDetails($_POST['calcMethod']);
            } else if (preg_match('/^[0-9]+(\,[0-9][0-9][0-9])?(.[0-9]{1,2})?$/', $_POST['calcValue'])) {
                $pi_value = $_POST['calcValue'];
                $pi_value = str_replace(",", "", $pi_value);
                $pi_calculator->setRequestCalculationValue($pi_value);
                $pi_resultArray = $pi_calculator->getRatepayRateDetails($_POST['calcMethod']);
            } else {
                $pi_calculator->setErrorMsg('wrongvalue');
            }
        } else {
            $pi_calculator->setErrorMsg('wrongvalue');
        }
    } else {
        $pi_calculator->setErrorMsg('wrongsubtype');
    }
} else {
    $pi_calculator->getData();
    $pi_resultArray = $pi_calculator->createFormattedResult();
}
$pi_language = $pi_calculator->getLanguage();
$pi_amount = $pi_calculator->getRequestAmount();
if ($pi_language == "DE") {
    require_once $pi_ratepay_rate_calc_path . 'php/languages/german.php';
    $pi_currency = 'EUR';
    $pi_decimalSeperator = ',';
    $pi_thousandSeperator = '.';
} else {
    require_once $pi_ratepay_rate_calc_path . 'php/languages/english.php';
    $pi_currency = 'EUR';
    $pi_decimalSeperator = '.';
    $pi_thousandSeperator = ',';
}
$pi_amount = number_format($pi_amount, 2, $pi_decimalSeperator, $pi_thousandSeperator);
if ($pi_calculator->getErrorMsg() != '') {
    if ($pi_calculator->getErrorMsg() == 'serveroff') {
        echo "<div class='pirperror'>" . $pi_lang_error . ":<br/>" . $pi_lang_server_off . "</div>";
    } else if ($pi_calculator->getErrorMsg() == 'wrongvalue') {
        echo "<div class='pirperror'>" . $pi_lang_error . ":<br/>" . $pi_lang_wrong_value . "</div>";
    } else {
        echo "<div class='pirperror'>" . $pi_lang_error . ":<br/>" . $pi_lang_request_error_else . "</div>";
    }
} else {
    if (isset($_POST['calcValue']) && isset($_POST['calcMethod'])) {
        ?>

    <div id="piRpNotfication"><?php echo $pi_lang_information . ":<br/>" . $pi_lang_info[$pi_calculator->getCode()]; ?></div>

    <?php } ?>

    <h2 class="pirpmid-heading"><?php echo $pi_lang_individual_rate_calculation; ?></h2>

    <table id="piInstallmentTerms">
        <tr class="piHighlight">
            <th><?php echo $pi_lang_total_amount; ?>:</th>
            <td><?php echo $pi_resultArray['totalAmount']; ?></td>
            <td>&euro;</td>
            </tr>
            <tr>
            <th><?php echo $pi_lang_cash_payment_price; ?>:</th>
            <td><?php echo $pi_resultArray['amount']; ?></td>
            <td>&euro;</td>
            </tr>
            <tr>
            <th><?php echo $pi_lang_interest_amount; ?>:</th>
            <td><?php echo $pi_resultArray['interestAmount']; ?></td>
            <td>&euro;</td>
            </tr>
        <tr class="piTableHr">
            <th><?php echo $pi_lang_service_charge; ?>:</th>
            <td><?php echo $pi_resultArray['serviceCharge']; ?></td>
            <td>&euro;</td>
            </tr>

        <tr class="piPriceSectionHead">
            <th><?php echo $pi_lang_effective_rate; ?>:</th>
            <td><?php echo $pi_resultArray['annualPercentageRate']; ?></td>
            <td>%</td>
            </tr>
        <tr class="piTableHr">
            <th><?php echo $pi_lang_debit_rate; ?>:</th>
            <td><?php echo $pi_resultArray['monthlyDebitInterest']; ?></td>
            <td>%</td>
            </tr>

        <tr class="piHighlight piPriceSectionHead">
            <th><?php echo $pi_lang_duration_time; ?>:</th>
            <td colspan="2"><?php echo $pi_resultArray['numberOfRatesFull']; ?><?php echo $pi_lang_months; ?></td>
                </tr>
                <tr>
            <th><?php echo $pi_resultArray['numberOfRates'];?><?php echo $pi_lang_duration_month;?>:</th>
            <td><?php echo $pi_resultArray['rate'];?></td>
            <td>&euro;</td>
                </tr>
                <tr>
            <th><?php echo $pi_lang_last_rate; ?>:</th>
            <td><?php echo $pi_resultArray['lastRate']; ?></td>
            <td>&euro;</td>
                </tr>
            </table>
    <?php
}
?>