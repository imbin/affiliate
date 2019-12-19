<?php
/**
 * Author: zhaobin
 * Date: 2019-11-24
 */

namespace App\Events;


class NewOrderRecordEvent
{
    public $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }
}