<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\JOHeader;
use App\Models\JOLine;
use App\Models\JOLinePart;
use App\Models\JobType;

class JobOrderController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $header = new JOHeader;
            $header->vin = $request->vin;
            $header->plate_no = $request->plate_no;
            $header->mileage = $request->mileage;
            $header->customer_type_id = $request->customer_type['id'];
            $header->customer_name = $request->customer_name;
            $header->contact_number = $request->contact_number;
            $header->department = $request->department['department'];
            $header->section = $request->section['section'];
            $header->date_sold = $request->date_sold;
            $header->status = 1; //pending
            $header->created_by = auth()->user()->first_name . ' ' . auth()->user()->last_name;
            $header->save();
            $job_header_id = $header->id;
            foreach($request->jobRequest as $row){
                $row = (object) $row;
             
                $line = new JOLine;
                $line->job_header_id = $job_header_id;
                if($row->job['job_name'] == 'OTHERS'){
                    $other_job = $row->other_job;
                    $newJobType = JobType::updateOrCreate(
                        ['job_name' => $other_job],
                        ['job_name' => $other_job, 'status' => 4]
                    );
                    $line->job_type = $other_job;
                }
                else {
                    $line->job_type = $row->job['job_name'];
                }
                $line->job_done = $row->job_done;
                $line->labor_charge = $row->labor_charge;
                $line->op_code = $row->op_code;
                $line->created_by = auth()->user()->first_name . ' ' . auth()->user()->last_name;
                $line->save();

                $line_id = $line->id;

                foreach($row->parts as $part) {
                    $part = (object) $part;
                    $linePart = new JOLinePart;
                    $linePart->part_no = $part->part_no;
                    $linePart->part_description = $part->part_description;
                    $linePart->quantity = $part->quantity;
                    $linePart->created_by = auth()->user()->first_name . ' ' . auth()->user()->last_name;
                    $linePart->job_line_id = $line_id;
                    $linePart->save();
                }
            }
            // 
            // $linePart = new JOLinePart;
            DB::commit();
        } catch(Exception $ex) {
            DB::rollBack();
        }

    }
}
