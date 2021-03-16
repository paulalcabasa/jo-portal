<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobModel extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_job_models";
}
