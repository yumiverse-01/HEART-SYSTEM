@extends('layouts.app')

@section('content')

<h3>Record Attendance</h3>

<form action="{{ route('attendance.store') }}" method="POST">

@csrf

<div class="mb-3">

<label>Beneficiary</label>

<select name="beneficiary_id" class="form-control">

@foreach($beneficiaries as $b)

<option value="{{ $b->id }}">{{ $b->name }}</option>

@endforeach

</select>

</div>


<div class="mb-3">

<label>Event</label>

<select name="event_id" class="form-control">

@foreach($events as $e)

<option value="{{ $e->id }}">{{ $e->title }}</option>

@endforeach

</select>

</div>


<div class="mb-3">

<label>Status</label>

<select name="attendance_status" class="form-control">

<option value="Present">Present</option>

<option value="Absent">Absent</option>

</select>

</div>

<button class="btn btn-primary">Save</button>

</form>

@endsection