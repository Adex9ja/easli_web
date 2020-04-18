@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Available User List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>E-mail Address</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>User Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($data as $farmer)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $farmer->fullname }}</td>
                                    <td>{{ $farmer->email }}</td>
                                    <td>{{ $farmer->phoneno }}</td>
                                    <td>{{ $farmer->address }}</td>
                                    <td>{{ $farmer->user_role }}</td>
                                    <td>
                                        @if($farmer->active == 1)
                                            <div class="badge badge-secondary">Active</div>
                                        @else
                                            <div class="badge badge-danger">De-Activated</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{--<a class="btn btn-outline-primary btn-sm" href="/users/list/detail/{{ base64_encode($farmer->email) }}">Detail</a>--}}
                                        <a class="btn btn-info btn-sm" href="/users/update/{{ base64_encode($farmer->email) }}">Update</a>
                                        @if($farmer->active == 1)
                                            <a class="btn btn-danger btn-sm" href="/users/deactivate/{{ base64_encode($farmer->email) }}">De-activate</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm" href="/users/activate/{{ base64_encode($farmer->email) }}">Activate</a>
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
