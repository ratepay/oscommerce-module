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

require_once('Data.php');

/**
 * Description of Loader
 */
class Loader
{
    public static function getRatepayPayment($paymentCode)
    {
        if (Data::isRatepayPayment($paymentCode)) {
            require_once(dirname(__FILE__) . '/../../../modules/payment/' . $paymentCode . '.php');
            return new $paymentCode();
        }
        
        trigger_error('Not a RatePAY payment! This loader is only for RatePAY payments.');
    }
}