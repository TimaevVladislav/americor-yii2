<?php

namespace app\widgets\HistoryList\helpers;

use app\models\Customer;
use app\models\History;

class HistoryListHelper
{
    public static function getBodyByModel(History $model): string
    {
        $eventHandlers = [
            History::EVENT_CREATED_TASK => 'getTaskEventText',
            History::EVENT_COMPLETED_TASK => 'getTaskEventText',
            History::EVENT_UPDATED_TASK => 'getTaskEventText',
            History::EVENT_INCOMING_SMS => 'getSmsEventText',
            History::EVENT_OUTGOING_SMS => 'getSmsEventText',
            History::EVENT_OUTGOING_FAX => 'getEventText',
            History::EVENT_INCOMING_FAX => 'getEventText',
            History::EVENT_CUSTOMER_CHANGE_TYPE => 'getCustomerChangeEventText',
            History::EVENT_CUSTOMER_CHANGE_QUALITY => 'getCustomerChangeEventText',
            History::EVENT_INCOMING_CALL => 'getCallEventText',
            History::EVENT_OUTGOING_CALL => 'getCallEventText'
        ];

        $handler = $eventHandlers[$model->event] ?? null;

        if ($handler && method_exists(self::class, $handler)) {
            return self::$handler($model);
        }

        return $model->eventText;
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