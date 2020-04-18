@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Available Wallet Transactions List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>E-mail Address</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Narration</th>
                                <th>Transaction Date</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($walletTransList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->trans_type }}</td>
                                    <td>
                                        <div class="badge {{ \App\Model\RequestStatus::getPill($item->status) }}">{{ \App\Model\RequestStatus::getReqTitle($item->status) }}</div>
                                    </td>
                                    <td>{{ $item->narration }}</td>
                                    <td>{{ $item->created_at }}</td>

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
