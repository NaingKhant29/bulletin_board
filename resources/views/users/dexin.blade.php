@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="user">
        <h3 class=user-header>User List</h3>


        <!-- Search Form -->
        <form method="GET" action="{{ route('users.dexin') }}" class="mb-3">
            <div class="row container-form">
                <!-- Name Search -->
                <div class="col-md-3  label">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ request('name') }}">
                </div>

                <!-- Email Search -->
                <div class="col-md-3 label">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" name="email" value="{{ request('email') }}">
                </div>

                <!-- DOB From -->
                <div class="col-md-2 label">
                    <label for="dob_from">From:</label>
                    <input type="date" class="form-control" name="dob_from" value="{{ request('dob_from') }}">
                </div>

                <!-- DOB To -->
                <div class="col-md-2 label">
                    <label for="dob_to">To: </label>
                    <input type="date" class="form-control" name="dob_to" value="{{ request('dob_to') }}">
                </div>

                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary search-btn">Search</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created User</th>
                    <th>Type</th>
                    <th>Phone</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->dob }}</td>
                        <td>{{ $user->address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
    </div>
@endsection
