<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpolyeeController extends Controller
{
    public function add_empolyee(){


        return view ('Employee/addEmpolyee');
    }
}