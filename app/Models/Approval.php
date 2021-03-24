<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Approval extends Model
{
    protected $connection = "oracle";
    protected $table = "ipc.ipc_jo_approval";
    
    public function getJOApproval($job_order_id)
    {
        $sql = "SELECT approval.id,     
                    approver.approver_name,
                    approver.employee_number,
                    approval.sequence_no,
                    approver.email_address,
                    st.status,
                    approval.date_sent,
                    approval.date_approved
                FROM ipc.ipc_jo_approval approval
                    LEFT JOIN ipc.ipc_jo_approvers approver
                        ON approval.approver_id = approver.id
                    LEFT JOIN fnd_user fu   
                        ON fu.user_name = approver.employee_number
                    LEFT JOIN ipc.ipc_jo_status st
                        ON st.id = approval.status
                WHERE 1 = 1
                    AND approval.reference_id = :job_order_id
                    ORDER BY approval.sequence_no ASC";
        $query = DB::select($sql, ['job_order_id' => $job_order_id]);
        return $query;
    }

    public function getCurrentJOApproval()
    {
        $sql = "SELECT headers.id job_order_id,
                        approval.sequence_no,
                        headers.vin,
                        headers.customer_name,
                        headers.department,
                        headers.section,
                        headers.plate_no,
                        approval.id approval_id,
                        approver.approver_name,
                        approver.email_address,
                        approver.employee_number,
                        jo_status_lookup.status job_order_status,
                        approval_status_lookup.status approval_status,
                        headers.created_by,
                        to_char(headers.created_at, 'MM/DD/YYYY') date_created,
                        cust_types.type customer_type,
                        vehicles.sales_model,
                        vehicles.color,
                        vehicles.serial_number
                FROM ipc.ipc_jo_headers headers 
                    INNER JOIN ipc.ipc_jo_approval approval
                        ON headers.id = approval.reference_id
                        AND headers.current_approver_sequence = approval.sequence_no
                    INNER JOIN ipc.ipc_jo_approvers approver
                        ON approver.id = approval.approver_id
                    INNER JOIN ipc.ipc_jo_status jo_status_lookup
                        ON jo_status_lookup.id = headers.status
                    INNER JOIN ipc.ipc_jo_status approval_status_lookup
                        ON approval_status_lookup.id = approval.status
                    INNER JOIN ipc.ipc_jo_customer_types cust_types
                        ON cust_types.id = headers.customer_type_id
                    INNER JOIN ipc.ipc_jo_vehicles_v vehicles
                        ON vehicles.vin = headers.vin
                WHERE 1 = 1
                    AND approval.date_sent IS NULL";
        $query = DB::select($sql);
        return $query;
    }

    public function getMaxApproval($job_order_id){
        $sql = "SELECT max(approval.sequence_no) sequence_no
                FROM ipc.ipc_jo_approval approval
                WHERE approval.reference_id = :reference_id";
        $query = DB::select($sql,['reference_id' => $job_order_id]);
        return !empty($query) ? $query[0] : $query;
    }
}
