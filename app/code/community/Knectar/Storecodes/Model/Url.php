<?php

/*
 * Wanted to override Mage_Core_Model_Store::getBaseUrl() but it was too risky.
 */
class Knectar_Storecodes_Model_Url extends Mage_Core_Model_Url
{

    public function getBaseUrl($params = array()) {
        if (!isset($params['_type']) || ($params['type'] == Mage_Core_Model_Store::URL_TYPE_LINK)) {
            if (!Mage::getStoreConfigFlag('web/url/use_store_default')) {
                if (Mage::app()->getDefaultStoreView()->getCode() === $this->getStore()->getCode()) {
                    $params['_type'] = Mage_Core_Model_Store::URL_TYPE_DIRECT_LINK;
                }
            }
        }

        return parent::getBaseUrl($params);
    }
}
