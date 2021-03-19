<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Department extends Model
{
    protected $connection = "ipc_central";
    protected $table = "ipc_central.department_tab";

  
    
}
