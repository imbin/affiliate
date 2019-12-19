<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use DateTime;
use Illuminate\Support\Facades\Log;

class QueryListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QueryExecuted $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        $params = $event->bindings;
        foreach ($params as $index => $param) {
            if ($param instanceof DateTime) {
                $params[$index] = $param->format('Y-m-d H:i:s');
            }
        }

        $params = array_map('addslashes', $params);

        if (strpos($event->sql, 'INSERT INTO') !== false && empty($event->bindings)) {
            Log::info($event->sql);
        } else {
            $sql = str_replace("%", "##", $event->sql);
            $sql = str_replace("?", "'%s'", $sql);

            array_unshift($params, $sql);
            $sql = call_user_func_array('sprintf', $params);
            $sql = str_replace("##", "%", $sql);
            Log::info($sql);
        }

    }
}
