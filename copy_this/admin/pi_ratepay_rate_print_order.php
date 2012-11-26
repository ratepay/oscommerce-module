<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
 */

require('includes/application_top.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
$orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int) $oID . "'");

include(DIR_WS_CLASSES . 'order.php');
$order = new order($oID);

// Get the RatePAY stuff for the Invoice
require_once '../includes/modules/payment/pi_ratepay_rate.php';
$sql = "select descriptor from pi_ratepay_rate_orders where order_number = '" . $oID . "'";
$query = tep_db_query($sql);
$descriptorArray = tep_db_fetch_array($query);

$language = $_SESSION['language'];
require_once 'includes/languages/' . $language . '/modules/payment/pi_ratepay.php';
require_once 'includes/languages/' . $language . '/invoice.php';

$piRatepay = new pi_ratepay_rate();

$accountHolder = $piRatepay->merchantName;
$bank = $piRatepay->bankName;
$sortCode = $piRatepay->sortCode;
$accountNr = $piRatepay->accountNr;
$descriptor = $descriptorArray['descriptor'];
$iban = $piRatepay->iban;
$swift = $piRatepay->swift;
$extraField = $piRatepay->extraField;

$owner = $piRatepay->owner;
$hr = $piRatepay->hr;
$court = $piRatepay->court;
$fon = $piRatepay->fon;
$fax = $piRatepay->fax;
$street = $piRatepay->street;
$plz = $piRatepay->plz;
$debtholder = $piRatepay->debtholder;
$ust = $piRatepay->ust;

$sql = "select configuration_value from configuration where configuration_key = 'STORE_OWNER_EMAIL_ADDRESS'";
$query = tep_db_query($sql);
$mailArray = tep_db_fetch_array($query);
$email = $mailArray['configuration_value'];
// End RatePAY stuff
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    </head>
    <body>
        <!-- body_text //-->
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
                            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . 'store_logo.png', STORE_NAME); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <td class="main"><strong><?php echo ENTRY_SOLD_TO; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->billing, 1, '', '<br />'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="main"><?php echo $order->customer['telephone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top">
                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <td class="main"><strong><?php echo ENTRY_SHIP_TO; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />'); ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="main"><strong><?php echo ENTRY_PAYMENT_METHOD; ?></strong></td>
                            <td class="main"><?php echo $order->info['payment_method']; ?></td>
                        </tr>
                        <tr>
                            <td class="main"><strong><?php echo PI_RATEPAY_RATE_PDF_DESCRIPTOR; ?></strong></td>
                            <td class="main"><strong><?php echo $descriptor; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
                <td><?php echo PI_RATEPAY_RATE_PDF_ABOVEARTICLE; ?></td>
            </tr>
            <tr>
                <td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr class="dataTableHeadingRow">
                            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
                        </tr>
                        <?php
                        for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
                            echo '      <tr class="dataTableRow">' . "\n" .
                            '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
                            '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

                            if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
                                for ($j = 0; $j < $k; $j++) {
                                    echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
                                    if ($order->products[$i]['attributes'][$j]['price'] != '0')
                                        echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                                    echo '</i></small></nobr>';
                                }
                            }

                            echo '        </td>' . "\n" .
                            '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
                            echo '        <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
                            '        <td class="dataTableContent" align="right" valign="top"><strong>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
                            '        <td class="dataTableContent" align="right" valign="top"><strong>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true), true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
                            '        <td class="dataTableContent" align="right" valign="top"><strong>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
                            '        <td class="dataTableContent" align="right" valign="top"><strong>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n";
                            echo '      </tr>' . "\n";
                        }
                        ?>
                        <tr>
                            <td align="right" colspan="8">
                                <table border="0" cellspacing="0" cellpadding="2">
                                    <?php
                                    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
                                        echo '          <tr>' . "\n" .
                                        '            <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
                                        '            <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
                                        '          </tr>' . "\n";
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- body_text_eof //-->
        <br/>
        <div style="font-family:Verdana;font-size:11px;">
            <span><?php echo PI_RATEPAY_RATE_PDF_INFO; ?></span>
            <br/>
            <br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_ADDITIONALINFO_1 . PI_RATEPAY_RATE_PDF_ADDITIONALINFO_2; ?></span>
            <?php echo $debtholder; ?>
            <br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_ADDITIONALINFO_3 . PI_RATEPAY_RATE_PDF_ADDITIONALINFO_4; ?></span>
            <?php echo $debtholder; ?>
            <span><?php echo PI_RATEPAY_RATE_PDF_ADDITIONALINFO_5 . PI_RATEPAY_RATE_PDF_ADDITIONALINFO_6; ?></span>
            <?php echo $debtholder; ?><span><?php echo PI_RATEPAY_RATE_PDF_ADDITIONALINFO_7; ?></span>
            <br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_ADDITIONALINFO_8; ?></span>
        </div>
        <br/>
        <div style="border-style:solid;border-width:1px;width:75%;padding:10px;font-family:Verdana;font-size:11px;">
            <span><?php echo PI_RATEPAY_RATE_PDF_ACCOUNTHOLDER; ?> <?php echo $accountHolder; ?></span><br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_BANKNAME; ?> <?php echo $bank; ?></span><br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_BANKCODENUMBER; ?> <?php echo $sortCode; ?></span><br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_ACCOUNTNUMBER; ?> <?php echo $accountNr; ?></span><br/>
            <span><b><?php echo PI_RATEPAY_RATE_PDF_REFERENCE; ?> <?php echo $descriptor; ?></b></span><br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_INTERNATIONALDESC; ?></span><br/>
            <span><?php echo PI_RATEPAY_RATE_PDF_SWIFTBIC; ?> <?php echo $swift; ?> <?php echo PI_RATEPAY_RATE_PDF_IBAN; ?> <?php echo $iban; ?></span><br/>
        </div>
        <br/>
        <div style="font-family:Verdana;font-size:11px;">
            <span><?php echo $extraField; ?></span>
        </div>
        <br/>
        <table style="font-family:Verdana;font-size:8px;font-weight:normal!important;" width="100%">
            <tr>
                <th style="text-align:left;font-weight:normal!important;">
                    <span><?php echo $owner; ?> <?php echo PI_RATEPAY_RATE_PDF_BULL; ?> <?php echo $_SERVER['SERVER_NAME']; ?></span><br/>
                    <span><?php echo $street . " , " . $plz; ?> <?php echo PI_RATEPAY_RATE_PDF_BULL; ?> <?php echo PI_RATEPAY_RATE_PDF_FON; ?> <?php echo $fon; ?> <?php echo PI_RATEPAY_RATE_PDF_BULL; ?> <?php echo PI_RATEPAY_RATE_PDF_FAX; ?> <?php echo $fax; ?> <?php echo PI_RATEPAY_RATE_PDF_BULL; ?> <?php echo PI_RATEPAY_RATE_PDF_EMAIL; ?> <?php echo $email; ?></span><br/>
                    <span><?php echo PI_RATEPAY_RATE_PDF_OWNER; ?> <?php echo $owner; ?> <?php echo PI_RATEPAY_RATE_PDF_BULL; ?> <?php echo PI_RATEPAY_RATE_PDF_COURT; ?> <?php echo $court; ?> <?php echo PI_RATEPAY_RATE_PDF_BULL; ?> <?php echo PI_RATEPAY_RATE_PDF_HR; ?> <?php echo $hr; ?> <?php echo PI_RATEPAY_RATE_PDF_BULL; ?> <?php echo PI_RATEPAY_RATE_PDF_UST; ?> <?php echo $ust; ?></span><br/>
                    <!--  <span>RatePAY GmbH - www.ratepay.com - customerservice@ratepay.com - HRB 124156 B - Ust-IdNr.: DE 270098222</span><br/>-->
                </th>
                <th style="text-align:right;">
                    <img src="../images/pi_ratepay_rate_checkout_logo.png" alt="RatePAY Logo" width="90px" height="25px" style="margin-left:5px;"/>
                </th>
            </tr>
        </table>
    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
