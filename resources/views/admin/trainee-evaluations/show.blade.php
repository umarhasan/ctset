@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!-- HEADER -->
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center no-print">
                    <h4 class="mb-0">TRAINEE EVALUATION FORM</h4>
                    <div class="ms-auto">
                        <a href="{{ route('trainee-evaluations.index') }}" class="btn btn-light btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- BODY -->
                <div class="card-body print-area">

                    <div class="text-center mb-4">
                        <h2 class="fw-bold">TRAINEE EVALUATION FORM</h2>
                        <h4 class="text-primary">MASTER IN SURGICAL SCIENCES M.SURG.SC (CT)</h4>
                        <p class="text-muted"><strong>{{ $evaluation->title }}</strong></p>
                        <p class="fst-italic">
                            Place an "✕" in the box that best reflects the specified attribute of the trainee.
                        </p>
                    </div>

                    @php
                        $savedRatings = \App\Models\EvaluationPointRating::where('evaluation_id', $evaluation->id)
                                        ->pluck('rating','point_id')->toArray();
                    @endphp

                    <!-- Sections -->
                    @foreach($evaluation->sections as $section)

                        @php
                            $isOverview = str_contains(strtoupper($section->section_title), 'OVERVIEW');
                            $isClinicalJudgement = str_contains(strtoupper($section->section_title), 'CLINICAL JUDGEMENT');
                        @endphp

                        <div class="evaluation-section mb-4">

                            <h5 class="section-title">{{ $section->section_title }}</h5>

                            {{-- NORMAL RATING TABLE --}}
                            @if(!$isOverview && !$isClinicalJudgement)

                                <div class="table-responsive">
                                    <table class="table table-bordered evaluation-table">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th width="40%">Criteria</th>
                                                <th class="text-center">Unsatisfactory</th>
                                                <th class="text-center">Needs Attention</th>
                                                <th class="text-center">Satisfactory</th>
                                                <th class="text-center">Well Above Average</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($section->points as $point)

                                                @php
                                                    $currentRating = $savedRatings[$point->id] ?? null;
                                                @endphp

                                                <tr>
                                                    <td>{{ $point->point_text }}</td>

                                                    <td class="text-center">
                                                        <span class="rating-circle"
                                                              data-point="{{ $point->id }}"
                                                              data-value="unsatisfactory">
                                                            {!! $currentRating=='unsatisfactory' ? '✕' : '○' !!}
                                                        </span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="rating-circle"
                                                              data-point="{{ $point->id }}"
                                                              data-value="needs_attention">
                                                            {!! $currentRating=='needs_attention' ? '✕' : '○' !!}
                                                        </span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="rating-circle"
                                                              data-point="{{ $point->id }}"
                                                              data-value="satisfactory">
                                                            {!! $currentRating=='satisfactory' ? '✕' : '○' !!}
                                                        </span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="rating-circle"
                                                              data-point="{{ $point->id }}"
                                                              data-value="well_above_average">
                                                            {!! $currentRating=='well_above_average' ? '✕' : '○' !!}
                                                        </span>
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            @endif


                            {{-- OVERVIEW SECTION --}}
                            @if($isOverview)
                                <div class="overview-section mt-3 p-2 bg-light rounded">
                                    <h6 class="fw-bold">GENERAL COMMENTS:</h6>
                                    <ul>
                                        @foreach($section->points as $point)
                                            <li>{{ $point->point_text }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            {{-- CLINICAL JUDGEMENT --}}
                            @if($isClinicalJudgement)
                                <div class="clinical-judgement-section mt-3 p-2 bg-light rounded">
                                    <h6 class="fw-bold">CLINICAL JUDGEMENT:</h6>
                                    <ul>
                                        @foreach($section->points as $point)
                                            <li>{{ $point->point_text }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </div>

                    @endforeach


                    <!-- SIGNATURE -->
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <h6>Evaluator's Signature: ______________________</h6>
                            <h6>Date: ______________________</h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Trainee's Signature: ______________________</h6>
                            <h6>Date: ______________________</h6>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="card-footer text-center no-print">
                    <button id="submitForm" class="btn btn-success">
                        <i class="fa fa-check"></i> Submit Form
                    </button>

                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>

let ratings = {};

// preload saved ratings
@foreach($savedRatings as $pointId => $value)
    ratings['{{ $pointId }}'] = '{{ $value }}';
@endforeach


// CLICK EVENT (fixed)
$(document).on('click','.rating-circle', function(){

    let pointId = $(this).data('point');
    let value = $(this).data('value');

    let row = $(this).closest('tr');

    row.find('.rating-circle').each(function(){
        $(this).html('○');
    });

    $(this).html('✕');

    ratings[pointId] = value;
});


// SUBMIT
$('#submitForm').click(function(){

    $.ajax({
        url: "{{ route('trainee-evaluations.submit', $evaluation->id) }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            ratings: ratings
        },
        success: function(res){
            alert(res.message);
            location.reload();
        },
        error:function(){
            alert('Error submitting evaluation');
        }
    });

});

</script>
@endpush


<style>

.rating-circle{
    font-size:20px;
    cursor:pointer;
}

.section-title{
    font-weight:600;
    padding:10px;
    background:#f8f9fa;
}

@media print{
    .no-print,
    .main-header,
    .main-sidebar,
    footer{
        display:none !important;
    }
    body{ background:white; }
}

</style>