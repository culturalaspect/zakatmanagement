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
            'categories' => 'nullable|array',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.amount' => 'required|numeric|min:0',
        ]);

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
            'categories' => 'nullable|array',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.amount' => 'required|numeric|min:0',
        ]);

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
