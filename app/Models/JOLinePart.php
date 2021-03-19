<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class JOLinePart extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_line_parts";

    public function deleteByJobHeaderId($job_header_id)
    {
        $sql = "DELETE FROM ipc.ipc_jo_line_parts WHERE id IN (SELECT line_parts.id
                FROM ipc.ipc_jo_line_parts line_parts
                        LEFT JOIN ipc.ipc_jo_lines lines
                            ON line_parts.job_line_id = lines.id
                WHERE  1 = 1
                    AND lines.job_header_id = ".$job_header_id.")";
        DB::raw($sql);
    }
}
