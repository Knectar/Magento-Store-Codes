<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Knectar Design
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 *
 * @category   Utilities
 * @package    Knectar_Storecodes
 * @author     Daniel Deady <daniel.deady@knectar.com>
 * @license    http://opensource.org/licenses/MIT
 */

class Knectar_Storecodes_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{

    /*
     * Only register this router if store codes are enabled
     */
    public function initControllerRouters(Varien_Event_Observer $observer)
    {
        // if store code is supposed to be embedded in URL
        // skip admin always so URLs always begin with the backend code
        // not being able to control backend prefix is vulnerable
        if (Mage::getStoreConfigFlag("web/url/use_store") && !Mage::app()->getStore()->isAdmin()) {
            /* @var $front Mage_Core_Controller_Varien_Front */
            $front = $observer->getFront();
            $front->addRouter('knectar_storecodes', $this);
        }
    }

    /**
     * Redirect bad URLs when they are missing a store code
     * or have a store code which they shouldn't
     * 
     * @param Mage_Core_Controller_Request_Http $request
     * @see Mage_Core_Controller_Request_Http::setPathInfo
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        $targetPath = "{$request->getModuleName()}/{$request->getControllerName()}/{$request->getActionName()}";
        /* @var $helper Knectar_Storecodes_Helper_Data */
        $helper = Mage::helper('knectar_storecodes');

        // setPathInfo() has determined there is no store code
        if (($targetPath == '//noRoute') || ($targetPath == 'pagecache/request/noRoute')) {
            $base = $request->getBaseUrl() . DS;
            $path = $request->getOriginalRequest()->getRequestUri();
            $path = substr($path, strlen($base));
            $redirect = $helper->getRedirectCode();

            if (! Mage::getStoreConfigFlag('web/url/use_store_default')) {
                // simply put empty request back in router loop
                $request->setActionName('');
            }
            elseif (($request->getMethod() != 'post') && $redirect) {
                // send a 302 (Found) redirect
                Mage::app()->getResponse()
                    ->setRedirect(Mage::getUrl('', array('_direct' => $path)), $redirect)
                    ->sendResponse();
                exit();
            }
        }
        // store is valid but is it wanted?
        elseif (! Mage::getStoreConfigFlag('web/url/use_store_default') && ($redirect = $helper->getRedirectCode())) {
            $storeCode = $helper->getStoreCode($request);
            $defaultCode = $helper->getWebsiteDefaultStore()->getCode();
            if ($storeCode === $defaultCode) {
                $storeCode = preg_quote($storeCode);
                $path = $request->getOriginalRequest()->getRequestUri();
                $path = preg_replace("#/{$storeCode}(/|$)#", '', $path, 1);
                Mage::app()->getResponse()
                    ->setRedirect(Mage::getUrl('', array('_direct' => $path)), $redirect)
                    ->sendResponse();
                exit();
            }
        }
        return false;
    }
}
