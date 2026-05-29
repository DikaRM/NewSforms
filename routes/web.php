<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PengawasController;
use App\Models\Ujian;
Route::get('/guru/ujian/{ujiId}/kelas', function($ujiId) {
    $uji = Ujian::findOrFail($ujiId);
    $kelas = $uji->kelas()->get();
    return response()->json(['success' => true, 'data' => $kelas]);
})->name('guru.ujian.kelas');


Route::get('/', function () {
if (Auth::check()) {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect('/admin');
            case 'admin-ops':
                return redirect('/admin-ops');
            case 'siswa':
                return redirect('/siswa/sis');
            case 'guru':
                return redirect('/guru/siap');
            case 'pengawas':
                return redirect('/pengawas');
            default:
                Auth::logout();
                return view('login');
        }
    }
    return view('welcome');
});
Route::get('/login', function () {

    if (Auth::check()) {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect('/admin');
            case 'admin-ops':
                return redirect('/admin-ops');
            case 'siswa':
                return redirect('/siswa/sis');
            case 'guru':
                return redirect('/guru/siap');
            case 'pengawas':
                return redirect('/pengawas');
            default:
                Auth::logout();
                return view('login');
        }
    }
    return view('login');
})->name('login');





Route::post("/login/load", [UsersController::class, "login"])->name("users.store");
Route::post("/logout", [UsersController::class, "logout"])->name("users.logout");
Route::middleware(['auth'])->group(function(){
// Route Profile
 Route::get('/profile',[App\Http\Controllers\UsersController::class,'profil'])->name('profile.index');

Route::post('/profil/password', [App\Http\Controllers\Userscontroller::class, 'updatePassword'])->name('profile.password.update');
// Route Reset Password (POST)
Route::post('/profil/reset', [App\Http\Controllers\UsersController::class, 'sendResetLink'])->name('profile.password.reset');
});

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    
    Route::resource('/guru', GuruController::class);
    Route::resource('/siswa', SiswaController::class);
    
    Route::get('/kelas', [AdminController::class, 'KelasIndex'])->name('kelas');
    Route::post('/kelas', [AdminController::class, 'KelasCreate'])->name('tambah');
    Route::put('/kelas/{id}', [AdminController::class, 'KelasUpdate'])->name('date');
    Route::delete('/kelas/{id}', [AdminController::class, 'KelasDestroy'])->name('let');
    Route::post('/kelas/{id}', [AdminController::class, 'AddSiswa'])->name('ade');
    Route::get('/ruangan', [AdminController::class, 'RuangIndex'])->name('ruangan');
    Route::post('/ruangan', [AdminController::class, 'RuangCreate'])->name('tambah-ruangan');
    Route::put('/ruangan/{id}', [AdminController::class, 'RuangUpdate'])->name('update-ruangan');
    Route::delete('/ruangan/{id}', [AdminController::class, 'RuangDestroy'])->name('delete-ruangan');
    Route::get('/mapel', [AdminController::class, 'MapelIndex'])->name('mapel');
    Route::post('/mapel/buat', [AdminController::class, 'Made'])->name('made');
    Route::put('/mapel/{id}', [AdminController::class, 'MapelUpdate'])->name('deat');
    Route::delete('/mapel/{id}', [AdminController::class, 'MapelDestroy'])->name('letroy');
    Route::delete('/mapel-guru/{id}', [AdminController::class, 'RemoveGuru'])->name('roy');
    Route::post('/mapel', [AdminController::class, 'AddGuru'])->name('built');
});

// SISWA ROUTES
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/uji', [SiswaController::class, 'Siswas'])->name('uji');
    Route::get('/sis', [SiswaController::class, 'dashboard'])->name('index');
    
    Route::get('/riwayat', [SiswaController::class, 'riwayat'])->name('riwayat');
    Route::get('/jadwal', [SiswaController::class, 'jadwal'])->name('jadwal');
    Route::get('/{id}', [SiswaController::class, 'Starts'])->name('shop');
    Route::get('/detail/{id}', [SiswaController::class, 'detail'])->name('detail');
    Route::post('/saved', [SiswaController::class, 'Saved'])->name('save');
    // Di web.php
    Route::get('/ujian/resume/{id}', [SiswaController::class, 'resume'])->name('resume');
});

// GURU ROUTES
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/siap', [GuruController::class, 'TeachIndex'])->name('index');
    Route::post('/create-soal', [GuruController::class, 'rheina'])->name('soal.save');
    Route::post('/create', [GuruController::class, 'sed'])->name('soal.sad');
    Route::post('/publish', [GuruController::class, 'sept'])->name('soal.keep');
    Route::delete('/create-soal/pus/{id}', [GuruController::class, 'bowl'])->name('soal.destroy');
    Route::delete('/hapus-soal/{id}', [GuruController::class, 'hapus'])->name('hapus');
    
    Route::post('/store', [GuruController::class, 'CreateUjian'])->name('store');
    Route::post('/update-nilai', [GuruController::class, 'updateNilai'])->name('update-nilai');
    Route::get('/create-soal/{id}', [GuruController::class, 'CreateSoal'])->name('create');
    Route::get('/ujian/{id}/detail', [GuruController::class, 'detail'])->name('ujian.detail');
    Route::post('/save/{id}', [GuruController::class, 'def'])->name('ujian.sold');
    Route::post('/ujian/{id}/praktik', [GuruController::class, 'storePraktik'])->name('ujian.praktik');

    Route::get('/jadwal', [GuruController::class, 'jadwal'])->name('jadwal');
    Route::get('/result', [GuruController::class, 'result'])->name('result');
    Route::get('/hasil/{id}', [GuruController::class, 'hasil'])->name('hasil');
    Route::post('/catat-kecurangan', [GuruController::class, 'catatKecurangan'])->name('catat-kecurangan');
    Route::get('/riwayat', [GuruController::class, 'riwayat'])->name('riwayat');
    Route::get('/jadwal-susulan/{ujianId}', [GuruController::class, 'formJadwalSusulan'])->name('jadwal-susulan.form');
    Route::post('/jadwal-susulan', [GuruController::class, 'bareroll'])->name('jadwal-susulan.store');
    Route::put('/jadwal-susulan/{id}', [GuruController::class, 'updateJadwalSusulan'])->name('jadwal-susulan.update');
    Route::delete('/jadwal-susulan/{id}', [GuruController::class, 'destroyJadwalSusulan'])->name('jadwal-susulan.destroy');
    Route::post('/soal/{id}/update', [GuruController::class, 'editSoal'])->name('soal.update');
    Route::post('/soal/ready/{id}', [GuruController::class, 'ChangeState'])->name('publish');
Route::delete('/soal/{id}/delete', [GuruController::class, 'deleteSoal'])->name('soal.delete');
});

// PENGAWAS ROUTES

  Route::get('/pengawas/{id}', [PengawasController::class, 'index'])->name('pengawas.index');
    Route::post('/pengawas/attach', [PengawasController::class, 'store'])->name('pengawas.store');
    Route::post('/pengawas/abcent', [PengawasController::class, 'abcent'])->name('pengawas.abcent.store');
    Route::post('/pelanggarans/penalty', [SiswaController::class, 'Pelanggaran'])->name('pengawas.pelanggaran.pen');
    Route::get('/pengawas/show/{id}', [PengawasController::class, 'show'])->name('pengawas.show');
    Route::post('/pengawas/unblock/{siswa_id}/{ujian_id}', [PengawasController::class, 'unblockSiswa'])->name('pengawas.unblock');

// ADMIN-OPS ROUTES
Route::middleware(['auth', 'role:admin-ops'])->prefix('admin-ops')->name('admin-ops.')->group(function () {
    Route::get('/', [AdminController::class, 'ops'])->name('index');
    Route::get('/{id}', [AdminController::class, 'SetUji'])->name('set');
    Route::post('/create', [AdminController::class, 'operateCreate'])->name('sav');
    Route::post('/jadwal', [GuruController::class, 'createJadwalSusulan'])->name('jadwal-susulan.store');
    // Route Hapus Jadwal
Route::delete('/jadwal/{id}', [AdminController::class, 'operateDestroy'])->name('jadwal.destroy');

// Route Update/Edit Jadwal
Route::post('/jadwal/update/{id}', [AdminController::class, 'operateUpdate'])->name('jadwal.update');
});
Route::resource('/admin', AdminController::class);
Route::post('/import/soal', [GuruController::class, 'import'])->name('import.soal');
Route::post('/import/preview', [GuruController::class, 'preview'])->name('import.preview');
Route::post('/import/confirm', [GuruController::class, 'confirm'])->name('import.confirm');
Route::post('/siswa/violation', [SiswaController::class, 'reportViolation'])->name('siswa.violation');
Route::get('/check-block/{siswa_id}/{ujian_id}', [SiswaController::class, 'checkBlockStatus']);

// Route untuk mengambil data jawaban detail (AJAX)
Route::get('/guru/get-jawaban/{pesertaId}', [App\Http\Controllers\GuruController::class, 'getJawabanSiswa'])->name('guru.get-jawaban');
Route::get("/ruangan/{id}",[UsersController::class,"show"])->name("show-qr");
Route::post('/ruangan-check/{id}', [App\Http\Controllers\PengawasController::class, 'checkRuangan'])->name('ruangan.check');
Route::get('/proxy-spline', function() {
    $url = 'https://my.spline.design/draganddropbookpencilschoolcopy-2oyBmqYoZQJF4pK46vZCTquJ/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return response($response)->header('Content-Type', 'text/html');
});
// routes/web.php
Route::get('/admin/monitor', function() {
    $redis = Redis::connection();
    
    return [
        'queue_length' => $redis->llen('queues:default'),
        'redis_status' => $redis->ping(),
        'failed_jobs' => DB::table('failed_jobs')->count(),
    ];
})->middleware('auth');