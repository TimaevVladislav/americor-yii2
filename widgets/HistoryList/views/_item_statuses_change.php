<?php
use app\models\History;
use app\widgets\DateTime\DateTime;
use yii\helpers\Html;

/* @var $model History */
/* @var $oldValue string */
/* @var $newValue string */
/* @var $content string */
?>

<div class="bg-success">
    <?= "{$model->eventText} " ?>
    <span class="badge badge-pill badge-warning">
        <?= Html::encode($oldValue ?? '<i>not set</i>') ?>
    </span>
    &#8594;
    <span class="badge badge-pill badge-success">
        <?= Html::encode($newValue ?? '<i>not set</i>') ?>
    </span>

    <span><?= DateTime::widget(['dateTime' => $model->ins_ts]) ?></span>
</div>

<?php if ($model->user): ?>
    <div class="bg-info"><?= Html::encode($model->user->username) ?></div>
<?php endif; ?>

<?php if ($content): ?>
    <div class="bg-info">
        <?= Html::encode($content) ?>
    </div>
<?php endif; ?>
