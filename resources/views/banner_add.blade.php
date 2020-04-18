
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Banner Information</h6>
                <form  class="mt-4" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input class="form-control mt-2" name="imageUpload" type="file" accept="image/*" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Banner Name</label>
                        <input name="banner_name" type="text" class="form-control" placeholder="Enter name" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Banner Description</label>
                        <input name="description" type="text" class="form-control" placeholder="Enter description" >
                    </div>
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

