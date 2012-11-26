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
 * @package   PayIntelligent_RatePAY
 * @copyright (C) 2010 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license   GPLv2
 */
//Ratepay Admin

//Tabellen überschriften
define('RATEPAY_ORDER_RATEPAY_NAME', 'RatePAY Rechnung');
define('RATEPAY_ORDER_RATEPAY_ADMIN_DELIVER_CANCEL', 'Lieferung / Stornierung');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RETOUR', 'Retoure');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RETOURE_BUTTON', 'retournieren');
define('RATEPAY_ORDER_RATEPAY_ADMIN_HISTORY', 'Historie');
define('RATEPAY_ORDER_RATEPAY_ADMIN_GOODWILL', 'Gutschrift');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RATE_DETAILS', 'Raten Details');
// end tabellen überschriften

//buttons
define('RATEPAY_ORDER_RATEPAY_ADMIN_DELIVERY', 'versenden');
define('RATEPAY_ORDER_RATEPAY_ADMIN_CANCELLATION', 'stornieren');
define('RATEPAY_ORDER_RATEPAY_ADMIN_CREATE_GOODWILL', 'Gutschrift erzeugen');
//end buttons

//table heads
define('RATEPAY_ORDER_RATEPAY_ADMIN_QTY', 'Anzahl');
define('RATEPAY_ORDER_RATEPAY_ART_ID', 'Art.-Nr.');
define('RATEPAY_ORDER_RATEPAY_ADMIN_PRODUCT_NAME', 'Bezeichnung');
define('RATEPAY_ORDER_RATEPAY_ADMIN_PRICE_NETTO', 'Preis (Netto)');
define('RATEPAY_ORDER_RATEPAY_ADMIN_TAX_AMOUNT', 'Prozentsatz Steuern');
define('RATEPAY_ORDER_RATEPAY_ADMIN_ROW_PRICE', 'Gesamtpreis (Brutto)');
define('RATEPAY_ORDER_RATEPAY_ADMIN_ORDERED', 'Bestellt');
define('RATEPAY_ORDER_RATEPAY_ADMIN_DELIVERED', 'Geliefert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_CANCELED', 'Storniert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RETURNED', 'Retourniert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_GOODWILL_AMOUNT', 'Wert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_ACTION', 'Action');
define('RATEPAY_ORDER_RATEPAY_ADMIN_DATE', 'Datum');
//end table heads

//end Ratepay admin

//Ratepay logging

define('PI_RATEPAY_ADMIN_LOGGING', 'Logging');
define('PI_RATEPAY_ADMIN_LOGGING_ID', 'ID');
define('PI_RATEPAY_ADMIN_LOGGING_ORDER_ID', 'ORDER ID');
define('PI_RATEPAY_ADMIN_LOGGING_TRANSACTION_ID', 'TRANSACTION ID');
define('PI_RATEPAY_ADMIN_LOGGING_PAYMENT_METHOD', 'PAYMENT METHOD');
define('PI_RATEPAY_ADMIN_LOGGING_OPERATION_TYPE', 'OPERATION TYPE');
define('PI_RATEPAY_ADMIN_LOGGING_OPERATION_SUBTYPE', 'OPERATION SUBTYPE');
define('PI_RATEPAY_ADMIN_LOGGING_RESULT', 'RESULT');
define('PI_RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT', 'RATEPAY RESULT');
define('PI_RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT_CODE', 'RATEPAY RESULT CODE');
define('PI_RATEPAY_ADMIN_LOGGING_REQUEST', 'REQUEST');
define('PI_RATEPAY_ADMIN_LOGGING_RESPONSE', 'RESPONSE');
define('PI_RATEPAY_ADMIN_LOGGING_DATE', 'DATE');
define('PI_RATEPAY_ADMIN_LOGGING_DELETE_TEXT_1', 'Alle Eintr&auml;ge die &auml;lter als');
define('PI_RATEPAY_ADMIN_LOGGING_DELETE_TEXT_2', 'Tage sind ');
define('PI_RATEPAY_ADMIN_LOGGING_DELETE', 'L&ouml;schen');
define('PI_RATEPAY_ADMIN_LOGGING_DELETE_SUCCESS', 'L&ouml;schen war erfolgreich.');
//end Ratepay logging

//RatePAY Order Overview
define('PI_RATEPAY_SUCCESSPARTIALCANCELLATION','Teilstornierung war erfolgreich.');
define('PI_RATEPAY_SUCCESSFULLCANCELLATION','Komplettstornierung war erfolgreich.');
define('PI_RATEPAY_SUCCESSPARTIALRETURN','Teilretournierung war erfolgreich.');
define('PI_RATEPAY_SUCCESSFULLRETURN','Komplettretournierung war erfolgreich.');
define('PI_RATEPAY_SUCCESSDELIVERY','Lieferung war erfolgreich.');
define('PI_RATEPAY_SUCCESSVOUCHER','Gutschrift wurde erfolgreich ausgef&uuml;hrt');
define('PI_RATEPAY_ERRORPARTIALCANCELLATION','Teilstornierung war nicht erfolgreich.');
define('PI_RATEPAY_ERRORFULLCANCELLATION','Komplettstornierung war nicht erfolgreich.');
define('PI_RATEPAY_ERRORPARTIALRETURN','Teilretournierung war nicht erfolgreich.');
define('PI_RATEPAY_ERRORFULLRETURN','Komplettretournierung war nicht erfolgreich.');
define('PI_RATEPAY_ERRORDELIVERY','Lieferung war nicht erfolgreich.');
define('PI_RATEPAY_ERRORVOUCHER','Gutschrift wurde nicht erfolgreich ausgef&uuml;hrt');
define('PI_RATEPAY_ERRORVOUCHER_AMOUNT_TO_LOW', 'Der Wert der Gutschrift muss gr&ouml;&szlig;er als 0 sein!');
define('PI_RATEPAY_ERRORTYPING','Falsche Eingabe. Eingabe wurde zur&uuml;ckgesetzt. Sie d&uuml;rfen nur Zahlen eintragen, die den vorausgef&uuml;llten Wert nicht &uuml;berschreiten.');
define('PI_RATEPAY_SERVICE','Service offline!');

define('PI_RATEPAY_SHIPPED','Geliefert');
define('PI_RATEPAY_RETURNED','Retourniert');
define('PI_RATEPAY_CANCELLED','Storniert');
define('PI_RATEPAY_CREDIT','Gutschrift');

define('PI_RATEPAY_VOUCHER', 'Anbieter Gutschrift');

//RatePAY PDF
define('PI_RATEPAY_RECHNUNG_PDF_OWNER','Gesch&auml;ftsf&uuml;hrer:');
define('PI_RATEPAY_RECHNUNG_PDF_FON','Telefon:');
define('PI_RATEPAY_RECHNUNG_PDF_FAX','Fax:');
define('PI_RATEPAY_RECHNUNG_PDF_EMAIL','E-Mail:');
define('PI_RATEPAY_RECHNUNG_PDF_COURT','Amtsgericht:');
define('PI_RATEPAY_RECHNUNG_PDF_HR','HR:');
define('PI_RATEPAY_RECHNUNG_PDF_UST','USt.-ID-Nr.:');
define('PI_RATEPAY_RECHNUNG_PDF_BULL',' &bull; ');
define('PI_RATEPAY_RECHNUNG_PDF_ACCOUNTHOLDER','Kontoinhaber:');
define('PI_RATEPAY_RECHNUNG_PDF_BANKNAME','Bank:');
define('PI_RATEPAY_RECHNUNG_PDF_BANKCODENUMBER','Bankleitzahl:');
define('PI_RATEPAY_RECHNUNG_PDF_ACCOUNTNUMBER','Kontonummer:');
define('PI_RATEPAY_RECHNUNG_PDF_SWIFTBIC','SWIFT-BIC:');
define('PI_RATEPAY_RECHNUNG_PDF_IBAN','IBAN:');
define('PI_RATEPAY_RECHNUNG_PDF_INTERNATIONALDESC','F&uuml;r den internationalen Zahlungstransfer:');
define('PI_RATEPAY_RECHNUNG_PDF_PAYTRANSFER','Bitte &uuml;berweisen Sie den oben aufgef&uuml;hrten Betrag auf folgendes Konto:');
define('PI_RATEPAY_RECHNUNG_PDF_PAYUNTIL','Es gelten folgende Zahlungsbedingungen: 14 Tage nach Rechnungsdatum ohne Abzug');
define('PI_RATEPAY_RECHNUNG_PDF_REFERENCE','Verwendungszweck:');
define('PI_RATEPAY_RECHNUNG_PDF_ADDITIONALINFO_1','Die Zahlungsabwicklung erfolgt durch die RatePAY GmbH. Der Verk&auml;ufer hat die f&auml;llige Kaufpreisforderung aus');
define('PI_RATEPAY_RECHNUNG_PDF_ADDITIONALINFO_2','Ihrer Bestellung einschlie&szlig;lich etwaiger Nebenforderungen an die RatePAY GmbH abgetreten. Forderungsinhaber');
define('PI_RATEPAY_RECHNUNG_PDF_ADDITIONALINFO_3','ist damit RatePAY GmbH. Eine schuldbefreiende Leistung durch Zahlung ist gem&auml;&szlig; &sect; 407 B&uuml;rgerliches Gesetzbuch');
define('PI_RATEPAY_RECHNUNG_PDF_ADDITIONALINFO_4','durch Sie nur an die RatePAY GmbH m&ouml;glich.');
define('PI_RATEPAY_RECHNUNG_PDF_ABOVEARTICLE','F&uuml;r Ihren Kauf auf Rechnung berechnen wir Ihnen folgende Artikel:');
define('PI_RATEPAY_RECHNUNG_PDF_DESCRIPTOR','RatePAY-Order:');

//RatePAY PDF
define('PI_RATEPAY_RATE_PDF_OWNER','Gesch&auml;ftsf&uuml;hrer:');
define('PI_RATEPAY_RATE_PDF_FON','Telefon:');
define('PI_RATEPAY_RATE_PDF_FAX','Fax:');
define('PI_RATEPAY_RATE_PDF_EMAIL','E-Mail:');
define('PI_RATEPAY_RATE_PDF_COURT','Amtsgericht:');
define('PI_RATEPAY_RATE_PDF_HR','HR:');
define('PI_RATEPAY_RATE_PDF_UST','USt.-ID-Nr.:');
define('PI_RATEPAY_RATE_PDF_BULL',' &bull; ');
define('PI_RATEPAY_RATE_PDF_ACCOUNTHOLDER','Kontoinhaber:');
define('PI_RATEPAY_RATE_PDF_BANKNAME','Bank:');
define('PI_RATEPAY_RATE_PDF_BANKCODENUMBER','Bankleitzahl:');
define('PI_RATEPAY_RATE_PDF_ACCOUNTNUMBER','Kontonummer:');
define('PI_RATEPAY_RATE_PDF_SWIFTBIC','SWIFT-BIC:');
define('PI_RATEPAY_RATE_PDF_IBAN','IBAN:');
define('PI_RATEPAY_RATE_PDF_INFO','Ihren Ratenplan und alle Informationen zur Zahlung erhalten Sie <u>gesondert per E-Mail.</u>');
define('PI_RATEPAY_RATE_PDF_PAYTRANSFER','Bitte nutzen Sie dazu die daf&auml;r eingerichtete Kontoverbindung des H&auml;ndlers:');
define('PI_RATEPAY_RATE_PDF_REFERENCE','Verwendungszweck:');
define('PI_RATEPAY_RATE_PDF_INTERNATIONALDESC','F&uuml;r den internationalen Zahlungstransfer:');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_1','Die Zahlungsabwicklung erfolgt durch die RatePAY GmbH. Der Verk&auml;ufer hat die f&auml;llige ');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_2','Kaufpreisforderung aus Ihrer Bestellung einschlie&szlig;lich etwaiger Nebenforderungen an die ');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_3',' abgetreten.');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_4','Forderungsinhaber ist damit die');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_5','. Eine schuldbefreiende Leistung durch Zahlung ist gem&auml;&szlig; &sect; 407 B&uuml;rgerliches ');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_6','Gesetzbuch durch Sie nur an die ');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_7',' m&ouml;glich.');
define('PI_RATEPAY_RATE_PDF_ADDITIONALINFO_8', 'Bitte nutzen Sie dazu die daf&uuml;r eingerichtete Kontoverbindung des H&auml;ndlers.');
define('PI_RATEPAY_RATE_PDF_ABOVEARTICLE','F&uuml;r Ihren Kauf auf Rate berechnen wir Ihnen folgende Artikel:');
define('PI_RATEPAY_RATE_PDF_DESCRIPTOR','RatePAY-Order:');

?>