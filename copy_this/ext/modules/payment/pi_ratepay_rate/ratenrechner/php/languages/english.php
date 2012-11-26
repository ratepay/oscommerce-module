<?php
/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */
$pi_lang_insert_wishrate 				= 'Enter the desired installment';
$pi_lang_insert_runtime					= 'Choose term';
$pi_lang_calculate_runtime 				= 'Calculate term';
$pi_lang_choose_runtime 				= 'Choose term';
$pi_lang_calculate_rate 				= 'Calculate installment';
$pi_lang_or								= 'or';
$pi_lang_hint_rate_1					= 'Enter your desired installment value';
$pi_lang_hint_rate_2					= 'and view the resulting conditions.';
$pi_lang_hint_runtime_1					= 'Enter your preferred term';
$pi_lang_hint_runtime_2					= 'and view the resulting conditions.';
$pi_lang_please							= 'Please ';
$pi_lang_months							= ' Months';
$pi_lang_total_amount					= 'Total';
$pi_lang_cash_payment_price 			= 'Cash price';
$pi_lang_interest_amount				= 'Interest Amount';
$pi_lang_service_charge 				= 'Upfront payment';
$pi_lang_effective_rate 				= 'Annual percentage rate';
$pi_lang_debit_rate 					= 'Borrowing rate per month';
$pi_lang_duration_time					= 'term';
$pi_lang_duration_month					= ' monthly installments of';
$pi_lang_last_rate						= 'plus a final installment of';
$pi_lang_server_off						= 'The RatePAY servers are currently unavailable. Please try again later.';
$pi_lang_config_error_else				= 'An error has occurred. Please contact the store owner immediately.';
$pi_lang_request_error_else				= 'An error has occurred. Please contact the store owner.';
$pi_lang_wrong_value					= 'Wrong entry. Please modify your entry.';
$pi_lang_information					= 'Information';
$pi_lang_error							= 'Error';
$pi_lang_info 							= array();
$pi_lang_info['603']					= 'The requested installment corresponds to the specified conditions.';
$pi_lang_info['671']					= 'The last installment was lower than allowed. Term and / or installment were adjusted.';
$pi_lang_info['688']					= 'The installment was lower than allowed for long term installment plans. The monthly installment was increased.';
$pi_lang_info['689']					= 'The installment was lower than allowed for short term installment plans. The monthly installment was increased.';
$pi_lang_info['695']					= 'Rate was too high to match shortest installment runtime - rate has been decreased.';
$pi_lang_info['696']					= 'Rate too low to match shortest installment runtime - rate has been increased.';
$pi_lang_info['697']					= 'There is no corresponding term for your selected amount of installments. The amount of installments was adjusted.';
$pi_lang_info['698']					= 'The installment was too low for the maximum available term. The installment was increased.';
$pi_lang_info['699']					= 'The installment was too high for the minimum available term. The installment was reduced.';
$pi_lang_individual_rate_calculation	= 'Individual Rate Calculation';
?>