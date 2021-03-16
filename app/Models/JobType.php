<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $table = "ipc.ipc_jo_job_types";

    protected $fillable = ['id','job_name','status','created_by','updated_by'];
}
