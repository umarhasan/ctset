@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>{{ $share->form->title }}</h3>
    <p><strong>Status:</strong> {{ $share->status }}</p>

    @foreach($share->form->sections as $section)
        <div class="card mb-3">
            <div class="card-header bg-light">
                <strong>{{ $section->section_title }}</strong>
            </div>
            <div class="card-body">
                @foreach($section->subsections as $sub)
                    <div class="mb-2">
                        <p><strong>{{ $sub->subtitle }}</strong></p>
                        <div class="row text-center">
                            <div class="col-md-4"><small>1-5:</small> {{ $sub->col_1_5 }}</div>
                            <div class="col-md-4"><small>6-7:</small> {{ $sub->col_6_7 }}</div>
                            <div class="col-md-4"><small>UE:</small> {{ $sub->ue }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
