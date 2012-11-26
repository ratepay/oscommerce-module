/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */

function switchRateOrRuntime(mode) {
    if (mode == 'rate') {
        document.getElementById('piRpSwitchToTerm').className = 'piRpActive';
        document.getElementById('piRpSwitchToRuntime').className = '';

        document.getElementById('piRpContentTerm').style.display = 'block';
        document.getElementById('piRpContentRuntime').style.display = 'none';
    } else if (mode == 'runtime') {
        document.getElementById('piRpSwitchToRuntime').className = 'piRpActive';
        document.getElementById('piRpSwitchToTerm').className = '';

        document.getElementById('piRpContentRuntime').style.display = 'block';
        document.getElementById('piRpContentTerm').style.display = 'none';
    }

}