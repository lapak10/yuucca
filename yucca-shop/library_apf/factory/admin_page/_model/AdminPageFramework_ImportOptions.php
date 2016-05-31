<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_ImportOptions extends yucca_shopAdminPageFramework_CustomSubmitFields
{
    public $aFilesImport = [];

    public function __construct($aFilesImport, $aPostImport)
    {
        parent::__construct($aPostImport);
        $this->aFilesImport = $aFilesImport;
    }

    private function getElementInFilesArray(array $aFilesImport, $sInputID, $sElementKey = 'error')
    {
        $sElementKey = strtolower($sElementKey);

        return $this->getElement($aFilesImport, [$sElementKey, $sInputID], null);
    }

    public function getError()
    {
        return $this->getElementInFilesArray($this->aFilesImport, $this->sInputID, 'error');
    }

    public function getType()
    {
        return $this->getElementInFilesArray($this->aFilesImport, $this->sInputID, 'type');
    }

    public function getImportData()
    {
        $sFilePath = $this->getElementInFilesArray($this->aFilesImport, $this->sInputID, 'tmp_name');
        $vData = file_exists($sFilePath) ? file_get_contents($sFilePath, true) : false;

        return $vData;
    }

    public function formatImportData(&$vData, $sFormatType = null)
    {
        $sFormatType = isset($sFormatType) ? $sFormatType : $this->getFormatType();
        switch (strtolower($sFormatType)) {
            case 'text':
                return;
            case 'json':
                $vData = json_decode((string) $vData, true);

                return;
            case 'array':
            default:
                $vData = maybe_unserialize(trim($vData));

                return;
        }
    }

    public function getFormatType()
    {
        $this->sFormatType = isset($this->sFormatType) && $this->sFormatType ? $this->sFormatType : $this->getSubmitValueByType($this->aPost, $this->sInputID, 'format');

        return $this->sFormatType;
    }
}
