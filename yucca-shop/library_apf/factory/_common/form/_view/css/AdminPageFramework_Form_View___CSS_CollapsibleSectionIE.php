<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___CSS_CollapsibleSectionIE extends yucca_shopAdminPageFramework_Form_View___CSS_Base
{
    protected function _get()
    {
        return $this->_getCollapsibleSectionsRules();
    }

    private function _getCollapsibleSectionsRules()
    {
        return 'tbody.yucca-shop-collapsible-content > tr > th,tbody.yucca-shop-collapsible-content > tr > td{padding-right: 20px;padding-left: 20px;}';
    }
}
