@extends('layouts.app')
@section('content')

<h4>{{ $form->title }}</h4>

<form id="evalForm">
@csrf

@foreach($form->sections as $s)
@php $r = $responses[$s->id] ?? null; @endphp

<div class="card p-3 mb-3">
    <strong>{{ $s->section_title }}</strong>

    <select name="responses[{{ $s->id }}][score_1_5]" class="form-select mt-2">
        <option value="">1–5</option>
        @for($i=1;$i<=5;$i++)
            <option value="{{ $i }}" @selected(optional($r)->score_1_5==$i)>
                {{ $i }}
            </option>
        @endfor
    </select>

    <select name="responses[{{ $s->id }}][score_6_7]" class="form-select mt-2">
        <option value="">6–7</option>
        <option value="6" @selected(optional($r)->score_6_7==6)>6</option>
        <option value="7" @selected(optional($r)->score_6_7==7)>7</option>
    </select>

    <textarea class="form-control mt-2"
        name="responses[{{ $s->id }}][comments]"
        placeholder="Comments">{{ $r->comments ?? '' }}</textarea>
</div>
@endforeach

<button type="button" onclick="saveDraft()" class="btn btn-warning">Save Draft</button>
<button type="button" onclick="finalSubmit()" class="btn btn-success">Final Submit</button>

</form>

<script>
function saveDraft(){
    $.post(location.pathname+'/save',$('#evalForm').serialize());
}

function finalSubmit(){
    if(confirm('Final submit?')){
        $.post(location.pathname+'/submit',{_token:'{{ csrf_token() }}'},()=>{
            alert('Submitted');
            location.reload();
        });
    }
}
</script>
@endsection
