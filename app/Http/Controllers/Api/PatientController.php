<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Account;
use App\Http\Resources\Patient as PatientResource;

class PatientController extends Controller
{
    public function index(){
        $patient = Patient::all();
        return PatientResource::collection($patient);
    }
    public function show($id){
        
        $patient = Patient::find($id);
        // return new PatientResource($patient);
        $patient->accounts;
        return $patient;
    }
}
