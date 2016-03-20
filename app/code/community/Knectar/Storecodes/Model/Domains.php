<?php

class Knectar_Storecodes_Model_Domains
{

    /**
     * Determines which stores are most important for each unique base URL
     *
     * If store's base URL is different from secure base URL or contains
     * `{{base_url}}` then origin of requested page might have an effect.
     *
     * @return Mage_Core_Model_Store[]
     */
    public function getPrimaryStores()
    {
        $origins = array();
        foreach (Mage::app()->getStores() as $store) {
            $dominance = 0;
            if ($store->getGroup()->getDefaultStoreId() === $store->getId()) {
                $dominance |= 1;
            }
            if ($store->getWebsite()->getDefaultGroupId() === $store->getGroupId()) {
                $dominance |= 2;
            }
            if ($store->getWebsite()->getIsDefault()) {
                $dominance |= 4;
            }
            $store->setDominance($dominance);
            $baseUrl = $store->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
            if (! isset($origins[$baseUrl]) || $dominance > $origins[$baseUrl]->getDominance()) {
                $origins[$baseUrl] = $store;
            }
        }
        return array_values($origins);
    }

    const CONFIG_STORE_IN_URL = Mage_Core_Model_Store::XML_PATH_STORE_IN_URL;

    /**
     * Temporarily update config tree but does not save to database
     *
     * @return Knectar_Storecodes_Model_Domains
     */
    public function updateConfig()
    {
        if (Mage::getStoreConfigFlag('web/url/use_store') && ! Mage::getStoreConfigFlag('web/url/use_store_default')) {
            $stores = $this->getPrimaryStores();
            foreach ($stores as $store) {
                $store->setConfig('web/url/use_store', false);
            }
        }
        
        return $this;
    }
}
