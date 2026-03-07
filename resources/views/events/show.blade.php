@extends('layouts.app')

@section('content')

<h3>Event Details</h3>

<div class="card p-3">

<p><strong>Name:</strong> {{ $event->event_name }}</p>

<p><strong>Type:</strong> {{ $event->event_type }}</p>

<p><strong>Date:</strong> {{ $event->event_date }}</p>

<p><strong>Location:</strong> {{ $event->location }}</p>

<p><strong>Description:</strong> {{ $event->description }}</p>

<p><strong>Status:</strong> {{ $event->status }}</p>

</div>

@endsection