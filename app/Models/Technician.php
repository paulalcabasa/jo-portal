<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_technicians";
}
