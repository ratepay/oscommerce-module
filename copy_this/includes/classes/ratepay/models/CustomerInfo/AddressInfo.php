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
class AddressInfo
{

    /**
     * Type
     * 
     * @var string
     */
    private $_type;

    /**
     * Street
     * 
     * @var string
     */
    private $_street;

    /**
     * Street number
     * 
     * @var string
     */
    private $_streetNumber;

    /**
     * Zip
     * 
     * @var string
     */
    private $_zip;

    /**
     * City
     * 
     * @var string
     */
    private $_city;

    /**
     * Country ID
     * 
     * @var string
     */
    private $_countryId;

    /**
     * Get type
     * 
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set type
     * 
     * @param string $type
     * @return AddressInfo
     */
    public function setType($type)
    {
        $this->_type = $type;

        return $this;
    }

    /**
     * Get street
     * 
     * @return string
     */
    public function getStreet()
    {
        return $this->_street;
    }

    /**
     * Set street
     * 
     * @param string $street
     * @return AddressInfo
     */
    public function setStreet($street)
    {
        $this->_street = $street;

        return $this;
    }

    /**
     * Get street number
     * 
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->_streetNumber;
    }

    /**
     * Set street number
     * 
     * @param string $streetNumber
     * @return AddressInfo
     */
    public function setStreetNumber($streetNumber)
    {
        $this->_streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get zip
     * 
     * @return string
     */
    public function getZip()
    {
        return $this->_zip;
    }

    /**
     * Set zip
     * 
     * @param string $zip
     * @return AddressInfo
     */
    public function setZip($zip)
    {
        $this->_zip = $zip;

        return $this;
    }

    /**
     * Get city
     * 
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * Set city
     * 
     * @param string $city
     * @return AddressInfo
     */
    public function setCity($city)
    {
        $this->_city = $city;

        return $this;
    }

    /**
     * Get country ID
     * 
     * @return string
     */
    public function getCountryId()
    {
        return $this->_countryId;
    }

    /**
     * Set country ID
     * 
     * @param string $countryId
     * @return AddressInfo
     */
    public function setCountryId($countryId)
    {
        $this->_countryId = $countryId;

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
            'type'         => $this->_type,
            'street'       => $this->_street,
            'streetNumber' => !empty($this->_streetNumber) ? $this->_streetNumber : null,
            'zipCode'      => $this->_zip,
            'city'         => $this->_city,
            'countryId'    => $this->_countryId
        );

        return $data;
    }

}
