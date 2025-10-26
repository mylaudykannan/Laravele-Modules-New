@extends('admin.layouts.app')

@section('content')
    
    <div class="container-fluid py-4">
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="card customCard my-4">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                  <h5 class="text-white text-capitalize pl-3 mb-0">Create Role</h5>
                </div>
              </div>
              <div class="card-body pb-2">
                    <form action="{{URL('admin/role')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Enter Role Name</label>
                            <input type="text" class="form-control" placeholder="" id="name" name="name">
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-sm btn-success">Submit</button>
                            <a  href="{{ URL('admin/role') }}" class="btn btn-sm btn-warning ml-3">Cancel</a>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection