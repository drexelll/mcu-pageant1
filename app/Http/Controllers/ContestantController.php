<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contestant;


class ContestantController extends Controller
{
    public function index()
    {
        $contestants = Contestant::all();

        return view('admin.contestants', compact('contestants'));
    }
}
