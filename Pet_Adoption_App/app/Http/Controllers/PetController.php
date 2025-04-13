<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $query = Pet::query();
        
        // Apply search filter if present
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('breed', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }
    
        // Apply species filter if selected
        if ($request->filled('species')) {
            $query->where('species', $request->species);
        }
    
        // Apply status filter if selected
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Get paginated results while preserving filters
        $pets = $query->latest()
                    ->paginate(10)
                    ->appends($request->query());
    
        return view('pets.index', compact('pets'));
    }

    public function create()
    {
        Gate::authorize('create', Pet::class);
        return view('pets.create');
    }

    public function store(Request $request, Pet $pet)
    {
        Gate::authorize('create', Pet::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:male,female',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,adopted,pending',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('pet_images', 'public');
        }

        $validated['user_id'] = auth()->id();

        Pet::create($validated);

        return redirect()->route('pets.index')->with('success', 'Pet added successfully!');
    }

    public function show(Pet $pet)
    {
        // Get or initialize recently viewed from session
        $recentlyViewed = collect(session('recently_viewed', []));
        
        // Remove current pet and invalid entries
        $recentlyViewed = $recentlyViewed
            ->reject(fn($item) => !is_array($item) || !isset($item['id']) || $item['id'] == $pet->id);
        
        // Add current pet
        $recentlyViewed->prepend(['id' => $pet->id, 'timestamp' => now()->timestamp]);
        
        // Keep only last 4
        $recentlyViewed = $recentlyViewed->take(4);
        
        // Save to session
        session(['recently_viewed' => $recentlyViewed->toArray()]);
        
        // Get pets in correct order
        $recentPets = Pet::whereIn('id', $recentlyViewed->pluck('id'))
            ->get()
            ->sortBy(fn($pet) => $recentlyViewed->pluck('id')->search($pet->id));
        
        return view('pets.show', compact('pet', 'recentPets'));
    }

    public function edit(Pet $pet)
    {
        Gate::authorize('update', $pet);
        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        Gate::authorize('update', $pet);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:male,female',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean',
            'status' => 'required|in:available,adopted,pending',
        ]);

        // Handle image removal
        if ($request->remove_image) {
            Storage::disk('public')->delete($pet->image);
            $validated['image'] = null; 
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($pet->image) {
                Storage::disk('public')->delete($pet->image);
            }
            
            $path = $request->file('image')->store('pet_images', 'public');
            $validated['image'] = $path;
        }

        $pet->update($validated);

        return redirect()->route('pets.show', $pet)
            ->with('success', 'Pet updated successfully');
    }

    public function destroy(Pet $pet)
    {
        Gate::authorize('delete', $pet);

        if ($pet->image) {
            Storage::disk('public')->delete($pet->image);
        }

        $pet->delete();

        return redirect()->route('pets.index')->with('success', 'Pet deleted successfully!');
    }
}