<?php

namespace Tests\Feature;

use App\Mail\EditLink;
use App\Models\Application;
use App\Models\ApplicationFile;
use App\Models\ApplicationLog;
use App\Models\ApplicationState;
use App\Models\MailQueue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class EditLinkTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private array $folders = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Mail::fake();

        foreach ([1 => 'Offen', 2 => 'Pendent', 3 => 'Genehmigt extern', 4 => 'Abgelehnt extern', 5 => 'Abgelehnt', 6 => 'Genehmigt', 7 => 'In Bearbeitung extern'] as $id => $description) {
            DB::table('application_states')->insert(['id' => $id, 'description' => $description]);
        }

        // User 1 owns the files applicants upload through the form.
        $this->admin = User::create([
            'uuid' => (string) Str::uuid(),
            'firstname' => 'Ada',
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
            'role' => 'admin',
        ]);
    }

    protected function tearDown(): void
    {
        // Media creates the application folders on the real disk.
        foreach ($this->folders as $uuid) {
            File::deleteDirectory(storage_path('app/files/' . $uuid));
        }
        parent::tearDown();
    }

    private function application(array $attributes = []): Application
    {
        $application = Application::forceCreate(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Verein Beispiel',
            'legal_form' => 'Verein',
            'bank_account' => 'ZKB',
            'iban' => '9300762011623852957',
            'beneficiary' => 'Verein Beispiel',
            'street' => 'Bahnhofstrasse',
            'street_number' => '1',
            'zip' => '8001',
            'city' => 'Zürich',
            'firstname' => 'Eva',
            'lastname' => 'Muster',
            'phone' => '044 000 00 00',
            'email' => 'eva@example.com',
            'portrait' => 'Portrait',
            'project_title' => 'Projekt',
            'project_description' => 'Beschreibung',
            'justification_funds' => 'Begründung',
            'project_beneficiaries' => 'Alle',
            'proportion_residents_benefit_program' => '80',
            'project_time' => '2027',
            'project_cost_total' => 10000,
            'project_own_contribution' => 2000,
            'project_contribution_requested' => 5000,
            'project_income' => 1000,
            'project_income_remarks' => 'Spenden',
            'application_state_id' => ApplicationState::OPEN,
            'year' => 2026,
            'remarks' => 'Interne Bemerkung',
            'textblock_approval' => 'Textbaustein',
        ], $attributes));
        $this->folders[] = $application->uuid;

        foreach (ApplicationFile::FORM_KEYS as $key => $title) {
            if ($key === 'project_description') {
                continue;
            }
            $this->storedFile($application, $key);
        }

        return $application;
    }

    private function storedFile(Application $application, ?string $key, int $userId = 1, ?string $title = null): ApplicationFile
    {
        $name = uniqid() . '_' . ($key ?? 'admin') . '.pdf';
        Storage::put('files/' . $application->uuid . '/' . $name, 'pdf');
        return ApplicationFile::create([
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'title' => $title ?? ($key ? ApplicationFile::FORM_KEYS[$key] : 'Admin-Datei'),
            'key' => $key,
            'application_id' => $application->id,
            'user_id' => $userId,
        ]);
    }

    private function tempUpload(?string $name = null): string
    {
        $name = $name ?? uniqid() . '_neu.pdf';
        Storage::put('public/uploads/temp/' . $name, 'pdf');
        return $name;
    }

    private function issue(Application $application): string
    {
        return $application->issueEditToken(14);
    }

    /**
     * The payload the form sends: what /form/edit returned, all files kept.
     */
    private function payload(string $token, array $overrides = []): array
    {
        $loaded = $this->getJson('/form/edit/' . $token)->assertOk();
        $payload = $loaded->json('application');
        $payload['name_change'] = $payload['former_name'] ? 'yes' : 'no';
        $payload['confirm_correctness'] = true;
        $payload['files'] = $loaded->json('files');
        return array_replace_recursive($payload, $overrides);
    }

    // Token lifecycle

    public function test_admin_issues_a_link_and_the_mail_is_sent(): void
    {
        $application = $this->application();

        $response = $this->actingAs($this->admin)->postJson('/api/application/edit-link/' . $application->uuid, [
            'email' => 'other@example.com',
            'message' => 'Bitte Budget ergänzen.',
        ]);

        $response->assertOk()->assertJson(['mail_sent' => true]);
        $url = $response->json('url');
        $this->assertStringContainsString('/formular/bearbeiten/', $url);

        $token = Str::afterLast($url, '/');
        $application->refresh();
        $this->assertSame(hash('sha256', $token), $application->edit_token);
        $this->assertTrue($application->edit_token_expires_at->between(now()->addDays(14)->subMinute(), now()->addDays(14)->addMinute()));

        Mail::assertSent(EditLink::class, fn (EditLink $mail) => $mail->hasTo('other@example.com'));
        $this->assertDatabaseHas('application_logs', ['application_id' => $application->id, 'user_id' => $this->admin->id]);
        $this->get('/formular/bearbeiten/' . $token)->assertOk()->assertSee('APORTA_EDIT_TOKEN', false);
    }

    public function test_link_requires_login_and_an_open_application(): void
    {
        $application = $this->application();
        $this->postJson('/api/application/edit-link/' . $application->uuid, ['email' => 'eva@example.com'])->assertUnauthorized();

        $application->update(['application_state_id' => ApplicationState::PENDING_APPROVAL]);
        $this->actingAs($this->admin)
            ->postJson('/api/application/edit-link/' . $application->uuid, ['email' => 'eva@example.com'])
            ->assertStatus(409);
        $this->assertNull($application->fresh()->edit_token);
    }

    public function test_editors_cannot_send_or_revoke_links(): void
    {
        $application = $this->application();
        $token = $this->issue($application);
        $editor = User::create([
            'uuid' => (string) Str::uuid(),
            'firstname' => 'Eddi',
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => bcrypt('secret'),
            'role' => 'editor',
        ]);

        $this->actingAs($editor)->postJson('/api/application/edit-link/' . $application->uuid, ['email' => 'x@example.com'])->assertForbidden();
        $this->actingAs($editor)->deleteJson('/api/application/edit-link/' . $application->uuid)->assertForbidden();
        $this->getJson('/form/edit/' . $token)->assertOk();
        Mail::assertNothingSent();
    }

    public function test_a_new_link_replaces_the_old_one(): void
    {
        $application = $this->application();
        $old = $this->issue($application);
        $new = $this->issue($application);

        $this->getJson('/form/edit/' . $old)->assertStatus(410);
        $this->getJson('/form/edit/' . $new)->assertOk();
    }

    public function test_expired_revoked_and_non_open_links_stop_working(): void
    {
        $application = $this->application();
        $token = $this->issue($application);
        $this->getJson('/form/edit/' . $token)->assertOk();

        $this->travel(15)->days();
        $this->getJson('/form/edit/' . $token)->assertStatus(410);
        $this->get('/formular/bearbeiten/' . $token)->assertStatus(410)->assertSee('nicht mehr gültig');
        $this->travelBack();

        $payload = $this->payload($token);
        $application->update(['application_state_id' => ApplicationState::IN_PROCESS_EXTERNAL]);
        $this->getJson('/form/edit/' . $token)->assertStatus(410);
        $this->putJson('/form/edit/' . $token, $payload)->assertStatus(410);

        $application->update(['application_state_id' => ApplicationState::OPEN]);
        $this->getJson('/form/edit/' . $token)->assertOk();

        $this->actingAs($this->admin)->deleteJson('/api/application/edit-link/' . $application->uuid)->assertOk();
        $this->getJson('/form/edit/' . $token)->assertStatus(410);
        $this->getJson('/form/edit/' . Str::random(64))->assertStatus(410);
    }

    // Loading

    public function test_show_returns_only_applicant_fields_and_form_files(): void
    {
        $application = $this->application();
        $admin = $this->storedFile($application, null, $this->admin->id, 'Budget');
        $token = $this->issue($application);

        $response = $this->getJson('/form/edit/' . $token)->assertOk();

        $this->assertSame('Verein Beispiel', $response->json('application.name'));
        $this->assertArrayNotHasKey('remarks', $response->json('application'));
        $this->assertArrayNotHasKey('application_state_id', $response->json('application'));
        $this->assertArrayHasKey('budget', $response->json('files'));
        $this->assertStringNotContainsString($admin->uuid, $response->getContent());
        $this->assertStringNotContainsString($application->edit_token, $response->getContent());
    }

    public function test_applicant_can_download_own_files_but_not_admin_files(): void
    {
        $application = $this->application();
        $admin = $this->storedFile($application, null, $this->admin->id);
        $token = $this->issue($application);
        $budget = $application->files()->where('key', 'budget')->first();

        $this->get('/form/edit/' . $token . '/file/' . $budget->uuid)->assertOk();
        $this->get('/form/edit/' . $token . '/file/' . $admin->uuid)->assertNotFound();
    }

    // Saving

    public function test_update_changes_applicant_fields_and_keeps_protected_fields(): void
    {
        $application = $this->application();
        $token = $this->issue($application);

        $this->putJson('/form/edit/' . $token, $this->payload($token, [
            'project_title' => 'Neuer Titel',
            'project_add_instit_2' => 'Stiftung X',
            'project_add_instit_total_2' => 1500,
            'project_add_instit_answer_2' => 'Zusage',
            // Not editable: ignored
            'application_state_id' => ApplicationState::APPROVED,
            'year' => 1999,
            'remarks' => 'Hack',
            'project_contribution_approved' => 99999,
        ]))->assertOk()->assertJson(['changes' => 4]);

        $application->refresh();
        $this->assertSame('Neuer Titel', $application->project_title);
        $this->assertEquals(1500, $application->project_add_instit_final_total);
        $this->assertSame(ApplicationState::OPEN, (int) $application->application_state_id);
        $this->assertEquals(2026, $application->year);
        $this->assertSame('Interne Bemerkung', $application->remarks);
        $this->assertSame('Textbaustein', $application->textblock_approval);
        $this->assertEquals(0, (float) $application->project_contribution_approved);
        $this->assertNotNull($application->edited_by_applicant_at);

        // The link keeps working until it expires.
        $this->getJson('/form/edit/' . $token)->assertOk();
    }

    public function test_update_logs_the_diff_and_queues_both_mails(): void
    {
        $application = $this->application();
        $token = $this->issue($application);

        $this->putJson('/form/edit/' . $token, $this->payload($token, [
            'iban' => '9300762011623852999',
            'city' => 'Winterthur',
            'project_cost_total' => '10000',  // same value as 10000.00, no change
        ]))->assertOk()->assertJson(['changes' => 2]);

        $log = ApplicationLog::where('application_id', $application->id)->latest('id')->first();
        $this->assertNull($log->user_id);
        $this->assertSame('Gesuch durch Gesuchsteller/in bearbeitet', $log->action);
        $this->assertSame(['iban', 'city'], array_column($log->details, 'field'));
        $this->assertSame('CH93 0076 2011 6238 5295 7', $log->details[0]['old']);
        $this->assertTrue($log->details[0]['bank']);
        $this->assertFalse($log->details[1]['bank']);

        $this->assertSame(['edit_notification', 'edit_confirmation'], MailQueue::orderBy('id')->pluck('type')->all());
        foreach (MailQueue::all() as $mail) {
            $this->assertStringNotContainsString($token, $mail->data);
            $this->assertStringNotContainsString($application->fresh()->edit_token, $mail->data);
            $this->assertStringNotContainsString('edit_token"', $mail->data);
        }
    }

    public function test_queued_edit_mails_render_and_send(): void
    {
        $application = $this->application();
        $token = $this->issue($application);
        $this->putJson('/form/edit/' . $token, $this->payload($token, ['iban' => '9300762011623852999']))->assertOk();

        (new \App\Tasks\Notification)();

        Mail::assertSent(\App\Mail\EditNotification::class, function ($mail) {
            $html = $mail->render();
            return $mail->hasTo(config('mail.to')) && str_contains($html, 'Bankangaben wurden geändert');
        });
        Mail::assertSent(\App\Mail\EditConfirmation::class, fn ($mail) => $mail->hasTo('eva@example.com') && $mail->render());
        $this->assertSame(0, MailQueue::unprocessed()->count());
        $this->assertNull(MailQueue::whereNotNull('error')->first());
    }

    // Files

    public function test_files_can_be_kept_replaced_and_removed_admin_files_stay(): void
    {
        $application = $this->application();
        $description = $this->storedFile($application, 'project_description');
        $admin = $this->storedFile($application, null, $this->admin->id, 'Budget');
        $oldBudget = $application->files()->where('key', 'budget')->first();
        $token = $this->issue($application);

        $payload = $this->payload($token);
        $payload['files']['budget'] = ['name' => $this->tempUpload('neu_budget.pdf'), 'title' => 'egal'];
        $payload['files']['project_description'] = null;

        $this->putJson('/form/edit/' . $token, $payload)->assertOk();

        $files = $application->files()->get();
        $budget = $files->firstWhere('key', 'budget');
        $this->assertSame('neu_budget.pdf', $budget->name);
        $this->assertSame('Budget', $budget->title);
        Storage::assertExists('files/' . $application->uuid . '/neu_budget.pdf');
        Storage::assertMissing('files/' . $application->uuid . '/' . $oldBudget->name);
        Storage::assertMissing('public/uploads/temp/neu_budget.pdf');

        $this->assertNull($files->firstWhere('key', 'project_description'));
        Storage::assertMissing('files/' . $application->uuid . '/' . $description->name);

        $this->assertNotNull($files->firstWhere('uuid', $admin->uuid));
        Storage::assertExists('files/' . $application->uuid . '/' . $admin->name);
        $this->assertNotNull($files->firstWhere('key', 'tax_exemption'));

        $log = ApplicationLog::where('application_id', $application->id)->latest('id')->first();
        $this->assertSame(['file_budget', 'file_project_description'], array_column($log->details, 'field'));
    }

    public function test_required_files_are_satisfied_by_existing_files_only(): void
    {
        $application = $this->application();
        $token = $this->issue($application);

        // Removing a required file fails.
        $payload = $this->payload($token);
        $payload['files']['budget'] = null;
        $response = $this->putJson('/form/edit/' . $token, $payload)->assertStatus(422);
        $this->assertSame('file_budget', $response->json('errors')['files.budget'][0]['field']);

        // Someone else's file, or a missing temp upload, is rejected.
        $other = $this->application();
        $payload = $this->payload($token);
        $payload['files']['budget'] = ['uuid' => $other->files()->where('key', 'budget')->first()->uuid, 'existing' => true];
        $payload['files']['bylaws'] = ['name' => '../../../.env', 'title' => 'Statuten'];
        $response = $this->putJson('/form/edit/' . $token, $payload)->assertStatus(422);
        $this->assertSame('file_budget', $response->json('errors')['files.budget'][0]['field']);
        $this->assertSame('file_bylaws', $response->json('errors')['files.bylaws'][0]['field']);

        // Field rules still apply.
        $this->putJson('/form/edit/' . $token, $this->payload($token, ['email' => 'kaputt']))
            ->assertStatus(422);
    }

    // Create mode is unchanged

    public function test_new_applications_still_work_and_get_file_keys(): void
    {
        $payload = $this->payload($this->issue($this->application()));
        $payload['files'] = [];
        foreach (ApplicationFile::FORM_KEYS as $key => $title) {
            $payload['files'][$key] = ['name' => $this->tempUpload(), 'title' => $title];
        }
        $payload['files']['portrait'] = null;

        $this->postJson('/form/submit', $payload)->assertOk();

        $application = Application::latest('id')->first();
        $this->folders[] = $application->uuid;
        $this->assertSame(ApplicationState::OPEN, (int) $application->application_state_id);
        $this->assertSame(array_keys(ApplicationFile::FORM_KEYS), $application->files()->orderBy('id')->pluck('key')->all());
        $this->assertSame(['notification', 'confirmation'], MailQueue::orderBy('id')->pluck('type')->all());
        $this->assertNull($application->edit_token);
    }
}
