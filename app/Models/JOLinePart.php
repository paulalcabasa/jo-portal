<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JOLinePart extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_line_parts";
}
