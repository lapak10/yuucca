<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Attribute_SectionTableBody extends yucca_shopAdminPageFramework_Form_View___Attribute_Base
{
    public $sContext = 'section_table_content';

    protected function _getAttributes()
    {
        $_sCollapsibleType = $this->getElement($this->aArguments, ['collapsible', 'type'], 'box');

        return ['class' => $this->getAOrB($this->aArguments['_is_collapsible'], 'yucca-shop-collapsible-section-content'.' '.'yucca-shop-collapsible-content'.' '.'accordion-section-content'.' '.'yucca-shop-collapsible-content-type-'.$_sCollapsibleType, null)];
    }
}
