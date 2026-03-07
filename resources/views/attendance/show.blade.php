@extends('layouts.app')

@section('content')

<h3>Attendance Details</h3>

<div class="card p-3">

<p><strong>Beneficiary:</strong> {{ $attendance->beneficiary->name }}</p>

<p><strong>Event:</strong> {{ $attendance->event->title }}</p>

<p><strong>Status:</strong>

@if($attendance->attendance_status == 'Present')

<span class="badge bg-success">Present</span>

@else

<span class="badge bg-danger">Absent</span>

@endif

</p>

</div>

@endsection