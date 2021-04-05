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

    public function get($id)
    {
        $sql = "SELECT  jh.id,
                        cust_type.type customer_type,
                        jh.customer_name,
                        jh.created_by,
                        jh.created_at,
                        st.status,
                        jh.section,
                        jh.department,
                        jh.date_sold,
                        jh.vin,
                        vehicle.engine,
                        vehicle.color,
                        vehicle.sales_model,
                        jh.plate_no,
                        vehicle.serial_number cs_no,
                        jh.mileage,
                        jh.contact_number,
                        jh.customer_type_id,
                        jh.technician_id,
                        jh.start_date,
                        jh.completion_date,
                        tech.first_name || ' ' || tech.last_name technician_name
                FROM ipc.ipc_jo_headers jh
                    LEFT JOIN ipc.ipc_jo_vehicles_v vehicle
                        ON jh.vin = vehicle.vin
                    LEFT JOIN ipc.ipc_jo_status st
                        ON st.id = jh.status
                    LEFT JOIN ipc.ipc_jo_customer_types cust_type
                        ON cust_type.id = jh.customer_type_id  
                    LEFT JOIN ipc.ipc_jo_technicians tech
                        ON tech.id = jh.technician_id
                WHERE jh.id = :job_order_id";
        $query = DB::select($sql, ['job_order_id' => $id]);

        return count($query) > 0 ? $query[0] : $query;   
    }
}
