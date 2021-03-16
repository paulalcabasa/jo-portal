<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobParts extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_job_parts";

    public function get($params){
        $sql = "SELECT parts.id,
                        parts.part_no,
                        parts.part_description,
                        parts.remarks,
                        parts.quantity
                FROM ipc.ipc_jo_job_parts parts
                LEFT JOIN  ipc.ipc_jo_job_models models
                    ON parts.job_model_id = models.id
                WHERE models.sales_model = :sales_model
                    AND models.job_type_id = :job_type_id";  
        $query = DB::select($sql, $params);
        return $query;
    }
}
