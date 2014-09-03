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

// Config text and description
define('MODULE_PAYMENT_RATEPAY_RATE_TEXT_DESCRIPTION', 'Bieten Sie Ihren Kunden flexiblen Ratenkauf');
define('MODULE_PAYMENT_RATEPAY_RATE_TEXT', 'RatePAY Rate');
define('MODULE_PAYMENT_RATEPAY_RATE_TEXT_TITLE', 'RatePAY Rate');
define('MODULE_PAYMENT_RATEPAY_RATE_TEXT_DESCRIPTION', '');
define('MODULE_PAYMENT_RATEPAY_RATE_STATUS_TITLE', 'RatePAY aktivieren');
define('MODULE_PAYMENT_RATEPAY_RATE_STATUS_DESC', 'Aktivieren Sie RatePAY Rate');
define('MODULE_PAYMENT_RATEPAY_RATE_SANDBOX_TITLE', 'Sandbox');
define('MODULE_PAYMENT_RATEPAY_RATE_SANDBOX_DESC', 'Testserver oder Livebetrieb?');
define('MODULE_PAYMENT_RATEPAY_RATE_PROFILE_ID_TITLE', 'Profil ID');
define('MODULE_PAYMENT_RATEPAY_RATE_PROFILE_ID_DESC', 'Ihre von RatePAY zugewiesende ID');
define('MODULE_PAYMENT_RATEPAY_RATE_SECURITY_CODE_TITLE', 'Sicherheitsschlüssel');
define('MODULE_PAYMENT_RATEPAY_RATE_SECURITY_CODE_DESC', 'Ihr von RatePAY zugewiesener Sicherheitsschlüssel');
define('MODULE_PAYMENT_RATEPAY_RATE_MIN_TITLE', 'Minimaler Bestellbetrag');
define('MODULE_PAYMENT_RATEPAY_RATE_MIN_DESC', 'Tragen Sie hier den minimalen Bestellbetrag ein');
define('MODULE_PAYMENT_RATEPAY_RATE_MAX_TITLE', 'Maximaler Bestellbetrag');
define('MODULE_PAYMENT_RATEPAY_RATE_MAX_DESC', 'Tragen Sie hier den maximalen Bestellbetrag ein');
define('MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_GTC_URL_TITLE', 'AGB URL');
define('MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_GTC_URL_DESC', 'Die URL zu Ihren AGB');
define('MODULE_PAYMENT_RATEPAY_RATE_RATEPAY_PRIVACY_URL_TITLE', 'RatePAY Datenschutz URL');
define('MODULE_PAYMENT_RATEPAY_RATE_RATEPAY_PRIVACY_URL_DESC', 'Die URL zur RatePAY Datenschutzerklärung');
define('MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_PRIVACY_URL_TITLE', 'Händler Datenschutz URL');
define('MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_PRIVACY_URL_DESC', 'Die URL zur Händler Datenschutzerklärung');
define('MODULE_PAYMENT_RATEPAY_RATE_PAYMENT_FIRSTDAY_TITLE', 'Dynamische Fälligkeit');
define('MODULE_PAYMENT_RATEPAY_RATE_PAYMENT_FIRSTDAY_DESC', 'Wann werden die Raten fällig');
define('MODULE_PAYMENT_RATEPAY_RATE_LOGGING_TITLE', 'Logging aktivieren');
define('MODULE_PAYMENT_RATEPAY_RATE_LOGGING_DESC', 'Loggen Sie alle Transaktionen mit RatePAY');
define('MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_NAME_TITLE', 'Begünstigte Firma');
define('MODULE_PAYMENT_RATEPAY_RATE_MERCHANT_NAME_DESC', 'Der Name Ihrer Firma');
define('MODULE_PAYMENT_RATEPAY_RATE_BANK_NAME_TITLE', 'Kreditinstitut');
define('MODULE_PAYMENT_RATEPAY_RATE_BANK_NAME_DESC', 'Der Name Ihrer Bank');
define('MODULE_PAYMENT_RATEPAY_RATE_SORT_CODE_TITLE', 'Bankleitzahl');
define('MODULE_PAYMENT_RATEPAY_RATE_SORT_CODE_DESC', 'Ihre Bankleitzahl');
define('MODULE_PAYMENT_RATEPAY_RATE_ACCOUNT_NR_TITLE', 'Konto-Nr.');
define('MODULE_PAYMENT_RATEPAY_RATE_ACCOUNT_NR_DESC', 'Ihre Kontonummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SWIFT_TITLE', 'SWIFT BIC');
define('MODULE_PAYMENT_RATEPAY_RATE_SWIFT_DESC', 'Ihre Swift/Bic');
define('MODULE_PAYMENT_RATEPAY_RATE_IBAN_TITLE', 'IBAN');
define('MODULE_PAYMENT_RATEPAY_RATE_IBAN_DESC', 'Ihre IBAN');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_OWNER_TITLE', 'Geschäftsführer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_OWNER_DESC', 'Ihr Geschäftsführer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_HR_TITLE', 'Handelsregisternummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_HR_DESC', 'Ihre Handelsregisternummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_FON_TITLE', 'Telefonnummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_FON_DESC', 'Ihre Telefonnummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_FAX_TITLE', 'Faxnummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_FAX_DESC', 'Ihre Faxnummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_PLZ_TITLE', 'PLZ und Ort');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_PLZ_DESC', 'Ihre PLZ und Ihr Ort');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_STREET_TITLE', 'Strasse und Nummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_STREET_DESC', 'Ihre Strasse und Ihre Hausnummer');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_COURT_TITLE', 'Amtsgericht');
define('MODULE_PAYMENT_RATEPAY_RATE_SHOP_COURT_DESC', 'Ihr zuständiges Amtsgericht');
define('MODULE_PAYMENT_RATEPAY_RATE_EXTRA_FIELD_DESC', 'Weitere Informationen die auf der Rechnung angezeigt werden');
define('MODULE_PAYMENT_RATEPAY_RATE_EXTRA_FIELD_TITLE', 'Zusatzfeld Rechnung');
define('MODULE_PAYMENT_RATEPAY_RATE_SORT_ORDER_TITLE', 'Sortierung');
define('MODULE_PAYMENT_RATEPAY_RATE_SORT_ORDER_DESC', 'Die Sortierung dieser Zahlweise');
define('MODULE_PAYMENT_RATEPAY_RATE_ALLOWED_TITLE', 'Erlaubte Länder');
define('MODULE_PAYMENT_RATEPAY_RATE_ALLOWED_DESC', 'Bitte geben Sie hier alle erlaubten Länder an.');
define('MODULE_PAYMENT_RATEPAY_RATE_ZONE_TITLE', 'Erlaubte Zonen');
define('MODULE_PAYMENT_RATEPAY_RATE_ZONE_DESC', 'Bitte geben Sie hier die erlaubten Zonen an');
define('MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID_TITLE', 'Bestellstatus');
define('MODULE_PAYMENT_RATEPAY_RATE_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen');
// Config text and description end
//Ratepay Info
define('RATEPAY_RATE_INFO_1', 'Mit RatePAY-Ratenzahlung wählen Sie eine Bezahlung in Raten.');
define('RATEPAY_RATE_INFO_2', 'RatePAY-Ratenzahlung ist ');
define('RATEPAY_RATE_INFO_3', 'ab einem Einkaufswert von ');
define('RATEPAY_RATE_INFO_4', ' bis zu einem Einkaufswert von ');
define('RATEPAY_RATE_INFO_5', ' möglich (jeweils inklusive Mehrwertsteuer und Versandkosten).');
define('RATEPAY_RATE_INFO_6', 'Ihre monatlichen Teilzahlungsrate, die Laufzeit der Teilzahlung und den entsprechenden Zinsaufschlag können Sie mit dem Ratenrechner im Anschluss ermitteln und festlegen.<br/><br/>Bitte beachten Sie, dass RatePAY-Rate nur genutzt werden kann, wenn Rechnungs- und Lieferaddresse identisch sind und Ihrem privaten Wohnort entsprechen. (keine Firmen- und keine Postfachadresse). Ihre Adresse muss im Gebiet der Bundesrepublik Deutschland liegen. Bitte gehen Sie gegebenenfalls zurück und korrigieren Sie Ihre Daten.');
define('RATEPAY_RATE_INFO_7', 'Ich habe die ');
define('RATEPAY_RATE_INFO_8', 'Allgemeinen Geschäftsbedingungen');
define('RATEPAY_RATE_INFO_9', ' zur Kenntnis genommen und erkläre mich mit deren Geltung einverstanden. Au&szlig;erdem erkläre ich hiermit meine Einwilligung zur Verwendung meiner Daten gemä&szlig; der ');
define('RATEPAY_RATE_INFO_10', 'RatePAY-Datenschutzerklärung');
define('RATEPAY_RATE_INFO_11', ' und bin insbesondere damit einverstanden, zum Zwecke der Durchführung des Vertrags über die von mir angebene E-Mail Adresse kontaktiert zu werden.');
define('RATEPAY_RATE_INFO_12', 'Au&szlig;erdem akzeptiere ich die ');
define('RATEPAY_RATE_INFO_13', 'Händler Datenschutzerklärung');
define('RATEPAY_RATE_AGB_ERROR', 'Bitte akzeptieren Sie die Allgemeinen Nutzungsbedingungen um mit RatePAY Rate einzukaufen.');
//End ratepay info
// Checkout
define('RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE_FORMAT', '(tt.mm.jjjj)');
define('RATEPAY_RATE_RATE_CALCULATOR_ERROR', 'Bitte lassen Sie zuerst Ihre Ratenplan berechnen bevor Sie mit dem Kauf fortfahren.');
// Checkout end
// Checkout errors
define('RATEPAY_LASTSCHRIFT_ERROR', '*Leider ist eine Bezahlung mit RatePAY nicht möglich. Diese Entscheidung ist auf Grundlage einer automatisierten Datenverarbeitung getroffen worden. Einzelheiten finden sie in den zusätzlichen Allgemeinen Geschäftsbedingungen und dem Datenschutzhinweis für RatePAY-Zahlungsarten.');
define('RATEPAY_RATE_ERROR_GATEWAY', '*Leider ist die Verbindung zu RatePAY derzeit nicht möglich, bitte versuchen Sie es zu einem späteren Zeitpunkt erneut.');
define('RATEPAY_RATE_PHONE_IS_MISSING', '*Geben Sie bitte für die Zahlungsoption RatePAY Rate Ihre Telefonnummer an.');
define('RATEPAY_RATE_PHONE_IS_INVALID', '*Geben Sie bitte für die Zahlungsoption RatePAY Rate eine gültige Telefonnummer an.');
define('RATEPAY_RATE_DOB_IS_MISSING', '*Geben Sie bitte für die Zahlungsoption RatePAY Rate Ihr Geburtsdatum an.');
define('RATEPAY_RATE_DOB_IS_INVALID', '*Geben Sie bitte für die Zahlungsoption RatePAY Rate Ihr Geburtsdatum in dem Format TT.MM.JJJJ an.');
define('RATEPAY_RATE_DOB_IS_YOUNGER', '*Leider ist eine Zahlung mit RatePAY nicht möglich. Für die Zahlungsoption RatePAY Rate müssen Sie mindestens 18 Jahre alt sein.');
define('RATEPAY_RATE_COMPANY_IS_MISSING', '*Sie haben eine USt-IdNr. angegeben. Geben Sie bitte für die Zahlungsoption RatePAY Rate den Namen Ihres Unternehmens an.');
define('RATEPAY_RATE_VATID_IS_MISSING', '*Geben Sie bitte für die Zahlungsoption RatePAY Rate die USt-IdNr. Ihres Unternehmens an.');
define('RATEPAY_RATE_AGB_MISSING', 'Sofern Sie unsere Allgemeinen Geschäftsbedingungen nicht akzeptieren, können wir Ihre Bestellung bedauerlicherweise nicht entgegennehmen!');
// Checkout errors end
// Invoice
define('RATEPAY_RATE_PDF_OWNER', 'Geschäftsführer:');
define('RATEPAY_RATE_PDF_FON', 'Telefon:');
define('RATEPAY_RATE_PDF_FAX', 'Fax:');
define('RATEPAY_RATE_PDF_EMAIL', 'E-Mail:');
define('RATEPAY_RATE_PDF_COURT', 'Amtsgericht:');
define('RATEPAY_RATE_PDF_HR', 'HR:');
define('RATEPAY_RATE_PDF_UST', 'USt.-ID-Nr.:');
define('RATEPAY_RATE_PDF_BULL', ' &bull; ');
define('RATEPAY_RATE_PDF_ACCOUNTHOLDER', 'Kontoinhaber:');
define('RATEPAY_RATE_PDF_BANKNAME', 'Bank:');
define('RATEPAY_RATE_PDF_BANKCODENUMBER', 'Bankleitzahl:');
define('RATEPAY_RATE_PDF_ACCOUNTNUMBER', 'Kontonummer:');
define('RATEPAY_RATE_PDF_SWIFTBIC', 'SWIFT-BIC:');
define('RATEPAY_RATE_PDF_IBAN', 'IBAN:');
define('RATEPAY_RATE_PDF_INFO', 'Ihren Ratenplan und alle Informationen zur Zahlung erhalten Sie <u>gesondert per E-Mail.</u>');
define('RATEPAY_RATE_PDF_PAYTRANSFER', 'Bitte nutzen Sie dazu die dafür eingerichtete Kontoverbindung des Händlers:');
define('RATEPAY_RATE_PDF_REFERENCE', 'Verwendungszweck:');
define('RATEPAY_RATE_PDF_ADDITIONALINFO_1', 'Die Zahlungsabwicklung erfolgt durch die RatePAY GmbH. Der Verkäufer hat die fällige ');
define('RATEPAY_RATE_PDF_ADDITIONALINFO_2', 'Kaufpreisforderung aus Ihrer Bestellung einschlie&szlig;lich etwaiger Nebenforderungen an die ');
define('RATEPAY_RATE_PDF_ADDITIONALINFO_3', ' abgetreten.');
define('RATEPAY_RATE_PDF_ADDITIONALINFO_4', 'Forderungsinhaber ist damit die ');
define('RATEPAY_RATE_PDF_ADDITIONALINFO_5', '. Eine schuldbefreiende Leistung durch Zahlung ist gemä&szlig; &sect; 407 Bürgerliches ');
define('RATEPAY_RATE_PDF_ADDITIONALINFO_6', 'Gesetzbuch durch Sie nur an die ');
define('RATEPAY_RATE_PDF_ADDITIONALINFO_7', ' möglich.');
define('RATEPAY_RATE_PDF_ABOVEARTICLE', 'Für Ihren Kauf auf Rate berechnen wir Ihnen folgende Artikel:');
define('RATEPAY_RATE_PDF_DESCRIPTOR', 'RatePAY-Order:');
// Invoice end