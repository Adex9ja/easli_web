@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Transaction Detail</h6>
                        <div class="badge {{ \App\Model\RequestStatus::getPill($transaction->approvalStatus) }}">{{ \App\Model\RequestStatus::getReqTitle($transaction->approvalStatus) }}</div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label class="control-label">Transaction ID</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->ref }}">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Transaction Date</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->transDate }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label class="control-label">Customer Name</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->fullname }}">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Customer Email</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->email }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label class="control-label">Product Name</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->product_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Sub Product Name</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->sub_name }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label class="control-label">Beneficiary</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->cr_acc }}">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Debit Account</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->dr_acc }}" placeholder="Only applicable for recharge card sale">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label class="control-label">Payment Channel</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->channel_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Card PIN </label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->cardPin }}" placeholder="Only applicable for recharge card sale">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label class="control-label">Account Number</label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->acc_no }}">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Amount </label>
                            <input class="form-control" type="text" readonly value="{{ $transaction->amount }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            @if($transaction->approvalStatus != \App\Model\RequestStatus::Cancelled)
                                @if($transaction->approvalStatus != \App\Model\RequestStatus::Approved)
                                    <a class="btn btn-success btn-sm " href="/transaction/approve/{{ $transaction->ref }}">Approve</a>
                                @endif
                                @if($transaction->approvalStatus != -1)
                                    <a class="btn btn-danger btn-sm" href="/transaction/decline/{{ $transaction->ref }}">Decline</a>
                                @endif
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
