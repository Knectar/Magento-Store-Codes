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

class Knectar_Storecodes_Model_Resource_Cms_Page extends Mage_Cms_Model_Resource_Page
{

    /*
     * When URL is missing a store code allow loading of "All Store Views"
     * pages with the same key instead.
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId() && Mage::getStoreConfigFlag('web/url/use_store') && Mage::getStoreConfigFlag('web/url/use_store_default')) {
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
