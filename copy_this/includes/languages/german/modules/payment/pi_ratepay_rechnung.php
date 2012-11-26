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
 * @package   PayIntelligent_Ratepay
 * @copyright (C) 2010 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license   GPLv2
 */
//Begin Backend RatePAY Rechnung Konfiguration
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_TEXT', 'RatePAY Rechnung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_STATUS_TITLE', 'RatePAY aktivieren');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_STATUS_DESC', 'Aktivieren Sie RatePAY Rechnung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_PROFILE_ID_TITLE','Profil ID');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_PROFILE_ID_DESC','Ihre von RatePAY zugewiesende ID');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SECURITY_CODE_TITLE','Sicherheitsschl&uuml;ssel');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SECURITY_CODE_DESC','Ihr von RatePAY zugewiesener Sicherheitsschl&uuml;ssel');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MIN_TITLE','Minimaler Bestellbetrag');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MIN_DESC','Tragen Sie hier den minimalen Bestellbetrag ein');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MAX_TITLE','Maximaler Bestellbetrag');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MAX_DESC','Tragen Sie hier den maximalen Bestellbetrag ein');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SANDBOX_TITLE','Sandbox');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SANDBOX_DESC','Testserver oder Livebetrieb?');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_LOGS_TITLE','Logging aktivieren');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_LOGS_DESC','Loggen Sie alle Transaktionen mit RatePAY');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_GTC_TITLE','AGB URL');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_GTC_DESC','Die URL zu Ihren AGB');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_PRIVACY_TITLE','Datenschutz URL');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_PRIVACY_DESC','Die URL zur RatePAY Datenschutzerkl&auml;rung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MERCHANT_PRIVACY_TITLE','H&auml;ndler Datenschutz URL');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MERCHANT_PRIVACY_DESC','Die URL zur H&auml;ndler Datenschutzerkl&auml;rung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MERCHANT_NAME_TITLE','Beg&uuml;nstigte Firma');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_MERCHANT_NAME_DESC','Der Name Ihrer Firma');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_BANK_NAME_TITLE','Kreditinstitut');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_BANK_NAME_DESC','Der Name Ihrer Bank');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SORT_CODE_TITLE','Bankleitzahl');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SORT_CODE_DESC','Ihre Bankleitzahl');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ACCOUNT_NR_TITLE','Konto-Nr.');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ACCOUNT_NR_DESC','Ihre Kontonummer');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SWIFT_TITLE','SWIFT BIC');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SWIFT_DESC','Ihre Swift/Bic');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_EXTRA_FIELD_TITLE','Zusatzfeld Rechnung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_EXTRA_FIELD_DESC','Ein Zusatzttext der in der Rechnung angezeigt wird');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ORDER_STATUS_ID_TITLE','Bestellstatus');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ORDER_STATUS_ID_DESC','Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_TEXT_DESCRIPTION','RatePAY Rechnung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ZONE_TITLE','Erlaubte Zonen');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ZONE_DESC','Bitte geben Sie hier die erlaubten Zonen an');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SORT_ORDER_TITLE','Sortierung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SORT_ORDER_DESC','Die Sortierung Ihrer Produkte');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ALLOWED_TITLE','Erlaubte L&auml;nder');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_ALLOWED_DESC','Bitte geben Sie hier alle erlaubten L&auml;nder an.');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_TEXT_TITLE','RatePAY Rechnung');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_IBAN_TITLE','IBAN');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_IBAN_DESC','');

define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_OWNER_TITLE','Gesch&auml;ftsf&uuml;hrer');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_HR_TITLE','Handelsregisternummer');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_FON_TITLE','Telefonnummer');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_FAX_TITLE','Faxnummer');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_PLZ_TITLE','PLZ und Ort');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_STREET_TITLE','Strasse und Nummer');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_COURT_TITLE','Amtsgericht');

define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_OWNER_DESC','');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_HR_DESC','');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_FON_DESC','');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_FAX_DESC','');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_PLZ_DESC','');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_STREET_DESC','');
define('MODULE_PAYMENT_PI_RATEPAY_RECHNUNG_SHOP_COURT_DESC','');
//End Backend RatePAY Rechnung Konfiguration

//Ratepay Info
define('PI_RATEPAY_RECHNUNG_INFO_1','stellt mit Unterst&uuml;tzung von RatePAY die M&ouml;glichkeit der RatePAY-Rechnung bereit. Sie nehmen damit einen Kauf auf Rechnung vor. Die Rechnung ist innerhalb von 14 Tagen nach Rechnungsdatum zur Zahlung f&auml;llig.');
define('PI_RATEPAY_RECHNUNG_INFO_2','RatePAY-Rechnung ist ');
define('PI_RATEPAY_RECHNUNG_INFO_3','ab einem Einkaufswert von ');
define('PI_RATEPAY_RECHNUNG_INFO_4',' bis zu einem Einkaufswert von ');
define('PI_RATEPAY_RECHNUNG_INFO_5',' m&ouml;glich (jeweils inklusive Mehrwertsteuer und Versandkosten).');
define('PI_RATEPAY_RECHNUNG_INFO_6','Bitte beachten Sie, dass RatePAY-Rechnung nur genutzt werden kann, wenn Rechnungs- und Lieferadresse identisch sind und Ihrem privaten Wohnsitz entsprechen (keine Firmen- und keine Postfachadresse). Ihre Adresse muss im Gebiet der Bundesrepublik Deutschland liegen. Bitte gehen Sie gegebenenfalls zur&uuml;ck und korrigieren Sie Ihre Daten. ');
define('PI_RATEPAY_RECHNUNG_INFO_7','Ich habe die ');
define('PI_RATEPAY_RECHNUNG_INFO_8','Allgemeinen Gesch&auml;ftsbedingungen');
define('PI_RATEPAY_RECHNUNG_INFO_9',' zur Kenntnis genommen und erkl&auml;re mich mit deren Geltung einverstanden. Au&szlig;erdem erkl&auml;re ich hiermit meine Einwilligung zur Verwendung meiner Daten gem&auml;&szlig; der ');
define('PI_RATEPAY_RECHNUNG_INFO_10','RatePAY-Datenschutzerkl&auml;rung');
define('PI_RATEPAY_RECHNUNG_INFO_11',' und bin insbesondere damit einverstanden, zum Zwecke der Durchf&uuml;hrung des Vertrags &uuml;ber die von mir angebene E-Mail Adresse kontaktiert zu werden.');
define('PI_RATEPAY_RECHNUNG_INFO_12','Au&szlig;erdem akzeptiere ich die ');
define('PI_RATEPAY_RECHNUNG_INFO_13','H&auml;ndler Datenschutzerkl&auml;rung');
define('PI_RATEPAY_RECHNUNG_AGB_ERROR','Bitte akzeptieren Sie die Allgemeinen Nutzungsbedingungen um mit RatePAY Rechnung einzukaufen.');
define('PI_RATEPAY_RECHNUNG_CHECKOUT_BACK', 'Zur&uuml;ck');
define('PI_RATEPAY_RECHNUNG_CHECKOUT_CONTINUE', 'Weiter');
//End ratepay info

//RatePAY Checkout
define('PI_RATEPAY_RECHNUNG_ERROR_BIRTH','*Geben Sie bitte f&uuml;r die Zahlungsoption RatePAY Rechnung Ihr Geburtsdatum in dem Format TT.MM.JJJJ an.');
define('PI_RATEPAY_RECHNUNG_ERROR_PHONE','*Geben Sie bitte f&uuml;r die Zahlungsoption RatePAY Rechnung Ihre Telefonnummer an.');
define('PI_RATEPAY_RECHNUNG_ERROR_PHONE_AND_BIRTH','*Geben Sie bitte f&uuml;r die Zahlungsoption RatePAY Rechnung Ihr Geburtsdatum in dem Format TT.MM.JJJJ sowie Ihre Telefonnummer an.');
define('PI_RATEPAY_RECHNUNG_ERROR_AGE','*Leider ist eine Zahlung mit RatePAY nicht m&ouml;glich. F&uuml;r die Zahlungsoption RatePAY Rechnung m&uuml;ssen Sie mindestens 18 Jahre alt sein.');
define('PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_FON','Telefon:');
define('PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_BIRTHDATE','Geburtsdatum:');
define('PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_VATID','Umsatzsteuer ID:');
define('PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_COMPANY','Firma:');
define('PI_RATEPAY_RECHNUNG_ERROR_COMPANY_ERROR', '*Sie haben eine USt-IdNr. angegeben. Geben Sie bitte f&uuml;r die Zahlungsoption RatePAY Rechnung den Namen Ihres Unternehmens an.');
define('PI_RATEPAY_ERROR_VATID_ERROR', '*Geben Sie bitte f&uuml;r die Zahlungsoption RatePAY Rechnung die USt-IdNr. Ihres Unternehmens an.');
define('PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_BIRTHDATE_FORMAT','(tt.mm.jjjj)');
define('PI_RATEPAY_RECHNUNG_ERROR','*Leider ist eine Bezahlung mit RatePAY nicht m&ouml;glich. Diese Entscheidung ist von RatePAY auf der Grundlage einer automatisierten Datenverarbeitung getroffen worden. Einzelheiten erfahren Sie in der RatePAY-Datenschutzerkl&auml;rung.');
define('PI_RATEPAY_RECHNUNG_ERROR_GATEWAY','*Leider ist die Verbindung zu RatePAY derzeit nicht m&ouml;glich, bitte versuchen Sie es zu einem sp&auml;teren Zeitpunkt erneut.');
define('PI_RATEPAY_RECHNUNG_ERROR_AMOUNT', 'RatePAY Rechnung ist leider nur bei einem Warenkorbbetrag von %s EUR bis %s m&ouml;glich. Falls Sie einen Coupon Code verwendet haben, m&uuml;sen Sie diesen erneut eingeben.');
?>