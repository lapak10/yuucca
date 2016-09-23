<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_UserMeta_Router extends yucca_shopAdminPageFramework_Factory
{
    public function __construct($oProp)
    {
        parent::__construct($oProp);
        if (!$this->oProp->bIsAdmin) {
            return;
        }
        $this->oUtil->registerAction('current_screen', [$this, '_replyToDetermineToLoad']);
        add_action('set_up_'.$this->oProp->sClassName, [$this, '_replyToSetUpHooks']);
    }

    public function _isInThePage()
    {
        if (!$this->oProp->bIsAdmin) {
            return false;
        }

        return in_array($this->oProp->sPageNow, ['user-new.php', 'user-edit.php', 'profile.php']);
    }

    public function _replyToSetUpHooks($oFactory)
    {
        add_action('show_user_profile', [$this, '_replyToPrintFields']);
        add_action('edit_user_profile', [$this, '_replyToPrintFields']);
        add_action('user_new_form', [$this, '_replyToPrintFields']);
        add_action('personal_options_update', [$this, '_replyToSaveFieldValues']);
        add_action('edit_user_profile_update', [$this, '_replyToSaveFieldValues']);
        add_action('user_register', [$this, '_replyToSaveFieldValues']);
    }
}
abstract class yucca_shopAdminPageFramework_UserMeta_Model extends yucca_shopAdminPageFramework_UserMeta_Router
{
    public function _replyToGetSavedFormData()
    {
        $_iUserID = isset($GLOBALS['profileuser']->ID) ? $GLOBALS['profileuser']->ID : 0;
        $_oMetaData = new yucca_shopAdminPageFramework_UserMeta_Model___UserMeta($_iUserID, $this->oForm->aFieldsets);
        $this->oProp->aOptions = $_oMetaData->get();

        return parent::_replyToGetSavedFormData();
    }

    public function _replyToSaveFieldValues($iUserID)
    {
        if (!current_user_can('edit_user', $iUserID)) {
            return;
        }
        $_aInputs = $this->oForm->getSubmittedData($_POST, true, false);
        $_aInputsRaw = $_aInputs;
        $_aSavedMeta = $this->oUtil->getSavedUserMetaArray($iUserID, array_keys($_aInputs));
        $_aInputs = $this->oUtil->addAndApplyFilters($this, "validation_{$this->oProp->sClassName}", call_user_func_array([$this, 'validate'], [$_aInputs, $_aSavedMeta, $this]), $_aSavedMeta, $this);
        if ($this->hasFieldError()) {
            $this->setLastInputs($_aInputsRaw);
        }
        $this->oForm->updateMetaDataByType($iUserID, $_aInputs, $this->oForm->dropRepeatableElements($_aSavedMeta), $this->oForm->sStructureType);
    }
}
abstract class yucca_shopAdminPageFramework_UserMeta_View extends yucca_shopAdminPageFramework_UserMeta_Model
{
    public function content($sContent)
    {
        return $sContent;
    }

    public function _replyToPrintFields()
    {
        $_aOutput = [];
        $_aOutput[] = $this->oForm->get();
        $_sOutput = $this->oUtil->addAndApplyFilters($this, 'content_'.$this->oProp->sClassName, $this->content(implode(PHP_EOL, $_aOutput)));
        $this->oUtil->addAndDoActions($this, 'do_'.$this->oProp->sClassName, $this);
        echo $_sOutput;
    }
}
abstract class yucca_shopAdminPageFramework_UserMeta_Controller extends yucca_shopAdminPageFramework_UserMeta_View
{
    public function setUp()
    {
    }

    public function enqueueStyles($aSRCs, $aPostTypes = [], $aCustomArgs = [])
    {
        return $this->oResource->_enqueueStyles($aSRCs, $aPostTypes, $aCustomArgs);
    }

    public function enqueueStyle($sSRC, $aPostTypes = [], $aCustomArgs = [])
    {
        return $this->oResource->_enqueueStyle($sSRC, $aPostTypes, $aCustomArgs);
    }

    public function enqueueScripts($aSRCs, $aPostTypes = [], $aCustomArgs = [])
    {
        return $this->oResource->_enqueueScripts($aSRCs, $aPostTypes, $aCustomArgs);
    }

    public function enqueueScript($sSRC, $aPostTypes = [], $aCustomArgs = [])
    {
        return $this->oResource->_enqueueScript($sSRC, $aPostTypes, $aCustomArgs);
    }
}
abstract class yucca_shopAdminPageFramework_UserMeta extends yucca_shopAdminPageFramework_UserMeta_Controller
{
    protected static $_sStructureType = 'user_meta';

    public function __construct($sCapability = 'read', $sTextDomain = 'yucca-shop')
    {
        $this->oProp = new yucca_shopAdminPageFramework_Property_user_meta($this, get_class($this), $sCapability, $sTextDomain, self::$_sStructureType);
        parent::__construct($this->oProp);
    }
}
