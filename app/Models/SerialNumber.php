<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SerialNumber extends Model
{
    protected $connection = "oracle";
    protected $table      = "apps.mtl_serial_numbers";
    protected $primaryKey = "serial_number";

    public function findByVin($vin)
    {
        $sql = "SELECT msib.inventory_item_id,
                        msib.organization_id,
                        ipc_dms.ipc_get_vehicle_variant (msib.segment1) model_variant,
                        NVL (msib.attribute8, 'NO COLOR')               color,
                        msib.segment1                                   prod_model,
                        msib.description                                prod_model_desc,
                        msib.attribute9                                 sales_model,
                        msib.creation_Date,
                        msn.attribute2 vin,
                        msn.serial_number,
                        msn.attribute3 engine
                FROM apps.mtl_system_items_b msib
                    LEFT JOIN apps.mtl_serial_numbers msn
                        ON msib.inventory_item_id = msn.inventory_item_id
                        AND msib.organization_id = msn.current_organization_id
                WHERE     1 = 1
                    AND msib.organization_id IN (121)
                    AND msib.inventory_item_status_code = 'Active'
                    AND msib.attribute9 IS NOT NULL
                    AND msib.item_type = 'FG'
                    AND msn.attribute2 = :vin";
        $query = DB::select($sql, ['vin' => $vin]);

        return $query ? $query[0] : [];
    }
}
