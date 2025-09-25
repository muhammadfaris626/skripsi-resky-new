<?php

namespace Tests\Feature;

use App\Livewire\Databases\Users\CreateUser;
use App\Livewire\Databases\Users\IndexUser;
use App\Livewire\Databases\Users\ReadUser;
use App\Livewire\Databases\Users\UpdateUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles for testing
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'user', 'guard_name' => 'web']);

        // Create a test user and authenticate
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $this->actingAs($this->user);
    }

    /** @test */
    public function can_view_users_index_page()
    {
        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function can_render_users_index_component()
    {
        Livewire::test(IndexUser::class)
            ->assertStatus(200)
            ->assertSee('Manage Users');
    }

    /** @test */
    public function can_search_users()
    {
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);

        Livewire::test(IndexUser::class)
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    /** @test */
    public function can_filter_users_by_role()
    {
        $adminUser = User::factory()->create(['name' => 'Admin User']);
        $regularUser = User::factory()->create(['name' => 'Regular User']);

        $adminUser->assignRole('admin');
        $regularUser->assignRole('user');

        Livewire::test(IndexUser::class)
            ->set('roleFilter', 'admin')
            ->assertSee('Admin User')
            ->assertDontSee('Regular User');
    }

    /** @test */
    public function can_delete_user()
    {
        $userToDelete = User::factory()->create(['name' => 'User to Delete']);

        Livewire::test(IndexUser::class)
            ->call('delete', $userToDelete->id)
            ->assertDispatched('alert');

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /** @test */
    public function cannot_delete_current_user()
    {
        Livewire::test(IndexUser::class)
            ->call('delete', $this->user->id)
            ->assertDispatched('alert');

        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
    }

    /** @test */
    public function can_view_create_user_page()
    {
        $response = $this->get(route('users.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function can_create_user()
    {
        Livewire::test(CreateUser::class)
            ->set('name', 'New User')
            ->set('email', 'newuser@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('selectedRoles', ['user'])
            ->call('setAction', 'save')
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com'
        ]);

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue($user->hasRole('user'));
    }

    /** @test */
    public function create_user_validates_required_fields()
    {
        Livewire::test(CreateUser::class)
            ->set('name', '')
            ->set('email', '')
            ->set('password', '')
            ->set('selectedRoles', [])
            ->call('setAction', 'save')
            ->assertHasErrors(['name', 'email', 'password', 'selectedRoles']);
    }

    /** @test */
    public function create_user_validates_unique_email()
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        Livewire::test(CreateUser::class)
            ->set('name', 'New User')
            ->set('email', 'existing@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('selectedRoles', ['user'])
            ->call('setAction', 'save')
            ->assertHasErrors(['email']);
    }

    /** @test */
    public function can_view_user_details()
    {
        $user = User::factory()->create(['name' => 'Test User']);
        $user->assignRole('admin');

        $response = $this->get(route('users.read', $user->id));
        $response->assertStatus(200);

        Livewire::test(ReadUser::class, ['id' => $user->id])
            ->assertSee('Test User')
            ->assertSee('Admin');
    }

    /** @test */
    public function can_view_update_user_page()
    {
        $user = User::factory()->create();
        $response = $this->get(route('users.update', $user->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function can_update_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        $user->assignRole('user');

        Livewire::test(UpdateUser::class, ['id' => $user->id])
            ->set('name', 'New Name')
            ->set('email', $user->email)
            ->set('selectedRoles', ['admin'])
            ->call('update')
            ->assertRedirect(route('users.index'));

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('user'));
    }

    /** @test */
    public function can_update_user_password()
    {
        $user = User::factory()->create();
        $oldPassword = $user->password;

        Livewire::test(UpdateUser::class, ['id' => $user->id])
            ->set('name', $user->name)
            ->set('email', $user->email)
            ->set('password', 'newpassword123')
            ->set('password_confirmation', 'newpassword123')
            ->set('selectedRoles', ['user'])
            ->call('update');

        $user->refresh();
        $this->assertNotEquals($oldPassword, $user->password);
    }
}
