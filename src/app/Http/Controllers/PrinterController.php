<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PrinterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }

}
