@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="user">
            <h3 class="user-header">User List</h3>
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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
                        <th>Created_date</th>
                        <th>Updated_date</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->type == 0 ? 'Admin' : 'User' }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->dob }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{$user->created_at}}</td>
                            <td>{{$user->updated_at}}</td>
                            <td>
                                <a href="#" 
                                   class="btn btn-danger" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#deleteModal{{ $user->id }}">
                                   Delete
                                </a>
                            </td>
                        </tr>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Delete Confirmation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="warning">Are you sure you want to delete this user?</p>
                                        <p><strong>ID:</strong> {{ $user->id }}</p>
                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                        <p><strong>Type  :</strong>{{ $user->type == 0 ? 'Admin' : 'User' }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong> Phone :</strong> {{ $user->phone }}</p>
                                        <p><strong>Date of Birth  :</strong>{{$user->dob}}</p>
                                        <p><strong>Address :</strong>{{$user->address}}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
