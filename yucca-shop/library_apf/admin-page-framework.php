<?php

/**
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> Included Components: Admin Pages, Network Admin Pages, Custom Post Types, Taxonomy Fields, Post Meta Boxes, Page Meta Boxes, Widgets, User Meta, Utilities
 Generated on 2016-02-16 */
if (!class_exists('yucca_shopAdminPageFramework_Registry', false)):
    abstract class yucca_shopAdminPageFramework_Registry_Base
    {
        const VERSION = '3.7.11';
        const NAME = 'Admin Page Framework';
        const DESCRIPTION = 'Facilitates WordPress plugin and theme development.';
        const URI = 'http://en.michaeluno.jp/admin-page-framework';
        const AUTHOR = 'Michael Uno';
        const AUTHOR_URI = 'http://en.michaeluno.jp/';
        const COPYRIGHT = 'Copyright (c) 2013-2016, Michael Uno';
        const LICENSE = 'MIT <http://opensource.org/licenses/MIT>';
        const CONTRIBUTORS = '';
    }
    final class yucca_shopAdminPageFramework_Registry extends yucca_shopAdminPageFramework_Registry_Base
    {
        const TEXT_DOMAIN = 'admin-page-framework';
        const TEXT_DOMAIN_PATH = '/language';
        public static $bIsMinifiedVersion = true;
        public static $bIsDevelopmentVersion = true;
        public static $sAutoLoaderPath;
        public static $sIncludeClassListPath;
        public static $aClassFiles = [];
        public static $sFilePath = '';
        public static $sDirPath = '';

        public static function setUp($sFilePath = __FILE__)
        {
            self::$sFilePath = $sFilePath;
            self::$sDirPath = dirname(self::$sFilePath);
            self::$sIncludeClassListPath = self::$sDirPath.'/admin-page-framework-include-class-list.php';
            self::$aClassFiles = self::_getClassFilePathList(self::$sIncludeClassListPath);
            self::$sAutoLoaderPath = isset(self::$aClassFiles['yucca_shopAdminPageFramework_RegisterClasses']) ? self::$aClassFiles['yucca_shopAdminPageFramework_RegisterClasses'] : '';
            self::$bIsMinifiedVersion = class_exists('yucca_shopAdminPageFramework_MinifiedVersionHeader', false);
            self::$bIsDevelopmentVersion = isset(self::$aClassFiles['yucca_shopAdminPageFramework_InclusionClassFilesHeader']);
        }

        private static function _getClassFilePathList($sInclusionClassListPath)
        {
            $aClassFiles = [];
            include $sInclusionClassListPath;

            return $aClassFiles;
        }

        public static function getVersion()
        {
            if (!isset(self::$sAutoLoaderPath)) {
                trigger_error('Admin Page Framework: '.' : '.sprintf(__('The method is called too early. Perform <code>%2$s</code> earlier.', 'admin-page-framework'), __METHOD__, 'setUp()'), E_USER_WARNING);

                return self::VERSION;
            }
            $_aMinifiedVesionSuffix = [0 => '', 1 => '.min'];
            $_aDevelopmentVersionSuffix = [0 => '', 1 => '.dev'];

            return self::VERSION.$_aMinifiedVesionSuffix[(int) self::$bIsMinifiedVersion].$_aDevelopmentVersionSuffix[(int) self::$bIsDevelopmentVersion];
        }

        public static function getInfo()
        {
            $_oReflection = new ReflectionClass(__CLASS__);

            return $_oReflection->getConstants() + $_oReflection->getStaticProperties();
        }
    }
endif;
if (!class_exists('yucca_shopAdminPageFramework_Bootstrap', false)):
    final class yucca_shopAdminPageFramework_Bootstrap
    {
        private static $_bLoaded = false;

        public function __construct($sLibraryPath)
        {
            if (!$this->_isLoadable()) {
                return;
            }
            yucca_shopAdminPageFramework_Registry::setUp($sLibraryPath);
            if (yucca_shopAdminPageFramework_Registry::$bIsMinifiedVersion) {
                return;
            }
            $this->_include();
        }

        private function _isLoadable()
        {
            if (self::$_bLoaded) {
                return false;
            }
            self::$_bLoaded = true;

            return defined('ABSPATH');
        }

        private function _include()
        {
            include yucca_shopAdminPageFramework_Registry::$sAutoLoaderPath;
            new yucca_shopAdminPageFramework_RegisterClasses('', ['exclude_class_names' => ['yucca_shopAdminPageFramework_MinifiedVersionHeader', 'yucca_shopAdminPageFramework_BeautifiedVersionHeader']], yucca_shopAdminPageFramework_Registry::$aClassFiles);
            self::$_bXDebug = isset(self::$_bXDebug) ? self::$_bXDebug : extension_loaded('xdebug');
            if (self::$_bXDebug) {
                new yucca_shopAdminPageFramework_Utility();
                new yucca_shopAdminPageFramework_WPUtility();
            }
        }

        private static $_bXDebug;
    }
    new yucca_shopAdminPageFramework_Bootstrap(__FILE__);
endif;
