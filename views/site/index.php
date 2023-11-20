<?php

use app\widgets\HistoryList\HistoryList;

/**
 * @var $this yii\web\View
 */

$this->title = Yii::t('app', 'Americor Test');
?>

<div class="site-index">
    <?= HistoryList::widget([]) ?>
</div>
