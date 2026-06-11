@extends('dashboard-blank')
{{-- @extends('dashboardHeader') --}}
@section('content')
    <div class="row">
        {{--  Here start User Info Add  --}}
        <div class="col-lg-8">
            <div class="card d-flex justify-content-center ">
                <div class="text-center card-header">
                    Add Empolyee Information.
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="#">Empolyee Id</label>
                            <input type="number" name="id" class="form-control" value=" ">
                        </div>
                        <div class="mb-3">
                            <label for="#">Name</label>
                            <input type="text" name="name" class="form-control" value=" ">
                        </div>
                        <div class="mb-3">
                            <label for="#">Phone Number</label>
                            <input type="Number" name="phone" class="form-control" value=" ">
                        </div>
                        <div class="mb-3">
                            <label for="#">designation</label>
                            <input type="text" name="designation" class="form-control" value=" ">
                        </div>
                        <div class="mb-3">
                            <label for="#">Address</label>
                            <input type="text" name="address" class="form-control" value=" ">
                        </div>
                        <div class="mb-3">
                            <label for="#">guardian_name</label>
                            <input type="text" name="guardian_name" class="form-control" value=" ">
                        </div>
                        <div class="mb-3">
                            <label for="imageUpload">Select an image:</label>
                            <input type="file" id="imageUpload" name="profile_pic" accept="image/*">
                        </div>
                        <div class="mb-3 d-flex justify-content-left">
                            <button class="btn btn-info" type="submit"> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
