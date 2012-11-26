<?php
require('includes/application_top.php');
if (isset($_POST['conditions'])) {
    if (isset($_POST['pirprate'])) {
        $checking = true;

        if (isset($_SESSION['pi_ratepay_rate_total_amount']) == false || $_SESSION['pi_ratepay_rate_total_amount'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_amount']) == false || $_SESSION['pi_ratepay_rate_amount'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_interest_amount']) == false || $_SESSION['pi_ratepay_rate_interest_amount'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_service_charge']) == false || $_SESSION['pi_ratepay_rate_service_charge'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_annual_percentage_rate']) == false || $_SESSION['pi_ratepay_rate_annual_percentage_rate'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_monthly_debit_interest']) == false || $_SESSION['pi_ratepay_rate_monthly_debit_interest'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_number_of_rates']) == false || $_SESSION['pi_ratepay_rate_number_of_rates'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_rate']) == false || $_SESSION['pi_ratepay_rate_rate'] == "") {
            $checking = false;
        } else if (isset($_SESSION['pi_ratepay_rate_last_rate']) == false || $_SESSION['pi_ratepay_rate_last_rate'] == "") {
            $checking = false;
        }

        if ($checking) {
                $form = '<form name="pi_coupon" id="pi_coupon" action="checkout_confirmation.php?osCsid=' . tep_session_id() . '" method="post">'
                       .    '<input type="hidden" value="' . $_POST['coupon'] . '" name="coupon" id="coupon">'
                       . '</form>';
                exit($form . '<script type="text/javascript">document.getElementById("pi_coupon").submit();</script>');//tep_redirect(tep_href_link("checkout_confirmation.php", '', 'SSL'));
        }
    }
    $_SESSION['pi_ratepay_rate_conditions'] = true;
} else {
    tep_redirect(tep_href_link("checkout_pi_ratepay_rechnung_terms_osc231.php?osCsid=" . tep_session_id() , '', 'SSL'));
}

require(DIR_WS_INCLUDES . 'template_top.php');

// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}

// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
}
require_once 'includes/languages/' . $_SESSION["language"] . '/modules/payment/pi_ratepay_rate.php';
require_once 'includes/modules/payment/pi_ratepay_rate.php';
$ratepay = new pi_ratepay_rate();

$min = $ratepay->min;
$max = $ratepay->max;
?>
<script type="text/javascript">
    function submitDetails(){
        document.getElementById('details').submit();
    }
</script>
<form name="details" id="details" action="checkout_pi_ratepay_rate_details_osc231.php?osCsid=<?php echo tep_session_id() ?>" method="post">
    <input type="hidden" id='conditions' name='conditions' /> 
    <input type="hidden" id='pirprate' name='pirprate' /> 
    <input type="hidden" value="<?php echo $_POST['coupon']; ?>" name="coupon" id="coupon">
</form>

<link type="text/css" rel="stylesheet" href="ext/modules/payment/pi_ratepay_rate/ratenrechner/css/style.css"/>
<script type="text/javascript" src="ext/modules/payment/pi_ratepay_rate/ratenrechner/js/path.js"></script>
<script type="text/javascript" src="ext/modules/payment/pi_ratepay_rate/ratenrechner/js/layout.js"></script>
<script type="text/javascript" src="ext/modules/payment/pi_ratepay_rate/ratenrechner/js/ajax.js"></script>

<div id="pirpmain-cont" class="pirpmain-cont">
</div>
<script type="text/javascript">
    if(document.getElementById('pirpmain-cont')) {
        piLoadrateCalculator("<?php echo tep_session_id(); ?>");
    }
</script>
<div style="width:100%;">
    <table style="width:100%;">
        <tr>
            <td style="text-align: left;"><a href="checkout_pi_ratepay_rate_terms_osc231.php?osCsid=<?php echo tep_session_id() ?>"><?php echo tep_draw_button(PI_RATEPAY_RATE_CHECKOUT_BACK, 'triangle-1-e', null, 'primary'); ?></a></td>
            <td style="text-align: right;"><a href="javascript:submitDetails();"><?php echo tep_draw_button(PI_RATEPAY_RATE_CHECKOUT_CONTINUE, 'triangle-1-e', null, 'primary'); ?></a></td>
        </tr>
    </table>
</div>
<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>