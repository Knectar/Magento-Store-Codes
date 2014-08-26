<?php

class Knectar_Storecodes_Model_Resource_Cms_Page extends Mage_Cms_Model_Resource_Page
{

    /*
     * When URL is missing a store code allow loading of "All Store Views"
     * pages with the same key instead.
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if (Mage::getStoreConfigFlag("web/url/use_store") && Mage::getStoreConfigFlag('web/url/use_store_default')) {
            $storeId = Mage::helper('knectar_storecodes')->getRequestedStoreId(Mage::app()->getRequest());
            if (is_null($storeId)) {
                $select
                    ->reset(Zend_Db_Select::ORDER)
                    ->order('cms_page_store.store_id ASC');
            }
        }

        return $select;
    }
}
