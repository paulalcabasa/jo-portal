<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use DB;
use App\Models\JOHeader;
use App\Services\LogService;
use App\Models\User;
use App\Models\Approver;

class JORejectController extends Controller
{
    public function show(Request $request)
    {
        $approval = Approval::findOrFail($request->approval_id);
        $data = [
            'job_order_id' => $approval->reference_id,
            'reject_api' => url('/') . '/api/job-order/reject',
            'approval_id' => $request->approval_id
        ];
        return view('jo-reject-form', $data);
    }

    public function store(Request $request)
    {
        
        DB::beginTransaction();
        try {
            $approval = Approval::findOrFail($request->approval_id);

            if($approval->status == 1){
                $approval->status = 6;
               // $approval->date_approved = null;
                $approval->remarks = $request->remarks;
                $approval->save();

                $joHeader = JOHeader::findOrFail($approval->reference_id);
                $joHeader->status = 6;
                $joHeader->save();

                $approver = Approver::where('id', $approval->approver_id)->first();
                $requestor = User::where('employee_id', $joHeader->created_by_id)->first();

            
                $logService = new LogService;
                $logService->createLog([
                    'content' => 'Job Order No. ' . $joHeader->id . ' has been rejected by ' . $approver->approver_name . ' due to ' . $request->remarks . '.',
                    'recipient' => $requestor->email,
                    'reference_id' => $joHeader->id,
                    'module_code' => 'JO',
                    'mail_flag' => 'Y',
                ]);
                
                DB::commit();

                $data  = [
                    'title' => 'Job Order Portal',
                    'message' => 'Job Order No. ' . $joHeader->id . ' has been rejected by ' . $approver->approver_name . ' due to ' . $request->remarks . '.',
                ];
                return view('message', $data);
            
            }
            else {
                $message = "";
                if($approval->status == 5) {
                    $message = 'It seems that you have already approved the job order.';
                }
                else if($approval->status == 6) {
                    $message = 'It seems that you have already rejected the job order.';
                }
                $data  = [
                    'title' => 'Job Order Portal',
                    'message' => $message
                ];
                return view('message', $data);
            }

        } catch(Exception $ex) {
            DB::rollBack();

            $data  = [
                'title' => 'Job Order Portal',
                'message' => 'Failed rejecting.',
            ];
            return view('message', $data);
        }
    }

}
