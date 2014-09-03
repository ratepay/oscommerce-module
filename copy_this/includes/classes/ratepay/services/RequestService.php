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

require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Data.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/services/CommunicationService.php');

/**
 * RequestService class, build the xml requests and parse the responses
 */
class RequestService
{

    /**
     * Xml response instance
     * 
     * @var SimpleXMLElement
     */
    private $_response = null;

    /**
     * Xml request instance
     * 
     * @var SimpleXMLElement
     */
    private $_request = null;

    /**
     * Error string
     * 
     * @var string
     */
    private $_error = '';

    /**
     * RatePAY gateway operation
     * 
     * @var string
     */
    private $_operation;

    /**
     * List of all request data objects
     * 
     * @var array
     */
    private $_requestDataObjects = array();
    
    /**
     * Sandbox on/off
     * 
     * @var boolean
     */
    private $_sandbox;
    

    /**
     * Construct
     * 
     * @param array $data
     */
    public function __construct($sandbox, array $data)
    {
        $this->_requestDataObjects = $data;
        $this->_sandbox = $sandbox;
    }

    /**
     * Returns the Request
     * 
     * @return SimpleXML
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Get response
     * 
     * @return SimpleXML
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * RatePAY gateway operation setter
     * 
     * @param string $operation
     */
    private function _setOperation($operation)
    {
        $this->_operation = $operation;

        return $this;
    }

    /**
     * Retrieve the gateway operation
     * 
     * @return string
     */
    private function _getOperation()
    {
        return $this->_operation;
    }

    /**
     * Retrieve error
     * 
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * Validates the Response
     * 
     * @return array
     */
    public function validateResponse()
    {
        $statusCode = '';
        $resultCode = '';
        if (!empty($this->_response)) {
            $statusCode = (string) $this->_response->head->processing->status->attributes()->code;
            $resultCode = (string) $this->_response->head->processing->result->attributes()->code;
            $reasonCode = (string) $this->_response->head->processing->reason->attributes()->code;
            $reasonMessage = (string) $this->_response->head->processing->reason;
        }

        switch ($this->_getOperation()) {
            case 'PAYMENT_INIT':
                if ($statusCode == "OK" && $resultCode == "350") {
                    $result = array();
                    $result['transactionId'] = (string) $this->_response->head->{'transaction-id'};
                    $result['transactionShortId'] = (string) $this->_response->head->{'transaction-short-id'};
                    $this->_error = '';
                    return $result;
                } else {
                    return array(
                        'error'      => $reasonMessage,
                        'error_code' => $reasonCode
                    );
                }
                break;
            case 'PAYMENT_REQUEST':
                if ($statusCode == "OK" && $resultCode == "402") {
                    $result = array();
                    $result['descriptor'] = (string) $this->_response->content->payment->descriptor;
                    $this->_error = '';
                    return $result;
                } else {
                    return array(
                        'error'              => 'FAIL',
                        'error_message'      => $reasonMessage,
                        'error_code'         => $reasonCode
                    );
                }
                break;
            case 'PAYMENT_CONFIRM':
                if ($statusCode == "OK" && $resultCode == "400") {
                    $this->_error = '';
                    return array(
                        'status'      => $statusCode,
                        'result_code' => $resultCode
                    );
                } else {
                    return array(
                        'error'      => $reasonMessage,
                        'error_code' => $reasonCode
                    );
                }
                break;
            case 'CONFIGURATION_REQUEST':
                if ($statusCode == "OK" && $resultCode == "500") {
                    $this->_error = '';
                    return $this->_getConfigResult();
                } else {
                    return array(
                        'error'       => $reasonMessage,
                        'error_code'  => $reasonCode
                    );
                }
                break;
            case 'CALCULATION_REQUEST':
                $successCodes = array('603', '671', '688', '689', '695', '696', '697', '698', '699');
                if ($statusCode == "OK" && in_array($reasonCode, $successCodes) && $resultCode == "502") {
                    return $this->_getCalculationResult($reasonCode);
                } else {
                    return array(
                        'error'      => $reasonMessage,
                        'error_code' => $reasonCode
                    );
                }
            case 'CONFIRMATION_DELIVER':
                if($statusCode == "OK" && $resultCode == "404") {
                    $this->_error = '';
                    return array(
                        'status'      => $statusCode,
                        'result_code' => $resultCode
                    );
                } else {
                    return array(
                        'error'              => 'FAIL',
                        'error_message'      => $reasonMessage,
                        'error_code'         => $reasonCode
                    );
                }
                break;
            case 'PAYMENT_CHANGE':
                if($statusCode == "OK" && $resultCode == "403") {
                    $this->_error = '';
                    return array(
                        'status'      => $statusCode,
                        'result_code' => $resultCode
                    );
                } else {
                    return array(
                        'error'              => 'FAIL',
                        'error_message'      => $reasonMessage,
                        'error_code'         => $reasonCode
                    );
                }
                break;
            default:
                return array('error' => 'FAIL');
                break;
        }
    }

    /**
     * Retrieve config result
     * 
     * @return array
     */
    private function _getConfigResult()
    {
        $result = array();
        $result['interestrateMin'] = (string) $this->_response->content->{'installment-configuration-result'}->{'interestrate-min'};
        $result['interestrateDefault'] = (string) $this->_response->content->{'installment-configuration-result'}->{'interestrate-default'};
        $result['interestrateMax'] = (string) $this->_response->content->{'installment-configuration-result'}->{'interestrate-max'};
        $result['monthNumberMin'] = (string) $this->_response->content->{'installment-configuration-result'}->{'month-number-min'};
        $result['monthNumberMax'] = (string) $this->_response->content->{'installment-configuration-result'}->{'month-number-max'};
        $result['monthLongrun'] = (string) $this->_response->content->{'installment-configuration-result'}->{'month-longrun'};
        $result['monthAllowed'] = (string) $this->_response->content->{'installment-configuration-result'}->{'month-allowed'};
        $result['paymentFirstday'] = (string) $this->_response->content->{'installment-configuration-result'}->{'payment-firstday'};
        $result['paymentAmount'] = (string) $this->_response->content->{'installment-configuration-result'}->{'payment-amount'};
        $result['paymentLastrate'] = (string) $this->_response->content->{'installment-configuration-result'}->{'payment-lastrate'};
        $result['rateMinNormal'] = (string) $this->_response->content->{'installment-configuration-result'}->{'rate-min-normal'};
        $result['rateMinLongrun'] = (string) $this->_response->content->{'installment-configuration-result'}->{'rate-min-longrun'};
        $result['serviceCharge'] = (string) $this->_response->content->{'installment-configuration-result'}->{'service-charge'};

        return $result;
    }

    /**
     * Retrieve config result
     * 
     * @return array
     */
    private function _getCalculationResult($reasonCode)
    {
        $result = array();
        $result['totalAmount'] = (string) $this->_response->content->{'installment-calculation-result'}->{'total-amount'};
        $result['amount'] = (string) $this->_response->content->{'installment-calculation-result'}->{'amount'};
        $result['interestRate'] = (string) $this->_response->content->{'installment-calculation-result'}->{'interest-rate'};
        $result['interestAmount'] = (string) $this->_response->content->{'installment-calculation-result'}->{'interest-amount'};
        $result['serviceCharge'] = (string) $this->_response->content->{'installment-calculation-result'}->{'service-charge'};
        $result['annualPercentageRate'] = (string) $this->_response->content->{'installment-calculation-result'}->{'annual-percentage-rate'};
        $result['monthlyDebitInterest'] = (string) $this->_response->content->{'installment-calculation-result'}->{'monthly-debit-interest'};
        $result['numberOfRatesFull'] = (string) $this->_response->content->{'installment-calculation-result'}->{'number-of-rates'};
        $result['numberOfRates'] = $result['numberOfRatesFull'] - 1;
        $result['rate'] = (string) $this->_response->content->{'installment-calculation-result'}->{'rate'};
        $result['lastRate'] = (string) $this->_response->content->{'installment-calculation-result'}->{'last-rate'};
        $result['debitSelect'] = (string) $this->_response->content->{'installment-calculation-result'}->{'payment-firstday'};
        $result['code'] = $reasonCode;

        return $result;
    }

    /**
     * Validate if HeadInfo instance is initialized
     * 
     * @return RequestService
     */
    private function _validateHeadInfo()
    {
        if (!array_key_exists('HeadInfo', $this->_requestDataObjects)) {
            trigger_error('Missing instance of HeadInfo for ' . $this->_getOperation() . ' call!');
        }
        return $this;
    }

    /**
     * Validate if CustomerInfo instance is initialized
     * 
     * @return RequestService
     */
    private function _validateCustomerInfo()
    {
        if (!array_key_exists('CustomerInfo', $this->_requestDataObjects)) {
            trigger_error('Missing instance of CustomerInfo for ' . $this->_getOperation() . ' call!');
        }

        return $this;
    }

    /**
     * Validate if BasketInfo instance is initialized
     * 
     * @return RequestService
     */
    private function _validateBasketInfo()
    {
        if (!array_key_exists('BasketInfo', $this->_requestDataObjects)) {
            trigger_error('Missing instance of BasketInfo for ' . $this->_getOperation() . ' call!');
        }

        return $this;
    }

    /**
     * Validate if PaymentInfo instance is initialized
     * @return RequestService
     */
    private function _validatePaymentInfo()
    {
        if (!array_key_exists('PaymentInfo', $this->_requestDataObjects)) {
            trigger_error('Missing instance of PaymentInfo for ' . $this->_getOperation() . ' call!');
        }

        return $this;
    }

    /**
     * Validate if CalculationInfo instance is initialized
     * 
     * @return RequestService
     */
    private function _validateCalculationInfo()
    {
        if (!array_key_exists('CalculationInfo', $this->_requestDataObjects)) {
            trigger_error('Missing instance of CalculationInfo for ' . $this->_getOperation() . ' call!');
        }

        return $this;
    }

    /**
     * Calls the PAYMENT_INIT
     * 
     * @return array
     */
    public function callPaymentInit()
    {
        $this->_validateHeadInfo();
        $this->_setOperation('PAYMENT_INIT')->constructXml()->_setRequestHead()->_sendXmlRequest();
        return $this->validateResponse();
    }

    /**
     * Calls the PAYMENT_REQUEST
     * 
     * @return array
     */
    public function callPaymentRequest()
    {
        $this->_validateHeadInfo()->_validateCustomerInfo()->_validateBasketInfo()->_validatePaymentInfo();
        $this->_setOperation('PAYMENT_REQUEST')->constructXml()->_setRequestHead()->_setRequestContent()->_sendXmlRequest();
        return $this->validateResponse();
    }

    /**
     * Calls the PAYMENT_CONFIRM
     * 
     * @return array
     */
    public function callPaymentConfirm()
    {
        $this->_validateHeadInfo();
        $this->_setOperation('PAYMENT_CONFIRM')->constructXml()->_setRequestHead()->_sendXmlRequest();
        return $this->validateResponse();
    }

    /**
     * Calls the CONFIGURATION_REQUEST
     * 
     * @return array
     */
    public function callConfigurationRequest()
    {
        $this->_validateHeadInfo();
        $this->_setOperation('CONFIGURATION_REQUEST')->constructXml()->_setRequestHead()->_sendXmlRequest();
        return $this->validateResponse();
    }

    /**
     * Calls the CALCULATION_REQUEST
     * 
     * @return array
     */
    public function callCalculationRequest()
    {
        $this->_validateHeadInfo()->_validateCalculationInfo();
        $this->_setOperation('CALCULATION_REQUEST')->constructXml()->_setRequestHead()->_setRatepayContentCalculation()->_sendXmlRequest();
        return $this->validateResponse();
    }
    
    /**
     * Calls the CONFIRMATION_DELIVER
     * 
     * @return boolean
     */
    public function callConfirmationDeliver()
    {
        $this->_validateHeadInfo()->_validateBasketInfo();
        $this->_setOperation('CONFIRMATION_DELIVER')->constructXml()->_setRequestHead()->_setRequestContent()->_sendXmlRequest();
        return $this->validateResponse();
    }
    
    /**
     * Calls the CONFIRMATION_DELIVER
     * 
     * @return boolean
     */
    public function callPaymentChange()
    {
        $this->_validateHeadInfo()->_validateBasketInfo()->_validatePaymentInfo()->_validateCustomerInfo();
        $this->_setOperation('PAYMENT_CHANGE')->constructXml()->_setRequestHead()->_setRequestContent()->_sendXmlRequest();
        return $this->validateResponse();
    }
    
    /**
     * Set calculation head subtype
     * 
     * @param string $method
     */
    private function _setCalculationHeadSubtype($method)
    {
        $operation = $this->_request->head->operation;
        if ($method == 'wishrate') {
            $operation->addAttribute('subtype', 'calculation-by-rate');
        } else if ($method == 'runtime') {
            $operation->addAttribute('subtype', 'calculation-by-time');
        }
    }

    /**
     * Sets the head tag with all informations based on the request type.
     * 
     * @return RequestService
     */
    private function _setRequestHead()
    {
        $head = $this->_request->addChild('head');
        $headInfo = $this->_requestDataObjects['HeadInfo']->getData();

        $head->addChild('system-id', $headInfo['systemId']);
        if (!empty($headInfo['transactionId'])) {
            $head->addChild('transaction-id', $headInfo['transactionId']);
        }
        if (!empty($headInfo['transactionShortId'])) {
            $head->addChild('transaction-short-id', $headInfo['transactionShortId']);
        }

        $operation = $head->addChild('operation', $this->_getOperation());
        if (!empty($headInfo['subtype'])) {
            $operation->addAttribute('subtype', $headInfo['subtype']);
        }

        $credential = $head->addChild('credential');
        $credential->addChild('profile-id', $headInfo['profileId']);
        $credential->addChild('securitycode', $headInfo['securityCode']);

        if ($this->_getOperation() != 'PAYMENT_INIT' && $this->_getOperation() != 'PROFILE_REQUEST') {
            if (!empty($headInfo['orderId'])) {
                $external = $head->addChild('external');
                $external->addChild('order-id', $headInfo['orderId']);
            }
        }
        
        $meta = $head->addChild('meta');
        $systems = $meta->addChild('systems');
        $system = $systems->addChild('system');
        $system->addAttribute('name', $headInfo['shopSystem']);
        $system->addAttribute('version', $headInfo['shopVersion'] . '_' . $headInfo['moduleVersion']);
        
        /*if ($this->_getOperation() == "PAYMENT_REQUEST") {
            $this->_setRatepayHeadCustomerDevice();
        }*/

        return $this;
    }
    
    /**
     * Sets the customer device to the head tag of the request
     * 
     * @return RequestService
     */
    private function _setRatepayHeadCustomerDevice()
    {
        $head = $this->_request->head;
        $customerDevice = $head->addChild('customer-device');

        return $this;
    }

    /**
     * Sets the content tag of the request
     * 
     * @return RequestService
     */
    private function _setRequestContent()
    {
        $this->_request->addChild('content');
        if ($this->_getOperation() != 'CONFIRMATION_DELIVER') {
            $this->_setRatepayContentCustomer();
        }

        $this->_setRatepayContentBasket();

        if ($this->_getOperation() != 'CONFIRMATION_DELIVER') {
            $this->_setRatepayContentPayment();
        }

        return $this;
    }

    /**
     * Sets the customer in the content tag of the request.
     * 
     * @return RequestService
     */
    private function _setRatepayContentCustomer()
    {
        $customerInfo = $this->_requestDataObjects['CustomerInfo']->getData();
        $customer = $this->_request->content->addChild('customer');

        $customer->addCDataChild('first-name', Data::removeSpecialChars($customerInfo['firstName']));
        $customer->addCDataChild('last-name', Data::removeSpecialChars($customerInfo['lastName']));
        $customer->addChild('gender', strtoupper($customerInfo['gender']));
        $customer->addChild('date-of-birth', $customerInfo['dob']);
        $customer->addChild('ip-address', $customerInfo['ip']);

        if (!empty($customerInfo['company'])) {
            $customer->addCDataChild('company-name', Data::removeSpecialChars($customerInfo['company']));
        }

        $contacts = $customer->addChild('contacts');
        $contacts->addChild('email', $customerInfo['email']);
        $phone = $contacts->addChild('phone');
        $phone->addChild('direct-dial', $customerInfo['phone']);

        if (!empty($customer['fax'])) {
            $fax = $contacts->addChild('fax');
            $fax->addChild('direct-dial', $customerInfo['fax']);
        }

        $addresses = $customer->addChild('addresses');

        $billingAddress = $addresses->addChild('address');
        $billingAddress->addAttribute('type', $customerInfo['billing']['type']);
        $billingAddress->addCDataChild('street', Data::removeSpecialChars($customerInfo['billing']['street']));
        if (!empty($customerInfo['billing']['streetNumber'])) {
            $billingAddress->addCDataChild('street-number', $customerInfo['billing']['streetNumber']);
        }
        $billingAddress->addChild('zip-code', $customerInfo['billing']['zipCode']);
        $billingAddress->addCDataChild('city', Data::removeSpecialChars($customerInfo['billing']['city']));
        $billingAddress->addChild('country-code', strtoupper($customerInfo['billing']['countryId']));

        $shippingAddress = $addresses->addChild('address');
        $shippingAddress->addAttribute('type', $customerInfo['shipping']['type']);
        $shippingAddress->addCDataChild('street', Data::removeSpecialChars($customerInfo['shipping']['street']));
        if (!empty($customerInfo['shipping']['streetNumber'])) {
            $shippingAddress->addCDataChild('street-number', $customerInfo['shipping']['streetNumber']);
        }
        $shippingAddress->addChild('zip-code', $customerInfo['shipping']['zipCode']);
        $shippingAddress->addCDataChild('city', Data::removeSpecialChars($customerInfo['shipping']['city']));
        $shippingAddress->addChild('country-code', strtoupper($customerInfo['shipping']['countryId']));

        if (!empty($customerInfo['bankAccount']['owner'])) {
            $bankData = $customer->addChild('bank-account');
            $bankData->addCDataChild('owner', Data::removeSpecialChars($customerInfo['bankAccount']['owner']));
            if (!empty($customerInfo['bankAccount']['bank-account-number']) && empty($customerInfo['bankAccount']['iban'])) {
                $bankData->addChild('bank-account-number', $customerInfo['bankAccount']['bank-account-number']);
                $bankData->addChild('bank-code', $customerInfo['bankAccount']['bank-code']);
            }
            $bankData->addCDataChild('bank-name', Data::removeSpecialChars($customerInfo['bankAccount']['bank-name']));
            if (!empty($customerInfo['bankAccount']['iban'])) {
                $bankData->addChild('iban', $customerInfo['bankAccount']['iban']);
                if (!empty($customerInfo['bankAccount']['bic-swift'])) {
                    $bankData->addChild('bic-swift', $customerInfo['bankAccount']['bic-swift']);
                }
            }
        }

        $customer->addChild('nationality', $customerInfo['nationality']);
        $customer->addChild('customer-allow-credit-inquiry', $customerInfo['creditInquiry']);
        
        if (!empty($customerInfo['company'])) {
            $customer->addChild('vat-id', $customerInfo['vatId']);
        }
        
        return $this;
    }

    /**
     * Sets the shopping basket in the content tag of the request.
     * 
     * @return RequestService
     */
    private function _setRatepayContentBasket()
    {
        $basketInfo = $this->_requestDataObjects['BasketInfo']->getData();
        $shoppingBasket = $this->_request->content->addChild('shopping-basket');
        $shoppingBasket->addAttribute('amount', number_format($basketInfo['amount'], 2, ".", ""));
        $shoppingBasket->addAttribute('currency', strtoupper($basketInfo['currency']));

        $items = $shoppingBasket->addChild('items');

        foreach ($basketInfo['items'] as $itemInfoObject) {
            $itemInfo = $itemInfoObject->getData();
            $item = $items->addCDataChild('item', Data::removeSpecialChars($itemInfo['articleName']));
            $item->addAttribute('article-number', Data::removeSpecialChars($itemInfo['articleNumber']));
            $item->addAttribute('quantity', number_format($itemInfo['quantity'], 0, '.', ''));
            $item->addAttribute('unit-price', number_format(round($itemInfo['unitPrice'], 2), 2, ".", ""));
            $item->addAttribute('total-price', number_format(round($itemInfo['totalPrice'], 2), 2, ".", ""));
            $item->addAttribute('tax', number_format(round($itemInfo['tax'], 2), 2, ".", ""));
        }

        return $this;
    }

    /**
     * Set the payment data in the content tag of the request.
     * 
     * @return RequestService
     */
    private function _setRatepayContentPayment()
    {
        $paymentInfo = $this->_requestDataObjects['PaymentInfo']->getData();
        $payment = $this->_request->content->addChild('payment');
        $payment->addAttribute('method', strtoupper($paymentInfo['method']));
        $payment->addAttribute('currency', strtoupper($paymentInfo['currency']));
        $payment->addChild('amount', number_format($paymentInfo['amount'], 2, ".", ""));
        if (!empty($paymentInfo['debitType'])) {
            if (!empty($paymentInfo['installmentNumber'])) {
                $installment = $payment->addChild('installment-details');
                $installment->addChild('installment-number', $paymentInfo['installmentNumber']);
                $installment->addChild('installment-amount', $paymentInfo['installmentAmount']);
                $installment->addChild('last-installment-amount', $paymentInfo['lastInstallmentAmount']);
                $installment->addChild('interest-rate', $paymentInfo['interestRate']);
                $installment->addChild('payment-firstday', $paymentInfo['paymentFirstDay']);
            }
            $payment->addChild('debit-pay-type', $paymentInfo['debitType']);
        }

        return $this;
    }

    /**
     * Set the installment calculation tag of the request.
     * 
     * @return RequestService
     */
    private function _setRatepayContentCalculation()
    {
        $calculation = $this->_requestDataObjects['CalculationInfo']->getData();
        $content = $this->_request->addChild('content');
        $installment = $content->addChild('installment-calculation');

        $installment->addChild('amount', $calculation['amount']);
        $this->_setCalculationHeadSubtype($calculation['method']);
        if ($calculation['method'] == 'wishrate') {
            $calcRate = $installment->addChild('calculation-rate');
            $calcRate->addChild('rate', $calculation['value']);
        } else if ($calculation['method'] == 'runtime') {
            $calcTime = $installment->addChild('calculation-time');
            $calcTime->addChild('month', $calculation['value']);
        }

        if (!empty($calculation['debitSelect'])) {
            $installment->addChild('payment-firstday', $calculation['debitSelect']);
        }

        return $this;
    }

    /**
     * Sending request to the RatePAY Server and returning the response.
     * 
     * @return SimpleXMLElement
     */
    private function _sendXmlRequest()
    {
        $client = new CommunicationService();
        $response = $client->send($this->_sandbox, $this->getRequest()->asXML());
        if ($response) {
            $this->_response = new SimpleXMLElement($response);
        }
        return $this->_response;
    }

    /**
     * Create SimpleXML object for request creation.
     * 
     * @return RequestService
     */
    public function constructXml()
    {
        require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/SimpleXmlExtended.php');
        $xmlString = '<request version="1.0" xmlns="urn://www.ratepay.com/payment/1_0"></request>';
        $this->_request = null;
        $this->_request = new SimpleXmlExtended($xmlString);

        return $this;
    }

}
