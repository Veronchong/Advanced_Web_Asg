@extends('layouts.app')

@section('title', 'My Adoption Applications')

@section('content')
<div class="container">
    <h1 class="mb-4">My Adoption Applications</h1>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Pet</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                <tr>
                    <td>
                        <a href="{{ route('pets.show', $request->pet) }}" class="text-decoration-none">
                            {{ $request->pet->name }}
                        </a>
                    </td>
                    <td>
                        <span class="badge 
                            @if($request->status == 'pending') bg-warning text-dark
                            @elseif($request->status == 'approved') bg-success
                            @else bg-danger
                            @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                    <td>{{ Str::limit($request->message, 50) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="alert alert-info">
                            You haven't submitted any applications yet.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection