<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $share->form->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header { font-size: 1.1rem; }
        .card-body p { margin-bottom: 0.5rem; }
        .btn-container { margin-top: 20px; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h3>{{ $share->form->title }}</h3>
    <p><strong>Status:</strong> {{ $share->status }}</p>

    <form id="evalForm">
        @csrf

        @foreach($share->form->sections as $section)
            @php $r = $responses[$section->id] ?? null; @endphp
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <strong>{{ $section->section_title }}</strong>
                </div>
                <div class="card-body">
                    <p><strong>{{ $section->title }}</strong></p>

                    <div class="row text-center mb-2">
                        <div class="col-md-4"><small>1-5:</small> {{ $r->score_1_5 ?? '' }}</div>
                        <div class="col-md-4"><small>6-7:</small> {{ $r->score_6_7 ?? '' }}</div>
                        <div class="col-md-4"><small>UE:</small> {{ $r->ue ?? '' }}</div>
                    </div>

                    <textarea class="form-control"
                              name="responses[{{ $section->id }}][comments]"
                              placeholder="Comments">{{ $r->comments ?? '' }}</textarea>
                </div>
            </div>
        @endforeach

        <div class="btn-container">
            <button type="button" onclick="saveDraft()" class="btn btn-warning me-2">Save Draft</button>
            <button type="button" onclick="finalSubmit()" class="btn btn-success">Final Submit</button>
        </div>
    </form>
</div>

<script>
function saveDraft() {
    $.ajax({
        url: "{{ url('360/evaluation/'.$share->id.'/save') }}",
        type: 'POST',
        data: $('#evalForm').serialize(),
        success: function(res){
            alert('Draft Saved!');
        },
        error: function(err){
            console.log(err);
            alert('Error saving draft');
        }
    });
}

function finalSubmit() {
    if(confirm('Are you sure?')) {
        $.ajax({
            url: "{{ url('360/evaluation/'.$share->id.'/submit') }}",
            type: 'POST',
            data: $('#evalForm').serialize(), // include all responses
            success: function(res){
                alert('Form submitted successfully!');
                location.reload();
            },
            error: function(err){
                console.log(err);
                alert('Error submitting form');
            }
        });
    }
}
</script>
</body>
</html>
