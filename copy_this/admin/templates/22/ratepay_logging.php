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
 * RatePAY log overview, displays all log entrys
 */
require_once ('includes/application_top.php');
require_once ('../includes/languages/' . $_SESSION['language'] . '/admin/modules/payment/ratepay.php');
require_once ('../includes/classes/ratepay/helpers/Data.php');
require_once ('../includes/classes/ratepay/helpers/Db.php');
require_once ('../includes/classes/ratepay/helpers/Globals.php');
$logs = Globals::hasParam('orderby') ? Db::getLogEntrys(Globals::getParam('orderby')) : Db::getLogEntrys();
$logical = Data::getLoggingLogical();
require(DIR_WS_INCLUDES . 'header.php'); 
?>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
            <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1"
                   cellpadding="1" class="columnLeft">
                <!-- left_navigation //-->
                <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
                            <tr>
                                <td class="pageHeading"><?php echo RATEPAY_ADMIN_LOGGING; ?></td>
                            </tr>
                            <tr>
                                <td><img width="100%" height="1" border="0" alt="" src="images/pixel_black.gif"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php if (Globals::hasParam('success')): ?>
                <tr>
                    <td class="messageStackSuccess">
                        <img border="0" title="" alt="" src="images/icons/success.gif">
                        <?php echo RATEPAY_ADMIN_LOGGING_DELETE_SUCCESS; ?>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td> 
                        <form method="post" action="delete_logging.php">
                            <span><?php echo RATEPAY_ADMIN_LOGGING_DELETE_TEXT_1; ?></span>
                            <input type="text" length="3" size="3" name="days">
                            <span><?php echo RATEPAY_ADMIN_LOGGING_DELETE_TEXT_2; ?></span>
                            <input type="submit" value="<?php echo RATEPAY_ADMIN_LOGGING_DELETE; ?>" name="submit" class="button">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr class="dataTableHeadingRow">
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=id&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_ID; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=order_number&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_ORDER_ID; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=transaction_id&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_TRANSACTION_ID; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=transaction_id&logical=<?php echo $logical; ?>"><b><?php echo 'NAME'; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=payment_method&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_PAYMENT_METHOD; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=payment_type&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_OPERATION_TYPE; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=payment_subtype&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_OPERATION_SUBTYPE; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=result&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_RESULT; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=result_code&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT_CODE; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=result&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=reason&logical=<?php echo $logical; ?>"><b><?php echo 'REASON'; ?></b></a></th>
                                <th class="dataTableHeadingContent"><b><?php echo RATEPAY_ADMIN_LOGGING_REQUEST; ?>/<?php echo RATEPAY_ADMIN_LOGGING_RESPONSE; ?></b></th>
                                <th class="dataTableHeadingContent"><a href="ratepay_logging.php?orderby=date&logical=<?php echo $logical; ?>"><b><?php echo RATEPAY_ADMIN_LOGGING_DATE; ?></b></a></th>
                            </tr>
                            <?php while ($log = tep_db_fetch_array($logs)): ?>
                            <tr class="dataTableRow">
                                <td class="dataTableContent"><?php echo $log['id']; ?></td>
                                <td class="dataTableContent"><?php echo $log['order_number']; ?></td>
                                <td class="dataTableContent"><?php echo $log['transaction_id']; ?></td>
                                <td class="dataTableContent"><?php echo Data::getFullName($log['order_number']); ?></td>
                                <td class="dataTableContent"><?php echo $log['payment_method']; ?></td>
                                <td class="dataTableContent"><?php echo $log['payment_type']; ?></td>
                                <td class="dataTableContent"><?php echo $log['payment_subtype']; ?></td>
                                <td class="dataTableContent"><?php echo $log['result']; ?></td>
                                <td class="dataTableContent"><?php echo $log['result_code']; ?></td>
                                <td class="dataTableContent"><?php echo Data::getRpResult($log['result']); ?></td>
                                <td class="dataTableContent"><?php echo $log['reason']; ?></td>
                                <td class="dataTableContent" style="text-align: center;"><a href="ratepay_log.php?id=<?php echo $log['id']; ?>"><b>XML</b></a></td>
                                <td class="dataTableContent"><?php echo $log['date']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                        <p>
                        <form method="post" action="delete_logging.php">
                            <span><?php echo RATEPAY_ADMIN_LOGGING_DELETE_TEXT_1; ?></span>
                            <input type="text" length="3" size="3" name="days">
                            <span><?php echo RATEPAY_ADMIN_LOGGING_DELETE_TEXT_2; ?></span>
                            <input type="submit" value="<?php echo RATEPAY_ADMIN_LOGGING_DELETE; ?>" name="submit" class="button">
                        </form>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
require(DIR_WS_INCLUDES . 'footer.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');