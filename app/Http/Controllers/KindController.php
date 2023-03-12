<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kind;

class KindController extends Controller
{
    public function index(){
        $kinds = Kind::all();
        return $kinds;
    }
}
