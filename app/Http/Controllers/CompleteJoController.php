<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JOHeader;
use App\Models\JOLine;
use DB;
use Carbon\Carbon;

class CompleteJoController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // update line data
            foreach($request->jobs as $row){
                $joLine = JOLine::findOrFail($row['id']);
                $joLine->job_done = $row['job_done'];
                $joLine->labor_charge = $row['labor_charge'];
                $joLine->op_code = $row['op_code'];
                $joLine->save();
            }

            // update header status
            $completion_date = Carbon::now();
            $joHeader = JOHeader::findOrFail($request->job_order_id);
            $joHeader->status = 3; // completed
            $joHeader->completion_date = $completion_date;
            $joHeader->save();
            DB::commit();

            return response()->json([
                'message' => 'You have successfully completed the job order.',
                'completion_date' => $completion_date
            ]);
        } catch(Exception $ex) {
            DB::rollBack();
        }
        
    }
}
