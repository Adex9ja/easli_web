@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Available Payout List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>E-mail Address</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Transaction Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($payoutList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <div class="badge {{ \App\Model\RequestStatus::getPill($item->status) }}">{{ \App\Model\RequestStatus::getReqTitle($item->status) }}</div>
                                    </td>
                                    <td>
                                        {{ number_format($item->amount, 2) }}
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>

                                        @if($item->status != \App\Model\RequestStatus::Cancelled)
                                            @if($item->status != \App\Model\RequestStatus::Approved)
                                                <a class="btn btn-success btn-sm " href="/wallet/withdrawal/approve/{{ $item->payout_id }}">Approve</a>
                                            @endif
                                            @if($item->status != -1)
                                                <a class="btn btn-danger btn-sm" href="/wallet/withdrawal/decline/{{ $item->payout_id }}">Decline</a>
                                            @endif
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
