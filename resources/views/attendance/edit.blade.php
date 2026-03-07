@extends('layouts.app')

@section('content')

<h3>Edit Attendance</h3>

<form action="{{ route('attendance.update',$attendance->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">

<label>Beneficiary</label>

<select name="beneficiary_id" class="form-control">

@foreach($beneficiaries as $b)

<option value="{{ $b->id }}"
@if($attendance->beneficiary_id == $b->id) selected @endif>

{{ $b->name }}

</option>

@endforeach

</select>

</div>


<div class="mb-3">

<label>Event</label>

<select name="event_id" class="form-control">

@foreach($events as $e)

<option value="{{ $e->id }}"
@if($attendance->event_id == $e->id) selected @endif>

{{ $e->title }}

</option>

@endforeach

</select>

</div>


<div class="mb-3">

<label>Status</label>

<select name="attendance_status" class="form-control">

<option value="Present"
@if($attendance->attendance_status == 'Present') selected @endif>

Present

</option>

<option value="Absent"
@if($attendance->attendance_status == 'Absent') selected @endif>

Absent

</option>

</select>

</div>


<button class="btn btn-success">Update Attendance</button>

</form>

@endsection