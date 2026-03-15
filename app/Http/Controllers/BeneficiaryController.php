<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Role;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{

    public function index()
    {
        $beneficiaries = Beneficiary::latest()->paginate(10);
        $roles = Role::all();

        return view('beneficiaries.index',compact('beneficiaries', 'roles'));
    }

    public function create()
    {
        return view('beneficiaries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:beneficiaries,email',
            'birth_date' => 'nullable|date',
            'age' => 'nullable|integer',
            'sex' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'date_registered' => 'nullable|date',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $data = $request->all();
        if (empty($data['date_registered'])) {
            $data['date_registered'] = now();
        }
        Beneficiary::create($data);

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary created successfully');
    }

    public function show($id)
    {

        $beneficiary = Beneficiary::findOrFail($id);

        return view('beneficiaries.show',compact('beneficiary'));

    }

    public function edit($id)
    {

        $beneficiary = Beneficiary::findOrFail($id);

        return view('beneficiaries.edit',compact('beneficiary'));

    }

    public function update(Request $request, $id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:beneficiaries,email,' . $beneficiary->beneficiary_id . ',beneficiary_id',
            'birth_date' => 'nullable|date',
            'age' => 'nullable|integer',
            'sex' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'date_registered' => 'nullable|date',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $data = $request->all();
        if (empty($data['date_registered'])) {
            $data['date_registered'] = now();
        }

        $beneficiary->update($data);

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary updated');
    }

    public function destroy($id)
    {

        $beneficiary = Beneficiary::findOrFail($id);

        $beneficiary->delete();

        return redirect()->route('beneficiaries.index')
        ->with('success','Beneficiary deleted');

    }
}