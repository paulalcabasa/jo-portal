<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JOHeader;

class SetScheduleController extends Controller
{
    public function store(Request $request)
    {
        $joHeader = JOHeader::findOrFail($request->job_order_id);
        $joHeader->technician_id = $request->assigned_technician_id['id'];
        $joHeader->start_date = $request->start_date;
        $joHeader->updated_by = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $joHeader->save();
    }
}
