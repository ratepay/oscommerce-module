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
 * Delete logging script
 */
require_once('includes/application_top.php');
require_once ('../includes/classes/ratepay/helpers/Data.php');
require_once ('../includes/classes/ratepay/helpers/Globals.php');
if (Globals::hasPostEntry('submit')) {
    $days = Globals::getPostEntry('days');
    if (preg_match("/^[0-9]{1,2}$/", $days)) {
        if ($days == 0) {
            tep_db_query("delete from ratepay_log");
        } else {
            tep_db_query("DELETE FROM ratepay_log WHERE TO_DAYS(now()) - TO_DAYS(date) > " . (int) $days);
        }
    }
}

tep_redirect(tep_href_link('ratepay_logging.php', 'success=1', 'SSL'));