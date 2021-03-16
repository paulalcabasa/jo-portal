<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobParts;

class JobPartsController extends Controller
{
    public function get(Request $request)
    {
        $job_type_id = $request->job_type_id;
        $sales_model = $request->sales_model;
        $jobParts = new JobParts;

        $parts = $jobParts->get([
            'job_type_id' => $job_type_id,
            'sales_model' => $sales_model
        ]);
        // $parts = JobParts::where([
        //     ['job_type_id',  $job_type_id],
        //     ['sales_model',  $sales_model]
        // ])->get();

        return $parts;
    }
}
