@extends('layouts.app')

@section('content')

<h3>Beneficiary Details</h3>

<div class="card p-3">

<p><strong>First Name:</strong> {{ $beneficiary->first_name }}</p>
<p><strong>Middle Name:</strong> {{ $beneficiary->middle_name }}</p>
<p><strong>Last Name:</strong> {{ $beneficiary->last_name }}</p>
<p><strong>Email:</strong> {{ $beneficiary->email }}</p>
<p><strong>Birth Date:</strong> {{ $beneficiary->birth_date }}</p>
<p><strong>Age:</strong> {{ $beneficiary->age }}</p>
<p><strong>Sex:</strong> {{ $beneficiary->sex }}</p>
<p><strong>Address:</strong> {{ $beneficiary->address }}</p>
<p><strong>Contact Number:</strong> {{ $beneficiary->contact_number }}</p>
<p><strong>Guardian Name:</strong> {{ $beneficiary->guardian_name }}</p>
<p><strong>Date Registered:</strong> {{ $beneficiary->date_registered }}</p>

</div>

@endsection