@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Promo Code List</h6>
                        <a href="/promo/code/add/new" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Promo Code</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($promoCodeList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->discount_code }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('discount_code') == $item->discount_code) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/promo/code/list/{{ $item->discount_code }}">Update Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($promoCode))
            <div class="col-md-5 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Faq Detail</h6>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Promo / Discount Code</label>
                                <input name="discount_code" type="text" class="form-control" placeholder="Enter promo code" required readonly="" value="{{ $promoCode->discount_code }}"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Promo / Discount Amount</label>
                                <input name="amount" type="text" class="form-control" placeholder="Enter amount" value="{{  $promoCode->amount }}" required />
                            </div>
                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a class="btn btn-danger btn-sm" href="/promo/code/deactivate/{{ $promoCode->discount_code }}">De-activate</a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
