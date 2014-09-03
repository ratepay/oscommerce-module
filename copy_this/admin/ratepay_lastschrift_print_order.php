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

require_once('includes/application_top.php');
require_once(DIR_WS_CLASSES . 'currencies.php');
require_once('includes/languages/' . $_SESSION['language'] . '/invoice.php');
require_once(DIR_WS_CLASSES . 'order.php');
require_once(DIR_FS_CATALOG . 'includes/languages/' . $_SESSION['language'] . '/modules/payment/ratepay_lastschrift.php');
require_once('../includes/modules/payment/ratepay_lastschrift.php');
require_once('../includes/classes/ratepay/helpers/Data.php');
require_once('../includes/classes/ratepay/helpers/Db.php');
require_once('../includes/classes/ratepay/helpers/Globals.php');

$currencies = new currencies();
$oID        = tep_db_prepare_input($_GET['oID']);
$order      = new order($oID);
$piRatepay  = Loader::getRatepayPayment($order->info['payment_method']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    </head>
    <body>
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
                            <td class="main">RatePAY Lastschrift</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
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
                        <?php for ($i = 0, $n = sizeof($order->products); $i < $n; $i++): ?>
                        <?php if ($order->products[$i]['qty'] > 0) : ?>
                        <tr class="dataTableRow">
                            <td class="dataTableContent" valign="top" align="right">
                                <?php echo $order->products[$i]['qty']; ?>
                            </td>
                            <td class="dataTableContent" valign="top">
                                <?php echo $order->products[$i]['name']; ?>
                                <?php if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)): ?>
                                <?php for ($j = 0; $j < $k; $j++): ?>
                                <br />
                                <nobr>
                                    <small>&nbsp;
                                        <i> - <?php echo $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value']; ?>
                                            <?php if ($order->products[$i]['attributes'][$j]['price'] != '0') : ?>
                                                <?php echo ' (' 
                                                         . $order->products[$i]['attributes'][$j]['prefix'] 
                                                         . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) 
                                                         . ')'; 
                                                ?>
                                            <?php endif; ?>
                                        </i>
                                    </small>
                                </nobr>
                                <?php endfor; ?>
                                <?php endif; ?>
                            </td>
                            <td class="dataTableContent" valign="top">
                                <?php echo $order->products[$i]['model']; ?>
                            </td>
                            <td class="dataTableContent" align="right" valign="top">
                                <?php tep_display_tax_value($order->products[$i]['tax']); ?>%
                            </td>
                            <td class="dataTableContent" align="right" valign="top">
                                <strong>
                                    <?php echo $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']); ?>
                                </strong>
                            </td>
                            <td class="dataTableContent" align="right" valign="top">
                                <strong>
                                    <?php echo $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true), true, $order->info['currency'], $order->info['currency_value']); ?>
                                </strong>
                            </td>
                            <td class="dataTableContent" align="right" valign="top">
                                <strong>
                                    <?php echo $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']); ?>
                                </strong>
                            </td>
                            <td class="dataTableContent" align="right" valign="top">
                                <strong>
                                    <?php echo $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']); ?>
                                </strong>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php endfor; ?>
                        <tr>
                            <td align="right" colspan="8">
                                <table border="0" cellspacing="0" cellpadding="2">
                                    <?php for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++): ?>
                                    <tr>
                                        <td align="right" class="smallText"><?php echo $order->totals[$i]['title']; ?></td>
                                        <td align="right" class="smallText"><?php echo $order->totals[$i]['text']; ?></td>
                                    </tr>
                                    <?php endfor; ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="border-style:solid;border-width:1px;width:75%;padding:10px;font-family:Verdana;font-size:11px;">
            <p><?php echo RATEPAY_LASTSCHRIFT_PDF_PAYUNTIL; ?></p>
            <p><?php echo RATEPAY_LASTSCHRIFT_PDF_PAYTRANSFER; ?></p>
            <span><?php echo RATEPAY_LASTSCHRIFT_PDF_ACCOUNTHOLDER; ?> <?php echo $piRatepay->shopOwner; ?></span><br/>
            <span><?php echo RATEPAY_LASTSCHRIFT_PDF_BANKNAME; ?> <?php echo $piRatepay->shopBankName; ?></span><br/>
            <span><?php echo RATEPAY_LASTSCHRIFT_PDF_BANKCODENUMBER; ?> <?php echo $piRatepay->shopSortCode; ?></span><br/>
            <span><?php echo RATEPAY_LASTSCHRIFT_PDF_ACCOUNTNUMBER; ?> <?php echo $piRatepay->shopAccountNumber; ?></span><br/>
            <span><b><?php echo RATEPAY_LASTSCHRIFT_PDF_REFERENCE; ?> <?php echo Db::getRatepayOrderDataEntry(Globals::getParam('oID'), 'descriptor'); ?></b></span><br/>
            <span><?php echo RATEPAY_LASTSCHRIFT_PDF_INTERNATIONALDESC; ?></span><br/>
            <span><?php echo RATEPAY_LASTSCHRIFT_PDF_SWIFTBIC; ?> <?php echo $piRatepay->shopSwift; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_IBAN; ?> <?php echo $piRatepay->shopIban; ?></span><br/>
        </div>
        <br/>
        <div style="font-family:Verdana;font-size:11px;">
        <span><?php echo RATEPAY_LASTSCHRIFT_PDF_ADDITIONALINFO_1; ?></span></span><br/>
        <span><?php echo RATEPAY_LASTSCHRIFT_PDF_ADDITIONALINFO_2; ?></span><br/>
        <span><?php echo RATEPAY_LASTSCHRIFT_PDF_ADDITIONALINFO_3; ?></span><br/>
        <span><?php echo RATEPAY_LASTSCHRIFT_PDF_ADDITIONALINFO_4; ?></span></span>
        </div>
        <br/>
        <div style="font-family:Verdana;font-size:11px;">
            <span><?php echo $piRatepay->extraInvoiceField; ?></span>
        </div>
        <br/>
        <div style="width:100%;color:black;" class="footer">
            <table style="font-family:Verdana;font-size:8px;font-weight:normal!important;" width="100%">
                <tr>
                    <th style="text-align:left;font-weight:normal!important;">
                        <span><?php echo $piRatepay->shopOwner; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_BULL; ?> <?php echo $_SERVER['SERVER_NAME']; ?></span><br/>
                        <span><?php echo $piRatepay->shopStreet . ", " . $piRatepay->shopZipCode; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_BULL; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_FON; ?> <?php echo $piRatepay->shopPhone; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_BULL; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_BULL; ?> <?php echo $piRatepay->shopFax; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_FAX; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_EMAIL; ?> <?php echo Db::getShopConfigEntry('STORE_OWNER_EMAIL_ADDRESS'); ?></span><br/>
                        <span><?php echo RATEPAY_LASTSCHRIFT_PDF_OWNER; ?> <?php echo $piRatepay->shopOwner; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_BULL; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_COURT; ?> <?php echo $piRatepay->shopCourt; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_BULL; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_HR; ?> <?php echo $piRatepay->shopHr; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_BULL; ?> <?php echo RATEPAY_LASTSCHRIFT_PDF_UST; ?> <?php echo Db::getShopConfigEntry('STORE_OWNER_VAT_ID'); ?></span><br/>
                    </th>
                    <th style="text-align:right;">
                        <img src="../images/ratepay_lastschrift_logo.png" alt="RatePAY Logo" style="margin-left:5px;"/>
                    </th>
                </tr>
            </table>
        </div>
    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
