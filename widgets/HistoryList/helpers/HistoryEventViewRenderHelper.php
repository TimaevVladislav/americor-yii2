<?php

namespace app\widgets\HistoryList\helpers;

use app\models\Call;
use app\models\Customer;
use app\models\History;
use app\models\Sms;

use yii\helpers\Html;
use Yii;

class HistoryEventViewRenderHelper
{
    public static function render(History $model)
    {
        if (method_exists(self::class, $renderMethod = "render{$model->event}Event")) {
            return self::$renderMethod($model);
        }

        return self::renderDefaultEvent($model);
    }

    private static function renderTaskEvent(History $model)
    {
        $task = $model->task;
        $creditorName = isset($task->customerCreditor->name) ? $task->customerCreditor->name : '';

        return self::renderCommonItem([
            'user' => $model->user,
            'body' => HistoryListHelper::getBodyByModel($model),
            'iconClass' => 'fa-check-square bg-yellow',
            'footerDatetime' => $model->ins_ts,
            'footer' => "Creditor: $creditorName",
        ]);
    }

    private static function renderSmsEvent(History $model)
    {
        $sms = $model->sms;

        return self::renderCommonItem([
            'user' => $model->user,
            'body' => HistoryListHelper::getBodyByModel($model),
            'footer' => $sms->direction == Sms::DIRECTION_INCOMING ?
                Yii::t('app', 'Incoming message from {number}', ['number' => $sms->phone_from ?? '']) :
                Yii::t('app', 'Sent message to {number}', ['number' => $sms->phone_to ?? '']),
            'iconIncome' => $sms->direction == Sms::DIRECTION_INCOMING,
            'footerDatetime' => $model->ins_ts,
            'iconClass' => 'icon-sms bg-dark-blue',
        ]);
    }

    private static function renderFaxEvent(History $model)
    {
        $fax = $model->fax;

        return self::renderCommonItem([
            'user' => $model->user,
            'body' => HistoryListHelper::getBodyByModel($model) .
                ' - ' .
                (isset($fax->document) ? Html::a(
                    Yii::t('app', 'view document'),
                    $fax->document->getViewUrl(),
                    ['target' => '_blank', 'data-pjax' => 0]
                ) : ''),
            'footer' => Yii::t('app', '{type} was sent to {group}', [
                'type' => $fax ? $fax->getTypeText() : 'Fax',
                'group' => isset($fax->creditorGroup) ?
                    Html::a($fax->creditorGroup->name, ['creditors/groups'], ['data-pjax' => 0]) :
                    '',
            ]),
            'footerDatetime' => $model->ins_ts,
            'iconClass' => 'fa-fax bg-green',
        ]);
    }

    private static function renderCustomerChangeTypeEvent(History $model)
    {
        return self::renderStatusesChangeEvent([
            'model' => $model,
            'oldValue' => Customer::getTypeTextByType($model->getDetailOldValue('type')),
            'newValue' => Customer::getTypeTextByType($model->getDetailNewValue('type')),
        ]);
    }

    private static function renderCustomerChangeQualityEvent(History $model)
    {
        return self::renderStatusesChangeEvent([
            'model' => $model,
            'oldValue' => Customer::getQualityTextByQuality($model->getDetailOldValue('quality')),
            'newValue' => Customer::getQualityTextByQuality($model->getDetailNewValue('quality')),
        ]);
    }

    private static function renderCallEvent(History $model)
    {
        $call = $model->call;
        $answered = $call && $call->status == Call::STATUS_ANSWERED;

        return self::renderCommonItem([
            'user' => $model->user,
            'content' => $call->comment ?? '',
            'body' => HistoryListHelper::getBodyByModel($model),
            'footerDatetime' => $model->ins_ts,
            'footer' => isset($call->applicant) ? "Called <span>{$call->applicant->name}</span>" : null,
            'iconClass' => $answered ? 'md-phone bg-green' : 'md-phone-missed bg-red',
            'iconIncome' => $answered && $call->direction == Call::DIRECTION_INCOMING,
        ]);
    }

    private static function renderDefaultEvent(History $model)
    {
        return self::renderCommonItem([
            'user' => $model->user,
            'body' => HistoryListHelper::getBodyByModel($model),
            'bodyDatetime' => $model->ins_ts,
            'iconClass' => 'fa-gear bg-purple-light',
        ]);
    }

    private static function renderCommonItem($params)
    {
        return Yii::$app->view->render('_item_common', $params);
    }

    private static function renderStatusesChangeEvent($params)
    {
        return Yii::$app->view->render('_item_statuses_change', $params);
    }
}