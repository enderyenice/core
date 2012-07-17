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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\Paypal\View\Button;

/**
 * Express Checkout button
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="cart.panel.totals", weight="100")
 * @ListChild (list="minicart.horizontal.buttons", weight="100")
 */
class ExpressCheckout extends \XLite\View\Button\Link
{
    /**
     * Returns widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/Paypal/button/express_checkout.tpl';
    }

    /**
     * Get widget template
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getTemplate()
    {
        return 'cart.panel.totals' == $this->viewListName
            ? 'modules/CDev/Paypal/button/cart_express_checkout.tpl'
            : $this->getDefaultTemplate();
    }

    /**
     * Returns true if widget is visible
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function isVisible()
    {
        return \XLite\Module\CDev\Paypal\Main::isExpressCheckoutEnabled();
    }

    /**
     * Get CSS class name
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getClass()
    {
        return 'pp-ec-button';
    }

    /**
     * defineWidgetParams 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams[self::PARAM_LOCATION] = new \XLite\Model\WidgetParam\String(
            'Redirect to',
            $this->buildURL(
                'checkout',
                'start_express_checkout'
            )
        );
    }

}
