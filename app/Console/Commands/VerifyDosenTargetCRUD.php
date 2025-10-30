<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pengguna;
use App\Models\TargetMingguan;
use Illuminate\Support\Facades\Route;

class VerifyDosenTargetCRUD extends Command
{
    protected $signature = 'verify:dosen-crud';
    protected $description = 'Verify that Dosen Target CRUD features are working';

    public function handle()
    {
        $this->info('🔍 Verifying Dosen Target CRUD Features...');
        $this->newLine();

        // Check User
        $this->info('1️⃣  Checking Dosen User...');
        $dosen = Pengguna::where('email', 'dosen@politala.ac.id')->first();
        
        if (!$dosen) {
            $this->error('   ❌ Dosen user not found!');
            $this->warn('   Create dosen user with: php artisan db:seed');
            return 1;
        }
        
        $this->info('   ✅ Dosen user found: ' . $dosen->name);
        $this->info('   ✅ Role: ' . $dosen->role);
        $this->newLine();

        // Check Routes
        $this->info('2️⃣  Checking Routes...');
        $requiredRoutes = [
            'targets.index' => 'GET /targets',
            'targets.create' => 'GET /targets/create',
            'targets.store' => 'POST /targets',
            'targets.show' => 'GET /targets/{target}/show',
            'targets.edit' => 'GET /targets/{target}/edit',
            'targets.update' => 'PUT /targets/{target}',
            'targets.destroy' => 'DELETE /targets/{target}',
        ];

        $allRoutesExist = true;
        foreach ($requiredRoutes as $routeName => $routePath) {
            if (Route::has($routeName)) {
                $this->info('   ✅ ' . $routeName . ' → ' . $routePath);
            } else {
                $this->error('   ❌ ' . $routeName . ' → NOT FOUND');
                $allRoutesExist = false;
            }
        }
        $this->newLine();

        if (!$allRoutesExist) {
            $this->error('Some routes are missing!');
            return 1;
        }

        // Check Controller
        $this->info('3️⃣  Checking Controller...');
        $controllerExists = class_exists('App\Http\Controllers\WeeklyTargetController');
        
        if ($controllerExists) {
            $this->info('   ✅ WeeklyTargetController exists');
            
            $controller = new \ReflectionClass('App\Http\Controllers\WeeklyTargetController');
            $methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
            
            foreach ($methods as $method) {
                if ($controller->hasMethod($method)) {
                    $this->info('   ✅ Method: ' . $method . '()');
                } else {
                    $this->error('   ❌ Method: ' . $method . '() NOT FOUND');
                }
            }
        } else {
            $this->error('   ❌ WeeklyTargetController not found!');
        }
        $this->newLine();

        // Check Views
        $this->info('4️⃣  Checking Views...');
        $requiredViews = [
            'targets.index',
            'targets.create',
            'targets.show',
            'targets.edit',
        ];

        foreach ($requiredViews as $view) {
            $viewPath = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');
            if (file_exists($viewPath)) {
                $this->info('   ✅ ' . $view . '.blade.php');
            } else {
                $this->error('   ❌ ' . $view . '.blade.php NOT FOUND');
            }
        }
        $this->newLine();

        // Check Permissions
        $this->info('5️⃣  Checking Permissions...');
        
        if ($dosen->isDosen()) {
            $this->info('   ✅ User has isDosen() method');
        } else {
            $this->error('   ❌ isDosen() returned false');
        }
        $this->newLine();

        // Check Targets
        $this->info('6️⃣  Checking Existing Targets...');
        $targetCount = TargetMingguan::count();
        
        $this->info('   📊 Total targets in database: ' . $targetCount);
        
        if ($targetCount > 0) {
            $recentTarget = TargetMingguan::latest()->first();
            $this->info('   📌 Latest target: ' . $recentTarget->title);
            $this->info('   👤 Created by: ' . ($recentTarget->creator->name ?? 'Unknown'));
        } else {
            $this->warn('   ⚠️  No targets found. You can create one!');
        }
        $this->newLine();

        // Summary
        $this->info('═══════════════════════════════════════════════════');
        $this->info('✅ VERIFICATION COMPLETE!');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();
        
        $this->info('📝 Next Steps:');
        $this->info('   1. Login as dosen: dosen@politala.ac.id / password');
        $this->info('   2. Navigate to: http://localhost:8000/targets');
        $this->info('   3. Look for button: "Buat Target Baru" (top right)');
        $this->info('   4. In table, look for: [Detail] [Edit] [Hapus] buttons');
        $this->newLine();
        
        $this->info('🎯 Access URLs:');
        $this->info('   • List Targets:   http://localhost:8000/targets');
        $this->info('   • Create Target:  http://localhost:8000/targets/create');
        $this->newLine();

        return 0;
    }
}



