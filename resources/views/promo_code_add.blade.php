
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Promo / Discount</h6>
                <form  class="mt-4" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Promo / Discount Code</label>
                        <input name="discount_code" type="text" class="form-control" placeholder="Enter promo code" value="{{ $code }}" required readonly />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Promo / Discount Amount</label>
                        <input name="amount" type="text" class="form-control" placeholder="Enter amount"  required />
                    </div>
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

