<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{Pengguna, TargetMingguan, Kelompok, RuangKelas};
use Illuminate\Foundation\Testing\RefreshDatabase;

class TargetMingguanTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $admin;
    protected Pengguna $dosen;
    protected Pengguna $mahasiswa;
    protected RuangKelas $classRoom;
    protected Kelompok $group;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat admin
        $this->admin = Pengguna::factory()->create(['role' => 'admin']);
        
        // Buat dosen
        $this->dosen = Pengguna::factory()->create(['role' => 'dosen']);
        
        // Buat kelas yang diampu dosen
        $this->classRoom = RuangKelas::factory()->create(['dosen_id' => $this->dosen->id]);
        
        // Buat kelompok di kelas
        $this->group = Kelompok::factory()->create(['class_room_id' => $this->classRoom->id]);
        
        // Buat mahasiswa
        $this->mahasiswa = Pengguna::factory()->create(['role' => 'mahasiswa']);
        
        // Tambahkan mahasiswa ke kelompok
        $this->group->members()->create(['user_id' => $this->mahasiswa->id]);
    }

    /**
     * Test dosen bisa akses halaman index target
     */
    public function test_dosen_can_view_targets_index(): void
    {
        $response = $this->actingAs($this->dosen)->get(route('targets.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('target.daftar');
    }

    /**
     * Test dosen bisa akses halaman create target
     */
    public function test_dosen_can_view_create_target_form(): void
    {
        $response = $this->actingAs($this->dosen)->get(route('targets.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('target.tambah');
    }

    /**
     * Test mahasiswa tidak bisa akses halaman create target
     */
    public function test_mahasiswa_cannot_access_create_target(): void
    {
        $response = $this->actingAs($this->mahasiswa)->get(route('targets.create'));
        
        $response->assertStatus(403);
    }

    /**
     * Test dosen bisa membuat target untuk kelasnya
     */
    public function test_dosen_can_create_target_for_own_class(): void
    {
        $data = [
            'target_type' => 'single',
            'group_id' => $this->group->id,
            'week_number' => 1,
            'title' => 'Target Minggu 1',
            'description' => 'Deskripsi target',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'todo_items' => [
                ['title' => 'Todo 1', 'description' => 'Deskripsi todo'],
            ],
        ];

        $response = $this->actingAs($this->dosen)->post(route('targets.store'), $data);
        
        $response->assertRedirect(route('targets.index'));
        $this->assertDatabaseHas('target_mingguan', [
            'group_id' => $this->group->id,
            'title' => 'Target Minggu 1',
        ]);
    }

    /**
     * Test validasi store target - field wajib
     */
    public function test_store_target_validation_required_fields(): void
    {
        $response = $this->actingAs($this->dosen)->post(route('targets.store'), []);
        
        $response->assertSessionHasErrors(['target_type', 'week_number', 'title', 'description', 'deadline', 'todo_items']);
    }

    /**
     * Test dosen bisa melihat target di kelasnya
     */
    public function test_dosen_can_view_target_in_own_class(): void
    {
        $target = TargetMingguan::factory()->create([
            'group_id' => $this->group->id,
            'created_by' => $this->dosen->id,
        ]);

        $response = $this->actingAs($this->dosen)->get(route('targets.show', $target));
        
        $response->assertStatus(200);
    }

    /**
     * Test dosen tidak bisa melihat target di kelas lain
     */
    public function test_dosen_cannot_view_target_in_other_class(): void
    {
        $otherDosen = Pengguna::factory()->create(['role' => 'dosen']);
        $otherClassRoom = RuangKelas::factory()->create(['dosen_id' => $otherDosen->id]);
        $otherGroup = Kelompok::factory()->create(['class_room_id' => $otherClassRoom->id]);
        
        $target = TargetMingguan::factory()->create([
            'group_id' => $otherGroup->id,
            'created_by' => $otherDosen->id,
        ]);

        $response = $this->actingAs($this->dosen)->get(route('targets.show', $target));
        
        $response->assertStatus(403);
    }

    /**
     * Test mahasiswa bisa lihat target kelompoknya
     */
    public function test_mahasiswa_can_view_own_group_target(): void
    {
        $target = TargetMingguan::factory()->create([
            'group_id' => $this->group->id,
            'created_by' => $this->dosen->id,
        ]);

        $response = $this->actingAs($this->mahasiswa)->get(route('targets.show', $target));
        
        $response->assertStatus(200);
    }

    /**
     * Test admin bisa melihat semua target
     */
    public function test_admin_can_view_all_targets(): void
    {
        $target = TargetMingguan::factory()->create([
            'group_id' => $this->group->id,
            'created_by' => $this->dosen->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('targets.show', $target));
        
        $response->assertStatus(200);
    }

    /**
     * Test dosen bisa update semua target dalam satu minggu
     */
    public function test_dosen_can_update_week_targets(): void
    {
        $target = TargetMingguan::factory()->create([
            'group_id' => $this->group->id,
            'created_by' => $this->dosen->id,
            'week_number' => 1,
        ]);

        $data = [
            'week_number' => 2,
            'title' => 'Target Week Updated',
            'description' => 'Deskripsi updated',
            'deadline' => now()->addDays(14)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->dosen)->put(
            route('targets.week.update', ['weekNumber' => 1, 'classRoomId' => $this->classRoom->id]), 
            $data
        );
        
        $response->assertRedirect(route('targets.index'));
        $this->assertDatabaseHas('target_mingguan', [
            'id' => $target->id,
            'title' => 'Target Week Updated',
        ]);
    }

    /**
     * Test dosen bisa hapus semua target dalam satu minggu
     */
    public function test_dosen_can_delete_week_targets(): void
    {
        $target = TargetMingguan::factory()->create([
            'group_id' => $this->group->id,
            'created_by' => $this->dosen->id,
            'week_number' => 1,
        ]);

        $response = $this->actingAs($this->dosen)->delete(
            route('targets.week.destroy', ['weekNumber' => 1, 'classRoomId' => $this->classRoom->id])
        );
        
        $response->assertRedirect(route('targets.index'));
        $this->assertDatabaseMissing('target_mingguan', ['id' => $target->id]);
    }
}
