<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\JOHeader;
use App\Models\JOLine;
use App\Models\JOLinePart;
use App\Models\JobType;
use App\Models\Approval;
use App\Models\Approver;
use Carbon\Carbon;

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
            $header->department = $request->department;
            $header->section = $request->section;
            $header->date_sold = $request->date_sold;
            $header->status = 1; //pending
            $header->created_by_id = auth()->user()->employee_id;
            $header->created_by = auth()->user()->first_name . ' ' . auth()->user()->last_name;
            $header->save();
            $job_header_id = $header->id;

            // approval
            $approver = new Approver;
            $defaultApprovers = $approver->getApprovers();
            foreach($defaultApprovers as $row){
                $row = (object) $row;
                $approval = new Approval;
                $approval->approver_id = $row->approver_id;
                $approval->sequence_no = $row->sequence_no;
                $approval->module_code = $row->module_code;
                $approval->status = 1;
                $approval->reference_id = $job_header_id;
                $approval->save();
            }

            // lines
            foreach($request->jobRequest as $row){
                $row = (object) $row;
                $job = $row->job;
                $line = new JOLine;
                $line->job_header_id = $job_header_id;
                if($job == 'OTHERS'){
                    $other_job = $row->other_job;
                    $newJobType = JobType::updateOrCreate(
                        ['job_name' => $other_job],
                        ['job_name' => $other_job, 'status' => 4]
                    );
                    $line->job_type = $other_job;
                }
                else {
                    $line->job_type = $row->job;
                }
                $line->job_done = $row->job_done;
                $line->labor_charge = $row->labor_charge;
                $line->op_code = $row->op_code;
                $line->created_by = auth()->user()->first_name . ' ' . auth()->user()->last_name;
                $line->save();

                $line_id = $line->id;

                // line part
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
            DB::commit();
            return [
                'job_order_id' => $job_header_id, 
                'message' => 'Job order has been created.'
            ];
        
        } catch(Exception $ex) {
            DB::rollBack();
        }
    }

    public function index()
    {
        $joHeader = new JOHeader;
        $data = $joHeader->getAll();
        return $data;
    }

    public function show(Request $request)
    {
        $joHeader = new JOHeader;
        $data = $joHeader->get($request->job_order_id);
        return response()->json($data);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $job_header_id = $request->job_order_id;
            $header = JOHeader::findOrFail($job_header_id);
            $header->vin = $request->vin;
            $header->plate_no = $request->plate_no;
            $header->mileage = $request->mileage;
            $header->customer_type_id = $request->customer_type['id'];
            $header->customer_name = $request->customer_name;
            $header->contact_number = $request->contact_number;
            $header->department = $request->department;
            $header->section = $request->section;
            $header->date_sold = $request->date_sold;
            $header->status = 1; //pending
            $header->created_by_id = auth()->user()->user_id;
            $header->created_by = auth()->user()->first_name . ' ' . auth()->user()->last_name;
            $header->save();


            // // delete lines and line parts first
            $delJoLinePart = new JOLinePart;
            $delJoLinePart->deleteByJobHeaderId($job_header_id);
            $delJoLine = new JOLine;
            $delJoLine->where('job_header_id', $job_header_id)->delete();


            // delete current approval
            $deleteApproval = Approval::where('job_header_id', $job_header_id)->delete();
            
            // approval
            $approver = new Approver;
            $defaultApprovers = $approver->getApprovers();
            foreach($defaultApprovers as $row){
                $row = (object) $row;
                $approval = new Approval;
                $approval->approver_id = $row->approver_id;
                $approval->sequence_no = $row->sequence_no;
                $approval->module_code = $row->module_code;
                $approval->status = 1;
                $approval->reference_id = $job_header_id;
                $approval->save();
            }

            foreach($request->jobRequest as $row){
                $row = (object) $row;
                $job = $row->job;
                $line = new JOLine;
                $line->job_header_id = $job_header_id;
                if($job == 'OTHERS'){
                    $other_job = $row->other_job;
                    $newJobType = JobType::updateOrCreate(
                        ['job_name' => $other_job],
                        ['job_name' => $other_job, 'status' => 4]
                    );
                    $line->job_type = $other_job;
                }
                else {
                    $line->job_type = $row->job;
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
            DB::commit();
            return [
                'job_order_id' => $job_header_id, 
                'message' => 'Job order has been updated.'
            ];
        
        } catch(Exception $ex) {
            DB::rollBack();
        }
    }

}
