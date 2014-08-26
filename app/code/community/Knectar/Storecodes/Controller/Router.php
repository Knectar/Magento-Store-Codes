<?php

class Knectar_Storecodes_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{

    /*
     * Only register this router if store codes are enabled
     */
    public function initControllerRouters(Varien_Event_Observer $observer)
    {
        // if store code is supposed to be embedded in URL
        if (Mage::getStoreConfigFlag("web/url/use_store")) {
            /* @var $front Mage_Core_Controller_Varien_Front */
            $front = $observer->getFront();
            $front->addRouter('knectar_storecodes', $this);
        }
    }

    /**
     * Redirect bad URLs when they are missing a store code
     * 
     * @param Mage_Core_Controller_Request_Http $request
     * @see Mage_Core_Controller_Request_Http::setPathInfo
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        $targetPath = "{$request->getModuleName()}/{$request->getControllerName()}/{$request->getActionName()}";

        // setPathInfo() has determined there is no store code
        if ($targetPath == '//noRoute') {
            $base = $request->getBaseUrl() . DS;
            $path = $request->getOriginalRequest()->getRequestUri();
            $path = substr($path, strlen($base));

            if (!Mage::getStoreConfigFlag('web/url/use_store_default')) {
                // simply put empty request back in router loop
                $request->setActionName('');
            }
            elseif ($request->getMethod() != 'post') {
                // send a 302 (Found) redirect
                Mage::app()->getResponse()
                    ->setRedirect(Mage::getUrl('', array('_direct' => $path)))
                    ->sendResponse();
                exit();
            }
        }
        return false;
    }
}
