<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JOLine;
use App\Models\JOLinePart;

class JobOrderLineController extends Controller
{
    public function index(Request $request)
    {
        $lines = JOLine::with('parts')
                    ->where('job_header_id', $request->job_order_id)
                    ->get();
        
 

        return $lines;
    }

}
