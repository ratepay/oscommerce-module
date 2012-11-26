<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  PayIntelligent
 * @package   PayIntelligent_RatePAY_Rechnung
 * @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3 
 */
// Create a Ratepay_XML Object
// Set the url to the server with the method "setRatepayserver($ratepayserver)"
// Set the parameter list with a nested array to create the request XML
class Ratepay_XML {

    var $live;
    var $operation;

    function _construct($live) {
        $this->live = $live;
    }

    // Getter
    // Get ratepayserver
    public function getRatepayserver() {
        include('Ratepay_XML_ini.php');
        if ($this->live) {
            return $ratepayLive;
        } else {
            return $ratepaySandbox;
        }
    }

    // Use this method for the payment operation's
    public function paymentOperation($xmlRequest) {
        $response = $this->httpsPost($xmlRequest->asXML());
        if ($response == false) {
            return false;
        }
        $xmlResponse = new SimpleXMLElement($response);
        return $xmlResponse;
    }

    // This method send a request to the RatePAY server and get the response
    private function httpsPost($xmlRequest) {
        // Initialisation
        $ch = curl_init();
        // Set parameters
        curl_setopt($ch, CURLOPT_URL, $this->getRatepayserver());

        // Return a variable instead of posting it directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Active the POST method
        curl_setopt($ch, CURLOPT_POST, 1);
        //Set HTTP Version
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        //Set HTTP Header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml; charset="UTF-8"',
            "Accept: */*",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Connection: keep-alive"
        ));
        // Request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // Execute the connection
        $result = curl_exec($ch);
        // Close it
        curl_close($ch);
        // Uncomment for xml debug
        //return $this->createXML();
        return $result;
    }

    // Wrapper method to create the XML

    public function getXMLObject() {
        $xmlString = '<request version="1.0" xmlns="urn://www.ratepay.com/payment/1_0"></request>';
        require_once('SimpleXMLExtended.php');
        $xml = new SimpleXMLExtended($xmlString);
        return $xml;
    }

}

?>