<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Access PIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

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
                            <label for="pin" class="form-label">PIN</label>
                            <input type="text" id="pin" name="pin" class="form-control" value="{{$share->pin}}" placeholder="Enter your PIN" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Submit</button>
                    </form>

                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
