/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */

function piRatepayRateCalculatorAction(mode, osCsid) {
    var calcValue;
    var calcMethod;

    var html;

    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    if (mode == 'rate') {
        calcValue = document.getElementById('rate').value;
        calcMethod = 'calculation-by-rate';
    } else if (mode == 'runtime') {
        calcValue = document.getElementById('runtime').value;
        calcMethod = 'calculation-by-time';
    }

    xmlhttp.open("POST",  "PiRatepayRateCalcRequest.php?osCsid=" + osCsid, false);

    xmlhttp.setRequestHeader("Content-Type",
        "application/x-www-form-urlencoded");

    xmlhttp.send("calcValue=" + calcValue + "&calcMethod=" + calcMethod);

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById('piRpResult').innerHTML = html;
        document.getElementById('piRpSwitchToTerm').style.display = 'none';
        document.getElementById('piRpSwitchToTerm').style.display = 'block';
    }

}

function piLoadrateCalculator(osCsid) {
    var html;

    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
	
    xmlhttp.open("POST", "PiRatepayRateCalcDesign.php?osCsid=" + osCsid, false);

    xmlhttp.setRequestHeader("Content-Type",
        "application/x-www-form-urlencoded");

    xmlhttp.send();

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById('pirpmain-cont').innerHTML = html;
    }
}

function piLoadrateResult() {
    var html;

    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("POST",  "PiRatepayRateCalcRequest.php", false);

    xmlhttp.setRequestHeader("Content-Type",
        "application/x-www-form-urlencoded");

    xmlhttp.send();

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById('pirpmain-cont').innerHTML = html;
    }
}