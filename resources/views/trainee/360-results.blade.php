@extends('layouts.app')
@section('content')

<h4>My 360 Evaluation</h4>

@foreach($evaluations as $e)
<div class="card p-3 mb-3">
    <h6>{{ $e->form->title }}</h6>

    @foreach($e->responses as $r)
        <p>
            <strong>{{ $r->section->section_title }}</strong> :
            {{ $r->score_1_5 }} / {{ $r->score_6_7 }}
        </p>
    @endforeach
</div>
@endforeach

@endsection
