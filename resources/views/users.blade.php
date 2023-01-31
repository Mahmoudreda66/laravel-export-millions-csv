@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center" style="justify-content: space-between">
                        <span>{{ __('Dashboard') }}</span>
                        <a href="{{ route('export_users') }}">
                            <button class="btn btn-success">Export All</button>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="text-align: center;">
                        {!! $users->links("pagination::bootstrap-5") !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection