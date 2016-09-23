<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Link_post_type extends yucca_shopAdminPageFramework_Link_Base
{
    public function __construct($oProp, $oMsg = null)
    {
        parent::__construct($oProp, $oMsg);
        if (isset($_GET['post_type']) && $_GET['post_type'] == $this->oProp->sPostType) {
            add_action('get_edit_post_link', [$this, '_replyToAddPostTypeQueryInEditPostLink'], 10, 3);
        }
    }

    public function _replyToAddSettingsLinkInPluginListingPage($aLinks)
    {
        $_sLinkLabel = $this->getElement($this->oProp->aPostTypeArgs, ['labels', 'plugin_listing_table_title_cell_link'], $this->oMsg->get('manage'));
        $_sLinkLabel = $this->getElement($this->oProp->aPostTypeArgs, ['labels', 'plugin_action_link'], $_sLinkLabel);
        if (!$_sLinkLabel) {
            return $aLinks;
        }
        array_unshift($aLinks, '<a '.$this->getAttributes(['href' => esc_url("edit.php?post_type={$this->oProp->sPostType}"), 'class' => 'apf-plugin-title-action-link apf-admin-page']).'>'.$_sLinkLabel.'</a>');

        return $aLinks;
    }

    public function _replyToSetFooterInfo()
    {
        if (!$this->isPostDefinitionPage($this->oProp->sPostType) && !$this->isPostListingPage($this->oProp->sPostType) && !$this->isCustomTaxonomyPage($this->oProp->sPostType)) {
            return;
        }
        parent::_replyToSetFooterInfo();
    }

    public function _replyToAddPostTypeQueryInEditPostLink($sURL, $iPostID = null, $sContext = null)
    {
        return add_query_arg(['post' => $iPostID, 'action' => 'edit', 'post_type' => $this->oProp->sPostType], $sURL);
    }
}
