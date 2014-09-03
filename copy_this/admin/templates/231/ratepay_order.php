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
/**
 * RatePAY order template, displays the deliver/cancel, refund, credit and histroy panel
 */

require_once ('includes/application_top.php');
require_once ('../includes/languages/' . $_SESSION['language'] . '/admin/modules/payment/ratepay.php');
require_once ('../includes/classes/ratepay/helpers/Data.php');
require_once ('../includes/classes/ratepay/helpers/Db.php');
require_once ('../includes/classes/ratepay/helpers/Session.php');
require_once ('../includes/classes/ratepay/helpers/Globals.php');
require_once ('includes/classes/order.php');
$orderId = Globals::hasParam('oID') ? Globals::getParam('oID') : die('Missing param: "oID"');
$order = new order($orderId);
$lang = $_SESSION['language'];
$basketAmount = Data::getBasketAmount($order, $orderId);
?>
<?php require(DIR_WS_INCLUDES . 'template_top.php'); ?>
<script language="javascript" src="<?php echo DIR_WS_CATALOG . 'templates/javascript/ratepay_order.js' ?>"></script>
<style>
    .piRpRight
    {
        text-align:right;
    }
</style>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td width="100%" valign="top" class="boxCenter">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td>
                            <?php if (!is_null(Session::getRpSessionEntry('message'))): ?>
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr>
                                                <td class="<?php echo Session::getRpSessionEntry('message_css_class'); ?>">
                                                    <img border="0" title="" alt="" src="images/icons/error.gif">
                                                    <?php echo Session::getRpSessionEntry('message'); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <?php endif; ?>
                            <?php if ($order->info['payment_method'] == 'ratepay_rate'): ?>
                            <?php $details = Db::getRatepayRateDetails($orderId); ?>
                            <table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
                                <tr>
                                    <td class="pageHeading"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_RATE_DETAILS; ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <img width="100%" height="1" border="0" alt="" src="images/pixel_black.gif"/>
                                    </td>
                                </tr>
                            </table>
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td valign="top">
                                        <!-- RatePAY Details START -->
                                        <table style="width: 30%">
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_TOTAL_AMOUNT; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo Data::getFormattedPrice($details['total_amount'], $lang, $order); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_AMOUNT; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo Data::getFormattedPrice($details['amount'], $lang, $order); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_INTEREST_AMOUNT; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo Data::getFormattedPrice($details['interest_amount'], $lang, $order); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_SERVICE_CHARGE; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo Data::getFormattedPrice($details['service_charge'], $lang, $order); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_ANNUAL_INTEREST; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo Data::getFormattedPrice($details['annual_percentage_rate'], $lang, $order); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_MONTHLY_INTEREST; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo $details['monthly_debit_interest']; ?>&nbsp;%</td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_RUNTIME; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo $details['number_of_rates'] . ' ' . RATEPAY_RATE_DETAILS_MONTH; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo $details['number_of_rates'] - 1; ?>&nbsp;<?php echo RATEPAY_RATE_DETAILS_MONTHLY_RATE_A; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo Data::getFormattedPrice($details['rate'], $lang, $order); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="main"><b><?php echo RATEPAY_RATE_DETAILS_AMOUNT_LAST_RATE_A; ?>:</b></td>
                                                <td class="main piRpRight"><?php echo Data::getFormattedPrice($details['last_rate'], $lang, $order); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <?php endif; ?>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
                            <tr>
                                <td class="pageHeading"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_DELIVER_CANCEL; ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <img width="100%" height="1" border="0" alt="" src="images/pixel_black.gif"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <form action="ratepay_order_script.php" method="POST" name="ship_or_cancel_form">
                                <tr>
                                    <td valign="top">
                                        <table style="border: 1px solid #CCCCCC; width: 100%">
                                            <tr class="dataTableHeadingRow">
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_QTY; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ART_ID; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_PRODUCT_NAME; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_PRICE_NETTO; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_TAX_AMOUNT; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_ROW_PRICE; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_ORDERED; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_DELIVERED; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_CANCELED; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_RETURNED; ?></th>
                                            </tr>
                                                <?php foreach (Db::getRpItems($orderId) as $item): ?>
                                                <tr class="dataTableRow">
                                                    <td class="dataTableContent">
                                                        <?php $qty = Data::getAvailableItemQtyToShipOrCancel($item); ?>
                                                        <input <?php if ($qty <= 0): echo 'readonly="readonly"'; endif; ?> type="text" name="items[<?php echo $item['id']; ?>]" value="<?php echo $qty; ?>" onKeyUp="RpOrder.check(this, <?php echo $qty; ?>);" onFocus="this.select();" size="3"/>
                                                    </td>
                                                    <td class="dataTableContent"><?php echo $item['articleNumber']; ?></td>
                                                    <td class="dataTableContent"><?php echo $item['articleName']; ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo Data::getFormattedPrice($item['unitPrice'], $lang, $order); ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo Data::getFormattedPrice($item['totalTax'], $lang, $order); ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo Data::getFormattedPrice($item['totalPriceWithTax'], $lang, $order); ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo $item['ordered']; ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo $item['shipped']; ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo $item['cancelled']; ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo $item['returned']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="dataTableRow">
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent piRpRight"><?php echo Data::getFormattedPrice($basketAmount, $lang, $order); ?></td>
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent"></td>
                                                <td class="dataTableContent"></td>
                                            </tr>
                                        </table>
                                        <div style="margin-top: 10px;">
                                            <input type="hidden" value="<?php echo $orderId; ?>" name="order_number"/>
                                            <input type="submit" value="<?php echo RATEPAY_ORDER_RATEPAY_ADMIN_DELIVERY; ?>" name="ship" class="button"/>&nbsp; 
                                            <input type="submit" value="<?php echo RATEPAY_ORDER_RATEPAY_ADMIN_CANCELLATION; ?>" name="cancel" class="button"/>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                        </table>
                        <br />
                        <br />
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <form action="ratepay_order_script.php" method="POST" name="refund_form">
                                <tr>
                                    <td valign="top">
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
                                            <tr>
                                                <td class="pageHeading"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_RETOUR; ?></td>
                                            </tr>
                                            <tr>
                                                <td><img width="100%" height="1" border="0" alt="" src="images/pixel_black.gif"></td>
                                            </tr>
                                        </table>
                                        <table style="border: 1px solid #CCCCCC; width: 100%">
                                            <tr class="dataTableHeadingRow">
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_QTY; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ART_ID; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_PRODUCT_NAME; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_DELIVERED; ?></th>
                                                <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_RETURNED; ?></th>
                                            </tr>
                                            <?php foreach (Db::getRpItems($orderId) as $item): ?>
                                                <tr class="dataTableRow">
                                                    <td class="dataTableContent">
                                                        <?php $qty = Data::getAvailableItemQtyToRefund($item); ?>
                                                        <input <?php if ($qty <= 0): echo 'disabled="disabled"';
                                                        endif; ?> type="text" name="items[<?php echo $item['id']; ?>]" value="<?php echo $qty; ?>" onKeyUp="RpOrder.check(this, <?php echo $qty; ?>);" onFocus="this.select();" size="3"/>
                                                    </td>
                                                    <td class="dataTableContent"><?php echo $item['articleNumber']; ?></td>
                                                    <td class="dataTableContent"><?php echo $item['articleName']; ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo $item['shipped']; ?></td>
                                                    <td class="dataTableContent piRpRight"><?php echo $item['returned']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                        <div style="margin-top: 10px;">
                                            <input type="hidden" value="<?php echo $orderId; ?>" name="order_number"/>
                                            <input type="submit" value="<?php echo RATEPAY_ORDER_RATEPAY_ADMIN_RETOURE_BUTTON; ?>" name="refund" class="button"/> 
                                        </div>
                                    </td>
                                </tr>
                            </form>
                        </table>
                        <br/>
                        <br/>
                        <form action="ratepay_order_script.php" method="POST" name="credit_form">
                            <table cellspacing="0" style="border: 1px solid #CCCCCC; width: 15%">
                                <tr class="dataTableHeadingRow">
                                    <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_GOODWILL; ?></th>
                                </tr>
                                <tr class="dataTableHeadingRow">
                                    <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_GOODWILL_AMOUNT; ?></th>
                                </tr>
                                <tr class="dataTableHeadingRow">
                                    <td class="dataTableHeadingContent">
                                        <input type="hidden" value="<?php echo $orderId; ?>" name="order_number"/>
                                        <input <?php if ($basketAmount <= 0): echo 'disabled="disabled"';
                                            endif; ?> id="voucherAmount" type="text" value="0" maxlength="4" size="2" name="voucherAmount" onKeyUp="RpOrder.checkVoucher(<?php echo $basketAmount; ?>)" onFocus="this.select();"/>
                                        &nbsp;,&nbsp;&nbsp;
                                        <input <?php if ($basketAmount <= 0): echo 'disabled="disabled"';
                                            endif; ?> id="voucherAmountKomma" type="text" value="00" maxlength="2" size="2" name="voucherAmountKomma" onKeyUp="RpOrder.checkVoucher(<?php echo $basketAmount; ?>)" onFocus="this.select();"/>
                                        &nbsp;EUR
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <input type="submit" value="<?php echo RATEPAY_ORDER_RATEPAY_ADMIN_CREATE_GOODWILL; ?>" name="credit" class="button"/>
                        </form>
                        <br/>
                        <br/>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td valign="top">
                                    <table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
                                        <tr>
                                            <td class="pageHeading"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_HISTORY; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <img width="100%" height="1" border="0" alt="" src="images/pixel_black.gif"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="border: 1px solid #CCCCCC; width: 100%">
                                        <tr class="dataTableHeadingRow">
                                            <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_QTY; ?></th>
                                            <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ART_ID; ?></th>
                                            <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_PRODUCT_NAME; ?></th>
                                            <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_ACTION; ?></th>
                                            <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_ACTION; ?></th>
                                            <th class="dataTableHeadingContent"><?php echo RATEPAY_ORDER_RATEPAY_ADMIN_DATE; ?></th>
                                        </tr>
                                         <?php foreach (Db::getRpHistory($orderId) as $entry): ?>
                                            <tr class="dataTableRow">
                                                <td class="dataTableContent"><?php echo $entry['quantity']; ?></td>
                                                <td class="dataTableContent"><?php echo $entry['article_number']; ?></td>
                                                <td class="dataTableContent"><?php echo $entry['article_name']; ?></td>
                                                <td class="dataTableContent"><?php echo $entry['method']; ?></td>
                                                <td class="dataTableContent"><?php echo $entry['submethod']; ?></td>
                                                <td class="dataTableContent"><?php echo $entry['date']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
Session::cleanRpSession();