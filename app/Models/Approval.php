<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_approval";
}
