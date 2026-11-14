<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Penduduk;
use App\Models\User;
use App\Services\ApiSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RestApiAndManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'posyandu_id' => null,
        ]);
    }

    private function createPosyanduUser(): User
    {
        return User::factory()->create([
            'role' => 'posyandu',
        ]);
    }

    public function test_admin_can_access_api_management_and_docs_while_posyandu_is_forbidden(): void
    {
        $admin = $this->createAdmin();
        $posyandu = $this->createPosyanduUser();

        $this->actingAs($posyandu)->get('/manajemen-api')->assertStatus(403);
        $this->actingAs($posyandu)->get('/manajemen-api/dokumentasi')->assertStatus(403);

        $this->actingAs($admin)->get('/manajemen-api')->assertStatus(200)->assertSee('Manajemen REST API');
        $this->actingAs($admin)->get('/manajemen-api/dokumentasi')->assertStatus(200)->assertSee('Dokumentasi REST API');
    }

    public function test_admin_can_update_api_security_settings_and_manage_keys(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post('/manajemen-api/settings', [
            'api_enabled' => '1',
            'api_auth_required' => '1',
            'api_rate_limit' => 30,
            'api_cors_origins' => 'https://example.com',
            'api_default_per_page' => 20,
            'api_max_per_page' => 100,
            'api_mask_sensitive' => '1',
            'api_logging_enabled' => '1',
        ])->assertRedirect();

        $this->assertEquals('30', ApiSettingsService::get('api_rate_limit'));
        $this->assertEquals('1', ApiSettingsService::get('api_mask_sensitive'));

        $response = $this->actingAs($admin)->post('/manajemen-api/keys', [
            'name' => 'Integrasi Mobile',
            'abilities' => ['read:penduduk'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('new_api_key');

        $key = ApiKey::first();
        $this->assertNotNull($key);
        $this->assertTrue($key->is_active);

        $this->actingAs($admin)->patch("/manajemen-api/keys/{$key->id}/toggle")->assertRedirect();
        $this->assertFalse($key->fresh()->is_active);

        $this->actingAs($admin)->delete("/manajemen-api/keys/{$key->id}")->assertRedirect();
        $this->assertDatabaseCount('api_keys', 0);
    }

    public function test_api_security_enforces_auth_scopes_masking_and_master_switch(): void
    {
        Penduduk::create([
            'nik' => '3201011234560001',
            'no_kk' => '3201011234560000',
            'nama' => 'Budi Santoso',
            'kelamin' => 'laki-laki',
        ]);

        // 1. Health endpoint works without key
        $this->getJson('/api/v1/health')->assertStatus(200)->assertJsonPath('success', true);

        // 2. Protected endpoint rejects missing key when api_auth_required = 1
        $this->getJson('/api/v1/penduduks')->assertStatus(401);

        // 3. Key with limited scope (read:penduduk) can access /penduduks but not /posyandus
        $generated = ApiKey::generateKey();
        ApiKey::create([
            'name' => 'Limited Key',
            'key_prefix' => $generated['prefix'],
            'key_hash' => $generated['hash'],
            'abilities' => ['read:penduduk'],
            'is_active' => true,
        ]);

        $this->withHeader('X-API-Key', $generated['plain'])
            ->getJson('/api/v1/penduduks')
            ->assertStatus(200)
            ->assertJsonPath('data.0.nama', 'Budi Santoso');

        $this->withHeader('X-API-Key', $generated['plain'])
            ->getJson('/api/v1/posyandus')
            ->assertStatus(403);

        // 4. Sensitive data masking works when enabled
        ApiSettingsService::updateMany(['api_mask_sensitive' => '1']);
        $maskedRes = $this->withHeader('Authorization', 'Bearer ' . $generated['plain'])
            ->getJson('/api/v1/penduduks')
            ->assertStatus(200);
        $this->assertEquals('3201********0001', $maskedRes->json('data.0.nik'));

        // 5. Master switch disables API with 503
        ApiSettingsService::updateMany(['api_enabled' => '0']);
        $this->withHeader('X-API-Key', $generated['plain'])
            ->getJson('/api/v1/penduduks')
            ->assertStatus(503);
    }

    public function test_all_non_mysql_specific_api_endpoints_respond_with_full_access_key(): void
    {
        $generated = ApiKey::generateKey();
        ApiKey::create([
            'name' => 'Full Access Key',
            'key_prefix' => $generated['prefix'],
            'key_hash' => $generated['hash'],
            'abilities' => ['*'],
            'is_active' => true,
        ]);

        $endpoints = [
            '/api/v1/penduduks',
            '/api/v1/ibus',
            '/api/v1/ibu-hamils',
            '/api/v1/bayi-balitas',
            '/api/v1/pemeriksaan-balitas',
            '/api/v1/imunisasi-balitas',
            '/api/v1/remajas',
            '/api/v1/pemeriksaan-remajas',
            '/api/v1/lansias',
            '/api/v1/pemeriksaan-lansias',
            '/api/v1/wuses',
            '/api/v1/puses',
            '/api/v1/beritas',
            '/api/v1/galeries',
            '/api/v1/jadwals',
            '/api/v1/tims',
            '/api/v1/buku-tamus',
            '/api/v1/laporan-masyarakats',
            '/api/v1/posyandus',
            '/api/v1/kaders',
            '/api/v1/pengaturans',
        ];

        foreach ($endpoints as $ep) {
            $this->withHeader('X-API-Key', $generated['plain'])
                ->getJson($ep)
                ->assertStatus(200)
                ->assertJsonPath('success', true);
        }
    }
}

