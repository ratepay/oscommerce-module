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
 * CustomerInfo model
 */
class CustomerInfo
{

    /**
     * First name
     * 
     * @var string
     */
    private $_firstName;

    /**
     * Last name
     * 
     * @var string
     */
    private $_lastName;

    /**
     * Gender
     * 
     * @var string
     */
    private $_gender;

    /**
     * Date of birth
     * 
     * @var string
     */
    private $_dateOfBirth;

    /**
     * IP
     * 
     * @var string
     */
    private $_ip;

    /**
     * Company
     * 
     * @var string
     */
    private $_company;

    /**
     * Vat id
     * 
     * @var string
     */
    private $_vatId;

    /**
     * E-Mail
     * 
     * @var string
     */
    private $_email;

    /**
     * Phone
     * 
     * @var string
     */
    private $_phone;

    /**
     * Fax
     * 
     * @var string
     */
    private $_fax;

    /**
     * Billing address info
     * 
     * @var AddressInfo
     */
    private $_billingAddressInfo;

    /**
     * Shipping address info
     * 
     * @var AddressInfo
     */
    private $_shippingAddressInfo;

    /**
     * Shipping address info
     *
     * @var BankInfo
     */
    private $_bankAccount;

    /**
     * Nationality
     * 
     * @var string
     */
    private $_nationality;

    /**
     * Credit inquiry
     * 
     * @var string
     */
    private $_creditInquiry;

    /**
     * Bank Info
     *
     * @var BankInfo
     */
    private $_bankInfo;

    /**
     * Get first name
     * 
     * @return string
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * Set first name
     * 
     * @param string $firstName
     * @return CustomerInfo
     */
    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;

        return $this;
    }

    /**
     * Get last name
     * 
     * @return string
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * Set last name
     * 
     * @param string $lastName
     * @return CustomerInfo
     */
    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;

        return $this;
    }

    /**
     * Get gender
     * 
     * @return string
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * Set gender
     * 
     * @param string $gender
     * @return CustomerInfo
     */
    public function setGender($gender)
    {
        $this->_gender = empty($gender) ? 'U' : strtoupper($gender);

        return $this;
    }

    /**
     * Retrieve date of birth
     * 
     * @return string
     */
    public function getDateOfBirth()
    {
        return $this->_dateOfBirth;
    }

    /**
     * Set date of birth
     * 
     * @param string $dateOfBirth
     * @return CustomerInfo
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->_dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get ip
     * 
     * @return string
     */
    public function getIp()
    {
        return $this->_ip;
    }

    /**
     * Set ip
     * 
     * @param string $ip
     * @return CustomerInfo
     */
    public function setIp($ip)
    {
        $this->_ip = $ip;

        return $this;
    }

    /**
     * Get company
     * 
     * @return string
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Set company
     * 
     * @param string $company
     * @return CustomerInfo
     */
    public function setCompany($company)
    {
        $this->_company = $company;

        return $this;
    }

    /**
     * Get vat id
     * 
     * @return string
     */
    public function getVatId()
    {
        return $this->_vatId;
    }

    /**
     * Set vat id
     * 
     * @param string $vatId
     * @return CustomerInfo
     */
    public function setVatId($vatId)
    {
        $this->_vatId = $vatId;

        return $this;
    }

    /**
     * Get e-mail address
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Set e-mail address
     * 
     * @param string $email
     * @return CustomerInfo
     */
    public function setEmail($email)
    {
        $this->_email = $email;

        return $this;
    }

    /**
     * Get phone number
     * 
     * @return string
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Set phone number
     * 
     * @param string $phone
     * @return CustomerInfo
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;

        return $this;
    }

    /**
     * Get fax number
     * 
     * @return string
     */
    public function getFax()
    {
        return $this->_fax;
    }

    /**
     * Set fax number
     * 
     * @param string $fax
     * @return CustomerInfo
     */
    public function setFax($fax)
    {
        $this->_fax = $fax;

        return $this;
    }

    /**
     * Get billing address
     * 
     * @return AddressInfo
     */
    public function getBillingAddressInfo()
    {
        return $this->_billingAddressInfo;
    }

    /**
     * Set billing address
     * 
     * @param AddressInfo $billingAddressInfo
     * @return CustomerInfo
     */
    public function setBillingAddressInfo(AddressInfo $billingAddressInfo)
    {
        $this->_billingAddressInfo = $billingAddressInfo;

        return $this;
    }

    /**
     * Get shipping address
     * 
     * @return AddressInfo
     */
    public function getShippingAddressInfo()
    {
        return $this->_shippingAddressInfo;
    }

    /**
     * Set shipping address
     * 
     * @param AddressInfo $shippingAddressInfo
     * @return CustomerInfo
     */
    public function setShippingAddressInfo(AddressInfo $shippingAddressInfo)
    {
        $this->_shippingAddressInfo = $shippingAddressInfo;

        return $this;
    }

    /**
     * Get bank account
     *
     * @return BankInfo
     */
    public function getBankAccount()
    {
        return $this->_bankAccount;
    }

    /**
     * Set bank account
     *
     * @param BankInfo $bankAccount
     * @return CustomerInfo
     */
    public function setBankAccount(BankInfo $bankAccount)
    {
        $this->_bankAccount = $bankAccount;

        return $this;
    }

    /**
     * Get nationality
     * 
     * @return string
     */
    public function getNationality()
    {
        return $this->_nationality;
    }

    /**
     * Set nationality
     * 
     * @param string $nationality
     * @return CustomerInfo
     */
    public function setNationality($nationality)
    {
        $this->_nationality = $nationality;

        return $this;
    }

    /**
     * Get credit inquiry
     * 
     * @return string
     */
    public function getCreditInquiry()
    {
        return $this->_creditInquiry;
    }

    /**
     * Set credit inquiry
     * 
     * @param string $creditInquiry
     * @return CustomerInfo
     */
    public function setCreditInquiry($creditInquiry)
    {
        $this->_creditInquiry = $creditInquiry;

        return $this;
    }

    /**
     * Get bank info
     *
     * @return BankInfo
     */
    public function getBankInfo()
    {
        return $this->_bankInfo;
    }

    /**
     * Set bank info
     *
     * @param BankInfo $bankInfo
     * @return CustomerInfo
     */
    public function setBankInfo(BankInfo $bankInfo)
    {
        $this->_bankInfo = $bankInfo;

        return $this;
    }

    /**
     * Get model data as array
     * 
     * @return array
     */
    public function getData()
    {
        $data = array(
            'firstName'     => $this->_firstName,
            'lastName'      => $this->_lastName,
            'gender'        => $this->_gender,
            'dob'           => $this->_dateOfBirth,
            'ip'            => $this->_ip,
            'company'       => isset($this->_company) ? $this->_company : null,
            'vatId'         => isset($this->_vatId)   ? $this->_vatId   : null,
            'email'         => $this->_email,
            'fax'           => isset($this->_fax) ? $this->_fax : null,
            'phone'         => $this->_phone,
            'billing'       => $this->_billingAddressInfo->getData(),
            'shipping'      => $this->_shippingAddressInfo->getData(),
            'nationality'   => $this->_nationality,
            'creditInquiry' => $this->_creditInquiry,
            'bankAccount'   => (isset($this->_bankAccount)) ? $this->_bankAccount->getData() : null
        );

        return $data;
    }

}
