<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contestant;
use Illuminate\Support\Facades\Auth;

class ContestantController extends Controller
{
    public function index()
    {
        $contestants = Contestant::all();
        return view('sas.contestants', compact('contestants'));
    }

    public function create()
    {
        return view('sas.contestants-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer|unique:contestants,number',
            'name' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'photo' => 'nullable|string',
        ]);

        Contestant::create($validated);

        return redirect()->route('sas.contestants')
            ->with('success', 'Contestant added successfully!');
    }
}
