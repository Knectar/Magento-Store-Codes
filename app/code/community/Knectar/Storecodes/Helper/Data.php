<?php

class Knectar_Storecodes_Helper_Data extends Mage_Core_Helper_Data
{

    /*
     * The configured HTTP response code set in admin.
     */
    public function getRedirectCode() {
        $code = (int) Mage::getStoreConfig('web/url/redirect_to_store');
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
}
