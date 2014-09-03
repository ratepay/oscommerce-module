
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

var RpCheckout = {  
    ratepayOnLoad : function()
    {
        var paymentRadioButtons = document.getElementsByName("payment");
        for (var i = 0; i < paymentRadioButtons.length; i++) {
            var payment = paymentRadioButtons[i].value;
            var currentEvent = paymentRadioButtons[i].parentNode.parentNode.getAttribute('onclick');
            paymentRadioButtons[i].parentNode.parentNode.setAttribute('onclick', 'RpCheckout.checkRpPayment("' + payment + '");' + currentEvent);
            if (paymentRadioButtons[i].checked) {
                var paymentChecked = payment;
            }
        }

        if(paymentRadioButtons.length == 1) {
            paymentRadioButtons[0].checked = true;
        }

        RpCheckout.checkRpPayment(paymentChecked);
    },
    checkRpPayment : function(payment)
    {
        var ratepayLastschriftBlock = document.getElementById("ratepay_lastschrift_block").parentNode.parentNode.parentNode;
        if (RpCheckout.isRpDirectDebit(payment)) {
            ratepayLastschriftBlock.style.display = 'block';
        } else {
            ratepayLastschriftBlock.style.display = 'none';
        }
    },
    isRpPayment : function (payment)
    {
        var payments = new Array('ratepay_rate', 'ratepay_rechnung', 'ratepay_lastschrift');
        return RpCheckout.inArray(payment, payments)
    },
    isRpDirectDebit : function (payment)
    {
        if (payment == 'ratepay_lastschrift') {
            return true;
        }
    },
    inArray : function(item, arr)
    {
        for(p = 0; p < arr.length; p++){ 
            if (item == arr[p]) {
                return true;
            }
        }
        return false;
    }
}