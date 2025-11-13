<?php

namespace App\Http\Controllers\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DiagnosticServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = DiagnosticService::all();
        return Inertia::render('Diagnostic/Services/Index', [
            'services' => $services,
        ]);
    }

    /**
     * Get distinct categories.
     */
    public function categories(Request $request)
    {
        $categories = DiagnosticService::distinct()->pluck('category')->filter();

        if ($request->has('search') && $request->search !== '') {
            $search = strtolower($request->search);
            $categories = $categories->filter(function ($category) use ($search) {
                return stripos(strtolower($category), $search) !== false;
            });
        }

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $paginatedCategories = $categories->slice($offset, $limit)->values();

        return response()->json($paginatedCategories);
    }

    /**
     * Get a listing of the resource for AJAX.
     */
    public function list(Request $request)
    {
        $query = DiagnosticService::query();

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Search by name or description
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Pagination
        $limit = $request->get('limit', 20);
        $offset = $request->get('offset', 0);

        $services = $query->skip($offset)->take($limit)->get();

        return response()->json($services);
    }

    /**
     * Display a public listing of the resource.
     */
    public function publicIndex()
    {
        $services = DiagnosticService::all();
        return Inertia::render('DiagnosticAll', [
            'services' => $services,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Diagnostic/Services/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'sample_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'home_collection_available' => 'boolean',
            'report_time' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('diagnostic-services', 'public');
        }

        DiagnosticService::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Service created successfully.']);
        }

        return redirect()->route('diagnostic.services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DiagnosticService $service)
    {
        return Inertia::render('Diagnostic/Services/Show', [
            'service' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DiagnosticService $service)
    {
        return Inertia::render('Diagnostic/Services/Edit', [
            'service' => $service,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DiagnosticService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'sample_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'home_collection_available' => 'boolean',
            'report_time' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('diagnostic-services', 'public');
        }

        $service->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Service updated successfully.']);
        }

        return redirect()->route('diagnostic.services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiagnosticService $service)
    {
        // Delete associated image if exists
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Service deleted successfully.']);
        }

        return redirect()->route('diagnostic.services.index')->with('success', 'Service deleted successfully.');
    }
}
