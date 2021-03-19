<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JOLine extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_lines";

    public function parts()
    {
        return $this->hasMany(
            'App\Models\JOLinePart', 
            'job_line_id', 'id'
        );
    }
}
