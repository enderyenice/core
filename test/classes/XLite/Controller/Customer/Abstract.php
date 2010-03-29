<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Abstract controller for Customer interface
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Controller_Customer_Abstract extends XLite_Controller_Abstract
{
    /**
     * cart 
     * 
     * @var    mixed
     * @access protected
     * @since  3.0.0
     */
    protected $cart = null;


    /**
     * Recalculates the shopping cart
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function updateCart()
    {
        $cart = $this->getCart();

        if ($cart->isPersistent) {
            $cart->calcTotals();
            $cart->update();
        }
    }

    /**
     * recalcCart 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function recalcCart()
    {
        $cart = $this->getCart();

        if ($cart->isPersistent) {
            $cart->recalcItems();
            $cart->calcTotals();
            $cart->update();
        }
    }

    /**
     * isCartProcessed 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isCartProcessed()
    {
        return $this->getCart()->isProcessed() || $this->getCart()->isQueued();
    }



	/**
     * Return current (or default) product object
     * 
     * @return XLite_Model_Product
     * @access public
     * @since  3.0.0
     */
    public function getProduct()
    {
		$product = parent::getProduct();

        return $product->get('enabled') ? $product : null; 
    }

    /**
     * Return cart instance 
     * 
     * @return XLite_Model_Order
     * @access public
     * @since  3.0.0
     */
    public function getCart()
    {
        return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_Model_Cart');
    }

    /**
     * Get the full URL of the page
     * Example: getShopUrl("cart.php") = "http://domain/dir/cart.php 
     * 
     * @param string $url    relative URL  
     * @param bool   $secure flag to use HTTPS
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getShopUrl($url, $secure = false)
    {
        $currentSecurity = $this->config->Security->full_customer_security;

        return parent::getShopUrl($url, $currentSecurity ? $currentSecurity : $secure);
    }

    /**
     * Cleanup processed cart for non-checkout pages
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
	public function __construct()
    {
        // TODO - to remove; backward compatibility
        $this->cart = $this->getCart();

        if ('checkout' == XLite_Core_Request::getInstance()->target && $this->isCartProcessed()) {
            $this->getCart()->clear();
        }
    }


	public function isSecure()
    {
		$result = parent::isSecure();

		if ($this->getComplex('config.Security.full_customer_security')) {
			$result = $this->xlite->get('HTMLCatalogWorking');
		} elseif (!is_null($this->get('feed')) && $this->get('feed') == 'login') {
			$result = $this->getComplex('config.Security.customer_security');
		}

		return $result;
    }
}

