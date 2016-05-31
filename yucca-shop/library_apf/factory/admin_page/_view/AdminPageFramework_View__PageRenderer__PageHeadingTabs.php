<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_View__PageRenderer__PageHeadingTabs extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $oFactory;
    public $sPageSlug;
    public $sTag = 'h2';

    public function __construct($oFactory, $sPageSlug)
    {
        $this->oFactory = $oFactory;
        $this->sPageSlug = $sPageSlug;
        $this->sTag = $oFactory->oProp->sPageHeadingTabTag ? $oFactory->oProp->sPageHeadingTabTag : 'h2';
    }

    public function get()
    {
        $_aPage = $this->oFactory->oProp->aPages[$this->sPageSlug];
        if (!$_aPage['show_page_title']) {
            return '';
        }

        return $this->_getOutput($_aPage, $this->sTag);
    }

    private function _getOutput($aPage, $sTag)
    {
        $sTag = $this->_getPageHeadingTabTag($sTag, $aPage);
        if (!$aPage['show_page_heading_tabs'] || count($this->oFactory->oProp->aPages) == 1) {
            return "<{$sTag}>".$aPage['title']."</{$sTag}>";
        }

        return $this->_getPageHeadingtabNavigationBar($this->oFactory->oProp->aPages, $sTag, $aPage['page_slug']);
    }

    private function _getPageHeadingTabTag($sTag, array $aPage)
    {
        return tag_escape($aPage['page_heading_tab_tag'] ? $aPage['page_heading_tab_tag'] : $sTag);
    }

    private function _getPageHeadingtabNavigationBar(array $aPages, $sTag, $sCurrentPageSlug)
    {
        $_oTabBar = new yucca_shopAdminPageFramework_TabNavigationBar($aPages, $sCurrentPageSlug, $sTag, [], ['format' => [$this, '_replyToFormatNavigationTabItem_PageHeadingTab']]);
        $_sTabBar = $_oTabBar->get();

        return $_sTabBar ? "<div class='yucca-shop-page-heading-tab'>".$_sTabBar.'</div>' : '';
    }

    public function _replyToFormatNavigationTabItem_PageHeadingTab($aSubPage, $aStructure, $aPages, $aArguments = [])
    {
        switch ($aSubPage['type']) {
            case 'link':
                return $this->_getFormattedPageHeadingtabNavigationBarLinkItem($aSubPage, $aStructure);
            default:
                return $this->_getFormattedPageHeadingtabNavigationBarPageItem($aSubPage, $aStructure);
        }

        return $aSubPage + $aStructure;
    }

    private function _getFormattedPageHeadingtabNavigationBarPageItem(array $aSubPage, $aStructure)
    {
        if (!isset($aSubPage['page_slug'])) {
            return [];
        }
        if (!$aSubPage['show_page_heading_tab']) {
            return [];
        }

        return ['slug' => $aSubPage['page_slug'], 'title' => $aSubPage['title'], 'href' => esc_url($this->getQueryAdminURL(['page' => $aSubPage['page_slug'], 'tab' => false], $this->oFactory->oProp->aDisallowedQueryKeys))] + $aSubPage + ['class' => null] + $aStructure;
    }

    private function _getFormattedPageHeadingtabNavigationBarLinkItem(array $aSubPage, $aStructure)
    {
        if (!isset($aSubPage['href'])) {
            return [];
        }
        if (!$aSubPage['show_page_heading_tab']) {
            return [];
        }
        $aSubPage = ['slug' => $aSubPage['href'], 'title' => $aSubPage['title'], 'href' => esc_url($aSubPage['href'])] + $aSubPage + ['class' => null] + $aStructure;
        $aSubPage['class'] = trim($aSubPage['class'].' link');

        return $aSubPage;
    }
}
