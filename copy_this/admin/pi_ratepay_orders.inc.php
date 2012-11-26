<?php
if($oInfo->payment_method == "RatePAY Rate") {
	include 'pi_ratepay_orders_rate.inc.php';
} else if($oInfo->payment_method == "RatePAY Rechnung") {
	include 'pi_ratepay_orders_rechnung.inc.php';
}
?>