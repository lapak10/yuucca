<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Widget_Router extends yucca_shopAdminPageFramework_Factory
{
    public function __construct($oProp)
    {
        parent::__construct($oProp);
        $this->oUtil->registerAction('widgets_init', [$this, '_replyToDetermineToLoad']);
    }

    public function _replyToLoadComponents()
    {
    }
}
abstract class yucca_shopAdminPageFramework_Widget_Model extends yucca_shopAdminPageFramework_Widget_Router
{
    public function __construct($oProp)
    {
        parent::__construct($oProp);
        $this->oUtil->registerAction("set_up_{$this->oProp->sClassName}", [$this, '_replyToRegisterWidget']);
        if ($this->oProp->bIsAdmin) {
            add_filter('validation_'.$this->oProp->sClassName, [$this, '_replyToSortInputs'], 1, 3);
        }
    }

    public function _replyToSortInputs($aSubmittedFormData, $aStoredFormData, $oFactory)
    {
        return $this->oForm->getSortedInputs($aSubmittedFormData);
    }

    public function _replyToHandleSubmittedFormData($aSavedData, $aArguments, $aSectionsets, $aFieldsets)
    {
        if (empty($aSectionsets) || empty($aFieldsets)) {
            return;
        }
        $this->oResource;
    }

    public function _replyToRegisterWidget()
    {
        global $wp_widget_factory;
        if (!is_object($wp_widget_factory)) {
            return;
        }
        $wp_widget_factory->widgets[$this->oProp->sClassName] = new yucca_shopAdminPageFramework_Widget_Factory($this, $this->oProp->sWidgetTitle, $this->oUtil->getAsArray($this->oProp->aWidgetArguments));
        $this->oProp->oWidget = $wp_widget_factory->widgets[$this->oProp->sClassName];
    }
}
abstract class yucca_shopAdminPageFramework_Widget_View extends yucca_shopAdminPageFramework_Widget_Model
{
    public function content($sContent, $aArguments, $aFormData)
    {
        return $sContent;
    }

    public function _printWidgetForm()
    {
        echo $this->oForm->get();
    }
}
abstract class yucca_shopAdminPageFramework_Widget_Controller extends yucca_shopAdminPageFramework_Widget_View
{
    public function setUp()
    {
    }

    public function load($oAdminWidget)
    {
    }

    public function enqueueStyles($aSRCs, $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueStyles')) {
            return $this->oResource->_enqueueStyles($aSRCs, [$this->oProp->sPostType], $aCustomArgs);
        }
    }

    public function enqueueStyle($sSRC, $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueStyle')) {
            return $this->oResource->_enqueueStyle($sSRC, [$this->oProp->sPostType], $aCustomArgs);
        }
    }

    public function enqueueScripts($aSRCs, $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueScripts')) {
            return $this->oResource->_enqueueScripts($aSRCs, [$this->oProp->sPostType], $aCustomArgs);
        }
    }

    public function enqueueScript($sSRC, $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueScript')) {
            return $this->oResource->_enqueueScript($sSRC, [$this->oProp->sPostType], $aCustomArgs);
        }
    }

    protected function setArguments(array $aArguments = [])
    {
        $this->oProp->aWidgetArguments = $aArguments;
    }
}
abstract class yucca_shopAdminPageFramework_Widget extends yucca_shopAdminPageFramework_Widget_Controller
{
    protected static $_sStructureType = 'widget';

    public function __construct($sWidgetTitle, $aWidgetArguments = [], $sCapability = 'edit_theme_options', $sTextDomain = 'yucca-shop')
    {
        if (empty($sWidgetTitle)) {
            return;
        }
        $this->oProp = new yucca_shopAdminPageFramework_Property_widget($this, null, get_class($this), $sCapability, $sTextDomain, self::$_sStructureType);
        $this->oProp->sWidgetTitle = $sWidgetTitle;
        $this->oProp->aWidgetArguments = $aWidgetArguments;
        parent::__construct($this->oProp);
    }
}
