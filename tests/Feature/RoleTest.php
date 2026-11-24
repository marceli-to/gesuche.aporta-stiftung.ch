<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationState;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * role:admin lets only admins through. Editors (the city's reviewers) keep
 * the archive list and their own profile.
 */
class RoleTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'firstname' => ucfirst($role),
            'name' => 'Test',
            'email' => $role . '@example.com',
            'password' => bcrypt('secret'),
            'role' => $role,
        ]);
        $user->email_verified_at = now();
        $user->save();
        return $user;
    }

    private function application(): Application
    {
        DB::table('application_states')->insert(['id' => ApplicationState::OPEN, 'description' => 'Offen']);
        return Application::forceCreate([
            'uuid' => (string) Str::uuid(),
            'name' => 'Verein',
            'legal_form' => 'Verein',
            'bank_account' => 'ZKB',
            'iban' => '9300762011623852957',
            'beneficiary' => 'Verein',
            'street' => 'Strasse',
            'street_number' => '1',
            'zip' => '8000',
            'city' => 'Zürich',
            'firstname' => 'Eva',
            'lastname' => 'Muster',
            'phone' => '044',
            'email' => 'eva@example.com',
            'portrait' => 'Portrait',
            'project_title' => 'Projekt',
            'project_description' => 'Beschreibung',
            'justification_funds' => 'Begründung',
            'project_beneficiaries' => 'Alle',
            'proportion_residents_benefit_program' => '80',
            'project_time' => '2027',
            'project_income_remarks' => 'Spenden',
            'application_state_id' => ApplicationState::OPEN,
            'year' => 2026,
        ]);
    }

    public function test_editors_are_blocked_from_admin_routes(): void
    {
        $this->user('admin');
        $editor = $this->user('editor');
        $application = $this->application();

        $this->actingAs($editor)->deleteJson('/api/application/' . $application->uuid)->assertForbidden();
        $this->actingAs($editor)->putJson('/api/application/archive/' . $application->uuid, ['archive' => 1])->assertForbidden();
        $this->actingAs($editor)->getJson('/api/application/reverse/' . $application->uuid)->assertForbidden();
        $this->actingAs($editor)->postJson('/api/application-file/store', [])->assertForbidden();

        $this->assertNotNull($application->fresh());
        $this->assertEquals(0, $application->fresh()->archive);
    }

    public function test_admins_pass(): void
    {
        $admin = $this->user('admin');
        $application = $this->application();

        $this->actingAs($admin)->putJson('/api/application/archive/' . $application->uuid, ['archive' => 1])->assertOk();
        $this->assertEquals(1, $application->fresh()->archive);
    }

    public function test_editors_keep_the_archive_list(): void
    {
        $this->actingAs($this->user('editor'))->getJson('/api/applications/archiv')->assertOk();
    }

    public function test_editors_cannot_edit_or_finalize_applications(): void
    {
        $editor = $this->user('editor');
        $application = $this->application();

        $this->actingAs($editor)->putJson('/api/application/' . $application->uuid, ['name' => 'Gehackt'])->assertForbidden();
        $this->actingAs($editor)->putJson('/api/application/approve/final/' . $application->uuid)->assertForbidden();

        $fresh = $application->fresh();
        $this->assertEquals('Verein', $fresh->name);
        $this->assertEquals(ApplicationState::OPEN, $fresh->application_state_id);
    }

    public function test_editors_cannot_manage_users(): void
    {
        $admin = $this->user('admin');
        $editor = $this->user('editor');

        $this->actingAs($editor)->getJson('/api/users')->assertForbidden();
        $this->actingAs($editor)->postJson('/api/user', [
            'firstname' => 'Neu',
            'name' => 'Admin',
            'email' => 'neu@example.com',
            'password' => 'geheim1234',
        ])->assertForbidden();
        $this->actingAs($editor)->putJson('/api/user/' . $admin->id, [
            'firstname' => 'Admin',
            'name' => 'Test',
            'email' => 'admin@example.com',
            'password' => 'geheim1234',
        ])->assertForbidden();
        $this->actingAs($editor)->deleteJson('/api/user/' . $admin->id)->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'neu@example.com']);
        $this->assertNotNull($admin->fresh());
    }

    public function test_editors_can_update_their_own_profile(): void
    {
        $editor = $this->user('editor');

        $this->actingAs($editor)->putJson('/api/user/' . $editor->id, [
            'firstname' => 'Erika',
            'name' => 'Muster',
            'email' => 'editor@example.com',
        ])->assertOk();

        $this->assertEquals('Erika', $editor->fresh()->firstname);
        $this->assertEquals('editor', $editor->fresh()->role);
    }

    public function test_admins_can_manage_other_users(): void
    {
        $admin = $this->user('admin');
        $editor = $this->user('editor');

        $this->actingAs($admin)->getJson('/api/users')->assertOk();
        $this->actingAs($admin)->putJson('/api/user/' . $editor->id, [
            'firstname' => 'Geändert',
            'name' => 'Test',
            'email' => 'editor@example.com',
        ])->assertOk();

        $this->assertEquals('Geändert', $editor->fresh()->firstname);
    }
}
