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
 * RatePAY logging template, displays the XML
 */
require_once ('includes/application_top.php');
require_once ('../includes/languages/' . $_SESSION['language'] . '/admin/modules/payment/ratepay.php');
require_once ('../includes/classes/ratepay/helpers/Data.php');
require_once ('../includes/classes/ratepay/helpers/Db.php');
require_once ('../includes/classes/ratepay/helpers/Globals.php');
Globals::hasParam('id') ? $log = Db::getLogEntry(Globals::getParam('id')) : die('Page not allowed!');
require(DIR_WS_INCLUDES . 'template_top.php');
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
                            <tr>
                                <td class="pageHeading">Log</td>
                            </tr>
                            <tr>
                                <td><img width="100%" height="1" border="0" alt="" src="images/pixel_black.gif"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="ratepay_logging.php"><b class="button"><?php echo RATEPAY_ADMIN_LOG_BACK ?></b></a>
                        <hr/>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <th class="pageHeading">Request</th>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <textarea style="width: 600px; height: 500px;">
                                        <?php echo Data::addXmlLineBreak(htmlentities(utf8_decode($log['request']))); ?>
                                    </textarea>
                                </td>
                            </tr>
                        </table>
                        <hr/>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <th class="pageHeading">Response</th>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <textarea style="width: 600px; height: 500px;">
                                        <?php echo Data::addXmlLineBreak(htmlentities(utf8_decode($log['response']))); ?>
                                    </textarea>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <hr/>
            <a href="ratepay_logging.php"><b class="button"><?php echo RATEPAY_ADMIN_LOG_BACK ?></b></a>
        </td>
    </tr>
</table>
<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');