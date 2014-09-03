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
 * PaymentInfo model
 */
class PaymentInfo
{

    /**
     * Payment method
     * 
     * @var string
     */
    private $_method;

    /**
     * Currency
     * 
     * @var string
     */
    private $_currency;

    /**
     * Amount
     * 
     * @var string
     */
    private $_amount;

    /**
     * Debit type
     * 
     * @var string
     */
    private $_debitType;

    /**
     * Installment number
     * 
     * @var string
     */
    private $_installmentNumber;

    /**
     * Installment amount
     * 
     * @var string
     */
    private $_installmentAmount;

    /**
     * Last installment amount
     * 
     * @var string
     */
    private $_lastInstallmentAmount;

    /**
     * Interest rate
     * 
     * @var string
     */
    private $_interestRate;

    /**
     * Payment first day
     * 
     * @var string
     */
    private $_paymentFirstDay;

    /**
     * Get method
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Set method
     * 
     * @param string $method
     * @return PaymentInfo
     */
    public function setMethod($method)
    {
        $this->_method = $method;

        return $this;
    }

    /**
     * Get currency
     * 
     * @return string
     */
    public function getCurrency()
    {
        return $this->_currency;
    }

    /**
     * Set currency
     * 
     * @param string $currency
     * @return PaymentInfo
     */
    public function setCurrency($currency)
    {
        $this->_currency = $currency;

        return $this;
    }

    /**
     * Get amount
     * 
     * @return string
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Set amount
     * 
     * @param string $amount
     * @return PaymentInfo
     */
    public function setAmount($amount)
    {
        $this->_amount = $amount;

        return $this;
    }

    /**
     * Get debit type
     * 
     * @return string
     */
    public function getDebitType()
    {
        return $this->_debitType;
    }

    /**
     * Set debit type
     * 
     * @param string $debitType
     * @return PaymentInfo
     */
    public function setDebitType($debitType)
    {
        $this->_debitType = $debitType;

        return $this;
    }

    /**
     * Get installment number
     * 
     * @return string
     */
    public function getInstallmentNumber()
    {
        return $this->_installmentNumber;
    }

    /**
     * Set installment number
     * 
     * @param string $installmentNumber
     * @return PaymentInfo
     */
    public function setInstallmentNumber($installmentNumber)
    {
        $this->_installmentNumber = $installmentNumber;

        return $this;
    }

    /**
     * Get installment amount
     * 
     * @return string
     */
    public function getInstallmentAmount()
    {
        return $this->_installmentAmount;
    }

    /**
     * Set installment amount
     * 
     * @param string $installmentAmount
     * @return PaymentInfo
     */
    public function setInstallmentAmount($installmentAmount)
    {
        $this->_installmentAmount = $installmentAmount;

        return $this;
    }

    /**
     * Get last installment amount
     * 
     * @return string
     */
    public function getLastInstallmentAmount()
    {
        return $this->_lastInstallmentAmount;
    }

    /**
     * Set installment amount
     * 
     * @param string $lastInstallmentAmount
     * @return PaymentInfo
     */
    public function setLastInstallmentAmount($lastInstallmentAmount)
    {
        $this->_lastInstallmentAmount = $lastInstallmentAmount;

        return $this;
    }

    /**
     * Get interest rate
     * 
     * @return string
     */
    public function getInterestRate()
    {
        return $this->_interestRate;
    }

    /**
     * Set interest rate
     * 
     * @param string $interestRate
     * @return PaymentInfo
     */
    public function setInterestRate($interestRate)
    {
        $this->_interestRate = $interestRate;

        return $this;
    }

    /**
     * Get payment first day
     * 
     * @return string
     */
    public function getPaymentFirstDay()
    {
        return $this->_paymentFirstDay;
    }

    /**
     * Set payment first day
     * 
     * @param string $paymentFirstDay
     * @return PaymentInfo
     */
    public function setPaymentFirstDay($paymentFirstDay)
    {
        $this->_paymentFirstDay = $paymentFirstDay;

        return $this;
    }

    /**
     * Get model data as array
     * @return string
     */
    public function getData()
    {
        $data = array(
            'method'                => $this->_method,
            'currency'              => $this->_currency,
            'amount'                => $this->_amount,
            'debitType'             => isset($this->_debitType) ? $this->_debitType : null,
            'installmentNumber'     => isset($this->_installmentNumber) ? $this->_installmentNumber : null,
            'installmentAmount'     => isset($this->_installmentAmount) ? $this->_installmentAmount : null,
            'lastInstallmentAmount' => isset($this->_lastInstallmentAmount) ? $this->_lastInstallmentAmount : null,
            'interestRate'          => isset($this->_interestRate) ? $this->_interestRate : null,
            'paymentFirstDay'       => isset($this->_paymentFirstDay) ? $this->_paymentFirstDay : null
        );

        return $data;
    }

}
