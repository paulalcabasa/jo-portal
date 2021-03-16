<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $connection = "ipc_central";
    protected $table = "ipc_central.section_tab";
}
