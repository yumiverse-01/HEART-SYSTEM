<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $beneficiaries = Beneficiary::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name',  'like', "%{$search}%")
                      ->orWhere('email',       'like', "%{$search}%")
                      ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('sex'), function ($query) use ($request) {
                $query->where('sex', $request->sex);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $this->logActivity(
            'Viewed Beneficiary List',
            'Beneficiary',
            'Staff viewed the beneficiary list',
            ['search' => $request->search, 'sex_filter' => $request->sex]
        );

        return view('beneficiaries.index', compact('beneficiaries'));
    }

    public function create()
    {
        $this->logActivity(
            'Viewed Beneficiary Create Form',
            'Beneficiary',
            'Staff opened the beneficiary creation form'
        );

        return view('beneficiaries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:beneficiaries,email',
            'birth_date'      => 'nullable|date',
            'age'             => 'nullable|integer',
            'sex'             => 'required|in:Male,Female,Other',
            'address'         => 'nullable|string',
            'contact_number'  => 'nullable|string',
            'guardian_name'   => 'nullable|string',
            'date_registered' => 'nullable|date',
        ]);

        $data = $request->all();
        if (empty($data['date_registered'])) {
            $data['date_registered'] = now();
        }

        $beneficiary = Beneficiary::create($data);

        $this->logActivity(
            'Created Beneficiary',
            'Beneficiary',
            'Staff created a new beneficiary: ' . $beneficiary->first_name . ' ' . $beneficiary->last_name,
            ['beneficiary_id' => $beneficiary->getKey(), 'name' => $beneficiary->first_name . ' ' . $beneficiary->last_name, 'email' => $beneficiary->email]
        );

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary created successfully');
    }

    public function show($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        $this->logActivity(
            'Viewed Beneficiary Profile',
            'Beneficiary',
            'Staff viewed profile of beneficiary: ' . $beneficiary->first_name . ' ' . $beneficiary->last_name,
            ['beneficiary_id' => $id]
        );

        return view('beneficiaries.show', compact('beneficiary'));
    }

    public function edit($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        $this->logActivity(
            'Viewed Beneficiary Edit Form',
            'Beneficiary',
            'Staff opened the edit form for beneficiary: ' . $beneficiary->first_name . ' ' . $beneficiary->last_name,
            ['beneficiary_id' => $id]
        );

        return view('beneficiaries.edit', compact('beneficiary'));
    }

    public function update(Request $request, $id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:beneficiaries,email,' . $beneficiary->beneficiary_id . ',beneficiary_id',
            'birth_date'      => 'nullable|date',
            'age'             => 'nullable|integer',
            'sex'             => 'nullable|in:Male,Female,Other',
            'address'         => 'nullable|string',
            'contact_number'  => 'nullable|string',
            'guardian_name'   => 'nullable|string',
            'date_registered' => 'nullable|date',
        ]);

        $data = $request->all();
        if (empty($data['date_registered'])) {
            $data['date_registered'] = now();
        }

        $beneficiary->update($data);

        $this->logActivity(
            'Updated Beneficiary',
            'Beneficiary',
            'Staff updated beneficiary: ' . $beneficiary->first_name . ' ' . $beneficiary->last_name,
            ['beneficiary_id' => $id, 'name' => $beneficiary->first_name . ' ' . $beneficiary->last_name]
        );

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary updated');
    }

    public function destroy($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        $this->logActivity(
            'Deleted Beneficiary',
            'Beneficiary',
            'Staff deleted beneficiary: ' . $beneficiary->first_name . ' ' . $beneficiary->last_name,
            ['beneficiary_id' => $id, 'name' => $beneficiary->first_name . ' ' . $beneficiary->last_name, 'email' => $beneficiary->email]
        );

        $beneficiary->delete();

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary deleted');
    }
}