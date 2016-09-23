<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_CSS
{
    public static function getDefaultCSS()
    {
        $_sCSS = ".wrap div.updated.yucca-shop-settings-notice-container, .wrap div.error.yucca-shop-settings-notice-container, .media-upload-form div.error.yucca-shop-settings-notice-container{clear: both;margin-top: 16px;}.wrap div.error.confirmation.yucca-shop-settings-notice-container {border-color: #368ADD;}.contextual-help-description {clear: left;display: block;margin: 1em 0;}.contextual-help-tab-title {font-weight: bold;}.yucca-shop-content {margin-bottom: 1.48em; width: 100%;display: block; }.yucca-shop-container #poststuff .yucca-shop-content h3 {font-weight: bold;font-size: 1.3em;margin: 1em 0;padding: 0;font-family: 'Open Sans', sans-serif;} .nav-tab.tab-disabled,.nav-tab.tab-disabled:hover {font-weight: normal;color: #AAAAAA;} .yucca-shop-in-page-tab .nav-tab.nav-tab-active {border-bottom-width: 2px;}.wrap .yucca-shop-in-page-tab div.error, .wrap .yucca-shop-in-page-tab div.updated {margin-top: 15px;}.yucca-shop-info {font-size: 0.8em;font-weight: lighter;text-align: right;}pre.dump-array {border: 1px solid #ededed;margin: 24px 2em;margin: 1.714285714rem 2em;padding: 24px;padding: 1.714285714rem;overflow-x: auto; white-space: pre-wrap;background-color: #FFF;margin-bottom: 2em;width: auto;}";

        return $_sCSS.PHP_EOL.self::_getPageLoadStatsRules().PHP_EOL.self::_getVersionSpecificRules();
    }

    private static function _getPageLoadStatsRules()
    {
        return '#yucca-shop-page-load-stats {clear: both;display: inline-block;width: 100%}#yucca-shop-page-load-stats li{display: inline;margin-right: 1em;} #wpbody-content {padding-bottom: 140px;}';
    }

    private static function _getVersionSpecificRules()
    {
        return '';
    }

    public static function getDefaultCSSIE()
    {
        return '';
    }
}
