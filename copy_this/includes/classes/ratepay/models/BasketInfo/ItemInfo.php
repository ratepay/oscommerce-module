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
 * ItemInfo model
 */
class ItemInfo
{

    /**
     * Article name
     * 
     * @var string
     */
    private $_articleName;

    /**
     * Article number
     * 
     * @var string
     */
    private $_articleNumber;

    /**
     * Quantity
     * 
     * @var string
     */
    private $_quantity;

    /**
     * Unit price
     * 
     * @var string
     */
    private $_unitPrice;

    /**
     * Total price
     * 
     * @var string
     */
    private $_totalPrice;

    /**
     * Tax
     * 
     * @var string
     */
    private $_tax;

    /**
     * Get article name
     * 
     * @return string
     */
    public function getArticleName()
    {
        return $this->_articleName;
    }

    /**
     * Set article name
     * 
     * @param string $articleName
     * @return ItemInfo
     */
    public function setArticleName($articleName)
    {
        $this->_articleName = $articleName;

        return $this;
    }

    /**
     * Get article number
     * 
     * @return string
     */
    public function getArticleNumber()
    {
        return $this->_articleNumber;
    }

    /**
     * Set article number
     * 
     * @param string $articleNumber
     * @return ItemInfo
     */
    public function setArticleNumber($articleNumber)
    {
        $this->_articleNumber = $articleNumber;

        return $this;
    }

    /**
     * Get quantity
     * 
     * @return string
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * Set quantity
     * 
     * @param string $quantity
     * @return ItemInfo
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;

        return $this;
    }

    /**
     * Get unit price
     * 
     * @return string
     */
    public function getUnitPrice()
    {
        return $this->_unitPrice;
    }

    /**
     * Set unit price
     * 
     * @param string $unitPrice
     * @return ItemInfo
     */
    public function setUnitPrice($unitPrice)
    {
        $this->_unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get total price
     * 
     * @return string
     */
    public function getTotalPrice()
    {
        return $this->_totalPrice;
    }

    /**
     * Set total price
     * 
     * @param string $totalPrice
     * @return ItemInfo
     */
    public function setTotalPrice($totalPrice)
    {
        $this->_totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get tax
     * 
     * @return string
     */
    public function getTax()
    {
        return $this->_tax;
    }

    /**
     * Set tax
     * 
     * @param string $tax
     * @return ItemInfo
     */
    public function setTax($tax)
    {
        $this->_tax = $tax;

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
            'articleName'   => $this->_articleName,
            'articleNumber' => $this->_articleNumber,
            'quantity'      => $this->_quantity,
            'unitPrice'     => $this->_unitPrice,
            'totalPrice'    => $this->_totalPrice,
            'tax'           => $this->_tax
        );

        return $data;
    }

}
