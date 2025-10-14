<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KPIController extends Controller
{
    public function index()
    {
        return view('kpi.index'); // menampilkan resources/views/kpi/index.blade.php
    }
}
