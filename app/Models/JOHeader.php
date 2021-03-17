<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class JOHeader extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_headers";

    public function getAll()
    {
        $sql = "SELECT jh.id,
                        jh.vin,
                        vehicle.engine,
                        vehicle.color,
                        vehicle.sales_model,
                        jh.created_by,
                        jh.created_at,
                        st.status,
                        jh.customer_name,
                        jh.plate_no,
                        vehicle.serial_number
                FROM ipc.ipc_jo_headers jh
                    LEFT JOIN ipc.ipc_jo_vehicles_v vehicle
                        ON jh.vin = vehicle.vin
                    LEFT JOIN ipc.ipc_jo_status st
                        ON st.id = jh.status";
        $query = DB::select($sql);
        return $query;   
    }
}
