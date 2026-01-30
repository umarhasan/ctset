@extends('layouts.app')
@section('content')

<h4>My 360 Evaluation</h4>

@foreach($evaluations as $e)
<div class="card p-3 mb-3">
    <h6>{{ $e->title }}</h6>
        <p>
            <strong>{{ $e->sections[0]['section_title'] }}</strong> :
            {{ $e->score_1_5 }} / {{ $e->score_6_7 }}
        </p>

</div>
@endforeach

@endsection
