<?php

namespace Tests\Feature;

use App\Models\Beneficiary;

class BeneficiaryTest extends HeartSystemTestCase
{
    public function test_admin_can_create_beneficiary(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('beneficiaries.store'), [
            'first_name'      => 'John',
            'middle_name'     => 'A',
            'last_name'       => 'Doe',
            'email'           => 'john.doe@example.com',
            'birth_date'      => '1990-01-01',
            'age'             => 33,
            'sex'             => 'Male',
            'address'         => '123 Main St',
            'contact_number'  => '09171234567',
            'guardian_name'   => 'Jane Doe',
            'date_registered' => '2026-05-09',
        ]);

        $response->assertRedirect(route('beneficiaries.index'));
        $this->assertDatabaseHas('beneficiaries', ['email' => 'john.doe@example.com']);
    }

    public function test_admin_can_update_beneficiary(): void
    {
        $beneficiary = Beneficiary::create([
            'first_name'      => 'Anne',
            'middle_name'     => 'M',
            'last_name'       => 'Smith',
            'email'           => 'anne.smith@example.com',
            'birth_date'      => '1985-04-05',
            'age'             => 41,
            'sex'             => 'Female',
            'address'         => '456 Oak Ave',
            'contact_number'  => '09175554433',
            'guardian_name'   => 'Mary Smith',
            'date_registered' => '2026-05-09',
        ]);

        $this->actingAs($this->admin);

        $response = $this->put(route('beneficiaries.update', $beneficiary->beneficiary_id), [
            'first_name'      => 'Anne',
            'middle_name'     => 'Marie',
            'last_name'       => 'Smith',
            'email'           => 'anne.smith@example.com',
            'birth_date'      => '1985-04-05',
            'age'             => 41,
            'sex'             => 'Female',
            'address'         => '789 Pine Rd',
            'contact_number'  => '09175554433',
            'guardian_name'   => 'Mary Smith',
            'date_registered' => '2026-05-09',
        ]);

        $response->assertRedirect(route('beneficiaries.index'));
        $this->assertDatabaseHas('beneficiaries', ['address' => '789 Pine Rd']);
    }

    public function test_admin_can_delete_beneficiary(): void
    {
        $beneficiary = Beneficiary::create([
            'first_name'      => 'Victor',
            'middle_name'     => 'L',
            'last_name'       => 'Hugo',
            'email'           => 'victor.hugo@example.com',
            'birth_date'      => '1970-03-10',
            'age'             => 56,
            'sex'             => 'Male',
            'address'         => '999 Elm St',
            'contact_number'  => '09170001122',
            'guardian_name'   => 'Louise Hugo',
            'date_registered' => '2026-05-09',
        ]);

        $this->actingAs($this->admin);

        $response = $this->delete(route('beneficiaries.destroy', $beneficiary->beneficiary_id));

        $response->assertRedirect(route('beneficiaries.index'));
        $this->assertDatabaseMissing('beneficiaries', ['email' => 'victor.hugo@example.com']);
    }

    public function test_beneficiary_search_filters_results(): void
    {
        Beneficiary::create([
            'first_name'      => 'Search',
            'middle_name'     => 'A',
            'last_name'       => 'Match',
            'email'           => 'search.match@example.com',
            'sex'             => 'Other',
        ]);

        Beneficiary::create([
            'first_name'      => 'Other',
            'middle_name'     => 'B',
            'last_name'       => 'Result',
            'email'           => 'other.result@example.com',
            'sex'             => 'Male',
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('beneficiaries.index', ['search' => 'Search']));

        $response->assertOk();
        $response->assertSeeText('Search A Match');
        $response->assertDontSeeText('Other Result');
    }

    public function test_duplicate_beneficiary_email_is_rejected(): void
    {
        Beneficiary::create([
            'first_name'      => 'Unique',
            'middle_name'     => null,
            'last_name'       => 'Email',
            'email'           => 'unique.email@example.com',
            'sex'             => 'Female',
        ]);

        $this->actingAs($this->admin);

        $response = $this->from(route('beneficiaries.create'))
            ->post(route('beneficiaries.store'), [
                'first_name'      => 'Duplicate',
                'middle_name'     => null,
                'last_name'       => 'Email',
                'email'           => 'unique.email@example.com',
                'sex'             => 'Female',
            ]);

        $response->assertRedirect(route('beneficiaries.create'));
        $response->assertSessionHasErrors(['email']);
    }
}
