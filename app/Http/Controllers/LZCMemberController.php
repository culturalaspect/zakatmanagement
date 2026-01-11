<?php

namespace App\Http\Controllers;

use App\Models\LZCMember;
use App\Models\LocalZakatCommittee;
use Illuminate\Http\Request;

class LZCMemberController extends Controller
{
    public function __construct()
    {
        // Restrict access for district users
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isDistrictUser()) {
                abort(403, 'Access denied. District users cannot access LZC Members.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Deactivate any members whose tenure has expired
        LZCMember::deactivateExpiredMembers();
        
        $members = LZCMember::with(['localZakatCommittee', 'localZakatCommittee.district'])->orderBy('full_name')->get();
        $committees = LocalZakatCommittee::where('is_active', true)->with('district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        return view('lzc-members.index', compact('members', 'committees', 'districts'));
    }

    public function create(Request $request)
    {
        $committees = LocalZakatCommittee::where('is_active', true)->with('district')->get();
        $districts = \App\Models\District::where('is_active', true)->get();
        $selectedCommitteeId = $request->get('local_zakat_committee_id');
        $selectedCommittee = null;
        
        // Convert to integer if it exists to ensure proper comparison
        if ($selectedCommitteeId) {
            $selectedCommitteeId = (int) $selectedCommitteeId;
            $selectedCommittee = LocalZakatCommittee::with('district')->find($selectedCommitteeId);
        }
        
        return view('lzc-members.create', compact('committees', 'districts', 'selectedCommitteeId', 'selectedCommittee'));
    }

    public function store(Request $request)
    {
        // First, deactivate any members whose tenure has expired
        LZCMember::deactivateExpiredMembers();

        $validated = $request->validate([
            'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
            'cnic' => ['required', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            'full_name' => 'required|string|max:255',
            'father_husband_name' => 'required|string|max:255',
            'mobile_number' => ['nullable', 'string', 'regex:/^03[0-9]{2}-[0-9]{7}$/'],
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // Check if a member with this CNIC is already active
        if (LZCMember::hasActiveMember($validated['cnic'])) {
            return back()
                ->withInput()
                ->withErrors(['cnic' => 'A member with this CNIC is already active in another committee. Please wait until their tenure expires or deactivate them first.']);
        }

        // Set default values for new members
        $validated['verification_status'] = 'pending';
        $validated['is_active'] = false; // Members are not active until verified

        $member = LZCMember::create($validated);

        // If coming from a committee view, redirect back to that committee
        if ($request->filled('return_to_committee') && $request->return_to_committee == '1') {
            return redirect()->route('local-zakat-committees.show', $validated['local_zakat_committee_id'])
                ->with('success', 'LZC Member added successfully.');
        }

        return redirect()->route('lzc-members.index')
            ->with('success', 'LZC Member added successfully.');
    }

    public function show(LZCMember $lZCMember)
    {
        $lZCMember->load('localZakatCommittee');
        return view('lzc-members.show', compact('lZCMember'));
    }

    /**
     * Get member details via AJAX
     */
    public function getMemberDetails(LZCMember $lZCMember)
    {
        $lZCMember->load('localZakatCommittee');
        
        // Format dates for JSON response (ensure YYYY-MM-DD format)
        $memberData = $lZCMember->toArray();
        if ($lZCMember->date_of_birth) {
            $memberData['date_of_birth'] = $lZCMember->date_of_birth->format('Y-m-d');
        }
        if ($lZCMember->start_date) {
            $memberData['start_date'] = $lZCMember->start_date->format('Y-m-d');
        }
        if ($lZCMember->end_date) {
            $memberData['end_date'] = $lZCMember->end_date->format('Y-m-d');
        }
        
        return response()->json([
            'success' => true,
            'member' => $memberData
        ]);
    }

    public function edit(LZCMember $lZCMember)
    {
        $lZCMember->load('localZakatCommittee.district');
        $committees = LocalZakatCommittee::where('is_active', true)->with('district')->get();
        $districts = \App\Models\District::where('is_active', true)->get();
        return view('lzc-members.edit', compact('lZCMember', 'committees', 'districts'));
    }

    public function update(Request $request, LZCMember $lZCMember)
    {
        // Prevent editing verified members
        if ($lZCMember->verification_status === 'verified') {
            return back()
                ->withErrors(['error' => 'Verified members cannot be edited. Only pending or rejected members can be edited.'])
                ->withInput();
        }
        
        // First, deactivate any members whose tenure has expired
        LZCMember::deactivateExpiredMembers();

        $validated = $request->validate([
            'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
            'cnic' => [
                'required', 
                'string', 
                'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/',
                'unique:lzc_members,cnic,' . $lZCMember->id
            ],
            'full_name' => 'required|string|max:255',
            'father_husband_name' => 'required|string|max:255',
            'mobile_number' => ['nullable', 'string', 'regex:/^03[0-9]{2}-[0-9]{7}$/'],
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Preserve existing is_active value - members can only be activated through verification, not through edit
        $validated['is_active'] = $lZCMember->is_active;

        // Check if another member with this CNIC is already active (excluding current member)
        // This check is mainly for verification, but we keep it here for safety
        if ($validated['is_active'] && LZCMember::hasActiveMember($validated['cnic'], $lZCMember->id)) {
            return back()
                ->withInput()
                ->withErrors(['cnic' => 'A member with this CNIC is already active in another committee. Please wait until their tenure expires or deactivate them first.']);
        }

        // If end_date has passed, automatically set is_active to false
        if (isset($validated['end_date']) && $validated['end_date'] && \Carbon\Carbon::parse($validated['end_date'])->isPast()) {
            $validated['is_active'] = false;
        }

        $lZCMember->update($validated);

        return redirect()->route('lzc-members.index')
            ->with('success', 'LZC Member updated successfully.');
    }

    public function destroy(LZCMember $lZCMember)
    {
        $lZCMember->delete();
        return redirect()->route('lzc-members.index')
            ->with('success', 'LZC Member deleted successfully.');
    }

    /**
     * Delete member via AJAX (from committee show page)
     */
    public function deleteAjax(Request $request, LZCMember $lZCMember)
    {
        // Check if member is verified - prevent deletion of verified members
        if ($lZCMember->verification_status === 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Verified members cannot be deleted. Please reject the member first if you need to remove them.'
            ], 403);
        }

        $memberName = $lZCMember->full_name;
        $lZCMember->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully.',
        ]);
    }

    /**
     * Store member via AJAX (from committee show page)
     */
    public function storeAjax(Request $request)
    {
        // First, deactivate any members whose tenure has expired
        LZCMember::deactivateExpiredMembers();

        $validated = $request->validate([
            'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
            'cnic' => ['required', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            'full_name' => 'required|string|max:255',
            'father_husband_name' => 'required|string|max:255',
            'mobile_number' => ['nullable', 'string', 'regex:/^03[0-9]{2}-[0-9]{7}$/'],
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Check if a member with this CNIC is already active
        if (LZCMember::hasActiveMember($validated['cnic'])) {
            return response()->json([
                'success' => false,
                'message' => 'A member with this CNIC is already active in another committee.'
            ], 422);
        }

        // Set default values for new members
        $validated['verification_status'] = 'pending';
        $validated['is_active'] = false;

        $member = LZCMember::create($validated);
        $member->load('localZakatCommittee');

        return response()->json([
            'success' => true,
            'message' => 'Member added successfully. Please verify the member to activate.',
            'member' => $member
        ]);
    }

    /**
     * Update member via AJAX (from committee show page)
     */
    public function updateAjax(Request $request, LZCMember $lZCMember)
    {
        // Prevent editing verified members
        if ($lZCMember->verification_status === 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Verified members cannot be edited. Only pending or rejected members can be edited.'
            ], 403);
        }
        
        // First, deactivate any members whose tenure has expired
        LZCMember::deactivateExpiredMembers();

        $validated = $request->validate([
            'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
            'cnic' => [
                'required', 
                'string', 
                'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/',
                'unique:lzc_members,cnic,' . $lZCMember->id
            ],
            'full_name' => 'required|string|max:255',
            'father_husband_name' => 'required|string|max:255',
            'mobile_number' => ['nullable', 'string', 'regex:/^03[0-9]{2}-[0-9]{7}$/'],
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Members can only be activated through verification, not through edit
        // So we don't allow is_active to be changed here
        // If end_date has passed, we still don't change is_active here - verification handles that

        $lZCMember->update($validated);
        $lZCMember->load('localZakatCommittee');

        return response()->json([
            'success' => true,
            'message' => 'Member updated successfully.',
            'member' => $lZCMember
        ]);
    }

    /**
     * Show verify member page
     */
    public function showVerify(LZCMember $lZCMember)
    {
        $lZCMember->load('localZakatCommittee');
        return view('lzc-members.verify', compact('lZCMember'));
    }

    /**
     * Verify or reject a member
     */
    public function verify(Request $request, LZCMember $lZCMember)
    {
        $validated = $request->validate([
            'action' => 'required|in:verify,reject',
            'verification_remarks' => 'required_if:action,verify|nullable|string|max:1000',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000',
        ]);

        if ($validated['action'] === 'verify') {
            // Check if another member with same CNIC is active
            if (LZCMember::hasActiveMember($lZCMember->cnic, $lZCMember->id)) {
                // Check if this is an AJAX request (from committee show page)
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A member with this CNIC is already active in another committee.'
                    ], 422);
                }
                return back()
                    ->withInput()
                    ->withErrors(['action' => 'A member with this CNIC is already active in another committee.']);
            }

            $lZCMember->update([
                'verification_status' => 'verified',
                'is_active' => true,
                'verification_remarks' => $validated['verification_remarks'],
                'verified_at' => now(),
                'rejection_reason' => null,
                'rejected_at' => null,
            ]);

            // Check if this is an AJAX request (from committee show page)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Member verified and activated successfully.',
                    'member' => $lZCMember->fresh()
                ]);
            }

            return redirect()->route('lzc-members.index')
                ->with('success', 'Member verified and activated successfully.');
        } else {
            $lZCMember->update([
                'verification_status' => 'rejected',
                'is_active' => false,
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
                'verification_remarks' => null,
                'verified_at' => null,
            ]);

            // Check if this is an AJAX request (from committee show page)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Member rejected successfully.',
                    'member' => $lZCMember->fresh()
                ]);
            }

            return redirect()->route('lzc-members.index')
                ->with('success', 'Member rejected successfully.');
        }
    }
}
