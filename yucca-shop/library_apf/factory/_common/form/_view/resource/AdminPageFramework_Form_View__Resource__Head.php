<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View__Resource__Head extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $oForm;

    public function __construct($oForm, $sHeadActionHook = 'admin_head')
    {
        $this->oForm = $oForm;
        if (in_array($this->oForm->aArguments['structure_type'], ['widget'])) {
            return;
        }
        add_action($sHeadActionHook, [$this, '_replyToInsertRequiredInlineScripts']);
    }

    public function _replyToInsertRequiredInlineScripts()
    {
        if (!$this->oForm->isInThePage()) {
            return;
        }
        if ($this->hasBeenCalled(__METHOD__)) {
            return;
        }
        echo "<script type='text/javascript' class='yucca-shop-form-script-required-in-head'>".'/* <![CDATA[ */ '.$this->_getScripts_RequiredInHead().' /* ]]> */'.'</script>';
    }

    private function _getScripts_RequiredInHead()
    {
        return 'document.write( "<style class=\'yucca-shop-js-embedded-inline-style\'>'.str_replace('\\n', '', esc_js($this->_getInlineCSS())).'</style>" );';
    }

    private function _getInlineCSS()
    {
        $_oLoadingCSS = new yucca_shopAdminPageFramework_Form_View___CSS_Loading();
        $_oLoadingCSS->add($this->_getScriptElementConcealerCSSRules());

        return $_oLoadingCSS->get();
    }

    private function _getScriptElementConcealerCSSRules()
    {
        return '.yucca-shop-form-js-on {visibility: hidden;}.widget .yucca-shop-form-js-on { visibility: visible; }';
    }
}
