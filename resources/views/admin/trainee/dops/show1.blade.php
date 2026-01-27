@extends('layouts.app')
<style>
        body {
            margin: 0px;
        }
        .dashboard14-col {
            background-color: #dee7e9;
            border-radius: 8px;
            color: #132625;
            font-weight: 500;
            margin-top: 25px;
            width: 100%;
            margin-left: 0px;
            font-size: 15px;
            padding: 14px 9px!important;
        }



        .Level-2 {

            height: 51px;
            border-right: solid #c7d8d8 thin;
        }


        .Level {
            border: solid #c7d8d8 thin;
            background-color: #fcfcfc;
            height: 52px;
            border-radius: 10px;
            margin-top: 10px;
            width: 100%;
            margin-left: 0px;
        }
        .Level-1 {

            margin-top: 12px;
    font-size: 15px;
    width: 83%;
        }
       .col-lg-12.Steps-to-be-Performed {
    background-color: #f6f6f6;
    margin-top: 15px;
    height: auto;
    border-radius: 10px;
    padding: 29px 15px 0;
    margin-bottom: 20px;
}

		.Steps-to-be-Performed li {
    margin-bottom: 11px;
}

        .col-lg-12.Steps-to-be-Performed span {
            background-color: #3db1b3;
            padding: 6px 11px;
            color: #ffffff;
            border-radius: 50px;
            margin-right: 8px;
            margin-left: 8px;
        }
        .col-lg-12.Steps-to-be-Performed p {
            font-size: 15px;
            color: #4e5c5c;
            margin-top: 15px;
        }
        .Rating {
            border: solid #c7d8d8 thin;
            background-color: #fcfcfc;
            height: 52px;
            border-radius: 10px;
            margin-top: 10px;
        }
        .Rating-1 {
            border-right: solid #c7d8d8 thin;
            margin-top: 1px;
            text-align: center;
            width: 10%;
            font-size: 20px;
            line-height: 50px;
            color: #324342;
            font-weight: 100;
        }
        .Rating-2 {
            margin-top: 15px;
            color: #425151;
            font-size: 15px;
        }
        .COMPETENCIES {
            border: solid #c7d8d8 thin;
            background-color: #fcfcfc;
            height: 65px;
            border-radius: 10px;
            margin-top: 10px;
        }
        .COMPETENCIES-1 {
            border-right: solid #c7d8d8 thin;
            margin-top: 1px;
            text-align: center;
            width: 6%;
            font-size: 20px;
            line-height: 63px;
            color: #324342;
            font-weight: 100;
        }
        .COMPETENCIES-2 {
            margin-top: 15px;
            color: #425151;
            font-size: 15px;
        }
        .COMPETENCIES-3 {
            border-left: solid #c7d8d8 thin;
            height: -webkit-fill-available;
        }

        .Defination {
            background-color: #dee7e9;
            border-radius: 10px;
            height: 60px;
            margin-bottom: 10px;
            margin-top: 15px;
        }
        .Defination span {
            background-color: #3db1b3;
            padding: 6px 11px;
            color: #ffffff;
            border-radius: 50px;
            margin-right: 8px;
            margin-left: 8px;
        }
        .Defination-1 {
            margin-top: 1px;
            text-align: center;
            width: 6%;
            font-size: 20px;
            line-height: 63px;
            color: #324342;
            font-weight: 100;

        }
        .Defination-cont {
            margin-top: 20px;
            font-size: 18px;
        }
        .Defination-cont1 {
            margin-top: 5px;
            text-align: center;
            font-size: 18px;
        }
        .Defination-cont2 {
            text-align: center;
            margin-top: 22px;
            font-size: 18px;
        }
        .Defination-cont11 {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
        .CICU {
            margin-left: -9px;
        }
        input.form-control {
            background-color: #fcfcfc;
            margin-top: 8px;
            border: none;
            box-shadow: none!important;
            outline: none;
            font-size: 17px;
        }
        .heading-mrg {
            margin-top: 22px;
        }
        .space {
            padding: 20px;
        }

		@media screen and (max-width: 420px) {
				.Level {
    height: auto;
    float: left;
    padding: 2px 0 14px;
}

		}


    </style>
@section('content')
<div class="container-fluid space">

    {{-- ================= HEADER ================= --}}
    <h3 class="inner-heading CICU">
        Global Summary - {{ $dops->title ?? '-' }}
    </h3>

    {{-- ================= BASIC INFO ================= --}}
    @if($dops->Dopsattempt->isNotEmpty())
        @php $attempt = $dops->Dopsattempt->first(); @endphp
        <table class="table table-bordered mb-4">
            <tr>
                <th>Trainee</th>
                <td>{{ $attempt->trainee->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $attempt->date ?? '-' }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $attempt->from_time ?? '-' }} - {{ $attempt->to_time ?? '-' }}</td>
            </tr>
        </table>
    @endif

    {{-- ================= LEVELS ================= --}}
    <div class="col-lg-12 dashboard14-col mb-3">
        <div class="col-lg-10">
            Level at which completed elements of the procedure based assessment were performed
        </div>
        <div class="col-lg-2">
            Tick (√) as Appropriate
        </div>
        <span style='float: right;color: white;background-color: red;' id="over_score_"></span>
        <span style='float: right;color: white;' id="over_grade_"></span>
    </div>

    @foreach($dops->levels ?? [] as $level)
        <div class="col-lg-12 Level mb-2">
            <div class="col-lg-8 col-xs-8 Level-1">
                {{ $level->level?->title ?? '-' }} - <small>{{ $level->level?->description ?? '-' }}</small>
            </div>
            <div class="col-lg-1 col-xs-2">
                <input type="checkbox"
                       class="form-control check_list"
                       data_score="{{ $level->level?->score ?? 0 }}"
                       disabled
                       {{ $level->status == 'A' ? 'checked' : '' }}
                       id="{{ $level->id }}"
                       pkid="{{ $level->pkid ?? 0 }}">
            </div>
            <div class="col-lg-1" style="float:right;margin-top:12px;font-size:15px;color:white;background-color:{{ $level->level?->color ?? '#333' }}">
                {{ $level->level?->score ?? 0 }}%
            </div>
        </div>
    @endforeach

    {{-- ================= RATINGS ================= --}}
    <p>
        <a class="btn btn-primary" data-bs-toggle="collapse" href="#ratingCollapse">
            Rating
        </a>
    </p>
    <div class="collapse" id="ratingCollapse">
        <div class="card card-body">
            @foreach($dops->ratings ?? [] as $rating)
                <div class="col-lg-12 Rating mb-2">
                    <div class="col-lg-2 Rating-1" style="color:white;background-color:{{ $rating->rating?->color ?? '#333' }}">
                        {{ $rating->rating?->score ?? 0 }}%
                    </div>
                    <div class="col-lg-2 Rating-1">
                        {{ $rating->rating?->title ?? '-' }}
                    </div>
                    <div class="col-lg-10 Rating-2">
                        <p>{{ $rating->rating?->description ?? '-' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= STEPS ================= --}}
    <h3 class="inner-heading heading-mrg">Steps to be Performed</h3>
    <div class="col-lg-12 Steps-to-be-Performed mb-3">
        {!! $dops->steps ?? '' !!}
    </div>

    {{-- ================= COMPETENCIES ================= --}}
    <h3 class="inner-heading heading-mrg">Competencies & Definitions</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($competencies as $comp)
                <tr style="background-color: lightgray">
                    <td>{{ $loop->iteration }}</td>
                    <td colspan="2"><strong>{{ $comp->competencies->name }}</strong></td>
                </tr>

                @foreach($comp->definitions as $def)
                    <tr>
                        <td></td>
                        <td>{{ $def->title }}</td>
                        <td width="20%">
                            <select class="form-control" disabled>
                                <option>Select...</option>
                                @foreach($dops->ratings ?? [] as $r)
                                    <option {{ $def->ratingid == $r->id ? 'selected' : '' }}>
                                        {{ $r->rating?->title ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    {{-- ================= COMMENTS ================= --}}
    <div class="row">
        <div class="col-lg-6">
            <h3 class="inner-heading heading-mrg">Trainee Comments</h3>
            <textarea class="form-control" rows="5" disabled>{{ $attempt->trainee_comments ?? '' }}</textarea>
        </div>

        <div class="col-lg-6">
            <h3 class="inner-heading heading-mrg">Trainer Comments</h3>
            <textarea class="form-control" rows="5" disabled>{{ $attempt->trainer_comments ?? '' }}</textarea>
        </div>
    </div>

    {{-- ================= HIDDEN INPUTS ================= --}}
    <input type="hidden" id="key" value="{{ $key ?? '' }}">
    <input type="hidden" id="id_dops" value="{{ $dops->id ?? '' }}">
    <input type="hidden" id="pid" value="{{ $pid ?? '' }}">
    <input type="hidden" id="did" value="{{ $dops->id ?? '' }}">
    <input type="hidden" id="login_id" value="{{ auth()->user()->id }}">
    <input type="hidden" id="ftime" value="{{ $ftime ?? '' }}">
    <input type="hidden" id="ttime" value="{{ $ttime ?? '' }}">
    <input type="hidden" id="dates" value="{{ $dates ?? '' }}">
    <input type="hidden" id="dtime" value="{{ $dtime ?? '' }}">
    <input type="hidden" id="rot" value="{{ $rot ?? '' }}">
    <input type="hidden" id="comments" value="{{ $comments ?? '' }}">
    <input type="hidden" id="procedure" value="{{ $procedure ?? '' }}">
    <input type="hidden" id="diagnosis" value="{{ $diagnosis ?? '' }}">
    <input type="hidden" id="mrn" value="{{ $mrn ?? '' }}">
    <input type="hidden" id="dia_free" value="{{ $dia_free ?? '' }}">
    <input type="hidden" id="pro_free" value="{{ $pro_free ?? '' }}">

</div>

{{-- ================= JS ================= --}}
@push('scripts')

<script src="{{ asset('js/tiny_mce/tiny_mce.js') }}"></script>
<script>
// TinyMCE init
function initEditor(EditorId, value){
    tinyMCE.init({
        selector: "#" + EditorId,
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | code',
        height: 150
    });
    $("#" + EditorId).val(value);
}

$(document).ready(function(){
    // Initialize editors
    initEditor('comment1','{{ $attempt->trainee_comments ?? '' }}');
    initEditor('comments2','{{ $attempt->trainer_comments ?? '' }}');

    // TODO: Add calculate(), calculate_check(), calculateoverall() JS functions here
});
</script>
@endpush

@endsection
