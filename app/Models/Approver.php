<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Approver extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_approvers";

    public function getApprovers()
    {
        $sql = "SELECT approver.id approver_id,
                        approver.employee_number,
                        approver.sequence_no,
                        approver.module_code
                FROM ipc.ipc_jo_approvers approver
                    LEFT JOIN per_people_f ppf
                        ON approver.employee_number = ppf.employee_number";
        $query = DB::select($sql);
        return $query;
    }
}
