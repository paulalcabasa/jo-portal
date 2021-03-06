<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use Carbon\Carbon;
use DB;
use App\Models\JOHeader;
use App\Services\LogService;
use App\Models\User;
use App\Models\Approver;

class JOApproveController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $approval = Approval::findOrFail($request->approval_id);

            if($approval->status == 1){
                $approval->status = 5;
                $approval->date_approved = Carbon::now();
                $approval->save();
                
                $joHeader = JOHeader::findOrFail($approval->reference_id);
                $joHeader->current_approver_sequence = $approval->sequence_no + 1;
                
                $maxApproval = new Approval;
                $maxApp = $maxApproval->getMaxApproval($approval->reference_id);
                if($maxApp->sequence_no == $approval->sequence_no) {
                    $joHeader->status = 5;
                }
                $joHeader->save();

                $approver = Approver::where('id', $approval->approver_id)->first();
                $requestor = User::where('employee_id', $joHeader->created_by_id)->first();
                $logService = new LogService;
                $logService->createLog([
                    'content' => 'Job Order No. ' . $joHeader->id . ' has been approved by ' . $approver->approver_name . '.',
                    'recipient' => $requestor->email,
                    'reference_id' => $joHeader->id,
                    'module_code' => 'JO',
                    'mail_flag' => 'Y',
                ]);

                DB::commit();
                $data  = [
                    'title' => 'Job Order Portal',
                    'message' => 'You have successfully approved the request for job order.'
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
        }
        
    }
}
