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

class Knectar_Storecodes_Helper_Data extends Mage_Core_Helper_Data
{

    /*
     * The configured HTTP response code set in admin.
     */
    public function getRedirectCode() {
        $code = (int) Mage::getStoreConfig('web/url/redirect_to_base');
        return $code === 1 ? 302 : $code;
    }

    /*
     * The actual first path segment of the requested URL.
     * May not be a real store, it could be a module name or other
     * legitimate path.
     */
    public function getStoreCode(Mage_Core_Controller_Request_Http $request)
    {
        $base = $request->getBaseUrl();
        $uri = $request->getOriginalRequest()->getRequestUri();
        if ((strlen($base) == 0) || (strpos($uri, $base) === 0)) {
            $uri = substr($uri, strlen($base));
            list ($storeCode) = explode(DS, trim($uri, DS), 2);
            return $storeCode;
        }
        return null;
    }

    /*
     * Attempt to load target store.
     * May differ from Mage::app()->getStore()
     * May be NULL
     */
    public function getRequestedStoreId(Mage_Core_Controller_Request_Http $request)
    {
        $code = $this->getStoreCode($request);
        return Mage::getModel('core/store')->load($code, 'code')->getId();
    }

    /**
     * Find the 'default' store view for current store group
     * 
     * @return Mage_Core_Model_Store
     */
    public function getGroupDefaultStore()
    {
        return Mage::app()->getStore()->getGroup()->getDefaultStore();
    }

    /**
     * Find the 'default' store view for current store & website
     * 
     * @return Mage_Core_Model_Store
     */
    public function getWebsiteDefaultStore()
    {
        return Mage::app()->getStore()->getWebsite()->getDefaultStore();
    }
}
