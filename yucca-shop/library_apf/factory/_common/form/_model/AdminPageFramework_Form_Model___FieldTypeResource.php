<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_Model___FieldTypeResource extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $aFieldTypeDefinition = [];
    public $aResources = ['inline_styles' => [], 'inline_styles_ie' => [], 'inline_scripts' => [], 'src_styles' => [], 'src_scripts' => []];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aFieldTypeDefinition, $this->aResources];
        $this->aFieldTypeDefinition = $this->getAsArray($_aParameters[0]);
        $this->aResources = $this->getAsArray($_aParameters[1]);
    }

    public function get()
    {
        $this->aResources['inline_scripts'] = $this->_getUpdatedInlineItemsByCallback($this->aResources['inline_scripts'], 'hfGetScripts');
        $this->aResources['inline_styles'] = $this->_getUpdatedInlineItemsByCallback($this->aResources['inline_styles'], 'hfGetStyles');
        $this->aResources['inline_styles_ie'] = $this->_getUpdatedInlineItemsByCallback($this->aResources['inline_styles_ie'], 'hfGetIEStyles');
        $this->aResources['src_styles'] = $this->_getUpdatedEnqueuingItemsByCallback($this->aResources['src_styles'], 'aEnqueueStyles');
        $this->aResources['src_scripts'] = $this->_getUpdatedEnqueuingItemsByCallback($this->aResources['src_scripts'], 'aEnqueueScripts');

        return $this->aResources;
    }

    private function _getUpdatedInlineItemsByCallback(array $aSubject, $sKey)
    {
        $_oCallable = $this->getElement($this->aFieldTypeDefinition, $sKey);
        if (!is_callable($_oCallable)) {
            return $aSubject;
        }
        $aSubject[] = call_user_func_array($_oCallable, []);

        return $aSubject;
    }

    private function _getUpdatedEnqueuingItemsByCallback($aSubject, $sKey)
    {
        return array_merge($aSubject, $this->getElementAsArray($this->aFieldTypeDefinition, $sKey));
    }
}
