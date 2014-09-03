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

if (file_exists(DIR_FS_ADMIN . 'includes/template_top.php')) {
    $params = array('type' => 'button', 'newwindow' => 'true');
    $contents[] = array(
        'align' => 'center', 
        'text' => tep_draw_button('RatePAY', 'document', tep_href_link('ratepay_order.php', 'oID=' . $oInfo->orders_id), null)
    );
    /*$contents[] = array(
        'align' => 'center', 
        'text' => tep_draw_button('RatePAY Rechnung', 'document', tep_href_link($oInfo->payment_method . '_print_order.php', 'oID=' . $oInfo->orders_id), null, $params)
    );*/
     
} else {
    array_push($contents, array(
            'align' => 'center',
            'text' => '<a href="' . tep_href_link($oInfo->payment_method . '_print_order.php', 'oID=' . $oInfo->orders_id . '&payment=' . $oInfo->payment_method) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>'
        )
    );
    
    array_push($contents, array(
            'align' => 'center',
            'text' => ' <a href="' . tep_href_link('ratepay_order.php', 'oID=' . $oInfo->orders_id) . '"><button>RatePAY</button></a>'
        )
    );
}
