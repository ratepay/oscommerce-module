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
 * BasketInfo model
 */
class BasketInfo
{

    /**
     * Amount
     * 
     * @var string
     */
    private $_amount;

    /**
     * Currency
     * 
     * @var string
     */
    private $_currency;

    /**
     * Items
     * 
     * @var array
     */
    private $_items = array();

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
     * @return BasketInfo
     */
    public function setAmount($amount)
    {
        $this->_amount = $amount;

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
     * @return BasketInfo
     */
    public function setCurrency($currency)
    {
        $this->_currency = $currency;

        return $this;
    }

    /**
     * Get items
     * 
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * Set items
     * 
     * @param array $items
     * @return BasketInfo
     */
    public function setItems(array $items)
    {
        $this->_items = $items;

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
            'amount'   => $this->_amount,
            'currency' => $this->_currency,
            'items'    => $this->_items
        );

        return $data;
    }

}
