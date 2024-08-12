@extends('layouts.base')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Family Tree</h1>
    <a href="{{ route('peoples.create') }}" class="btn btn-primary mb-3">Add User</a>
    <div class="list-group">
        @foreach($people as $user)
            <div class="list-group-item">
                <h5>{{ $user->first_name }} {{ $user->last_name }}</h5>
                <ul class="list-unstyled">
                    <li><strong>Mother:</strong> {{ $user->mother->first_name ?? 'N/A' }}</li>
                    <li><strong>Father:</strong> {{ $user->father->first_name ?? 'N/A' }}</li>
                    <li><strong>Spouse:</strong> {{ $user->spouse->first_name ?? 'N/A' }}</li>
                    <li><strong>Siblings:</strong>
                        <ul class="list-unstyled">
                            @foreach($user->siblings as $sibling)
                                <li>{{ $sibling->first_name }}</li>
                            @endforeach
                        </ul>
                    </li>
                    <li><strong>Children:</strong>
                        <ul class="list-unstyled">
                            @foreach($user->children as $child)
                                <li>{{ $child->first_name }}</li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endsection