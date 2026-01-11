<?php

namespace App\Http\Controllers;

use App\Models\ZakatCouncilMember;
use Illuminate\Http\Request;

class ZakatCouncilMemberController extends Controller
{
    public function index()
    {
        $members = ZakatCouncilMember::orderBy('start_date', 'desc')->get();
        // Get unique designations and roles for filters
        $designations = ZakatCouncilMember::distinct()->pluck('designation')->filter()->sort()->values();
        $roles = ZakatCouncilMember::distinct()->pluck('role_in_committee')->filter()->sort()->values();
        return view('zakat-council-members.index', compact('members', 'designations', 'roles'));
    }

    public function create()
    {
        return view('zakat-council-members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'role_in_committee' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        ZakatCouncilMember::create($validated);

        return redirect()->route('zakat-council-members.index')
            ->with('success', 'Zakat Council Member added successfully.');
    }

    public function show(ZakatCouncilMember $zakatCouncilMember)
    {
        return view('zakat-council-members.show', compact('zakatCouncilMember'));
    }

    public function edit(ZakatCouncilMember $zakatCouncilMember)
    {
        return view('zakat-council-members.edit', compact('zakatCouncilMember'));
    }

    public function update(Request $request, ZakatCouncilMember $zakatCouncilMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'role_in_committee' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $zakatCouncilMember->update($validated);

        return redirect()->route('zakat-council-members.index')
            ->with('success', 'Zakat Council Member updated successfully.');
    }

    public function destroy(ZakatCouncilMember $zakatCouncilMember)
    {
        $zakatCouncilMember->delete();
        return redirect()->route('zakat-council-members.index')
            ->with('success', 'Zakat Council Member deleted successfully.');
    }
}
