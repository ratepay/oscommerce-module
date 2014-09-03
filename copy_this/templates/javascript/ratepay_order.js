
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


var RpOrder = {
    checkVoucher : function(totalAmount) 
    {
        var voucherTotal = 0;
        var sub = document.getElementById('voucherAmount').value;
        var subKomma = document.getElementById('voucherAmountKomma').value;
        if (sub.match(/^[0-9]{1,4}$/i)) {
            voucherTotal = parseInt(sub);
            if (voucherTotal > totalAmount) {
                document.getElementById('voucherAmount').value = "0";
            } else {
                if (subKomma.match(/^[0-9]{1,2}$/i)) {
                    voucherTotal = sub + "." + subKomma;
                    voucherTotal = parseFloat(voucherTotal);
                    totalAmount = parseFloat(totalAmount);
                    if (voucherTotal > totalAmount) {
                        document.getElementById('voucherAmountKomma').value = "00";
                    }
                } else {
                    document.getElementById('voucherAmountKomma').value = "00";
                }
            }
        } else {
            document.getElementById('voucherAmount').value = "0";
        }
    },
    check : function(element, maxQty) 
    {
        if(element.value.match(/^[0-9]{1,4}$/i)) {
            if(maxQty < element.value) {
                element.value = maxQty;
            }
        }
    }
}