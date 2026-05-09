<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;

class UserManagementTest extends HeartSystemTestCase
{
    public function test_admin_can_create_user(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('user-management.store'), [
            'first_name'            => 'New',
            'last_name'             => 'User',
            'email'                 => 'new.user@example.com',
            'username'              => 'newuser',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role_id'               => Role::where('name', 'Worker')->first()->id,
            'status'                => 'active',
        ]);

        $response->assertRedirect('/user-management');
        $this->assertDatabaseHas('users', ['email' => 'new.user@example.com', 'username' => 'newuser']);
    }

    public function test_admin_can_update_user(): void
    {
        $newUser = User::create([
            'email'      => 'edit.user@example.com',
            'first_name' => 'Edit',
            'last_name'  => 'User',
            'username'   => 'edituser',
            'password'   => bcrypt('oldpassword'),
            'role_id'    => Role::where('name', 'Worker')->first()->id,
            'status'     => 'active',
        ]);

        $this->actingAs($this->admin);

        $response = $this->put(route('user-management.update', $newUser->user_id), [
            'first_name'            => 'Edited',
            'last_name'             => 'User',
            'email'                 => 'edit.user@example.com',
            'username'              => 'edituser',
            'password'              => '',
            'password_confirmation' => '',
            'role_id'               => Role::where('name', 'Worker')->first()->id,
            'status'                => 'inactive',
        ]);

        $response->assertRedirect('/user-management');
        $this->assertDatabaseHas('users', ['user_id' => $newUser->user_id, 'status' => 'inactive']);
    }

    public function test_admin_can_delete_user(): void
    {
        $deleteUser = User::create([
            'email'      => 'delete.user@example.com',
            'first_name' => 'Delete',
            'last_name'  => 'User',
            'username'   => 'deleteuser',
            'password'   => bcrypt('password123'),
            'role_id'    => Role::where('name', 'Worker')->first()->id,
            'status'     => 'active',
        ]);

        $this->actingAs($this->admin);

        $response = $this->delete(route('user-management.destroy', $deleteUser->user_id));

        $response->assertRedirect('/user-management');
        $this->assertDatabaseMissing('users', ['email' => 'delete.user@example.com']);
    }

    public function test_duplicate_username_or_email_is_rejected(): void
    {
        User::create([
            'email'      => 'duplicate.user@example.com',
            'first_name' => 'Duplicate',
            'last_name'  => 'User',
            'username'   => 'duplicateuser',
            'password'   => bcrypt('password123'),
            'role_id'    => Role::where('name', 'Worker')->first()->id,
            'status'     => 'active',
        ]);

        $this->actingAs($this->admin);

        $response = $this->from('/user-management')
            ->post(route('user-management.store'), [
                'first_name'            => 'Duplicate',
                'last_name'             => 'User',
                'email'                 => 'duplicate.user@example.com',
                'username'              => 'duplicateuser',
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'role_id'               => Role::where('name', 'Worker')->first()->id,
                'status'                => 'active',
            ]);

        $response->assertRedirect('/user-management');
        $response->assertSessionHasErrors(['email', 'username']);
    }

    public function test_user_search_filters_results(): void
    {
        User::create([
            'email'      => 'search.user@example.com',
            'first_name' => 'Search',
            'last_name'  => 'User',
            'username'   => 'searchuser',
            'password'   => bcrypt('password123'),
            'role_id'    => Role::where('name', 'Worker')->first()->id,
            'status'     => 'active',
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('user-management.index', ['search' => 'searchuser']));

        $response->assertOk();
        $response->assertSeeText('Search User');
    }
}
