<?php

function is_osc231() {
    $filename = DIR_FS_ADMIN . 'includes/template_top.php';
    if (file_exists($filename)) {
        return true;
    } else {
        return false;
    }
}

if (is_osc231()) {
    $params = array('type' => 'button', 'newwindow' => 'true');
    array_push($contents, array(
            'align' => 'center',
            'text' => tep_draw_button('Rechnung', 'document', tep_href_link('pi_ratepay_rechnung_print_order.php', 'oID=' . $oInfo->orders_id), null, $params) . tep_draw_button(IMAGE_ORDERS_PACKINGSLIP, 'document', tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id), null, $params)
        )
    );
    array_push($contents, array(
            'align' => 'center',
            'text' => tep_draw_button('RatePAY Details', 'document', tep_href_link('pi_ratepay_admin_osc231.php', 'oID=' . $oInfo->orders_id), null)
        )
    );
    
} else {

    array_push($contents, array(
            'align' => 'center',
            'text' => '<a href="' . tep_href_link('pi_ratepay_rechnung_print_order.php', 'oID=' . $oInfo->orders_id . '&payment=' . $oInfo->payment_method) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>'
        )
    );
    array_push($contents, array(
            'align' => 'center',
            'text' => ' <a href="' . tep_href_link('pi_ratepay_admin_osc22.php', 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_template_admin_details.gif', "RatePAY Details") . '</a>'
        )
    );
}
?>

