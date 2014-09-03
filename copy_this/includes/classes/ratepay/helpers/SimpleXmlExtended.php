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
 * SimpleXmlExtended Class workaround to add cdata elements to a simple xml object
 */
class SimpleXmlExtended extends SimpleXMLElement
{

    /**
     * This method add a new child element with a cdata tag arround the content
     * @param string $sName
     * @param string $sValue
     * @return PayIntelligent_Util_SimpleXmlExtended
     */
    public function addCDataChild($sName, $sValue)
    {
        $oNodeOld = dom_import_simplexml($this);
        $oDom = new DOMDocument('1.0', 'utf-8');
        $oDataNode = $oDom->appendChild($oDom->createElement($sName));
        $oDataNode->appendChild($oDom->createCDATASection(utf8_decode($sValue)));
        $oNodeTarget = $oNodeOld->ownerDocument->importNode($oDataNode, true);
        $oNodeOld->appendChild($oNodeTarget);

        return simplexml_import_dom($oNodeTarget);
    }
}
