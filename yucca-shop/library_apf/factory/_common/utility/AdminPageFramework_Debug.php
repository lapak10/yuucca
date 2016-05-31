<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Debug extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public static function dump($asArray, $sFilePath = null)
    {
        echo self::get($asArray, $sFilePath);
    }

    public static function dumpArray($asArray, $sFilePath = null)
    {
        self::dump($asArray, $sFilePath);
    }

    public static function get($asArray, $sFilePath = null, $bEscape = true)
    {
        if ($sFilePath) {
            self::log($asArray, $sFilePath);
        }

        return $bEscape ? "<pre class='dump-array'>".htmlspecialchars(self::getAsString($asArray)).'</pre>' : self::getAsString($asArray);
    }

    public static function getArray($asArray, $sFilePath = null, $bEscape = true)
    {
        return self::get($asArray, $sFilePath, $bEscape);
    }

    public static function log($mValue, $sFilePath = null)
    {
        static $_fPreviousTimeStamp = 0;
        $_oCallerInfo = debug_backtrace();
        $_sCallerFunction = self::getElement($_oCallerInfo, [1, 'function'], '');
        $_sCallerClass = self::getElement($_oCallerInfo, [1, 'class'], '');
        $_fCurrentTimeStamp = microtime(true);
        file_put_contents(self::_getLogFilePath($sFilePath, $_sCallerClass), self::_getLogHeadingLine($_fCurrentTimeStamp, round($_fCurrentTimeStamp - $_fPreviousTimeStamp, 3), $_sCallerClass, $_sCallerFunction).PHP_EOL.self::_getLogContents($mValue), FILE_APPEND);
        $_fPreviousTimeStamp = $_fCurrentTimeStamp;
    }

    private static function _getLogFilePath($sFilePath, $sCallerClass)
    {
        if (file_exists($sFilePath)) {
            return $sFilePath;
        }
        if (true === $sFilePath) {
            return WP_CONTENT_DIR.DIRECTORY_SEPARATOR.get_class().'_'.date('Ymd').'.log';
        }

        return WP_CONTENT_DIR.DIRECTORY_SEPARATOR.get_class().'_'.$sCallerClass.'_'.date('Ymd').'.log';
    }

    private static function _getLogContents($mValue)
    {
        $_sType = gettype($mValue);
        $_iLengths = self::_getValueLength($mValue, $_sType);

        return '('.$_sType.(null !== $_iLengths ? ', length: '.$_iLengths : '').') '.self::getAsString($mValue).PHP_EOL.PHP_EOL;
    }

    private static function _getValueLength($mValue, $sVariableType)
    {
        if (in_array($sVariableType, ['string', 'integer'])) {
            return strlen($mValue);
        }
        if ('array' === $sVariableType) {
            return count($mValue);
        }
    }

    private static function _getLogHeadingLine($fCurrentTimeStamp, $nElapsed, $sCallerClass, $sCallerFunction)
    {
        static $_iPageLoadID;
        static $_nGMTOffset;
        $_nGMTOffset = isset($_nGMTOffset) ? $_nGMTOffset : get_option('gmt_offset');
        $_iPageLoadID = $_iPageLoadID ? $_iPageLoadID : uniqid();
        $_nNow = $fCurrentTimeStamp + ($_nGMTOffset * 60 * 60);
        $_nMicroseconds = str_pad(round(($_nNow - floor($_nNow)) * 10000), 4, '0');
        $_aOutput = [date('Y/m/d H:i:s', $_nNow).'.'.$_nMicroseconds, self::_getFormattedElapsedTime($nElapsed), $_iPageLoadID, yucca_shopAdminPageFramework_Registry::getVersion(), $sCallerClass.'::'.$sCallerFunction, current_filter(), self::getCurrentURL()];

        return implode(' ', $_aOutput);
    }

    private static function _getFormattedElapsedTime($nElapsed)
    {
        $_aElapsedParts = explode('.', (string) $nElapsed);
        $_sElapsedFloat = str_pad(self::getElement($_aElapsedParts, 1, 0), 3, '0');
        $_sElapsed = self::getElement($_aElapsedParts, 0, 0);
        $_sElapsed = strlen($_sElapsed) > 1 ? '+'.substr($_sElapsed, -1, 2) : ' '.$_sElapsed;

        return $_sElapsed.'.'.$_sElapsedFloat;
    }

    public static function logArray($asArray, $sFilePath = null)
    {
        self::log($asArray, $sFilePath);
    }

    public static function getAsString($mValue)
    {
        $mValue = is_object($mValue) ? (method_exists($mValue, '__toString') ? (string) $mValue : (array) $mValue) : $mValue;
        $mValue = is_array($mValue) ? self::getSliceByDepth($mValue, 5) : $mValue;

        return print_r($mValue, true);
    }

    public static function getSliceByDepth(array $aSubject, $iDepth = 0)
    {
        foreach ($aSubject as $_sKey => $_vValue) {
            if (is_object($_vValue)) {
                $aSubject[$_sKey] = method_exists($_vValue, '__toString') ? (string) $_vValue : get_object_vars($_vValue);
            }
            if (is_array($_vValue)) {
                $_iDepth = $iDepth;
                if ($iDepth > 0) {
                    $aSubject[$_sKey] = self::getSliceByDepth($_vValue, --$iDepth);
                    $iDepth = $_iDepth;
                    continue;
                }
                unset($aSubject[$_sKey]);
            }
        }

        return $aSubject;
    }
}
