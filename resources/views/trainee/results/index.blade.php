@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        My Results
    </div>

    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Exam</th>
                    <th>Average</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->exam->exam_name }}</td>
                    <td>{{ $result->average_marks }}</td>
                    <td>
                        <a href="{{ route('trainee.results.view',$result->exam_id) }}"
                           class="btn btn-info btn-sm">
                            View
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $results->links() }}
    </div>
</div>
@endsection
