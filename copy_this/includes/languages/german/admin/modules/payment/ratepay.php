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

// Logging
define('RATEPAY_ADMIN_LOGGING', 'Logging');
define('RATEPAY_ADMIN_LOGGING_ID', 'ID');
define('RATEPAY_ADMIN_LOGGING_ORDER_ID', 'ORDER ID');
define('RATEPAY_ADMIN_LOGGING_TRANSACTION_ID', 'TRANSACTION ID');
define('RATEPAY_ADMIN_LOGGING_PAYMENT_METHOD', 'PAYMENT METHOD');
define('RATEPAY_ADMIN_LOGGING_OPERATION_TYPE', 'OPERATION TYPE');
define('RATEPAY_ADMIN_LOGGING_OPERATION_SUBTYPE', 'OPERATION SUBTYPE');
define('RATEPAY_ADMIN_LOGGING_RESULT', 'RESULT');
define('RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT', 'RATEPAY RESULT');
define('RATEPAY_ADMIN_LOGGING_RATEPAY_RESULT_CODE', 'RATEPAY RESULT CODE');
define('RATEPAY_ADMIN_LOGGING_REQUEST', 'REQUEST');
define('RATEPAY_ADMIN_LOGGING_RESPONSE', 'RESPONSE');
define('RATEPAY_ADMIN_LOGGING_DATE', 'DATE');
define('RATEPAY_ADMIN_LOGGING_DELETE_TEXT_1', 'Alle Einträge die älter als');
define('RATEPAY_ADMIN_LOGGING_DELETE_TEXT_2', 'Tage sind ');
define('RATEPAY_ADMIN_LOGGING_DELETE', 'Löschen');
define('RATEPAY_ADMIN_LOGGING_DELETE_SUCCESS', 'Löschen war erfolgreich.');
define('RATEPAY_ADMIN_LOG_BACK', 'Zurück');
// Logging end
// Ratepay order
define('RATEPAY_ORDER_RATEPAY_NAME', 'RatePAY Rechnung');
define('RATEPAY_ORDER_RATEPAY_ADMIN_DELIVER_CANCEL', 'Lieferung / Stornierung');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RETOUR', 'Retoure');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RETOURE_BUTTON', 'retournieren');
define('RATEPAY_ORDER_RATEPAY_ADMIN_HISTORY', 'Historie');
define('RATEPAY_ORDER_RATEPAY_ADMIN_GOODWILL', 'Gutschrift');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RATE_DETAILS', 'Raten Details');
define('RATEPAY_ORDER_RATEPAY_ADMIN_DELIVERY', 'versenden');
define('RATEPAY_ORDER_RATEPAY_ADMIN_CANCELLATION', 'stornieren');
define('RATEPAY_ORDER_RATEPAY_ADMIN_CREATE_GOODWILL', 'Gutschrift erzeugen');
define('RATEPAY_ORDER_RATEPAY_ADMIN_QTY', 'Anzahl');
define('RATEPAY_ORDER_RATEPAY_ART_ID', 'Art.-Nr.');
define('RATEPAY_ORDER_RATEPAY_ADMIN_PRODUCT_NAME', 'Bezeichnung');
define('RATEPAY_ORDER_RATEPAY_ADMIN_PRICE_NETTO', 'Preis (Netto)');
define('RATEPAY_ORDER_RATEPAY_ADMIN_TAX_AMOUNT', 'Steuerbetrag');
define('RATEPAY_ORDER_RATEPAY_ADMIN_ROW_PRICE', 'Gesamtpreis (Brutto)');
define('RATEPAY_ORDER_RATEPAY_ADMIN_ORDERED', 'Bestellt');
define('RATEPAY_ORDER_RATEPAY_ADMIN_DELIVERED', 'Geliefert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_CANCELED', 'Storniert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_RETURNED', 'Retourniert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_GOODWILL_AMOUNT', 'Wert');
define('RATEPAY_ORDER_RATEPAY_ADMIN_ACTION', 'Action');
define('RATEPAY_ORDER_RATEPAY_ADMIN_DATE', 'Datum');
// Ratepay order end
// Ratepay order messages
define('RATEPAY_ORDER_MESSAGE_DELIVER_SUCCESS', 'Die Lieferung war erfolgreich.');
define('RATEPAY_ORDER_MESSAGE_DELIVER_ERROR', 'Die Lieferung war nicht erfolgreich.');
define('RATEPAY_ORDER_MESSAGE_CANCEL_SUCCESS', 'Die Stornierung war erfolgreich.');
define('RATEPAY_ORDER_MESSAGE_CANCEL_ERROR', 'Die Stornierung war nicht erfolgreich.');
define('RATEPAY_ORDER_MESSAGE_REFUND_SUCCESS', 'Die Retournierung war erfolgreich.');
define('RATEPAY_ORDER_MESSAGE_REFUND_ERROR', 'Die Retournierung war nicht erfolgreich.');
define('RATEPAY_ORDER_MESSAGE_CREDIT_SUCCESS', 'Die Gutschrift war erfolgreich.');
define('RATEPAY_ORDER_MESSAGE_CREDIT_ERROR', 'Die Gutschrift war nicht erfolgreich.');
// Ratepay order messages end
// Rate details
define('RATEPAY_RATE_DETAILS_TOTAL_AMOUNT', 'Gesamtbetrag');
define('RATEPAY_RATE_DETAILS_AMOUNT', 'Barzahlungspreis');
define('RATEPAY_RATE_DETAILS_INTEREST_AMOUNT', 'Zinsbetrag');
define('RATEPAY_RATE_DETAILS_SERVICE_CHARGE', 'Vertragsabschlussgebühr');
define('RATEPAY_RATE_DETAILS_ANNUAL_INTEREST', 'Effektiver Jahreszins');
define('RATEPAY_RATE_DETAILS_MONTHLY_INTEREST', 'Sollzinssatz pro Monat');
define('RATEPAY_RATE_DETAILS_RUNTIME', 'Laufzeit');
define('RATEPAY_RATE_DETAILS_MONTHLY_RATE_A', 'monatliche Raten a');
define('RATEPAY_RATE_DETAILS_AMOUNT_LAST_RATE_A', 'zzgl. einer Abschlussrate a');
define('RATEPAY_RATE_DETAILS_MONTH', 'Monat(e)');
// Rate details end