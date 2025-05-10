@extends('layouts.app')

@section('title', 'Manage Adoption Applications')

@section('content')
<div class="container">
    <h1 class="mb-4">Manage Adoption Applications</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('adoption-requests.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pet</th>
                    <th>Applicant</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                <tr>
                    <td>
                        <a href="{{ route('pets.show', $request->pet) }}">
                            {{ $request->pet->name }}
                        </a>
                    </td>
                    <td>{{ $request->user->name }}<br><small>{{ $request->user->email }}</small></td>
                    <td>{{ Str::limit($request->message, 50) }}</td>
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
                    <td>
                        @if($request->status == 'pending')
                        <div class="d-flex gap-2">
                            <form action="{{ route('adoption-requests.update', $request) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>

                            <form action="{{ route('adoption-requests.update', $request) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No adoption applications found.</td>
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