<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;

class ApprovalController extends Controller
{
    public function index(Request $request, Approval $approval)
    {
        return $approval->getJOApproval($request->job_order_id);
    }

}
