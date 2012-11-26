<?php

class SimpleXMLExtended extends SimpleXMLElement {

    public function addCDataChild($sName, $sValue) {
        $oNodeOld = dom_import_simplexml($this);
        $oNodeNew = new DOMNode();
        $oDom = new DOMDocument();
        $oDataNode = $oDom->appendChild($oDom->createElement($sName));
        $oDataNode->appendChild($oDom->createCDATASection($sValue));
        $oNodeTarget = $oNodeOld->ownerDocument->importNode($oDataNode, true);
        $oNodeOld->appendChild($oNodeTarget);
        return simplexml_import_dom($oNodeTarget);
    }

}

?>