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
 * Communication service for the curl communication 
 */
class CommunicationService
{

    /**
     * Test endpoints
     * 
     * @var string 
     */
    private $_testEndpoint = 'https://gateway-int.ratepay.com/api/xml/1_0';
    /**
     * Live endpoints
     * 
     * @var string 
     */
    private $_liveEndpoint = 'https://gateway.ratepay.com/api/xml/1_0';

    /**
     * Retrieve a cURL-Handler
     *
     * @param boolean $testmode
     * @param string $xml
     * @return cURL-Handler
     */
    private function _getCurl($testmode, $xml)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_getServerUrl($testmode));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: text/xml; charset=UTF-8",
            "Accept: */*",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Connection: keep-alive"
        ));

        return $curl;
    }

    /**
     * Send a cURL HTTP POST request and retrieve response
     * 
     * @param cURL-Handler $curl
     * @return string
     */
    public function send($testmode, $xml)
    {
        $curl = $this->_getCurl($testmode, $xml);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Returns the Server URL depending on Testmode
     * 
     * @param boolean $testmode
     * @return string
     */
    private function _getServerUrl($testmode)
    {
        return ($testmode) ? $this->_testEndpoint : $this->_liveEndpoint;
    }

}
