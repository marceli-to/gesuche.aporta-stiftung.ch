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
 * role:admin lets only admins through. Editors keep the archive list.
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
}
