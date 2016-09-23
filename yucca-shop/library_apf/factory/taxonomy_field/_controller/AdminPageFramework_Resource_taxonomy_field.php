<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Resource_taxonomy_field extends yucca_shopAdminPageFramework_Resource_post_meta_box
{
    public function _enqueueStyles($aSRCs, $aCustomArgs = [], $_deprecated = null)
    {
        $_aHandleIDs = [];
        foreach ((array) $aSRCs as $_sSRC) {
            $_aHandleIDs[] = $this->_enqueueStyle($_sSRC, $aCustomArgs);
        }

        return $_aHandleIDs;
    }

    public function _enqueueStyle($sSRC, $aCustomArgs = [], $_deprecated = null)
    {
        $sSRC = trim($sSRC);
        if (empty($sSRC)) {
            return '';
        }
        $sSRC = $this->oUtil->getResolvedSRC($sSRC);
        $_sSRCHash = md5($sSRC);
        if (isset($this->oProp->aEnqueuingStyles[$_sSRCHash])) {
            return '';
        }
        $this->oProp->aEnqueuingStyles[$_sSRCHash] = $this->oUtil->uniteArrays((array) $aCustomArgs, ['sSRC' => $sSRC, 'sType' => 'style', 'handle_id' => 'style_'.$this->oProp->sClassName.'_'.(++$this->oProp->iEnqueuedStyleIndex)], self::$_aStructure_EnqueuingResources);
        $this->oProp->aResourceAttributes[$this->oProp->aEnqueuingStyles[$_sSRCHash]['handle_id']] = $this->oProp->aEnqueuingStyles[$_sSRCHash]['attributes'];

        return $this->oProp->aEnqueuingStyles[$_sSRCHash]['handle_id'];
    }

    public function _enqueueScripts($aSRCs, $aCustomArgs = [], $_deprecated = null)
    {
        $_aHandleIDs = [];
        foreach ((array) $aSRCs as $_sSRC) {
            $_aHandleIDs[] = $this->_enqueueScript($_sSRC, $aCustomArgs);
        }

        return $_aHandleIDs;
    }

    public function _enqueueScript($sSRC, $aCustomArgs = [], $_deprecated = null)
    {
        $sSRC = trim($sSRC);
        if (empty($sSRC)) {
            return '';
        }
        $sSRC = $this->oUtil->getResolvedSRC($sSRC);
        $_sSRCHash = md5($sSRC);
        if (isset($this->oProp->aEnqueuingScripts[$_sSRCHash])) {
            return '';
        }
        $this->oProp->aEnqueuingScripts[$_sSRCHash] = $this->oUtil->uniteArrays((array) $aCustomArgs, ['sSRC' => $sSRC, 'sType' => 'script', 'handle_id' => 'script_'.$this->oProp->sClassName.'_'.(++$this->oProp->iEnqueuedScriptIndex)], self::$_aStructure_EnqueuingResources);
        $this->oProp->aResourceAttributes[$this->oProp->aEnqueuingScripts[$_sSRCHash]['handle_id']] = $this->oProp->aEnqueuingScripts[$_sSRCHash]['attributes'];

        return $this->oProp->aEnqueuingScripts[$_sSRCHash]['handle_id'];
    }

    public function _forceToEnqueueStyle($sSRC, $aCustomArgs = [])
    {
        return $this->_enqueueStyle($sSRC, $aCustomArgs);
    }

    public function _forceToEnqueueScript($sSRC, $aCustomArgs = [])
    {
        return $this->_enqueueScript($sSRC, $aCustomArgs);
    }

    protected function _enqueueSRCByCondition($aEnqueueItem)
    {
        return $this->_enqueueSRC($aEnqueueItem);
    }
}
