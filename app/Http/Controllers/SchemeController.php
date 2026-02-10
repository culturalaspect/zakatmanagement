<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Http\Request;

class SchemeController extends Controller
{
    public function index()
    {
        $schemes = Scheme::with('categories')->orderBy('name')->get();
        return view('schemes.index', compact('schemes'));
    }

    public function create()
    {
        return view('schemes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'percentage' => 'required|numeric|min:0|max:100',
            'has_age_restriction' => 'boolean',
            'minimum_age' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_institutional' => 'boolean',
            'institutional_type' => 'required_if:is_institutional,1|nullable|in:educational,madarsa,health',
            'allows_representative' => 'boolean',
            'beneficiary_required_fields' => 'nullable|array',
            'beneficiary_required_fields.*' => 'string|in:cnic,full_name,father_husband_name,mobile_number,date_of_birth,gender',
            'representative_required_fields' => 'nullable|array',
            'representative_required_fields.*' => 'string|in:cnic,full_name,father_husband_name,mobile_number,date_of_birth,gender',
            'categories' => 'nullable|array',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.amount' => 'required|numeric|min:0',
        ]);

        // Process required fields arrays
        if ($request->has('beneficiary_required_fields') && is_array($request->beneficiary_required_fields)) {
            $validated['beneficiary_required_fields'] = array_values($request->beneficiary_required_fields);
        } else {
            $validated['beneficiary_required_fields'] = null;
        }
        
        if ($request->has('representative_required_fields') && is_array($request->representative_required_fields)) {
            $validated['representative_required_fields'] = array_values($request->representative_required_fields);
        } else {
            $validated['representative_required_fields'] = null;
        }
        
        $validated['is_institutional'] = $request->has('is_institutional') ? true : false;
        $validated['institutional_type'] = $request->has('is_institutional') && $request->has('institutional_type') 
            ? $request->institutional_type 
            : null;
        $validated['allows_representative'] = $request->has('allows_representative') ? true : false;
        
        $scheme = Scheme::create($validated);

        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                SchemeCategory::create([
                    'scheme_id' => $scheme->id,
                    'name' => $category['name'],
                    'amount' => $category['amount'],
                ]);
            }
        }

        return redirect()->route('schemes.index')
            ->with('success', 'Scheme added successfully.');
    }

    public function show(Scheme $scheme)
    {
        $scheme->load('categories');
        return view('schemes.show', compact('scheme'));
    }

    public function edit(Scheme $scheme)
    {
        $scheme->load('categories');
        return view('schemes.edit', compact('scheme'));
    }

    public function update(Request $request, Scheme $scheme)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'percentage' => 'required|numeric|min:0|max:100',
            'has_age_restriction' => 'boolean',
            'minimum_age' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_institutional' => 'boolean',
            'institutional_type' => 'required_if:is_institutional,1|nullable|in:educational,madarsa,health',
            'allows_representative' => 'boolean',
            'beneficiary_required_fields' => 'nullable|array',
            'beneficiary_required_fields.*' => 'string|in:cnic,full_name,father_husband_name,mobile_number,date_of_birth,gender',
            'representative_required_fields' => 'nullable|array',
            'representative_required_fields.*' => 'string|in:cnic,full_name,father_husband_name,mobile_number,date_of_birth,gender',
            'categories' => 'nullable|array',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.amount' => 'required|numeric|min:0',
        ]);

        // Process required fields arrays
        if ($request->has('beneficiary_required_fields') && is_array($request->beneficiary_required_fields)) {
            $validated['beneficiary_required_fields'] = array_values($request->beneficiary_required_fields);
        } else {
            $validated['beneficiary_required_fields'] = null;
        }
        
        if ($request->has('representative_required_fields') && is_array($request->representative_required_fields)) {
            $validated['representative_required_fields'] = array_values($request->representative_required_fields);
        } else {
            $validated['representative_required_fields'] = null;
        }
        
        $validated['is_institutional'] = $request->has('is_institutional') ? true : false;
        $validated['institutional_type'] = $request->has('is_institutional') && $request->has('institutional_type') 
            ? $request->institutional_type 
            : null;
        $validated['allows_representative'] = $request->has('allows_representative') ? true : false;
        
        $scheme->update($validated);

        // Update categories
        if ($request->has('categories')) {
            $scheme->categories()->delete();
            foreach ($request->categories as $category) {
                SchemeCategory::create([
                    'scheme_id' => $scheme->id,
                    'name' => $category['name'],
                    'amount' => $category['amount'],
                ]);
            }
        }

        return redirect()->route('schemes.index')
            ->with('success', 'Scheme updated successfully.');
    }

    public function destroy(Scheme $scheme)
    {
        $scheme->delete();
        return redirect()->route('schemes.index')
            ->with('success', 'Scheme deleted successfully.');
    }
}
