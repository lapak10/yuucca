<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_PostType_Router extends yucca_shopAdminPageFramework_Factory
{
    public function __construct($oProp)
    {
        parent::__construct($oProp);
        $this->oUtil->registerAction('init', [$this, '_replyToDetermineToLoad']);
        $this->oUtil->registerAction('current_screen', [$this, '_replyToDetermineToLoadAdmin']);
    }

    public function _replyToDetermineToLoadAdmin()
    {
        if (!$this->_isInThePage()) {
            return;
        }
        $this->load();
        $this->oUtil->addAndDoAction($this, "load_{$this->oProp->sPostType}", $this);
    }

    public function _replyToDetermineToLoad()
    {
        $this->_setUp();
        $this->oUtil->addAndDoAction($this, "set_up_{$this->oProp->sClassName}", $this);
    }

    protected function _getLinkObject()
    {
        return new yucca_shopAdminPageFramework_Link_post_type($this->oProp, $this->oMsg);
    }

    protected function _getPageLoadObject()
    {
        return new yucca_shopAdminPageFramework_PageLoadInfo_post_type($this->oProp, $this->oMsg);
    }

    public function _isInThePage()
    {
        if (!$this->oProp->bIsAdmin) {
            return false;
        }
        if ($this->oProp->bIsAdminAjax && $this->oUtil->getElement($this->oProp->aPostTypeArgs, 'public', true)) {
            return true;
        }
        if (!in_array($this->oProp->sPageNow, ['edit.php', 'edit-tags.php', 'post.php', 'post-new.php'])) {
            return false;
        }
        if (isset($_GET['page'])) {
            return false;
        }

        return $this->oUtil->getCurrentPostType() === $this->oProp->sPostType;
    }

    public function _replyToLoadComponents()
    {
        if ('plugins.php' === $this->oProp->sPageNow) {
            $this->oLink = $this->_replyTpSetAndGetInstance_oLink();
        }
        parent::_replyToLoadComponents();
    }
}
abstract class yucca_shopAdminPageFramework_PostType_Model extends yucca_shopAdminPageFramework_PostType_Router
{
    public function __construct($oProp)
    {
        parent::__construct($oProp);
        add_action("set_up_{$this->oProp->sClassName}", [$this, '_replyToRegisterPostType'], 999);
        if ($this->oProp->bIsAdmin) {
            add_action('load_'.$this->oProp->sPostType, [$this, '_replyToSetUpHooksForModel']);
            if ($this->oProp->sCallerPath) {
                new yucca_shopAdminPageFramework_PostType_Model__FlushRewriteRules($this);
            }
        }
    }

    public function _replyToSetUpHooksForModel()
    {
        add_filter("manage_{$this->oProp->sPostType}_posts_columns", [$this, '_replyToSetColumnHeader']);
        add_filter("manage_edit-{$this->oProp->sPostType}_sortable_columns", [$this, '_replyToSetSortableColumns']);
        add_action("manage_{$this->oProp->sPostType}_posts_custom_column", [$this, '_replyToPrintColumnCell'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, '_replyToDisableAutoSave']);
        $this->oProp->aColumnHeaders = ['cb' => '<input type="checkbox" />', 'title' => $this->oMsg->get('title'), 'author' => $this->oMsg->get('author'), 'comments' => '<div class="comment-grey-bubble"></div>', 'date' => $this->oMsg->get('date')];
    }

    public function _replyToSetSortableColumns($aColumns)
    {
        return $this->oUtil->getAsArray($this->oUtil->addAndApplyFilter($this, "sortable_columns_{$this->oProp->sPostType}", $aColumns));
    }

    public function _replyToSetColumnHeader($aHeaderColumns)
    {
        return $this->oUtil->getAsArray($this->oUtil->addAndApplyFilter($this, "columns_{$this->oProp->sPostType}", $aHeaderColumns));
    }

    public function _replyToPrintColumnCell($sColumnKey, $iPostID)
    {
        echo $this->oUtil->addAndApplyFilter($this, "cell_{$this->oProp->sPostType}_{$sColumnKey}", '', $iPostID);
    }

    public function _replyToDisableAutoSave()
    {
        if ($this->oProp->bEnableAutoSave) {
            return;
        }
        if ($this->oProp->sPostType != get_post_type()) {
            return;
        }
        wp_dequeue_script('autosave');
    }

    public function _replyToRegisterPostType()
    {
        register_post_type($this->oProp->sPostType, $this->oProp->aPostTypeArgs);
        new yucca_shopAdminPageFramework_PostType_Model__SubMenuOrder($this);
    }

    public function _replyToRegisterTaxonomies()
    {
        foreach ($this->oProp->aTaxonomies as $_sTaxonomySlug => $_aArguments) {
            $this->_registerTaxonomy($_sTaxonomySlug, $this->oUtil->getAsArray($this->oProp->aTaxonomyObjectTypes[$_sTaxonomySlug]), $_aArguments);
        }
    }

    public function _registerTaxonomy($sTaxonomySlug, array $aObjectTypes, array $aArguments)
    {
        if (!in_array($this->oProp->sPostType, $aObjectTypes)) {
            $aObjectTypes[] = $this->oProp->sPostType;
        }
        register_taxonomy($sTaxonomySlug, array_unique($aObjectTypes), $aArguments);
        $this->_setCustomMenuOrderForTaxonomy($this->oUtil->getElement($aArguments, 'submenu_order', 15), $sTaxonomySlug);
    }

    private function _setCustomMenuOrderForTaxonomy($nSubMenuOrder, $sTaxonomySlug)
    {
        if (15 == $nSubMenuOrder) {
            return;
        }
        $this->oProp->aTaxonomySubMenuOrder["edit-tags.php?taxonomy={$sTaxonomySlug}&amp;post_type={$this->oProp->sPostType}"] = $nSubMenuOrder;
    }

    public function _replyToRemoveTexonomySubmenuPages()
    {
        foreach ($this->oProp->aTaxonomyRemoveSubmenuPages as $sSubmenuPageSlug => $sTopLevelPageSlug) {
            remove_submenu_page($sTopLevelPageSlug, $sSubmenuPageSlug);
            unset($this->oProp->aTaxonomyRemoveSubmenuPages[$sSubmenuPageSlug]);
        }
    }
}
abstract class yucca_shopAdminPageFramework_PostType_View extends yucca_shopAdminPageFramework_PostType_Model
{
    public function __construct($oProp)
    {
        parent::__construct($oProp);
        if ($this->oProp->bIsAdmin) {
            add_action('load_'.$this->oProp->sPostType, [$this, '_replyToSetUpHooksForView']);
            add_action('admin_menu', [$this, '_replyToRemoveAddNewSidebarMenu']);
        }
        add_action('the_content', [$this, '_replyToFilterPostTypeContent']);
    }

    public function _replyToSetUpHooksForView()
    {
        add_action('restrict_manage_posts', [$this, '_replyToAddAuthorTableFilter']);
        add_action('restrict_manage_posts', [$this, '_replyToAddTaxonomyTableFilter']);
        add_filter('parse_query', [$this, '_replyToGetTableFilterQueryForTaxonomies']);
        add_filter('post_row_actions', [$this, '_replyToModifyActionLinks'], 10, 2);
        add_action('admin_head', [$this, '_replyToPrintStyle']);
    }

    public function _replyToRemoveAddNewSidebarMenu()
    {
        if ($this->oUtil->getElement($this->oProp->aPostTypeArgs, 'show_submenu_add_new', true)) {
            return;
        }
        $this->_removeAddNewSidebarSubMenu($this->oUtil->getPostTypeSubMenuSlug($this->oProp->sPostType, $this->oProp->aPostTypeArgs), $this->oProp->sPostType);
    }

    private function _removeAddNewSidebarSubMenu($sMenuKey, $sPostTypeSlug)
    {
        if (!isset($GLOBALS['submenu'][$sMenuKey])) {
            return;
        }
        foreach ($GLOBALS['submenu'][$sMenuKey] as $_iIndex => $_aSubMenu) {
            if (!isset($_aSubMenu[2])) {
                continue;
            }
            if ('post-new.php?post_type='.$sPostTypeSlug === $_aSubMenu[2]) {
                unset($GLOBALS['submenu'][$sMenuKey][$_iIndex]);
                break;
            }
        }
    }

    public function _replyToModifyActionLinks($aActionLinks, $oPost)
    {
        if ($oPost->post_type !== $this->oProp->sPostType) {
            return $aActionLinks;
        }

        return $this->oUtil->addAndApplyFilters($this, "action_links_{$this->oProp->sPostType}", $aActionLinks, $oPost);
    }

    public function _replyToAddAuthorTableFilter()
    {
        if (!$this->oProp->bEnableAuthorTableFileter) {
            return;
        }
        if (!(isset($_GET['post_type']) && post_type_exists($_GET['post_type']) && in_array(strtolower($_GET['post_type']), [$this->oProp->sPostType]))) {
            return;
        }
        wp_dropdown_users(['show_option_all' => $this->oMsg->get('show_all_authors'), 'show_option_none' => false, 'name' => 'author', 'selected' => empty($_GET['author']) ? 0 : $_GET['author'], 'include_selected' => false]);
    }

    public function _replyToAddTaxonomyTableFilter()
    {
        if ($GLOBALS['typenow'] != $this->oProp->sPostType) {
            return;
        }
        $_oPostCount = wp_count_posts($this->oProp->sPostType);
        if (0 == $_oPostCount->publish + $_oPostCount->future + $_oPostCount->draft + $_oPostCount->pending + $_oPostCount->private + $_oPostCount->trash) {
            return;
        }
        foreach (get_object_taxonomies($GLOBALS['typenow']) as $_sTaxonomySulg) {
            if (!in_array($_sTaxonomySulg, $this->oProp->aTaxonomyTableFilters)) {
                continue;
            }
            $_oTaxonomy = get_taxonomy($_sTaxonomySulg);
            if (0 == wp_count_terms($_oTaxonomy->name)) {
                continue;
            }
            wp_dropdown_categories(['show_option_all' => $this->oMsg->get('show_all').' '.$_oTaxonomy->label, 'taxonomy' => $_sTaxonomySulg, 'name' => $_oTaxonomy->name, 'orderby' => 'name', 'selected' => intval(isset($_GET[$_sTaxonomySulg])), 'hierarchical' => $_oTaxonomy->hierarchical, 'show_count' => true, 'hide_empty' => false, 'hide_if_empty' => false, 'echo' => true]);
        }
    }

    public function _replyToGetTableFilterQueryForTaxonomies($oQuery = null)
    {
        if ('edit.php' != $this->oProp->sPageNow) {
            return $oQuery;
        }
        if (!isset($GLOBALS['typenow'])) {
            return $oQuery;
        }
        foreach (get_object_taxonomies($GLOBALS['typenow']) as $sTaxonomySlug) {
            if (!in_array($sTaxonomySlug, $this->oProp->aTaxonomyTableFilters)) {
                continue;
            }
            $sVar = &$oQuery->query_vars[$sTaxonomySlug];
            if (!isset($sVar)) {
                continue;
            }
            $oTerm = get_term_by('id', $sVar, $sTaxonomySlug);
            if (is_object($oTerm)) {
                $sVar = $oTerm->slug;
            }
        }

        return $oQuery;
    }

    public function _replyToPrintStyle()
    {
        if ($this->oUtil->getCurrentPostType() !== $this->oProp->sPostType) {
            return;
        }
        if (isset($this->oProp->aPostTypeArgs['screen_icon']) && $this->oProp->aPostTypeArgs['screen_icon']) {
            $this->oProp->sStyle .= $this->_getStylesForPostTypeScreenIcon($this->oProp->aPostTypeArgs['screen_icon']);
        }
        $this->oProp->sStyle = $this->oUtil->addAndApplyFilters($this, "style_{$this->oProp->sClassName}", $this->oProp->sStyle);
        if (!empty($this->oProp->sStyle)) {
            echo "<style type='text/css' id='yucca-shop-style-post-type'>".$this->oProp->sStyle.'</style>';
        }
    }

    private function _getStylesForPostTypeScreenIcon($sSRC)
    {
        $sNone = 'none';
        $sSRC = esc_url($this->oUtil->getResolvedSRC($sSRC));

        return "#post-body-content {margin-bottom: 10px;}#edit-slug-box {display: {$sNone};}#icon-edit.icon32.icon32-posts-{$this->oProp->sPostType} {background: url('{$sSRC}') no-repeat;background-size: 32px 32px;} ";
    }

    public function content($sContent)
    {
        return $sContent;
    }

    public function _replyToFilterPostTypeContent($sContent)
    {
        if (!is_singular()) {
            return $sContent;
        }
        if (!is_main_query()) {
            return $sContent;
        }
        global $post;
        if ($this->oProp->sPostType !== $post->post_type) {
            return $sContent;
        }

        return $this->oUtil->addAndApplyFilters($this, "content_{$this->oProp->sClassName}", $this->content($sContent));
    }
}
abstract class yucca_shopAdminPageFramework_PostType_Controller extends yucca_shopAdminPageFramework_PostType_View
{
    public function setUp()
    {
    }

    public function load()
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

    protected function setAutoSave($bEnableAutoSave = true)
    {
        $this->oProp->bEnableAutoSave = $bEnableAutoSave;
    }

    protected function addTaxonomy($sTaxonomySlug, array $aArguments, array $aAdditionalObjectTypes = [])
    {
        $sTaxonomySlug = $this->oUtil->sanitizeSlug($sTaxonomySlug);
        $aArguments = $aArguments + ['show_table_filter' => null, 'show_in_sidebar_menus' => null, 'submenu_order' => 15];
        $this->oProp->aTaxonomies[$sTaxonomySlug] = $aArguments;
        if ($aArguments['show_table_filter']) {
            $this->oProp->aTaxonomyTableFilters[] = $sTaxonomySlug;
        }
        if (!$aArguments['show_in_sidebar_menus']) {
            $this->oProp->aTaxonomyRemoveSubmenuPages["edit-tags.php?taxonomy={$sTaxonomySlug}&amp;post_type={$this->oProp->sPostType}"] = "edit.php?post_type={$this->oProp->sPostType}";
        }
        $_aExistingObjectTypes = $this->oUtil->getElementAsArray($this->oProp->aTaxonomyObjectTypes, $sTaxonomySlug, []);
        $aAdditionalObjectTypes = array_merge($_aExistingObjectTypes, $aAdditionalObjectTypes);
        $this->oProp->aTaxonomyObjectTypes[$sTaxonomySlug] = array_unique($aAdditionalObjectTypes);
        $this->_addTaxonomy_setUpHooks($sTaxonomySlug, $aArguments, $aAdditionalObjectTypes);
    }

    private function _addTaxonomy_setUpHooks($sTaxonomySlug, array $aArguments, array $aAdditionalObjectTypes)
    {
        if (did_action('init')) {
            $this->_registerTaxonomy($sTaxonomySlug, $aAdditionalObjectTypes, $aArguments);
        } else {
            add_action('init', [$this, '_replyToRegisterTaxonomies']);
        }
        $this->oUtil->registerAction('admin_menu', [$this, '_replyToRemoveTexonomySubmenuPages'], 999);
    }

    protected function setAuthorTableFilter($bEnableAuthorTableFileter = false)
    {
        $this->oProp->bEnableAuthorTableFileter = $bEnableAuthorTableFileter;
    }

    protected function setPostTypeArgs($aArgs)
    {
        $this->setArguments((array) $aArgs);
    }

    protected function setArguments(array $aArguments = [])
    {
        $this->oProp->aPostTypeArgs = $aArguments;
    }
}
abstract class yucca_shopAdminPageFramework_PostType extends yucca_shopAdminPageFramework_PostType_Controller
{
    public function __construct($sPostType, $aArguments = [], $sCallerPath = null, $sTextDomain = 'yucca-shop')
    {
        if (empty($sPostType)) {
            return;
        }
        $this->oProp = new yucca_shopAdminPageFramework_Property_post_type($this, $this->_getCallerScriptPath($sCallerPath), get_class($this), 'publish_posts', $sTextDomain, 'post_type');
        $this->oProp->sPostType = yucca_shopAdminPageFramework_WPUtility::sanitizeSlug($sPostType);
        $this->oProp->aPostTypeArgs = $aArguments;
        parent::__construct($this->oProp);
    }

    private function _getCallerScriptPath($sCallerPath)
    {
        $sCallerPath = trim($sCallerPath);
        if ($sCallerPath) {
            return $sCallerPath;
        }
        if (!is_admin()) {
            return;
        }
        $_sPageNow = yucca_shopAdminPageFramework_Utility::getElement($GLOBALS, 'pagenow');
        if (in_array($_sPageNow, ['edit.php', 'post.php', 'post-new.php', 'plugins.php', 'tags.php', 'edit-tags.php'])) {
            return yucca_shopAdminPageFramework_Utility::getCallerScriptPath(__FILE__);
        }
    }
}
