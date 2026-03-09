<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FichaFuncionarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}