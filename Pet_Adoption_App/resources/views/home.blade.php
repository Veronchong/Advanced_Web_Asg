@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="jumbotron bg-light p-5 rounded-lg">
    <h1 class="display-4">Welcome to Pet Adoption System</h1>
    <p class="lead">Find your perfect furry friend and give them a loving home.</p>
    <hr class="my-4">
    <p>Browse through our available pets and start your adoption journey today.</p>
    <a class="btn btn-primary btn-lg" href="{{ route('pets.index') }}" role="button">Browse Pets</a>
</div>

<div class="row mt-5">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-heart-fill text-danger" style="font-size: 2rem;"></i>
                <h5 class="card-title mt-3">Adopt, Don't Shop</h5>
                <p class="card-text">Give a homeless pet a second chance at life by providing a loving forever home.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-house-fill text-primary" style="font-size: 2rem;"></i>
                <h5 class="card-title mt-3">Find Your Match</h5>
                <p class="card-text">Search our database to find the perfect pet that matches your lifestyle.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                <h5 class="card-title mt-3">Support System</h5>
                <p class="card-text">Our team is here to help you through every step of the adoption process.</p>
            </div>
        </div>
    </div>
</div>
@endsection