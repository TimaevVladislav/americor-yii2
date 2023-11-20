<?php

namespace app\widgets\HistoryList;

use app\models\search\HistorySearch;
use app\widgets\Export\Export;
use yii\base\Widget;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

class HistoryList extends Widget
{
    private $request;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->request = Yii::$app->request;
    }

    public function run()
    {
        $model = new HistorySearch();
        $dataProvider = $model->search($this->request->queryParams);

        return $this->render('main', [
            'model' => $model,
            'linkExport' => $this->getLinkExport(),
            'dataProvider' => $dataProvider
        ]);
    }

    private function getLinkExport()
    {
        if (!$this->request->getQueryParams()) {
            return Url::to(['site/export', 'exportType' => Export::FORMAT_CSV]);
        }

        $params = ArrayHelper::merge([
            'exportType' => Export::FORMAT_CSV
        ], $this->request->getQueryParams());

        $params[0] = 'site/export';

        return Url::to($params);
    }
}