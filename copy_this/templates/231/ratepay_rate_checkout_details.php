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

require('includes/application_top.php');
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

// load all enabled payment modules
require(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment();

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add('RatePAY Terms', tep_href_link('ratepay_rechnung_checkout_terms.php', '', 'SSL'));
$breadcrumb->add('RatePAY Ratecalculator', tep_href_link('ratepay_rate_checkout_details.php', '', 'SSL'));
require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div style="margin:10px;color:#2F2F2F;">
    <form name="details" id="details" action="checkout_confirmation.php" method="post">
        <table style="width:100%;">
            <tr>
                <td colspan="2">
                    <style>
                        .payment-error
                        {
                            padding:2px 4px;
                            margin:0px;
                            border:solid 1px #FBD3C6;
                            background:#FDE4E1;
                            color:#CB4721;
                            font-family:Arial, Helvetica, sans-serif;
                            font-size:14px;
                            font-weight:bold;
                            text-align:center;
                            width: 100%;
                            margin-bottom: 15px;
                        }
                    </style>
                    <script type="text/javascript">
                        function submitDetails(){
                            document.getElementById('details').submit();
                        }
                    </script>
                    <input type="hidden" id='conditions' name='conditions' value='conditions'/>
                    <link type="text/css" rel="stylesheet" href="ext/modules/payment/ratepay/ratepay_rate/ratenrechner/css/style.css"/>
                    <script type="text/javascript" src="ext/modules/payment/ratepay/ratepay_rate/ratenrechner/js/path.js"></script>
                    <script type="text/javascript" src="ext/modules/payment/ratepay/ratepay_rate/ratenrechner/js/layout.js"></script>
                    <script type="text/javascript" src="ext/modules/payment/ratepay/ratepay_rate/ratenrechner/js/ajax.js"></script>
                    <script type="text/javascript" src="ext/modules/payment/ratepay/ratepay_rate/ratenrechner/js/mouseaction.js"></script>
                    <?php echo Loader::getRatepayPayment('ratepay_rate')->getRateCalculatorError(); ?>
                    <div id="pirpmain-cont" class="pirpmain-cont">
                    </div>
                    <script type="text/javascript">
                        if(document.getElementById('pirpmain-cont')) {
                            piLoadrateCalculator();
                        }
                    </script>
                </td>
            </tr>
            <tr>
                <!--<td style="text-align:left;">
                    <a href="checkout_payment.php">
                        <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-e', null, 'primary'); ?>
                    </a>
                </td>-->
                <td style="text-align:right;">
                    <input type="submit" value="<?php echo 'Weiter'; ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>
<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');