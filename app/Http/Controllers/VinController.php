<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialNumber;

class VinController extends Controller
{
    public function show(Request $request) {
        $vehicle = new SerialNumber;
        $data = $vehicle->findByVin($request->vin);
        return response()->json($data);
    }
}
