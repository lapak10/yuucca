<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_PluginBootstrap
{
    public $sFilePath;
    public $bIsAdmin;
    public $sHookPrefix;

    public function __construct($sPluginFilePath, $sPluginHookPrefix = '', $sSetUpHook = 'plugins_loaded', $iPriority = 10)
    {
        if ($this->_hasLoaded()) {
            return;
        }
        $this->sFilePath = $sPluginFilePath;
        $this->bIsAdmin = is_admin();
        $this->sHookPrefix = $sPluginHookPrefix;
        $this->sSetUpHook = $sSetUpHook;
        $this->iPriority = $iPriority;
        $_bValid = $this->start();
        if (false === $_bValid) {
            return;
        }
        $this->setConstants();
        $this->setGlobals();
        $this->_registerClasses();
        register_activation_hook($this->sFilePath, [$this, 'replyToPluginActivation']);
        register_deactivation_hook($this->sFilePath, [$this, 'replyToPluginDeactivation']);
        if (!$this->sSetUpHook || did_action($this->sSetUpHook)) {
            $this->_replyToLoadPluginComponents();
        } else {
            add_action($this->sSetUpHook, [$this, '_replyToLoadPluginComponents'], $this->iPriority);
        }
        add_action('init', [$this, 'setLocalization']);
        $this->construct();
    }

    protected function _hasLoaded()
    {
        static $_bLoaded = false;
        if ($_bLoaded) {
            return true;
        }
        $_bLoaded = true;

        return false;
    }

    protected function _registerClasses()
    {
        if (!class_exists('yucca_shopAdminPageFramework_RegisterClasses', false)) {
            return;
        }
        new yucca_shopAdminPageFramework_RegisterClasses($this->getScanningDirs(), [], $this->getClasses());
    }

    public function _replyToLoadPluginComponents()
    {
        if ($this->sHookPrefix) {
            do_action("{$this->sHookPrefix}_action_before_loading_plugin");
        }
        $this->setUp();
        if ($this->sHookPrefix) {
            do_action("{$this->sHookPrefix}_action_after_loading_plugin");
        }
    }

    public function setConstants()
    {
    }

    public function setGlobals()
    {
    }

    public function getClasses()
    {
        $_aClasses = [];

        return $_aClasses;
    }

    public function getScanningDirs()
    {
        $_aDirs = [];

        return $_aDirs;
    }

    public function replyToPluginActivation()
    {
    }

    public function replyToPluginDeactivation()
    {
    }

    public function setLocalization()
    {
    }

    public function setUp()
    {
    }

    protected function construct()
    {
    }

    public function start()
    {
    }
}
