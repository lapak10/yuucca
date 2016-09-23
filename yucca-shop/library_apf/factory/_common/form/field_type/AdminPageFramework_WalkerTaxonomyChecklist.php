<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_WalkerTaxonomyChecklist extends Walker_Category
{
    public function start_el(&$sOutput, $oTerm, $iDepth = 0, $aArgs = [], $iCurrentObjectID = 0)
    {
        $aArgs = $aArgs + ['_name_prefix' => null, '_input_id_prefix' => null, '_attributes' => [], '_selected_items' => [], 'taxonomy' => null, 'disabled' => null];
        $_iID = $oTerm->term_id;
        $_sTaxonomySlug = empty($aArgs['taxonomy']) ? 'category' : $aArgs['taxonomy'];
        $_sID = "{$aArgs['_input_id_prefix']}_{$_sTaxonomySlug}_{$_iID}";
        $_sPostCount = $aArgs['show_post_count'] ? " <span class='font-lighter'>(".$oTerm->count.')</span>' : '';
        $_aInputAttributes = isset($_aInputAttributes[$_iID]) ? $_aInputAttributes[$_iID] + $aArgs['_attributes'] : $aArgs['_attributes'];
        $_aInputAttributes = ['id' => $_sID, 'value' => 1, 'type' => 'checkbox', 'name' => "{$aArgs['_name_prefix']}[{$_iID}]", 'checked' => in_array($_iID, (array) $aArgs['_selected_items']) ? 'checked' : null] + $_aInputAttributes + ['class' => null];
        $_aInputAttributes['class'] .= ' apf_checkbox';
        $_aLiTagAttributes = ['id' => "list-{$_sID}", 'class' => 'category-list', 'title' => $oTerm->description];
        $sOutput .= "\n".'<li '.yucca_shopAdminPageFramework_WPUtility::getAttributes($_aLiTagAttributes).'>'."<label for='{$_sID}' class='taxonomy-checklist-label'>"."<input value='0' type='hidden' name='".$_aInputAttributes['name']."' class='apf_checkbox' />".'<input '.yucca_shopAdminPageFramework_WPUtility::getAttributes($_aInputAttributes).' />'.esc_html(apply_filters('the_category', $oTerm->name)).$_sPostCount.'</label>';
    }
}
