@extends('layouts.app')

@section('content')

<h3>Edit Event</h3>

<form action="{{ route('events.update',$event->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">
<label>Event Name</label>
<input type="text" name="event_name" value="{{ $event->event_name }}" class="form-control" required>
</div>

<div class="mb-3">
<label>Event Type</label>
<input type="text" name="event_type" value="{{ $event->event_type }}" class="form-control">
</div>

<div class="mb-3">
<label>Date</label>
<input type="date" name="event_date" value="{{ $event->event_date }}" class="form-control" required>
</div>

<div class="mb-3">
<label>Location</label>
<input type="text" name="location" value="{{ $event->location }}" class="form-control">
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control">{{ $event->description }}</textarea>
</div>

<div class="mb-3">
<label>Status</label>
<select name="status" class="form-control">
    <option value="Upcoming" {{ $event->status=='Upcoming'?'selected':'' }}>Upcoming</option>
    <option value="Completed" {{ $event->status=='Completed'?'selected':'' }}>Completed</option>
    <option value="Cancelled" {{ $event->status=='Cancelled'?'selected':'' }}>Cancelled</option>
</select>
</div>

<button class="btn btn-success">Update</button>

</form>

@endsection