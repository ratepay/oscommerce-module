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
 * AddressInfo model
 */
class BankInfo
{

    /**
     * Owner
     * 
     * @var string
     */
    private $_owner;

    /**
     * iban
     *
     * @var string
     */
    private $_iban;

    /**
     * bank-account-number
     * 
     * @var int
     */
    private $_accountNumber;

    /**
     * bis-swift
     *
     * @var string
     */
    private $_bic;

    /**
     * bank-code
     * 
     * @var int
     */
    private $_bankCode;

    /**
     * bank-name
     * 
     * @var string
     */
    private $_bankName;

    /**
     * Set owner
     * 
     * @param string $owner
     * @return AddressInfo
     */
    public function setOwner($owner)
    {
        $this->_owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->_owner;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->_iban;
    }

    /**
     * Set iban
     *
     * @param string $iban
     * @return BankInfo
     */
    public function setIban($iban)
    {
        $this->_iban = $iban;

        return $this;
    }

    /**
     * Get bank-account-number
     * 
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->_accountNumber;
    }

    /**
     * Set bank-account-number
     * 
     * @param string $accountNumber
     * @return BankInfo
     */
    public function setAccountNumber($accountNumber)
    {
        $this->_accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get bic-swift
     *
     * @return string
     */
    public function getBic()
    {
        return $this->_bic;
    }

    /**
     * Set bic-swift
     *
     * @param string $bic
     * @return BankInfo
     */
    public function setBic($bic)
    {
        $this->_bic = $bic;

        return $this;
    }

    /**
     * Get bank-code
     * 
     * @return string
     */
    public function getBankCode()
    {
        return $this->_bankCode;
    }

    /**
     * Set bank-code
     * 
     * @param string $bankCode
     * @return BankInfo
     */
    public function setBankCode($bankCode)
    {
        $this->_bankCode = $bankCode;

        return $this;
    }

    /**
     * Get bank-name
     * 
     * @return string
     */
    public function getBankName()
    {
        return $this->_bankName;
    }

    /**
     * Set bank-name
     * 
     * @param string $bankName
     * @return BankInfo
     */
    public function setBankName($bankName)
    {
        $this->_bankName = $bankName;

        return $this;
    }

    /**
     * Get model data as array
     * 
     * @return array
     */
    public function getData()
    {
        $data = array();
        $data['owner'] = $this->_owner;
        if (!empty($this->_iban)) {
            $data['iban'] = $this->_iban;
            if (!empty($this->_bic)) {
                $data['bic-swift'] = $this->_bic;
            }
        } else {
            $data['bank-account-number'] = $this->_accountNumber;
            $data['bank-code'] = $this->_bankCode;
        }
        $data['bank-name'] = $this->_bankName;

        return $data;
    }

}
