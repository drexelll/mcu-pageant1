<?php

namespace App\Http\Controllers\sas;

use Illuminate\Http\Request;
use App\Models\Contestant;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
            'name'   => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('contestants', 'public');
            $validated['photo'] = $path;
        }

        Contestant::create($validated);

        return redirect()->route('sas.contestants')
            ->with('success', 'Contestant added successfully!');
    }

    public function edit(Contestant $contestant)
    {
        return view('sas.contestants-edit', compact('contestant'));
    }

    public function update(Request $request, Contestant $contestant)
    {
        $validated = $request->validate([
            'number' => 'required|integer|unique:contestants,number,' . $contestant->id,
            'name'   => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('contestants', 'public');
            $validated['photo'] = $path;
        }

        $contestant->update($validated);

        return redirect()->route('sas.contestants')
            ->with('success', 'Contestant updated successfully!');
    }

    // ── Soft delete (archive) ────────────────────────────────────
    public function destroy(Contestant $contestant)
    {
        $name = $contestant->name;
        $contestant->delete(); // soft delete — sets deleted_at, not actually removed

        return redirect()->route('sas.contestants')
            ->with('success', 'Contestant "' . $name . '" has been archived.');
    }

    // ── Archive view ─────────────────────────────────────────────
    public function archive()
    {
        $contestants = Contestant::onlyTrashed()
                                 ->orderBy('deleted_at', 'desc')
                                 ->paginate(10);

        return view('sas.contestants-archive', compact('contestants'));
    }

    // ── Restore ──────────────────────────────────────────────────
    public function restore($id)
    {
        $contestant = Contestant::onlyTrashed()->findOrFail($id);
        $contestant->restore();

        return redirect()->route('sas.contestants.archive')
            ->with('success', 'Contestant "' . $contestant->name . '" has been restored.');
    }

    // ── Force delete (permanent) ─────────────────────────────────
    public function forceDelete($id)
    {
        $contestant = Contestant::onlyTrashed()->findOrFail($id);
        $name = $contestant->name;
        $contestant->forceDelete();

        return redirect()->route('sas.contestants.archive')
            ->with('success', 'Contestant "' . $name . '" has been permanently deleted.');
    }
}
