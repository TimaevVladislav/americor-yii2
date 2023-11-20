<?php
use app\models\User;
use app\widgets\DateTime\DateTime;
use yii\helpers\Html;

/**
 * Notification Widget View
 *
 * @var $user User|null
 * @var $body string
 * @var $footer string|null
 * @var $footerDatetime string|null
 * @var $bodyDatetime string|null
 * @var $iconClass string
 */
?>

<?= Html::tag('i', '', ['class' => "icon icon-circle icon-main white $iconClass"]) ?>

<div class="bg-success">
    <?= $body ?>

    <?php if (isset($bodyDatetime)): ?>
        <span>
            <?= DateTime::widget(['dateTime' => $bodyDatetime]) ?>
        </span>
    <?php endif; ?>
</div>

<?php if (isset($user)): ?>
    <div class="bg-info"><?= $user->username ?></div>
<?php endif; ?>

<?php if (isset($content) && $content): ?>
    <div class="bg-info">
        <?= $content ?>
    </div>
<?php endif; ?>

<?php if (isset($footer) || isset($footerDatetime)): ?>
    <div class="bg-warning">
        <?= isset($footer) ? $footer : '' ?>
        <?php if (isset($footerDatetime)): ?>
            <span><?= DateTime::widget(['dateTime' => $footerDatetime]) ?></span>
        <?php endif; ?>
    </div>
<?php endif; ?>
