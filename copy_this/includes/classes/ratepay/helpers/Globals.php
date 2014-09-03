<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Globals
 */
class Globals
{

    /**
     * Retrieve $_POST array
     * 
     * @return array
     */
    public static function getPost()
    {
        return $_POST;
    }

    /**
     * Retrieve a post entry
     * 
     * @param string $key
     * @return string
     */
    public static function getPostEntry($key)
    {
        return $_POST[$key];
    }

    /**
     * Is post entry available
     * 
     * @param string $key
     * @return boolean
     */
    public static function hasPostEntry($key)
    {
        return array_key_exists($key, $_POST);
    }

    /**
     * Is get param available
     * 
     * @param string $key
     * @return boolean
     */
    public static function hasParam($key)
    {
        return array_key_exists($key, $_GET);
    }

    /**
     * Retrieve url parameter
     * 
     * @param string $key
     * @return string
     */
    public static function getParam($key)
    {
        return $_GET[$key];
    }

}
