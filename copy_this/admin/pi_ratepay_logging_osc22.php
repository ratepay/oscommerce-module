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
 * @package   PayIntelligent_RatePAY
 * @copyright (C) 2010 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license   GPLv2
 */
require('includes/application_top.php');
$language = $_SESSION['language'];
require_once 'includes/languages/'.$language.'/modules/payment/pi_ratepay.php';
!empty($_GET['oID']) ? $shopOrderID = $_GET['oID'] : $shopOrderID = $_POST['oID'];

if (isset($_POST['submit'])) {
    if (preg_match("/^[0-9]{1,2}$/", $_POST['days'])) {
        $days = $_POST['days'];
        if ($days == 0) {
            tep_db_query("delete from pi_ratepay_log");
        } else {
            $days = $_POST['days'];
            $sql = "DELETE FROM pi_ratepay_log WHERE TO_DAYS(now()) - TO_DAYS(date) > " . (int) $days;
            tep_db_query($sql);
        }
        $success = RATEPAY_ADMIN_LOGGING_DELETE_SUCCESS;
    }
}

$orderBy = 'date';
if (isset($_GET['orderby'])) {
    $orderBy = $_GET['orderby'];
}

if ($orderBy == 'first_name') {
    $sql = 'select * from pi_ratepay_log order by ' . tep_db_input($orderBy) . ' desc, last_name desc';
    $logs = tep_db_query($sql);
} else {
    $sql = 'select * from pi_ratepay_log order by ' . tep_db_input($orderBy) . ' desc';
    $logs = tep_db_query($sql);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
        if ($type == 'request') {
            $sql = "select request from pi_ratepay_log where id = '" . tep_db_input($id) . "'";
            $query = tep_db_query($sql);
            $request = tep_db_fetch_array($query);
            $xml = $request['request'];
            $div = '<div id="xmlWindow" class="xmlWindow"><center><a onClick="hideXML()">Schlie&szlig;en</a></center><hr/>' . str_replace("&gt;&lt;", "&gt;<br/>&lt;", htmlentities($xml)) . '<hr/><center><a onClick="hideXML()">Schlie&szlig;en</a></center></div>';
        } else if ($type == 'response') {
            $sql = "select response from pi_ratepay_log where id = '" . tep_db_input($id) . "'";
            $query = tep_db_query($sql);
            $response = tep_db_fetch_array($query);
            $xml = $response['response'];
            $div = '<div id="xmlWindow" class="xmlWindow"><center><a onClick="hideXML()">Schlie&szlig;en</a></center><hr/>' . str_replace("&gt;&lt;", "&gt;<br/>&lt;", htmlentities($xml)) . '<hr/><center><a onClick="hideXML()">Schlie&szlig;en</a></center></div>';
        }
    }
}
?>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<style type="text/css">


    #xmlWindow {
        position: fixed;
        top: 10%;
        left: 30%;
        right: 30%;
        width: 40%;
        height: 60%;
        border-width: 1px;
        border-style: solid;
        background-color: white;
        overflow: auto;
    }

</style>

<!--[if IE]>
<style type="text/css">
#xmlWindow {
        position: absolute;
        top: 10%;
        left: 30%;
        right: 30%;
        width: 40%;
        height: 60%;
        border-width: 1px;
        border-style: solid;
        background-color: white;
        overflow: auto;
        }
        </style>
<![endif]-->
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<style>
    .dataTableHeadingLinks {
    font-family: Verdana, Arial, sans-serif;
    font-size: 10px;
    color: white!important;
    font-weight: bold;
    text-decoration: none;
    }
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
        <script type="text/javascript">
        function hideXML(){
                document.getElementById('xmlWindow').style.display = 'none';
        }
        </script>
        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
                            <tr>
                                <td class="pageHeading"><?php echo PI_RATEPAY_ADMIN_LOGGING; ?></td>
                            </tr>
                            <tr>
                                <td><img width="100%" height="1" border="0" alt="" src="images/pixel_black.gif"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php if (isset($success)) { ?>
                    <tr>
                        <td class="messageStackSuccess"><img border="0" title="" alt="" src="images/icons/success.gif"><?php echo $success; ?></td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td>
                            <form method="post" action="pi_ratepay_logging_osc22.php">
                                <span><?php echo PI_RATEPAY_ADMIN_LOGGING_DELETE_TEXT_1; ?></span>
                                <input type="text" length="3" size="3" name="days">
                                <span><?php echo PI_RATEPAY_ADMIN_LOGGING_DELETE_TEXT_2; ?></span>
                                <input type="submit" value="<?php echo PI_RATEPAY_ADMIN_LOGGING_DELETE; ?>" name="submit">
                            </form>
                        </td>
                    </tr>
                <tr>
                    <td>
                        <!-- RatePAY Content start //-->
                        <table>
                            <tr class="dataTableHeadingRow">
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=id"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_ID; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=order_number"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_ORDER_ID; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=transaction_id"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_TRANSACTION_ID; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=first_name"><b><?php echo 'NAME'; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=payment_method"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_PAYMENT_METHOD; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=payment_type"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_OPERATION_TYPE; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=payment_subtype"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_OPERATION_SUBTYPE; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=result"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_RESULT; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=result_code"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT_CODE; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=result"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT; ?></b></a></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=reason"><b><?php echo 'REASON'; ?></b></a></th>
                                <th class="dataTableHeadingContent"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_REQUEST; ?></b></th>
                                <th class="dataTableHeadingContent"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_RESPONSE; ?></b></th>
                                <th class="dataTableHeadingContent"><a class="dataTableHeadingLinks" href="pi_ratepay_logging_osc22.php?orderby=date"><b><?php echo PI_RATEPAY_ADMIN_LOGGING_DATE; ?></b></a></th>
                            </tr>
                            <?php
                            while ($log = tep_db_fetch_array($logs)) {
                                if ($log['result'] == 'Confirmation deliver successful' || $log['result'] == 'Transaction initialized' || $log['result'] == 'Payment change successful' || $log['result'] == 'Transaction result successful' || $log['result'] == 'Transaction result pending') {
                                    $rpResult = 'SUCCESS';
                                } else {
                                    $rpResult = 'ERROR';
                                }
                                ?>
                                <tr class="dataTableRow">
                                    <td class="dataTableContent"><?php echo $log['id']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['order_number']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['transaction_id']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['first_name'] . '&nbsp;' . $log['last_name']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['payment_method']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['payment_type']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['payment_subtype']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['result']; ?></td>
                                    <td class="dataTableContent"><?php echo $log['result_code']; ?></td>
                                    <td class="dataTableContent"><?php echo $rpResult; ?></td>
                                    <td class="dataTableContent"><?php echo $log['reason']; ?></td>
                                    <td class="dataTableContent"><a href="pi_ratepay_logging_osc22.php?id=<?php echo $log['id']; ?>&type=request">Request</a></td>
                                    <td class="dataTableContent"><a href="pi_ratepay_logging_osc22.php?id=<?php echo $log['id']; ?>&type=response">Response</a></td>
                                    <td class="dataTableContent"><?php echo $log['date']; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <p>
                        <form method="post" action="pi_ratepay_logging_osc22.php">
                            <span><?php echo PI_RATEPAY_ADMIN_LOGGING_DELETE_TEXT_1; ?></span>
                            <input type="text" length="3" size="3" name="days">
                            <span><?php echo PI_RATEPAY_ADMIN_LOGGING_DELETE_TEXT_2; ?></span>
                            <input type="submit" value="<?php echo PI_RATEPAY_ADMIN_LOGGING_DELETE; ?>" name="submit">
                        </form>
                        </p>
                        <?php
                        if (isset($div)) {
                            echo $div;
                        }
                        ?>
                        <!-- RatePAY Content end //-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- body_text_eof //-->
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