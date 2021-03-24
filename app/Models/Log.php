<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_activity_logs";
}
