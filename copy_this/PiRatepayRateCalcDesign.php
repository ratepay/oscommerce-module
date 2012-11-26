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
$pi_calculator->unsetData();
$pi_config = $pi_calculator->getRatepayRateConfig();
$pi_monthAllowed = $pi_config['month_allowed'];
$pi_monthAllowedArray = explode(',', $pi_monthAllowed);

$pi_amount = $pi_calculator->getRequestAmount();
$pi_language = $pi_calculator->getLanguage();

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
        echo "<div>" . $pi_lang_server_off . "</div>";
    } else {
        echo "<div>" . $pi_lang_config_error_else . "</div>";
    }
} else {
    ?>

    <div id="piRpHeader">
        <img src="<?php echo $pi_ratepay_rate_calc_path; ?>images/ratepay-logo.png" width="183" height="39" alt="" style="float:left;">
        <div>
            <?php echo $pi_lang_cash_payment_price; ?>:
            <span><?php echo $pi_amount; ?> &euro;</span>
        </div>
    </div>

    <p id='pirptop-text-runtime' class="piRpTop-Text piRpText" style="display: none">
        <?php echo $pi_lang_hint_runtime_1; ?> <?php echo $pi_lang_hint_runtime_2; ?>
    </p>

    <p id='pirptop-text-rate' class="piRpTop-Text piRpText">
        <?php echo $pi_lang_hint_rate_1; ?> <?php echo $pi_lang_hint_rate_2; ?>
    </p>

    <div id="piRpContentSwitch">
        <div id="piRpSwitchToTerm" class="piRpActive" onClick="switchRateOrRuntime('rate');">
            <span id='pirpspanrate'>
                <?php echo $pi_lang_insert_wishrate; ?> <?php echo $pi_lang_calculate_runtime; ?>
            </span>
            <input name="" value="<?php echo $pi_lang_calculate_runtime; ?>" type="button" class="piRpInput-button">
        </div>
        <div id="piRpSwitchToRuntime" onClick="switchRateOrRuntime('runtime');">
            <span id="pirpspanruntime" class="pirpactive">
                <?php echo $pi_lang_choose_runtime; ?> <?php echo $pi_lang_calculate_rate; ?>
            </span> 
            <input name="" value="<?php echo $pi_lang_calculate_rate; ?>" type="button" class="piRpInput-button">
        </div>
    </div>

    <div class="piRpClearFix"></div>

    <div id="piRpContentTerm" class="piRpContent">
        <p class="piRpText">
            <?php echo $pi_lang_hint_rate_1; ?> <?php echo $pi_lang_hint_rate_2; ?>
        </p>
        <div>
            <span><?php echo $pi_lang_please . " " . $pi_lang_insert_wishrate; ?>:</span>
            <input name="" id="rate" class="piRpInput-amount" type="text">
            <span class="piRpCurrency"> &euro;</span>
            <input name="" onclick="piRatepayRateCalculatorAction('rate', '<?php echo tep_session_id(); ?>')" value="<?php echo $pi_lang_calculate_runtime; ?>" class="piRpInput-button" type="button">
        </div>
    </div>

    <div id="piRpContentRuntime" class="piRpContent" style="display: none;">
        <p class="piRpText">
            <?php echo $pi_lang_hint_runtime_1; ?> <?php echo $pi_lang_hint_runtime_2; ?>
        </p>
        <div>
            <span><?php echo $pi_lang_please . " " . $pi_lang_insert_runtime; ?>:</span>
            <select id="runtime">
                <?php
                foreach ($pi_monthAllowedArray as $pi_month) {
                    echo '<option value="' . $pi_month . '">' . $pi_month . ' ' . $pi_lang_months . '</option>';
                }
                ?>
            </select>
            <input name="" onclick="piRatepayRateCalculatorAction('runtime', '<?php echo tep_session_id(); ?>')" value="<?php echo $pi_lang_calculate_rate; ?>" type="button" class="piRpInput-button">
        </div>
    </div>

    <div id="piRpResult"></div>

    <?php
}
?>
