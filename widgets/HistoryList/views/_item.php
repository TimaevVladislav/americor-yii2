<?php
use app\models\search\HistorySearch;
use app\widgets\HistoryList\helpers\HistoryEventViewRenderHelper;

/** @var $model HistorySearch */

echo HistoryEventViewRenderHelper::render($model);