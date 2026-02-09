<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::orderBy('type')->orderBy('sort_order')->get();
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:messages,voice,images',
            'quantity' => 'required|integer|min:1',
            'price_inr' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer',
        ]);

        $validated['slug'] = Str::slug($request->name . '-' . $request->type . '-' . $request->quantity);
        $validated['is_active'] = $request->has('is_active');

        Addon::create($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully!');
    }

    public function edit(Addon $addon)
    {
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:messages,voice,images',
            'quantity' => 'required|integer|min:1',
            'price_inr' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $addon->update($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully!');
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted successfully!');
    }
}
