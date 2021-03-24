<?php

namespace App\Services;

use App\Models\Log;

class LogService 
{
    public function createLog($params)
    {
        $log = new Log;
        $log->content = $params['content'];
        $log->recipient = $params['recipient'];
        $log->reference_id = $params['reference_id'];
        $log->module_code = $params['module_code'];
        $log->mail_flag = $params['mail_flag'];
        $log->save();
    }
}