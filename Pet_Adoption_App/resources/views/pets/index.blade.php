@extends('layouts.app')

@section('title', 'Browse Pets')

@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h1>Available Pets</h1>
        </div>
        <div class="col-md-6 text-end">
            @can('create', App\Models\Pet::class)
                <a href="{{ route('pets.create') }}" class="btn btn-primary">Add New Pet</a>
            @endcan
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
        <form action="{{ route('pets.index') }}" method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                        placeholder="Search pets..." value="{{ request('search') }}">
                </div>
                
                <div class="col-md-3">
                    <select name="species" class="form-control">
                        <option value="">All Species</option>
                        @foreach(['dog', 'cat', 'bird', 'other'] as $species)
                            <option value="{{ $species }}" 
                                {{ request('species') == $species ? 'selected' : '' }}>
                                {{ ucfirst($species) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        @foreach(['available', 'pending', 'adopted'] as $status)
                            <option value="{{ $status }}" 
                                {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
        </div>
    </div>

    <div class="row">
        @forelse ($pets as $pet)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $pet->image_url }}" class="card-img-top" alt="{{ $pet->name }}" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $pet->name }}</h5>
                        <p class="card-text">
                            <strong>Species:</strong> {{ ucfirst($pet->species) }}<br>
                            <strong>Breed:</strong> {{ $pet->breed ?? 'Unknown' }}<br>
                            <strong>Age:</strong> {{ $pet->age }} years<br>
                            <strong>Status:</strong> 
                            <span class="badge 
                                @if($pet->status == 'available') bg-success 
                                @elseif($pet->status == 'pending') bg-warning text-dark 
                                @else bg-secondary 
                                @endif">
                                {{ ucfirst($pet->status) }}
                                @if($pet->status == 'pending')
                                    (Applied)
                                @endif
                            </span>
                        </p>
                        <a href="{{ route('pets.show', $pet) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No pets found matching your criteria.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $pets->links() }}
    </div>
</div>
@endsection