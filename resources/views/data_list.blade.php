@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-4 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Product List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($dataList as $data)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $data->product_name }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('product_id') == $data->product_id) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/product/{{ collect(request()->segments())[1] }}/list/{{ $data->product_id }}">Sub Products</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($subProdList))
            <div class="col-md-4 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Sub Product List</h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subProdList as $data)
                                    <tr>
                                        <td>{{ $data->sub_name }}</td>
                                        <td>{{ $data->sub_price }}</td>
                                        <td>
                                            <a class="btn @if(request()->route('sub_prod_id') == $data->sub_prod_id) btn-outline-info @else btn-info @endif  btn-sm" href="/product/{{ collect(request()->segments())[1] }}/list/{{ request()->route('product_id') . '/' . $data->sub_prod_id  }}">Detail</a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(isset($subProdDetail))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Sub Product Detail</h6>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Sub Product Name</label>
                                <input name="sub_name" type="text" class="form-control" placeholder="Enter name" value="{{  $subProdDetail->sub_name }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sub Product Price</label>
                                <input name="sub_price" type="text" class="form-control" placeholder="Enter price" value="{{  $subProdDetail->sub_price }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sub Product Description</label>
                                <input name="sub_desc" type="text" class="form-control" placeholder="Enter description" value="{{  $subProdDetail->sub_desc }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Beneficiary</label>
                                <input name="optional_param" type="text" class="form-control" placeholder="Enter beneficiary" value="{{  $subProdDetail->optional_param }}">
                            </div>
                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a href="/product/sub-prod/delete/{{ $subProdDetail->sub_prod_id }}" type="submit" class="btn btn-danger">De-Activate</a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
