@extends('layouts.app')

@section('content')

<h3>Create Beneficiary</h3>

<form action="{{ route('beneficiaries.store') }}" method="POST">

@csrf

<div class="mb-3">
    <label>First Name</label>
    <input type="text" name="first_name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Middle Name</label>
    <input type="text" name="middle_name" class="form-control">
</div>

<div class="mb-3">
    <label>Last Name</label>
    <input type="text" name="last_name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control">
</div>

<div class="mb-3">
    <label>Birth Date</label>
    <input type="date" name="birth_date" class="form-control">
</div>

<div class="mb-3">
    <label>Age</label>
    <input type="number" name="age" class="form-control">
</div>

<div class="mb-3">
    <label>Sex</label>
    <select name="sex" class="form-control">
        <option value="">Select</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
</div>

<div class="mb-3">
    <label>Address</label>
    <input type="text" name="address" class="form-control">
</div>

<div class="mb-3">
    <label>Contact Number</label>
    <input type="text" name="contact_number" class="form-control">
</div>

<div class="mb-3">
    <label>Guardian Name</label>
    <input type="text" name="guardian_name" class="form-control">
</div>

<div class="mb-3">
    <label>Date Registered</label>
    <input type="datetime-local" name="date_registered" class="form-control">
</div>

<button class="btn btn-primary">Save</button>

</form>

@endsection