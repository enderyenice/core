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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\DrupalConnector\View;

/**
 * Abstract widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AView extends \XLite\View\AView implements \XLite\Base\IDecorator
{
    /**
     * Relative path from web directory path to the XLite web directory 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected static $drupalRelativePath = null;


    /**
     * prepareBasePath 
     * 
     * @param string $path Path to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function prepareBasePath($path)
    {
        $path = trim($path, '/');

        return ('' === $path) ? array() : explode('/', $path);
    }

    /**
     * Return relative path from web directory path to the XLite web directory 
     * FIXME - it's the hack
     * TODO - check if there is a more convenient way to implement this
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected static function getDrupalRelativePath()
    {
        if (!isset(self::$drupalRelativePath)) {

            // FIXME - "base_path()" is a Drupal function declared in global scope
            $basePath  = self::prepareBasePath(base_path());
            $xlitePath = self::prepareBasePath(\XLite::getInstance()->getOptions(array('host_details', 'web_dir')));

            $basePathSize = count($basePath);
            $minPathSize  = min($basePathSize, count($xlitePath));

            for ($i = 0; $i < $minPathSize; $i++) {
                if ($basePath[$i] !== $xlitePath[$i]) {
                    break;
                } else {
                    unset($xlitePath[$i]);
                }
            }

            self::$drupalRelativePath = str_repeat('../', $basePathSize - $i) . join('/', $xlitePath) . '/';
        }

        return self::$drupalRelativePath;
    }

    /**
     * Add the relative part to the resources' URLs 
     * 
     * @param array $data Data to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function modifyResourcePaths(array $data)
    {
        foreach ($data as &$file) {
            $file = static::getDrupalRelativePath() . $file;
        }

        return $data;
    }

    /**
     * Prepare resources list
     *
     * @param array   $data     Data to prepare
     * @param boolean $isCommon Flag to determine how to prepare URL OPTIONAL
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function prepareResources(array $data, $isCommon = false)
    {
        return static::modifyResources(parent::prepareResources($data, $isCommon));
    }

    /** 
     * Modify resources list
     * 
     * @param mixed $data Data to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function modifyResources(array $data)
    {
        if (\XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $data = static::modifyResourcePaths($data);
        }   

        return $data;
    }


    /**
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $result = parent::getJSFiles();

        if (\XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $result[] = 'modules/DrupalConnector/drupal.js';
        }

        return $result;
    }
}

