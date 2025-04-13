@extends('layouts.app')

@section('title', $pet->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-5">
                        <img src="{{ $pet->imageUrl }}" class="img-fluid rounded-start" alt="{{ $pet->name }}" style="height: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h1 class="card-title">{{ $pet->name }}</h1>
                            <p class="card-text">
                                <strong>Species:</strong> {{ ucfirst($pet->species) }}<br>
                                <strong>Breed:</strong> {{ $pet->breed ?? 'Unknown' }}<br>
                                <strong>Age:</strong> {{ $pet->age }} years<br>
                                <strong>Gender:</strong> {{ ucfirst($pet->gender) }}<br>
                                <strong>Status:</strong> 
                                <span class="badge 
                                    @if($pet->status == 'available') bg-success 
                                    @elseif($pet->status == 'pending') bg-warning text-dark 
                                    @else bg-secondary 
                                    @endif">
                                    {{ ucfirst($pet->status) }}
                                </span>
                            </p>
                            <p class="card-text"><strong>Description:</strong><br>{{ $pet->description }}</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Posted {{ $pet->created_at->diffForHumans() }}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @auth
                @if($pet->status == 'available')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Adoption Application</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('adoption-requests.store', $pet) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="message" class="form-label">Why would you like to adopt {{ $pet->name }}?</label>
                                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Application</button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">register</a> to apply for adoption.
                </div>
            @endauth
        </div>

        <div class="col-md-4">
            @if(isset($recentPets) && $recentPets->isNotEmpty())
            <div class="recently-viewed">
                <h5 class="mb-3 border-bottom pb-2">Recently Viewed</h5>
                <div class="row">
                    @foreach($recentPets as $recentPet)
                    <div class="col-12 mb-3">
                        <div class="card recently-viewed-card h-100">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ $recentPet->imageUrl }}" class="img-fluid rounded-start h-100" alt="{{ $recentPet->name }}" style="object-fit: cover; min-height: 120px;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            {{ $recentPet->name }}
                                            <span class="badge bg-{{ $recentPet->status == 'available' ? 'success' : ($recentPet->status == 'pending' ? 'warning text-dark' : 'secondary') }} float-end">
                                                {{ ucfirst($recentPet->status) }}
                                            </span>
                                        </h6>
                                        <p class="card-text text-muted small mb-1">
                                            <span class="text-primary">{{ ucfirst($recentPet->species) }}</span> • 
                                            {{ $recentPet->breed ?? 'Mixed' }} • 
                                            {{ $recentPet->age }} years
                                        </p>
                                        <p class="card-text small">{{ Str::limit($recentPet->description, 60) }}</p>
                                        <a href="{{ route('pets.show', $recentPet) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    @can('update', $pet)
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('pets.edit', $pet) }}" class="btn btn-warning me-2">Edit</a>
            @can('delete', $pet)
                <form action="{{ route('pets.destroy', $pet) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</button>
                </form>
            @endcan
        </div>
    @endcan
</div>

<style>
    .recently-viewed-card {
        transition: transform 0.2s;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.1);
    }
    .recently-viewed-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .recently-viewed h5 {
        font-weight: 600;
        color: #333;
    }
    .card-title {
        font-weight: 600;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endsection