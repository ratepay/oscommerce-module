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

require_once('includes/classes/order.php');
require_once('../includes/languages/' . $_SESSION['language'] . '/admin/modules/payment/ratepay.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/mappers/RequestMapper.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Data.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Db.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Session.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Globals.php');
require_once(dirname(__FILE__) . '/../../../classes/ratepay/helpers/Loader.php');

/**
 * Order controller
 */
class OrderController
{
    /**
     * Call CONFIRMATION_DELIVER and updates order and item data
     */
    public static function deliverAction()
    {
        $post = Globals::getPost();
        $orderId = Globals::getPostEntry('order_number');
        $order = new order($orderId);
        $transactionId = Db::getRatepayOrderDataEntry($orderId, 'transaction_id');
        $transactionShortId = Db::getRatepayOrderDataEntry($orderId, 'transaction_short_id');
        $subType = Data::isFullDeliver(self::getDeliverPostData($post), $orderId) ? 'full-deliver' : 'partial-deliver';
        $data = array(
            'HeadInfo'   => RequestMapper::getHeadInfoModel($order, $transactionId, $transactionShortId, $orderId, $subType),
            'BasketInfo' => RequestMapper::getBasketInfoModel($order, $orderId, self::getDeliverPostData($post))
        );
        $payment = Loader::getRatepayPayment($order->info['payment_method']);
        $requestService = new RequestService($payment->sandbox, $data);
        $result = $requestService->callConfirmationDeliver();
        Db::xmlLog($order, $requestService->getRequest(), $orderId, $requestService->getResponse());
        if (!array_key_exists('error', $result)) {
            Session::setRpSessionEntry('message_css_class', 'messageStackSuccess');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_DELIVER_SUCCESS);
            Db::shipRpOrder(self::getDeliverPostData($post), $order);
            Db::setRpHistoryEntrys($post, 'CONFIRMATION_DELIVER', $subType);
            $flag = true;
            foreach (Db::getRpItems($orderId) as $item) {
                if ($item['ordered'] != $item['shipped']) {
                    $flag = false;
                }
            }
            
            if ($flag) {
                $sql = "UPDATE orders SET "
                        . "orders_status = " . (int) 3
                        . " WHERE "
                        . "orders_id = '" . tep_db_input($orderId) . "'";
                tep_db_query($sql);
            }
            
        } else {
            Session::setRpSessionEntry('message_css_class', 'messageStackError');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_DELIVER_ERROR);
        }
        
        tep_redirect(tep_href_link("ratepay_order.php", 'oID=' . $orderId, 'SSL'));
    }
    
    /**
     * Call PAYMENT_CHANGE with the subtype full or 
     * part cancellation and updates order and item data
     */
    public static function cancelAction()
    {
        $post = Globals::getPost();
        $orderId = Globals::getPostEntry('order_number');
        $order = new order($orderId);
        $payment = Loader::getRatepayPayment($order->info['payment_method']);
        $transactionId = Db::getRatepayOrderDataEntry($orderId, 'transaction_id');
        $transactionShortId = Db::getRatepayOrderDataEntry($orderId, 'transaction_short_id');
        $subType = Data::isFullCancel(self::getCancelPostData($post), $orderId) ? 'full-cancellation' : 'partial-cancellation';
        $data = array(
            'HeadInfo'   => RequestMapper::getHeadInfoModel($order, $transactionId, $transactionShortId, $orderId, $subType),
            'BasketInfo' => RequestMapper::getBasketInfoModel($order, $orderId, self::getCancelPostData($post)),
            'CustomerInfo' => RequestMapper::getCustomerInfoModel($order, $orderId),
            'PaymentInfo' => RequestMapper::getPaymentInfoModel($order, $orderId)
        );
        $requestService = new RequestService($payment->sandbox, $data);
        $result = $requestService->callPaymentChange();
        Db::xmlLog($order, $requestService->getRequest(), $orderId, $requestService->getResponse());
        if (!array_key_exists('error', $result)) {
            Session::setRpSessionEntry('message_css_class', 'messageStackSuccess');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_CANCEL_SUCCESS);
            Db::cancelRpOrder(self::getCancelPostData($post), $order);
            Db::setRpHistoryEntrys($post, 'PAYMENT_CHANGE', $subType);
            Db::cancelOrRefundShopItems($post, $orderId);
            Db::updateShopOrderTotals($orderId);
        } else {
            Session::setRpSessionEntry('message_css_class', 'messageStackError');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_CANCEL_ERROR);
        }
        
        tep_redirect(tep_href_link("ratepay_order.php", 'oID=' . $orderId, 'SSL'));
    }
    
    /**
     * Call PAYMENT_CHANGE with the subtype full or 
     * part return and updates order and item data
     */
    public static function refundAction()
    {
        $post = Globals::getPost();
        $orderId = Globals::getPostEntry('order_number');
        $order = new order($orderId);
        $payment = Loader::getRatepayPayment($order->info['payment_method']);
        $transactionId = Db::getRatepayOrderDataEntry($orderId, 'transaction_id');
        $transactionShortId = Db::getRatepayOrderDataEntry($orderId, 'transaction_short_id');
        $subType = Data::isFullReturn(self::getRefundPostData($post), $orderId) ? 'full-return' : 'partial-return';
        $data = array(
            'HeadInfo'   => RequestMapper::getHeadInfoModel($order, $transactionId, $transactionShortId, $orderId, $subType),
            'BasketInfo' => RequestMapper::getBasketInfoModel($order, $orderId, self::getRefundPostData($post)),
            'CustomerInfo' => RequestMapper::getCustomerInfoModel($order, $orderId),
            'PaymentInfo' => RequestMapper::getPaymentInfoModel($order, $orderId)
        );
        $requestService = new RequestService($payment->sandbox, $data);
        $result = $requestService->callPaymentChange();
        Db::xmlLog($order, $requestService->getRequest(), $orderId, $requestService->getResponse());
        if (!array_key_exists('error', $result)) {
            Session::setRpSessionEntry('message_css_class', 'messageStackSuccess');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_REFUND_SUCCESS);
            Db::refundRpOrder(self::getRefundPostData($post), $order);
            Db::setRpHistoryEntrys($post, 'PAYMENT_CHANGE', $subType);
            Db::cancelOrRefundShopItems($post, $orderId);
            Db::updateShopOrderTotals($orderId);
        } else {
            Session::setRpSessionEntry('message_css_class', 'messageStackError');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_REFUND_ERROR);
        }
        
        tep_redirect(tep_href_link("ratepay_order.php", 'oID=' . $orderId, 'SSL'));
    }
    
    /**
     * Call PAYMENT_CHANGE with the subtype credit
     * and add a credit item to the order
     */
    public static function creditAction()
    {
        $orderId = Globals::getPostEntry('order_number');
        $order = new order($orderId);
        $payment = Loader::getRatepayPayment($order->info['payment_method']);
        $transactionId = Db::getRatepayOrderDataEntry($orderId, 'transaction_id');
        $transactionShortId = Db::getRatepayOrderDataEntry($orderId, 'transaction_short_id');
        $data = array(
            'HeadInfo'   => RequestMapper::getHeadInfoModel($order, $transactionId, $transactionShortId, $orderId, 'credit'),
            'BasketInfo' => RequestMapper::getBasketInfoModel($order, $orderId, Globals::getPost()),
            'CustomerInfo' => RequestMapper::getCustomerInfoModel($order, $orderId),
            'PaymentInfo' => RequestMapper::getPaymentInfoModel($order, $orderId)
        );
        $requestService = new RequestService($payment->sandbox, $data);
        $result = $requestService->callPaymentChange();
        Db::xmlLog($order, $requestService->getRequest(), $orderId, $requestService->getResponse());
        if (!array_key_exists('error', $result)) {
            Session::setRpSessionEntry('message_css_class', 'messageStackSuccess');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_CREDIT_SUCCESS);
            Db::setRpCreditItem(Globals::getPost());
            Db::setRpHistoryEntry($orderId, Data::getCreditItem(Globals::getPost()), 'PAYMENT_CHANGE', 'credit');
            Db::addCreditToShop($orderId, Globals::getPost());
            Db::updateShopOrderTotals($orderId);
        } else {
            Session::setRpSessionEntry('message_css_class', 'messageStackError');
            Session::setRpSessionEntry('message', RATEPAY_ORDER_MESSAGE_CREDIT_ERROR);
        }
        
        tep_redirect(tep_href_link("ratepay_order.php", 'oID=' . $orderId, 'SSL'));
    }
    
    /**
     * Build the deliver post array
     * 
     * @param array $post
     * @return array
     */
    private static function getDeliverPostData(array $post)
    {
        $postData = array();
        $postData['deliver'] = true;
        foreach ($post['items'] as $key => $value) {
            $postData[$key]['toShip'] = $value;
        }
        
        return $postData;
    }
    
    /**
     * Build the refund post array
     * 
     * @param array $post
     * @return array
     */
    private static function getRefundPostData(array $post)
    {
        $postData = array();
        foreach ($post['items'] as $key => $value) {
            $postData[$key]['toRefund'] = $value;
        }
        
        return $postData;
    }
    
    /**
     * Build the cancel post array
     * 
     * @param array $post
     * @return array
     */
    private static function getCancelPostData(array $post)
    {
        $postData = array();
        foreach ($post['items'] as $key => $value) {
            $postData[$key]['toCancel'] = $value;
        }
        
        return $postData;
    }
}