@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">Dr Abdul Aziz CTR</h3>
                            <p class="text-muted mb-0">SELF EVALUATION FORM</p>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('self-evaluations.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <a href="{{ route('self-evaluations.edit', $evaluation->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- SELF EVALUATION FORM HEADER --}}
                    <div class="text-center mb-4">
                        <h2>SELF EVALUATION FORM - SELF EVALUATION FORM</h2>
                    </div>

                    {{-- GOALS SECTION --}}
                    <div class="goals-section mb-4">
                        <h4>Goals (Mandatory)</h4>
                        <ul class="text-muted">
                            <li>A minimum of three goals is required.</li>
                            <li>Trainees must include at least 1 non-technical goal.</li>
                            <li>Detail regarding incidents, circumstances, interactions etc leading to these goals is encouraged.</li>
                            <li>A plan to achieve each goal should be included – can be short, medium, long-term or a combination.</li>
                        </ul>

                        {{-- GOALS TABLE --}}
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="10%">No.</th>
                                        <th width="45%">Goals</th>
                                        <th width="45%">Plan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $goalPlanActions = is_array($evaluation->goal_plan_actions) ? $evaluation->goal_plan_actions : [];
                                    @endphp
                                    
                                    @for($i = 1; $i <= 5; $i++)
                                        <tr>
                                            <td class="text-center align-middle">{{ $i }}</td>
                                            <td>{{ $goalPlanActions[$i-1]['goal'] ?? '' }}</td>
                                            <td>{{ $goalPlanActions[$i-1]['plan'] ?? '' }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- REFLECTION SECTION WITH COLLAPSE --}}
                    <div class="reflection-section mb-4">
                        <h4>Reflection (optional)</h4>
                        
                        @php
                            $reflectionCategories = [
                                'A' => 'MEDICAL EXPERTISE',
                                'B' => 'JUDGEMENT – CLINICAL DECISION MAKING',
                                'C' => 'TECHNICAL EXPERTISE',
                                'D' => 'PROFESSIONALISM AND ETHICS',
                                'E' => 'HEALTH ADVOCACY',
                                'F' => 'COMMUNICATION',
                                'G' => 'COLLABORATION AND TEAMWORK',
                                'H' => 'MANAGEMENT AND LEADERSHIP',
                                'I' => 'SCHOLAR AND TEACHER'
                            ];
                            
                            $questionActions = is_array($evaluation->question_actions) ? $evaluation->question_actions : [];
                        @endphp

                        <div class="accordion" id="reflectionAccordion">
                            @foreach($reflectionCategories as $key => $category)
                                <div class="accordion-item mb-2 border">
                                    <h2 class="accordion-header" id="heading{{ $key }}">
                                        <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $key }}" aria-expanded="false" 
                                                aria-controls="collapse{{ $key }}">
                                            <strong>{{ $key }}. {{ $category }}</strong>
                                            @if(!empty($questionActions[$loop->index]['question'] ?? ''))
                                                <span class="badge bg-success ms-3">Completed</span>
                                            @else
                                                <span class="badge bg-secondary ms-3">Not filled</span>
                                            @endif
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $key }}" class="accordion-collapse collapse" 
                                         aria-labelledby="heading{{ $key }}" data-bs-parent="#reflectionAccordion">
                                        <div class="accordion-body">
                                            @if(!empty($questionActions[$loop->index]['question'] ?? ''))
                                                {{ $questionActions[$loop->index]['question'] }}
                                            @else
                                                <p class="text-muted mb-0">No reflection provided for {{ $category }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- COMMENTS SECTION --}}
                    <div class="comments-section">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Trainee Comments</h5>
                                <div class="p-3 bg-light">
                                    {{ $evaluation->trainee_comments ?? '' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Trainer Comments</h5>
                                <div class="p-3 bg-light">
                                    {{ $evaluation->trainer_comments ?? 'trainer test comments' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.accordion-button:not(.collapsed) {
    background-color: #e7f1ff;
    color: #0d6efd;
}
.accordion-button.collapsed {
    background-color: #f8f9fa;
}
.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(0,0,0,.125);
}
.accordion-item {
    border-radius: 5px;
    overflow: hidden;
}
</style>

@push('scripts')
<script>
// Bootstrap 5 collapse - ensure all are collapsed by default
document.addEventListener('DOMContentLoaded', function() {
    var accordions = document.querySelectorAll('.accordion-collapse');
    accordions.forEach(function(accordion) {
        accordion.classList.remove('show');
    });
});
</script>
@endpush

@endsection