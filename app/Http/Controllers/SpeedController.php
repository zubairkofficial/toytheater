<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speed;

class SpeedController extends Controller
{
    public function index(){
        $speeds = Speed::all();
        return $speeds;
    }
}
