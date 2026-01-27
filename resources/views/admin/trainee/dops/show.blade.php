@extends('layouts.app')

<style>
        body {
            margin: 0px;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container-fluid.space {
            padding: 20px;
            background-color: white;
            min-height: 100vh;
        }

        .inner-heading.CICU {
            color: #132625;
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3db1b3;
        }

        /* Basic Info Table */
        .table.table-bordered {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .table.table-bordered th,
        .table.table-bordered td {
            padding: 12px 15px;
            border: 1px solid #c7d8d8;
        }

        .table.table-bordered th {
            background-color: #dee7e9;
            color: #132625;
            font-weight: 500;
            width: 150px;
        }

        .table.table-bordered td {
            background-color: #fcfcfc;
            color: #425151;
        }

        /* Dashboard Header */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard14-col .col-lg-10 {
            flex: 1;
        }

        .dashboard14-col .col-lg-2 {
            text-align: right;
            font-weight: 600;
        }

        /* Level Items */
        .Level {
            border: solid #c7d8d8 thin;
            background-color: #fcfcfc;
            height: 52px;
            border-radius: 10px;
            margin-top: 10px;
            width: 100%;
            margin-left: 0px;
            display: flex;
            align-items: center;
            padding: 0 15px;
        }

        .Level-1 {
            margin-top: 12px;
            font-size: 15px;
            width: 83%;
            color: #324342;
        }

        .Level-1 small {
            color: #4e5c5c;
            font-size: 13px;
        }

        .Level .col-lg-1 input.form-control {
            background-color: #fcfcfc;
            margin-top: 8px;
            border: none;
            box-shadow: none!important;
            outline: none;
            font-size: 17px;
            width: 20px;
            height: 20px;
        }

        .Level .col-lg-1:last-child {
            font-size: 15px;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-align: center;
            min-width: 60px;
        }

        /* Rating Section */
        .btn-primary {
            background-color: #3db1b3;
            border-color: #3db1b3;
            margin: 15px 0;
        }

        .Rating {
            border: solid #c7d8d8 thin;
            background-color: #fcfcfc;
            height: 52px;
            border-radius: 10px;
            margin-top: 10px;
            display: flex;
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .Rating-2 {
            margin-top: 15px;
            color: #425151;
            font-size: 15px;
            padding-left: 15px;
            display: flex;
            align-items: center;
        }

        /* Steps Section */
        .heading-mrg {
            margin-top: 22px;
            color: #132625;
            font-weight: 600;
            font-size: 18px;
        }

        .col-lg-12.Steps-to-be-Performed {
            background-color: #f6f6f6;
            margin-top: 15px;
            height: auto;
            border-radius: 10px;
            padding: 29px 15px 0;
            margin-bottom: 20px;
        }

        .Steps-to-be-Performed ol {
            padding-left: 20px;
        }

        .Steps-to-be-Performed li {
            margin-bottom: 11px;
            color: #4e5c5c;
            font-size: 15px;
            line-height: 1.5;
            padding-left: 10px;
        }

        .col-lg-12.Steps-to-be-Performed span {
            background-color: #3db1b3;
            padding: 6px 11px;
            color: #ffffff;
            border-radius: 50px;
            margin-right: 8px;
            margin-left: 8px;
        }

        /* Competencies Table */
        .table.table-bordered {
            width: 100%;
            margin-top: 20px;
        }

        .table.table-bordered thead th {
            background-color: #dee7e9;
            color: #132625;
            font-weight: 500;
            padding: 12px;
            text-align: left;
        }

        .table.table-bordered tbody tr {
            border-bottom: 1px solid #c7d8d8;
        }

        .table.table-bordered tbody tr:first-child {
            border-top: 1px solid #c7d8d8;
        }

        .table.table-bordered tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        .table.table-bordered tbody tr[style*="background-color: lightgray"] {
            background-color: #dee7e9 !important;
        }

        .table.table-bordered tbody tr[style*="background-color: lightgray"] td {
            font-weight: 600;
            color: #132625;
        }

        .form-control {
            background-color: #fcfcfc;
            border: 1px solid #c7d8d8;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
            color: #425151;
        }

        .form-control:disabled {
            background-color: #f5f5f5;
            color: #666;
        }

        /* Comments Section */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .col-lg-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 15px;
        }

        textarea.form-control {
            width: 100%;
            min-height: 120px;
            padding: 15px;
            border: 1px solid #c7d8d8;
            border-radius: 8px;
            background-color: #fcfcfc;
            resize: vertical;
            font-size: 14px;
            color: #425151;
        }

        /* Hidden Inputs */
        input[type="hidden"] {
            display: none;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .col-lg-6 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 20px;
            }

            .Level {
                height: auto;
                padding: 10px 15px;
                flex-wrap: wrap;
            }

            .Level-1 {
                width: 100%;
                margin-bottom: 10px;
            }

            .dashboard14-col {
                flex-direction: column;
                text-align: center;
            }

            .dashboard14-col .col-lg-2 {
                margin-top: 10px;
            }
        }

        @media screen and (max-width: 420px) {
            .container-fluid.space {
                padding: 15px;
            }

            .Level {
                padding: 10px;
            }

            .Rating {
                flex-direction: column;
                height: auto;
                padding: 10px;
            }

            .Rating-1 {
                width: 100%;
                border-right: none;
                border-bottom: solid #c7d8d8 thin;
                padding-bottom: 10px;
                margin-bottom: 10px;
            }

            .Rating-2 {
                width: 100%;
                padding-left: 0;
            }
        }


        /* Table basic styling */
.table-bordered {
    border: 1px solid #ccc;
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}

.table-bordered th, .table-bordered td {
    border: 1px solid #ccc;
    padding: 8px;
    vertical-align: top;
}

/* Header styling */
.table-bordered thead th {
    background-color: #f2f2f2;
    font-weight: bold;
    text-align: center;
}

/* Competency row styling */
.competency-row {
    background-color: #d9d9d9;
    font-weight: bold;
}

/* Definitions styling */
.definition-row td {
    background-color: #fff;
    padding-left: 20px;
}

/* Rating dropdown styling */
.definition-row select {
    width: 100%;
}

/* Center the Negative/Neutral/Positive text */
.text-center {
    text-align: center;
}

/* Optional: small letters for definitions a), b), c) */
.definition-label {
    font-weight: normal;
}
    </style>
 @section('content')
<div class="container-fluid">


    <div class="container-fluid space">
        <!-- Header -->
        <h3 class="inner-heading CICU">
            Global Summary - {{ $dops->title ?? 'Procedure Assessment' }}
        </h3>

        <!-- Basic Information -->
        @if($dops->Dopsattempt && $dops->Dopsattempt->isNotEmpty())
            @php $attempt = $dops->Dopsattempt->first(); @endphp
            <table class="table table-bordered mb-4">
                <tr>
                    <th>Trainee</th>
                    <td>{{ $attempt->trainee->name ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ $attempt->date ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td>{{ $attempt->from_time ?? 'Not specified' }} - {{ $attempt->to_time ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>
                        @if($attempt->duration)
                            {{ $attempt->duration }} minutes
                        @elseif($attempt->from_time && $attempt->to_time)
                            @php
                                $start = strtotime($attempt->from_time);
                                $end = strtotime($attempt->to_time);
                                $duration = round(($end - $start) / 60);
                            @endphp
                            {{ $duration }} minutes
                        @else
                            Not specified
                        @endif
                    </td>
                </tr>
            </table>
        @else
            <div class="alert alert-warning">
                No assessment attempt data available.
            </div>
        @endif

        <!-- Levels Section -->
        <div class="col-lg-12 dashboard14-col mb-3">
            <div class="col-lg-10">
                Level at which completed elements of the procedure based assessment were performed
            </div>
            <div class="col-lg-2">
                Tick (√) as Appropriate
            </div>
        </div>

        <!-- Level Items - Dynamic from Controller -->
        @if(isset($dops->levels) && $dops->levels->isNotEmpty())
            @foreach($dops->levels as $level)
                <div class="col-lg-12 Level mb-2">
                    <div class="col-lg-8 col-xs-8 Level-1">
                        {{ $level->level?->title ?? 'Level' }} -
                        <small>{{ $level->level?->description ?? 'No description' }}</small>
                    </div>
                    <div class="col-lg-1 col-xs-2">
                        <input type="checkbox"
                               class="form-control check_list"
                               data_score="{{ $level->level?->score ?? 0 }}"
                               disabled
                               {{ $level->status == 'A' ? 'checked' : '' }}>
                    </div>
                    <div class="col-lg-1" style="float:right;margin-top:12px;font-size:15px;color:white;background-color:{{ $level->level?->color ?? '#3db1b3' }}">
                        {{ $level->level?->score ?? 0 }}%
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                No levels defined for this assessment.
            </div>
        @endif

        <!-- Ratings Section -->
        <p>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#ratingCollapse">
                Rating Scale
            </a>
        </p>
        <div class="collapse" id="ratingCollapse">
            <div class="card card-body">
                <!-- Rating Items - Dynamic from Controller -->
                @if(isset($dops->ratings) && $dops->ratings->isNotEmpty())
                    @foreach($dops->ratings as $rating)
                        <div class="col-md-12 Rating mb-2">
                            <div class="col-md-2 Rating-1" style="color:white;background-color:{{ $rating->rating?->color ?? '#3db1b3' }}">
                                {{ $rating->rating?->score ?? 0 }}%
                            </div>
                            <div class="col-md-8 Rating-1">
                        {{ strtoupper(substr(trim($rating->rating?->title ?? 'N'), 0, 1)) }}
                    </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        No rating scale defined for this assessment.
                    </div>
                @endif
            </div>
        </div>

        <!-- Steps Section -->
        <h3 class="inner-heading heading-mrg">Steps to be Performed</h3>
        <div class="col-lg-12 Steps-to-be-Performed mb-3">
            @if($dops->steps)
                {!! $dops->steps !!}
            @else
                <p>No steps defined for this procedure.</p>
            @endif
        </div>

       <!-- Competencies Section -->
<h3 class="inner-heading heading-mrg">Competencies & Definitions</h3>
@if($competencies && $competencies->isNotEmpty())
    <table class="table table-bordered" id="tbl_posts">
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th>Name</th>
                <th style="width:15%;">Rating</th>
            </tr>
        </thead>
        <tbody id="tbl_posts_body">
            @foreach($competencies as $comp)
                {{-- Compute totals for this competency --}}
                @php
                    $totalNegative = $comp->definitions->sum('mNegative');
                    $totalNeutral  = $comp->definitions->sum('mNeutral');
                    $totalPositive = $comp->definitions->sum('mPositive');
                @endphp

                {{-- COMPETENCY HEADER ROW --}}
                <tr class="parentNd" id="comp_{{ $comp->id }}" style="background-color: lightgray; font-weight:bold;">
                    <td>{{ $loop->iteration }}.</td>
                    <td>
                        <span style="font-weight:bold;">
                            {{ $comp->competencies->name ?? 'Competency' }}
                        </span>
                        <span style="float:right; font-weight:normal;">
                            Negative: {{ $totalNegative }}, Neutral: {{ $totalNeutral }}, Positive: {{ $totalPositive }}
                        </span>
                    </td>
                    <td></td>
                </tr>

                {{-- Definitions --}}
                @foreach($comp->definitions as $def)
                    <tr class="ChildNd_{{ $comp->id }}" data-pid="{{ $def->id }}">
                        <td></td>
                        <td>
                            {{ chr(96 + $loop->iteration) }}) {{ $def->title ?? '' }}
                        </td>
                        <td>
                            <select class="form-control dops_list" parentid="{{ $comp->id }}">
                                <option value="0">Select...</option>
                                @foreach($dops->ratings as $r)
                                    <option value="{{ $r->id }}" {{ $def->ratingid == $r->id ? 'selected' : '' }}>
                                        {{ strtoupper(substr($r->rating?->title ?? 'N', 0, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-info">
        No competencies defined for this assessment.
    </div>
    @endif


        <!-- Comments Section -->
        <div class="row">
            <div class="col-lg-6">
                <h3 class="inner-heading heading-mrg">Trainee Comments</h3>
                <textarea class="form-control" rows="5" disabled>
                    {{ $attempt->trainee_comments ?? 'No comments provided by the trainee.' }}
                </textarea>
            </div>

            <div class="col-lg-6">
                <h3 class="inner-heading heading-mrg">Trainer Comments</h3>
                <textarea class="form-control" rows="5" disabled>
                    {{ $attempt->trainer_comments ?? 'No feedback provided by the trainer.' }}
                </textarea>
            </div>
        </div>

        <!-- Overall Score Display -->
        @if(isset($attempt) && $attempt->overall_score)
            <div class="mt-4 p-3" style="background-color: #dee7e9; border-radius: 10px;">
                <h4 class="inner-heading">Overall Assessment Score</h4>
                <div class="row">
                    <div class="col-md-6">
                        <h5 style="color: #132625;">Score: <strong>{{ $attempt->overall_score }}/100</strong></h5>
                    </div>
                    <div class="col-md-6 text-end">
                        @php
                            $grade = '';
                            $gradeColor = '#132625';

                            if($attempt->overall_score >= 90) {
                                $grade = 'A+';
                                $gradeColor = '#28a745';
                            } elseif($attempt->overall_score >= 80) {
                                $grade = 'A';
                                $gradeColor = '#28a745';
                            } elseif($attempt->overall_score >= 70) {
                                $grade = 'B';
                                $gradeColor = '#3db1b3';
                            } elseif($attempt->overall_score >= 60) {
                                $grade = 'C';
                                $gradeColor = '#ffc107';
                            } else {
                                $grade = 'D';
                                $gradeColor = '#dc3545';
                            }
                        @endphp
                        <h5 style="color: {{ $gradeColor }};">Grade: <strong>{{ $grade }}</strong></h5>
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-4 pt-3 border-top text-center">
            <p style="color: #4e5c5c; font-size: 14px;">
                DOPS Assessment Form | Generated on {{ date('F d, Y') }}
            </p>
            <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
                Print Assessment
            </button>
        </div>

    </div>

@endsection

@push('scripts')
<script></script>

    <script>
        // Simple script for any interactive features
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOPS Assessment loaded successfully');

            // Auto-expand ratings section if needed
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('showRatings') === 'true') {
                const collapseElement = document.getElementById('ratingCollapse');
                if(collapseElement) {
                    const bsCollapse = new bootstrap.Collapse(collapseElement, {
                        toggle: true
                    });
                }
            }
        });
</script>
@endpush

