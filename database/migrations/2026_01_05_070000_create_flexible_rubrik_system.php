<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat sistem kategori penilaian fleksibel
     */
    public function up(): void
    {
        // 1. Buat tabel rubrik_kategori
        Schema::create('rubrik_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubrik_penilaian_id')
                  ->constrained('rubrik_penilaian')
                  ->cascadeOnDelete();
            $table->string('nama', 100); // Nama kategori: UTS, UAS, Quiz, Tugas, dll
            $table->decimal('bobot', 5, 2); // Bobot kategori (0-100)
            $table->integer('urutan')->default(0);
            $table->string('deskripsi')->nullable();
            $table->string('kode', 20)->nullable(); // Kode internal: uts, uas, quiz, dll
            $table->timestamps();
        });

        // 2. Tambah kolom rubrik_kategori_id ke rubrik_item
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->unsignedBigInteger('rubrik_kategori_id')
                  ->nullable()
                  ->after('rubrik_penilaian_id');
        });

        // 3. Migrasi data existing: buat kategori untuk setiap rubrik yang ada
        $rubriks = DB::table('rubrik_penilaian')->get();
        
        foreach ($rubriks as $rubrik) {
            // Buat kategori UTS
            $kategoriUtsId = DB::table('rubrik_kategori')->insertGetId([
                'rubrik_penilaian_id' => $rubrik->id,
                'nama' => 'UTS',
                'bobot' => $rubrik->bobot_uts ?? 50,
                'urutan' => 1,
                'deskripsi' => 'Ujian Tengah Semester (Minggu 1-8)',
                'kode' => 'uts',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat kategori UAS
            $kategoriUasId = DB::table('rubrik_kategori')->insertGetId([
                'rubrik_penilaian_id' => $rubrik->id,
                'nama' => 'UAS',
                'bobot' => $rubrik->bobot_uas ?? 50,
                'urutan' => 2,
                'deskripsi' => 'Ujian Akhir Semester (Minggu 9-16)',
                'kode' => 'uas',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update rubrik_item dengan kategori_id yang sesuai
            DB::table('rubrik_item')
                ->where('rubrik_penilaian_id', $rubrik->id)
                ->where('periode_ujian', 'uts')
                ->update(['rubrik_kategori_id' => $kategoriUtsId]);

            DB::table('rubrik_item')
                ->where('rubrik_penilaian_id', $rubrik->id)
                ->where('periode_ujian', 'uas')
                ->update(['rubrik_kategori_id' => $kategoriUasId]);
        }

        // 4. Tambah foreign key constraint setelah data dimigrasi
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->foreign('rubrik_kategori_id')
                  ->references('id')
                  ->on('rubrik_kategori')
                  ->cascadeOnDelete();
        });

        // 5. Hapus kolom periode_ujian dari rubrik_item
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->dropColumn('periode_ujian');
        });

        // 6. Hapus kolom bobot_uts dan bobot_uas dari rubrik_penilaian
        Schema::table('rubrik_penilaian', function (Blueprint $table) {
            $table->dropColumn(['bobot_uts', 'bobot_uas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Tambah kembali kolom bobot_uts dan bobot_uas
        Schema::table('rubrik_penilaian', function (Blueprint $table) {
            $table->decimal('bobot_uts', 5, 2)->default(50)->after('semester');
            $table->decimal('bobot_uas', 5, 2)->default(50)->after('bobot_uts');
        });

        // 2. Tambah kembali kolom periode_ujian
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->enum('periode_ujian', ['uts', 'uas'])->default('uts')->after('rubrik_penilaian_id');
        });

        // 3. Migrasi data kembali dari kategori ke periode_ujian
        $kategoris = DB::table('rubrik_kategori')->get();
        
        foreach ($kategoris as $kategori) {
            $periode = $kategori->kode === 'uts' ? 'uts' : 'uas';
            
            DB::table('rubrik_item')
                ->where('rubrik_kategori_id', $kategori->id)
                ->update(['periode_ujian' => $periode]);

            // Update bobot di rubrik_penilaian
            $column = $kategori->kode === 'uts' ? 'bobot_uts' : 'bobot_uas';
            DB::table('rubrik_penilaian')
                ->where('id', $kategori->rubrik_penilaian_id)
                ->update([$column => $kategori->bobot]);
        }

        // 4. Hapus foreign key dan kolom rubrik_kategori_id
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->dropForeign(['rubrik_kategori_id']);
            $table->dropColumn('rubrik_kategori_id');
        });

        // 5. Hapus tabel rubrik_kategori
        Schema::dropIfExists('rubrik_kategori');
    }
};
