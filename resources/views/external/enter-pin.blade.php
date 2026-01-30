@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Enter Access PIN</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('360/evaluation/'.$share->id.'/check-pin') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">PIN</label>
                            <input type="text" name="pin" class="form-control" placeholder="Enter your PIN" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Submit</button>
                    </form>
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
