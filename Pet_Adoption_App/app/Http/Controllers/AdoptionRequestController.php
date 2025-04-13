<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdoptionRequestController extends Controller
{
    public function index(Request $request)
    {
        // For admin/staff
        $query = AdoptionRequest::with(['user', 'pet'])
            ->latest();
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('pet', function($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%');
                })->orWhereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%');
                });
            });
        }
        
        $requests = $query->paginate(10);
        
        return view('adoption-requests.admin-index', compact('requests'));
    }
    
    public function userIndex()
    {
        // For regular users
        $requests = AdoptionRequest::with(['pet'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('adoption-requests.user-index', compact('requests'));
    }

    public function store(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);
    
        // Check for existing pending request
        $existingRequest = AdoptionRequest::where('user_id', auth()->id())
            ->where('pet_id', $pet->id)
            ->where('status', 'pending')
            ->exists();
    
        if ($existingRequest) {
            return back()->with('error', 'You already have a pending request for this pet.');
        }
    
        // Create the adoption request
        $adoptionRequest = AdoptionRequest::create([
            'pet_id' => $pet->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'status' => 'pending',
        ]);
    
        // Update pet status to pending
        $pet->update(['status' => 'pending']);
    
        return redirect()->route('pets.show', $pet)
            ->with('success', 'Adoption request submitted successfully!');
    }

    public function update(Request $request, AdoptionRequest $adoptionRequest)
    {
        Gate::authorize('update', $adoptionRequest);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $adoptionRequest->update([
            'status' => $validated['status'],
        ]);

        // If approved, update pet status
        if ($validated['status'] === 'approved') {
            $adoptionRequest->pet->update([
                'status' => 'adopted',
                'user_id' => $adoptionRequest->user_id,
            ]);

            // Reject all other pending requests for this pet
            AdoptionRequest::where('pet_id', $adoptionRequest->pet_id)
                ->where('id', '!=', $adoptionRequest->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        }

        return redirect()->route('adoption-requests.index')->with('success', 'Adoption request updated successfully!');
    }

    public function destroy(AdoptionRequest $adoptionRequest)
    {
        Gate::authorize('delete', $adoptionRequest);

        $adoptionRequest->delete();

        return back()->with('success', 'Adoption request deleted successfully!');
    }
}
