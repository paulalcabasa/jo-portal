<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician;

class TechnicianController extends Controller
{
    public function index()
    {
        return Technician::where('status', 4)->get();
    }
}
