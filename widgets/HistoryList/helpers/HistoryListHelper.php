<?php

namespace app\widgets\HistoryList\helpers;

use app\models\Customer;
use app\models\History;

class HistoryListHelper
{
    public static function getBodyByModel(History $model)
    {
        switch ($model->event) {
            case History::EVENT_CREATED_TASK:
            case History::EVENT_COMPLETED_TASK:
            case History::EVENT_UPDATED_TASK:
                return self::getTaskEventText($model);
            case History::EVENT_INCOMING_SMS:
            case History::EVENT_OUTGOING_SMS:
                return self::getSmsEventText($model);
            case History::EVENT_OUTGOING_FAX:
            case History::EVENT_INCOMING_FAX:
                return $model->eventText;
            case History::EVENT_CUSTOMER_CHANGE_TYPE:
                return self::getCustomerChangeEventText($model, 'type');
            case History::EVENT_CUSTOMER_CHANGE_QUALITY:
                return self::getCustomerChangeEventText($model, 'quality');
            case History::EVENT_INCOMING_CALL:
            case History::EVENT_OUTGOING_CALL:
                return self::getCallEventText($model);
            default:
                return $model->eventText;
        }
    }

    private static function getTaskEventText(History $model): string
    {
        $task = $model->task;
        return "$model->eventText: " . ($task->title ?? '');
    }

    private static function getSmsEventText(History $model): string
    {
        return $model->sms->message ?? '';
    }

    private static function getCustomerChangeEventText(History $model, string $detail): string
    {
        $oldValue = $model->getDetailOldValue($detail);
        $newValue = $model->getDetailNewValue($detail);
        $oldText = $detail === 'type' ? Customer::getTypeTextByType($oldValue) : Customer::getQualityTextByQuality($oldValue);
        $newText = $detail === 'type' ? Customer::getTypeTextByType($newValue) : Customer::getQualityTextByQuality($newValue);

        return "$model->eventText " . ($oldText ?? "not set") . ' to ' . ($newText ?? "not set");
    }

    private static function getCallEventText(History $model): string
    {
        $call = $model->call;
        if ($call) {
            $totalDisposition = $call->getTotalDisposition(false);
            return $call->totalStatusText . ($totalDisposition ? " <span class='text-grey'>$totalDisposition</span>" : "");
        }

        return '<i>Deleted</i>';
    }
}