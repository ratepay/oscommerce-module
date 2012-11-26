<?php
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
$payment_modules = new payment;

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

$ratepay = new pi_ratepay_rechnung();

$min = $ratepay->min;
$max = $ratepay->max;

$gtcURL = $ratepay->gtcURL;
$privacyURL = $ratepay->privacyURL;
$merchantPrivacyURL = $ratepay->merchantPrivacyURL;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <script type="text/javascript">
            function submitTerms(){
                if(document.getElementById('conditions').checked == true){
                    document.getElementById('terms').submit();
                }
                else{
                    document.getElementById('error').style.display = 'block';
                }
            }
        </script>
        <style>
            .piRpFontSize {
                font-size:11px;
            }
            .piRpCheckbox{
                vertical-align: top;
            }
            .piRpConditionContainer{
                font-family: Verdana, Arial, sans-serif;
                margin:10px;
                color:#2F2F2F;
                vertical-align: top;
            }
            .piRpConditionLink{
                text-decoration: underline;
                color: #1E7EC8;
            }
        </style>
    </head>
    
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
        <!-- header //-->
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_eof //-->
        <!-- body //-->
        <table border="0" width="100%" cellspacing="3" cellpadding="3">
            <tr>
                <td width="<?php echo BOX_WIDTH; ?>" valign="top">
                    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
                        <!-- left_navigation //-->
                        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
                        <!-- left_navigation_eof //-->
                    </table>
                </td>
                <td class="piRpConditionContainer" valign="top">
                    <div>
                        <table>
                            <tr>
                                <td colspan="2" class="piRpFontSize">
                                    <img src="images/ratepay_rechnung_rgb.png" alt="RatePAY Rechnung"/>
                                    &nbsp;
                                    <br/>
                                    <div style="color:red;display:none;" id="error">
                                        <?php echo PI_RATEPAY_RECHNUNG_AGB_ERROR; ?>
                                    </div>
                                    <br/>
                                    <b>
                                        <span style="color:red;"><?php echo TITLE; ?></span> 
                                        <?php echo PI_RATEPAY_RECHNUNG_INFO_1; ?>
                                    </b>
                                    <br/>
                                    <br/>
                                    <?php echo PI_RATEPAY_RECHNUNG_INFO_2; ?>
                                    <b><?php echo PI_RATEPAY_RECHNUNG_INFO_3 . $min; ?> EUR </b>
                                    und 
                                    <b><?php echo PI_RATEPAY_RECHNUNG_INFO_4 . $max; ?> EUR</b>
                                    <br/>
                                    <?php echo PI_RATEPAY_RECHNUNG_INFO_5; ?>
                                    <br/>
                                    <br/>
                                    <p>
                                        <?php echo PI_RATEPAY_RECHNUNG_INFO_6; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' class="piRpFontSize">
                                    <table>
                                        <tr>
                                            <td class="piRpCheckbox">
                                                <form action="checkout_confirmation.php?osCsid=<?php echo tep_session_id() ?>" name="terms" method="POST" id="terms">
                                                    <input id="conditions" type="checkbox" name="conditions" value="conditions"/>
                                                    <input type="hidden" value="<?php echo $_GET['coupon']; ?>" name="coupon" id="coupon">
                                                </form>
                                            </td>
                                            <td class="piRpFontSize">
                                                <?php echo PI_RATEPAY_RECHNUNG_INFO_7; ?>
                                                <a href="<?php echo $gtcURL; ?>" target="_blank" class="piRpConditionLink">
                                                    <?php echo PI_RATEPAY_RECHNUNG_INFO_8; ?>
                                                </a>
                                                <?php echo PI_RATEPAY_RECHNUNG_INFO_9; ?>
                                                <a href="<?php echo $privacyURL; ?>" target="_blank" class="piRpConditionLink">
                                                    <?php echo PI_RATEPAY_RECHNUNG_INFO_10; ?>
                                                </a>
                                                <?php echo PI_RATEPAY_RECHNUNG_INFO_11; ?>
                                                <?php if ($merchantPrivacyURL): ?>
                                                <?php echo PI_RATEPAY_RECHNUNG_INFO_12; ?>
                                                <a href="<?php echo $merchantPrivacyURL; ?>" target="_blank" class="piRpConditionLink"><?php echo PI_RATEPAY_RECHNUNG_INFO_13; ?></a>.
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">
                                    <a href="checkout_payment.php?osCsid=<?php echo tep_session_id() ?>">
                                        <img src="<?php echo DIR_WS_LANGUAGES . $language; ?>/images/buttons/button_back.gif" alt="Zur&uuml;ck"/>
                                    </a>
                                </td>
                                <td style="text-align:right;">
                                    <a href="javascript:submitTerms();">
                                        <img src="<?php echo DIR_WS_LANGUAGES . $language; ?>/images/buttons/button_continue.gif" alt="Weiter"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td valign="top">
                    <table>
                        <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
                    </table>
                </td>
            </tr>
        </table>
        <!-- body_eof //-->
        <!-- footer //-->
        <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
        <!-- footer_eof //-->
        <br>
    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
