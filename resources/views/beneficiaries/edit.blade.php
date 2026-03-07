@extends('layouts.app')

@section('content')

<h3>Edit Beneficiary</h3>

<form action="{{ route('beneficiaries.update',$beneficiary->beneficiary_id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">
    <label>First Name</label>
    <input type="text" name="first_name" value="{{ $beneficiary->first_name }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Middle Name</label>
    <input type="text" name="middle_name" value="{{ $beneficiary->middle_name }}" class="form-control">
</div>

<div class="mb-3">
    <label>Last Name</label>
    <input type="text" name="last_name" value="{{ $beneficiary->last_name }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" value="{{ $beneficiary->email }}" class="form-control">
</div>

<div class="mb-3">
    <label>Birth Date</label>
    <input type="date" name="birth_date" value="{{ $beneficiary->birth_date }}" class="form-control">
</div>

<div class="mb-3">
    <label>Age</label>
    <input type="number" name="age" value="{{ $beneficiary->age }}" class="form-control">
</div>

<div class="mb-3">
    <label>Sex</label>
    <select name="sex" class="form-control">
        <option value="" {{ $beneficiary->sex==''?'selected':'' }}>Select</option>
        <option value="Male" {{ $beneficiary->sex=='Male'?'selected':'' }}>Male</option>
        <option value="Female" {{ $beneficiary->sex=='Female'?'selected':'' }}>Female</option>
        <option value="Other" {{ $beneficiary->sex=='Other'?'selected':'' }}>Other</option>
    </select>
</div>

<div class="mb-3">
    <label>Address</label>
    <input type="text" name="address" value="{{ $beneficiary->address }}" class="form-control">
</div>

<div class="mb-3">
    <label>Contact Number</label>
    <input type="text" name="contact_number" value="{{ $beneficiary->contact_number }}" class="form-control">
</div>

<div class="mb-3">
    <label>Guardian Name</label>
    <input type="text" name="guardian_name" value="{{ $beneficiary->guardian_name }}" class="form-control">
</div>

<div class="mb-3">
    <label>Date Registered</label>
    <input type="datetime-local" name="date_registered" value="{{ $beneficiary->date_registered }}" class="form-control">
</div>

<button class="btn btn-success">Update</button>

</form>

@endsection