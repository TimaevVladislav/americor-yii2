<?php

namespace app\widgets\Export;

use kartik\export\ExportMenu;
use Yii;

class Export extends ExportMenu
{
    public $exportType = self::FORMAT_CSV;

    public function init()
    {
        $this->initializeOptions();
        $this->setExportRequestParams();

        parent::init();
    }

    private function initializeOptions()
    {
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (empty($this->exportRequestParam)) {
            $this->exportRequestParam = 'exportFull_' . $this->options['id'];
        }
    }

    private function setExportRequestParams()
    {
        $_POST[Yii::$app->request->methodParam] = 'POST';
        $_POST[$this->exportRequestParam] = true;
        $_POST[$this->exportTypeParam] = $this->exportType;
        $_POST[$this->colSelFlagParam] = false;
    }
}
