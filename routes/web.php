<?php

use App\Http\Controllers\pages\AbsensiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\data_essentials\AreaController;
use App\Http\Controllers\data_essentials\EmployeeTypeController;
use App\Http\Controllers\data_essentials\FormasiTimController;
use App\Http\Controllers\data_essentials\JabatanController;
use App\Http\Controllers\data_essentials\JenisAbsensiController;
use App\Http\Controllers\data_essentials\JenisCutiController;
use App\Http\Controllers\data_essentials\KategoriController;
use App\Http\Controllers\data_essentials\RoleController;
use App\Http\Controllers\data_essentials\SeksiController;
use App\Http\Controllers\data_essentials\ProvinsiController;
use App\Http\Controllers\data_essentials\RoleUserController;
use App\Http\Controllers\data_essentials\WalikotaController;
use App\Http\Controllers\data_essentials\KecamatanController;
use App\Http\Controllers\data_essentials\KelurahanController;
use App\Http\Controllers\data_essentials\KonfigurasiAbsensiController;
use App\Http\Controllers\data_essentials\KonfigurasiCutiController;
use App\Http\Controllers\data_essentials\KonfigurasiGudangController;
use App\Http\Controllers\data_essentials\KontrakController;
use App\Http\Controllers\data_essentials\UnitKerjaController;
use App\Http\Controllers\data_essentials\PulauController;
use App\Http\Controllers\data_essentials\StrukturController;
use App\Http\Controllers\data_essentials\TimController;
use App\Http\Controllers\data_essentials\UserController;
use App\Http\Controllers\data_essentials\GudangController;
use App\Http\Controllers\pages\BarangController;
use App\Http\Controllers\pages\BarangPulauController;
use App\Http\Controllers\pages\KinerjaController;
use App\Http\Controllers\pages\CutiController;
use App\Http\Controllers\pages\PengirimanBarangController;
use App\Http\Controllers\pages\TransaksiBarangPulauController;
use App\Http\Controllers\user\aset\DashboardController as AsetDashboardController;
use App\Http\Controllers\user\aset\GudangBarangController;
use App\Http\Controllers\user\aset\KontrakController as AsetKontrakController;
use App\Http\Controllers\user\aset\ShippingController;
use App\Http\Controllers\user\simoja\AbsensiController as SimojaAbsensiController;
use App\Http\Controllers\user\simoja\CutiController as SimojaCutiController;
use App\Http\Controllers\user\simoja\DashboardController as SimojaDashboardController;
use App\Http\Controllers\user\simoja\KinerjaController as SimojaKinerjaController;

// Route::controller(LoginController::class)->group(function () {
//     Route::get('/', 'login')->name('login.index');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    return redirect('login');
})->middleware('guest');
Route::get('/', function () {
    return redirect('dashboard');
})->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    // ---------------------DASHBOARDCONTROLLER----------------------------

    Route::controller(DashboardController::class)->group(function () {
        // Mainmenu
        Route::get('/dashboard', 'index')->name('dashboard.index');
        // Data Essentials
        Route::get('/data_essentials', 'data_essentials')->name('data_essentials.index');
        // Data Assets
        Route::get('/data_assets', 'data_assets')->name('data_assets.index');
        // Data Relasi
        Route::get('/data_relasi', 'data_relasi')->name('data_relasi.index');
        // Landing Page
        Route::get('/landingpage', 'landingpage')->name('landingpage');


    });
    // ---------------------MASTERDATA ESSENTIALS----------------------------

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('user.index');
        Route::get('/users-create', 'create')->name('user.create');
        Route::post('/users', 'store')->name('user.store');
        Route::get('/users-show/{uuid}', 'show')->name('user.show');
        Route::put('/users/{id}', 'update')->name('user.update');
        Route::put('/user/photo-profil', 'update_photo')->name('user.update_photo');
        Route::put('/user/photo-ttd', 'update_ttd')->name('user.update_ttd');
        Route::delete('/users', 'destroy')->name('user.destroy');
        Route::get('/user-profile', 'user_profile')->name('user.profile');
        Route::get('/user-profile/ubah-password', 'edit_password')->name('user.profile.edit.password');
        Route::put('/user-profile/ubah-password', 'update_password')->name('user.profile.update.password');
    });

    Route::controller(ProvinsiController::class)->group(function () {
        Route::get('/provinsi', 'index')->name('provinsi.index');
        Route::get('/provinsi-create', 'create')->name('provinsi.create');
        Route::post('/provinsi-store', 'store')->name('provinsi.store');
        Route::get('/provinsi-show/{uuid}', 'show')->name('provinsi.show');
        Route::put('/provinsi-update/{uuid}', 'update')->name('provinsi.update');
        Route::delete('/provinsi-delete', 'destroy')->name('provinsi.destroy');
    });

    Route::controller(WalikotaController::class)->group(function () {
        Route::get('/kota', 'index')->name('walikota.index');
        Route::get('/kota-create', 'create')->name('walikota.create');
        Route::post('/kota', 'store')->name('walikota.store');
        Route::get('/kota-show/{uuid}', 'show')->name('walikota.show');
        Route::put('/kota/{uuid}', 'update')->name('walikota.update');
        Route::delete('/kota', 'destroy')->name('walikota.destroy');
    });

    Route::controller(KecamatanController::class)->group(function () {
        Route::get('/kecamatan', 'index')->name('kecamatan.index');
        Route::get('/kecamatan-create', 'create')->name('kecamatan.create');
        Route::post('/kecamatan', 'store')->name('kecamatan.store');
        Route::get('/kecamatan-show/{uuid}', 'show')->name('kecamatan.show');
        Route::put('/kecamatan/{uuid}', 'update')->name('kecamatan.update');
        Route::delete('/kecamatan', 'destroy')->name('kecamatan.destroy');
    });

    Route::controller(KelurahanController::class)->group(function () {
        Route::get('/kelurahan', 'index')->name('kelurahan.index');
        Route::get('/kelurahan-create', 'create')->name('kelurahan.create');
        Route::post('/kelurahan', 'store')->name('kelurahan.store');
        Route::get('/kelurahan-show/{uuid}', 'show')->name('kelurahan.show');
        Route::put('/kelurahan/{uuid}', 'update')->name('kelurahan.update');
        Route::delete('/kelurahan', 'destroy')->name('kelurahan.destroy');
    });

    Route::controller(UnitKerjaController::class)->group(function () {
        Route::get('/unit-kerja', 'index')->name('unitkerja.index');
        Route::get('/unit-kerja-create', 'create')->name('unitkerja.create');
        Route::post('/unit-kerja', 'store')->name('unitkerja.store');
        Route::get('/unit-kerja-show/{uuid}', 'show')->name('unitkerja.show');
        Route::put('/unit-kerja/{uuid}', 'update')->name('unitkerja.update');
        Route::delete('/unit-kerja', 'destroy')->name('unitkerja.destroy');
    });

    Route::controller(SeksiController::class)->group(function () {
        Route::get('/seksi', 'index')->name('seksi.index');
        Route::get('/seksi-create', 'create')->name('seksi.create');
        Route::post('/seksi', 'store')->name('seksi.store');
        Route::get('/seksi-show/{uuid}', 'show')->name('seksi.show');
        Route::put('/seksi/{uuid}', 'update')->name('seksi.update');
        Route::delete('/seksi', 'destroy')->name('seksi.destroy');
    });

    Route::controller(TimController::class)->group(function () {
        Route::get('/tim', 'index')->name('tim.index');
        Route::get('/tim-create', 'create')->name('tim.create');
        Route::post('/tim', 'store')->name('tim.store');
        Route::get('/tim-show/{uuid}', 'show')->name('tim.show');
        Route::put('/tim/{uuid}', 'update')->name('tim.update');
        Route::delete('/tim', 'destroy')->name('tim.destroy');
    });

    Route::controller(PulauController::class)->group(function () {
        Route::get('/pulau', 'index')->name('pulau.index');
        Route::get('/pulau-create', 'create')->name('pulau.create');
        Route::post('/pulau', 'store')->name('pulau.store');
        Route::get('/pulau-show/{uuid}', 'show')->name('pulau.show');
        Route::put('/pulau/{uuid}', 'update')->name('pulau.update');
        Route::delete('/pulau', 'destroy')->name('pulau.destroy');
    });

    Route::controller(JabatanController::class)->group(function () {
        Route::get('/jabatan', 'index')->name('jabatan.index');
        Route::get('/jabatan-create', 'create')->name('jabatan.create');
        Route::post('/jabatan', 'store')->name('jabatan.store');
        Route::get('/jabatan-show/{uuid}', 'show')->name('jabatan.show');
        Route::put('/jabatan/{uuid}', 'update')->name('jabatan.update');
        Route::delete('/jabatan', 'destroy')->name('jabatan.destroy');
    });

    Route::controller(EmployeeTypeController::class)->group(function () {
        Route::get('/employee-type', 'index')->name('employee-type.index');
        Route::get('/employee-type-create', 'create')->name('employee-type.create');
        Route::post('/employee-type', 'store')->name('employee-type.store');
        Route::get('/employee-type-show/{uuid}', 'show')->name('employee-type.show');
        Route::put('/employee-type/{uuid}', 'update')->name('employee-type.update');
        Route::delete('/employee-type', 'destroy')->name('employee-type.destroy');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('/role', 'index')->name('role.index');
        Route::get('/role-create', 'create')->name('role.create');
        Route::post('/role', 'store')->name('role.store');
        Route::get('/role-show/{uuid}', 'show')->name('role.show');
        Route::put('/role/{uuid}', 'update')->name('role.update');
        Route::delete('/role', 'destroy')->name('role.destroy');
    });

    Route::controller(KategoriController::class)->group(function () {
        Route::get('/kategori', 'index')->name('kategori.index');
        Route::get('/kategori-create', 'create')->name('kategori.create');
        Route::post('/kategori', 'store')->name('kategori.store');
        Route::get('/kategori/{uuid}/edit', 'edit')->name('kategori.edit');
        Route::put('/kategori/{uuid}', 'update')->name('kategori.update');
        Route::delete('/kategori', 'destroy')->name('kategori.destroy');
    });

    Route::controller(JenisCutiController::class)->group(function () {
        Route::get('/jenis-cuti', 'index')->name('jenis_cuti.index');
        Route::get('/jenis-cuti-create', 'create')->name('jenis_cuti.create');
        Route::post('/jenis-cuti', 'store')->name('jenis_cuti.store');
        Route::get('/jenis-cuti/{uuid}/edit', 'edit')->name('jenis_cuti.edit');
        Route::put('/jenis-cuti/{uuid}', 'update')->name('jenis_cuti.update');
        Route::delete('/jenis-cuti', 'destroy')->name('jenis_cuti.destroy');
    });

    Route::controller(JenisAbsensiController::class)->group(function () {
        Route::get('/jenis-absensi', 'index')->name('jenis_absensi.index');
        Route::get('/jenis-absensi-create', 'create')->name('jenis_absensi.create');
        Route::post('/jenis-absensi', 'store')->name('jenis_absensi.store');
        Route::get('/jenis-absensi/{uuid}/edit', 'edit')->name('jenis_absensi.edit');
        Route::put('/jenis-absensi/{uuid}', 'update')->name('jenis_absensi.update');
        Route::delete('/jenis-absensi', 'destroy')->name('jenis_absensi.destroy');
    });

    Route::controller(KontrakController::class)->group(function () {
        Route::get('/kontrak', 'index')->name('kontrak.index');
        Route::get('/kontrak-create', 'create')->name('kontrak.create');
        Route::post('/kontrak', 'store')->name('kontrak.store');
        Route::get('/kontrak/{uuid}/edit', 'edit')->name('kontrak.edit');
        Route::put('/kontrak/{uuid}/update', 'update')->name('kontrak.update');
        Route::delete('/kontrak', 'destroy')->name('kontrak.destroy');
    });

    Route::controller(GudangController::class)->group(function () {
        Route::get('/gudang', 'index')->name('gudang.index');
        Route::get('/gudang-create', 'create')->name('gudang.create');
        Route::post('/gudang', 'store')->name('gudang.store');
        Route::get('/gudang/{uuid}/edit', 'edit')->name('gudang.edit');
        Route::put('/gudang/{uuid}/update', 'update')->name('gudang.update');
        Route::delete('/gudang', 'destroy')->name('gudang.destroy');
    });

    // ---------------------MASTERDATA RELASI----------------------------

    Route::controller(RoleUserController::class)->group(function () {
        Route::get('/role-user', 'index')->name('role_user.index');
        Route::get('/role-user-create', 'create')->name('role_user.create');
        Route::post('/role-user', 'store')->name('role_user.store');
        Route::get('/role-user/{uuid}/edit', 'edit')->name('role_user.edit');
        Route::put('/role-user/{uuid}/update', 'update')->name('role_user.update');
        Route::delete('/role-user', 'destroy')->name('role_user.destroy');
    });

    Route::controller(AreaController::class)->group(function () {
        Route::get('/area', 'index')->name('area.index');
        Route::get('/area-create', 'create')->name('area.create');
        Route::post('/area', 'store')->name('area.store');
        Route::get('/area/{uuid}/edit', 'edit')->name('area.edit');
        Route::put('/area/{uuid}/update', 'update')->name('area.update');
        Route::delete('/area', 'destroy')->name('area.destroy');
    });

    Route::controller(StrukturController::class)->group(function () {
        Route::get('/struktur', 'index')->name('struktur.index');
        Route::get('/struktur-create', 'create')->name('struktur.create');
        Route::post('/struktur', 'store')->name('struktur.store');
        Route::get('/struktur/{uuid}/edit', 'edit')->name('struktur.edit');
        Route::put('/struktur/{uuid}/update', 'update')->name('struktur.update');
        Route::delete('/struktur', 'destroy')->name('struktur.destroy');
    });

    Route::controller(FormasiTimController::class)->group(function () {
        Route::get('/formasi-tim', 'index')->name('formasi_tim.index');
        Route::get('/formasi-tim-create', 'create')->name('formasi_tim.create');
        Route::post('/formasi-tim', 'store')->name('formasi_tim.store');
        Route::get('/formasi-tim/{uuid}/edit', 'edit')->name('formasi_tim.edit');
        Route::put('/formasi-tim/{uuid}/update', 'update')->name('formasi_tim.update');
        Route::delete('/formasi-tim', 'destroy')->name('formasi_tim.destroy');
    });

    Route::controller(KonfigurasiCutiController::class)->group(function () {
        Route::get('/konfigurasi-cuti', 'index')->name('konfigurasi_cuti.index');
        Route::get('/konfigurasi-cuti-create', 'create')->name('konfigurasi_cuti.create');
        Route::post('/konfigurasi-cuti', 'store')->name('konfigurasi_cuti.store');
        Route::get('/konfigurasi-cuti/{uuid}/edit', 'edit')->name('konfigurasi_cuti.edit');
        Route::put('/konfigurasi-cuti/{uuid}/update', 'update')->name('konfigurasi_cuti.update');
        Route::delete('/konfigurasi-cuti', 'destroy')->name('konfigurasi_cuti.destroy');
    });

    Route::controller(KonfigurasiAbsensiController::class)->group(function () {
        Route::get('/konfigurasi-absensi', 'index')->name('konfigurasi_absensi.index');
        Route::get('/konfigurasi-absensi-create', 'create')->name('konfigurasi_absensi.create');
        Route::post('/konfigurasi-absensi', 'store')->name('konfigurasi_absensi.store');
        Route::get('/konfigurasi-absensi/{uuid}/edit', 'edit')->name('konfigurasi_absensi.edit');
        Route::put('/konfigurasi-absensi/{uuid}/update', 'update')->name('konfigurasi_absensi.update');
        Route::delete('/konfigurasi-absensi', 'destroy')->name('konfigurasi_absensi.destroy');
    });

    // Route::controller(KonfigurasiGudangController::class)->group(function () {
    //     Route::get('/konfigurasi-gudang', 'index')->name('konfigurasi_gudang.index');
    //     Route::get('/konfigurasi-gudang-create', 'create')->name('konfigurasi_gudang.create');
    //     Route::post('/konfigurasi-gudang', 'store')->name('konfigurasi_gudang.store');
    //     Route::get('/konfigurasi-gudang/{uuid}/edit', 'edit')->name('konfigurasi_gudang.edit');
    //     Route::put('/konfigurasi-gudang/{uuid}/update', 'update')->name('konfigurasi_gudang.update');
    //     Route::delete('/konfigurasi-gudang', 'destroy')->name('konfigurasi_gudang.destroy');
    // });

    // KINERJA
    Route::controller(KinerjaController::class)->group(function () {
        Route::get('/kinerja', 'index')->name('kinerja.index');
        Route::get('/my-kinerja', 'my_index')->name('kinerja.saya');
        Route::get('/kinerja-create', 'create')->name('kinerja.create');
        Route::post('/kinerja', 'store')->name('kinerja.store');
        Route::get('/kinerja/{uuid}/edit', 'edit')->name('kinerja.edit');
        Route::put('/kinerja/{uuid}/update', 'update')->name('kinerja.update');
        Route::delete('/kinerja', 'destroy')->name('kinerja.destroy');

        Route::get('/kinerja/filter', 'filter')->name('kinerja.filter');
        Route::get('/kinerja/export-excel', 'excel')->name('kinerja.excel');

        Route::get('/formasi', 'formasi')->name('formasi.index');
    });

    // CUTI
    Route::controller(CutiController::class)->group(function () {
        Route::get('/cuti-setting', 'index')->name('cuti.index');
        Route::get('/cuti-create', 'create')->name('cuti.create');
        Route::post('/cuti', 'store')->name('cuti.store');
        Route::get('/cuti/{uuid}/edit', 'edit')->name('cuti.edit');
        Route::put('/cuti/{uuid}/update', 'update')->name('cuti.update');
        Route::delete('/cuti', 'destroy')->name('cuti.destroy');
        Route::put('/cuti/approve', 'approve')->name('cuti.approve');
        Route::put('/cuti/reject', 'reject')->name('cuti.reject');

        Route::get('/cuti-saya', 'cuti_saya')->name('cuti.saya');
        Route::get('/cuti-approval', 'approval_page')->name('cuti.approval_page');
        Route::get('/cuti/{uuid}/export-pdf', 'pdf')->name('cuti.pdf');
        Route::get('/cuti/export-excel', 'excel')->name('cuti.excel');

        Route::get('/cuti/filter', 'filter')->name('cuti.filter');
    });

    // ABSENSI
    Route::controller(AbsensiController::class)->group(function () {
        Route::get('/absensi', 'index')->name('absensi.index');
        Route::get('/my-absensi', 'my_index')->name('absensi.my_index');
        Route::get('/absensi-create', 'create')->name('absensi.create');
        Route::post('/absensi', 'store')->name('absensi.store');
        Route::get('/absensi/{uuid}/edit', 'edit')->name('absensi.edit');
        Route::put('/absensi/{uuid}/update', 'update')->name('absensi.update');
        Route::delete('/absensi', 'destroy')->name('absensi.destroy');
    });

    // Barang
    Route::controller(BarangController::class)->group(function () {
        Route::get('/barang-gudang-utama', 'index')->name('barang.index');
        Route::get('/barang-create', 'create')->name('barang.create');
        Route::get('/barang/filter', 'filter')->name('barang.filter');
        Route::post('/barang', 'store')->name('barang.store');
        Route::get('/barang/{uuid}/edit', 'edit')->name('barang.edit');
        Route::put('/barang/{uuid}/update', 'update')->name('barang.update');
        Route::delete('/barang', 'destroy')->name('barang.destroy');

        Route::get('/penerimaan', 'penerimaan')->name('barang.penerimaan');
    });

    // Pengiriman Barang
    Route::controller(PengirimanBarangController::class)->group(function () {
        Route::get('/pengiriman', 'index')->name('pengiriman.index');
        Route::get('/pengiriman/{no_resi}/detail', 'show')->name('pengiriman.show');

        Route::get('/pengiriman-barang/create', 'create')->name('barang.kirim.create');
        Route::post('/pengiriman-barang/store', 'store')->name('barang.kirim.store');
        Route::put('/pengiriman-barang/terima', 'terima')->name('pengiriman.barang.terima');
        Route::put('/pengiriman-barang/{id}/photo-terima', 'photoTerima')->name('pengiriman.barang.photo.terima');
        Route::get('/pengiriman-barang/generate-BAST', 'generateBAST')->name('pengiriman.barang.generate.BAST');
    });

    // Barang Pulau
    Route::controller(BarangPulauController::class)->group(function () {
        Route::get('/barang-pulau', 'index')->name('barang.pulau.index');
    });

    // Transaksi Barang Pulau
    Route::controller(TransaksiBarangPulauController::class)->group(function () {
        Route::get('/transaksi-barang', 'index')->name('transaksi.barang.index');
        Route::get('/transaksi-barang/create', 'create')->name('transaksi.barang.create');
        Route::post('/transaksi-barang', 'store')->name('transaksi.barang.store');
        Route::get('/transaksi-barang/export-excel', 'excel')->name('transaksi.barang.excel');
    });


    // ----------------------------------USER-----------------------------------

    // START SIMOJA-------------------------------------------------------------------
    Route::controller(SimojaDashboardController::class)->group(function () {
        Route::get('/simoja-kasi-index', 'kasi_index')->name('simoja.kasi.index')->middleware('KepalaSeksi');
        Route::get('/simoja-koordinator-index', 'koordinator_index')->name('simoja.koordinator.index')->middleware('Koordinator');
        Route::get('/simoja-pjlp-index', 'pjlp_index')->name('simoja.pjlp.index')->middleware('PJLP');
    });

    // ABSENSI
    Route::controller(SimojaAbsensiController::class)->group(function () {
        // KASI
        Route::middleware('KepalaSeksi')->group(function () {
            Route::get('/simoja-kasi-absensi', 'index_kasi')->name('simoja.kasi.absensi');
            Route::get('/simoja-kasi-absensi/filter', 'filter_kasi')->name('simoja.kasi.absensi.filter');
            Route::get('/simoja-kasi-absensi/export/excel', 'export_excel_kasi')->name('simoja.kasi.absensi.export.excel');
            Route::get('/simoja-kasi-absensi/export/pdf', 'export_pdf_kasi')->name('simoja.kasi.absensi.export.pdf');
            Route::get('/simoja-kasi-absensi/ringkasan', 'ringkasan_kasi')->name('simoja.kasi.absensi.ringkasan');
        });

        // KOORDINATOR
        Route::middleware('Koordinator')->group(function () {
            Route::get('/simoja-koordinator-absensi', 'my_index_koordinator')->name('simoja.koordinator.my-absensi');
            Route::get('/simoja-koordinator-absensi-create', 'create_koordinator')->name('simoja.koordinator.absensi-create');
            Route::post('/simoja-koordinator-absensi', 'store_koordinator')->name('simoja.koordinator.absensi.store');
            Route::get('/simoja-koordinator-absensi-tim', 'tim_index_koordinator')->name('simoja.koordinator.absensi.tim');
        });

        // PJLP
        Route::middleware('PJLP')->group(function () {
            Route::get('/simoja-pjlp-absensi', 'my_index_pjlp')->name('simoja.pjlp.my-absensi');
            Route::get('/simoja-pjlp-absensi-create', 'create_pjlp')->name('simoja.pjlp.absensi-create');
            Route::post('/simoja-pjlp-absensi', 'store_pjlp')->name('simoja.pjlp.absensi.store');
            Route::get('/simoja-pjlp-absensi/filter', 'filter_pjlp')->name('simoja.pjlp.absensi.filter');
        });

    });

    // KINERJA
    Route::controller(SimojaKinerjaController::class)->group(function () {
        // KASI
        Route::middleware('KepalaSeksi')->group(function () {
            Route::get('/simoja-kasi-kinerja', 'index')->name('simoja.kasi.kinerja');
            Route::get('/simoja-kasi-kinerja/filter', 'filter_kasi')->name('simoja.kasi.kinerja.filter');
            Route::get('/simoja-kasi-kinerja/export/excel', 'export_excel_kasi')->name('simoja.kasi.kinerja.export.excel');
            Route::get('/simoja-kasi-kinerja/export/pdf', 'export_pdf_kasi')->name('simoja.kasi.kinerja.export.pdf');
        });

        // KOORDINATOR
        Route::middleware('Koordinator')->group(function () {
            Route::get('/simoja-koordinator-kinerja', 'my_index_koordinator')->name('simoja.koordinator.my-kinerja');
            Route::get('/simoja-koordinator-kinerja-create', 'create_koordinator')->name('simoja.koordinator.kinerja-create');
            Route::post('/simoja-koordinator-kinerja', 'store_koordinator')->name('simoja.kinerja.koordinator.store');
            Route::get('/simoja-koordinator-kinerja-tim', 'tim_index_koordinator')->name('simoja.koordinator.kinerja.tim');
        });

        // PJLP
        Route::middleware('PJLP')->group(function () {
            Route::get('/simoja-pjlp-kinerja', 'my_index_pjlp')->name('simoja.pjlp.my-kinerja');
            Route::get('/simoja-pjlp-kinerja-create', 'create_pjlp')->name('simoja.pjlp.kinerja-create');
            Route::post('/simoja-pjlp-kinerja', 'store_pjlp')->name('simoja.kinerja.pjlp.store');
            Route::get('/simoja-pjlp-kinerja/filter', 'filter_pjlp')->name('simoja.kinerja.pjlp.filter');
        });

    });

    // CUTI
    Route::controller(SimojaCutiController::class)->group(function () {
        // KASI
        Route::middleware('KepalaSeksi')->group(function () {
            Route::get('/simoja-kasi-cuti', 'index')->name('simoja.kasi.cuti');
            Route::get('/simoja-kasi-cuti/approval', 'approval')->name('simoja.kasi.cuti.approval');
            Route::put('/simoja-kasi-cuti/approve', 'approve')->name('simoja.kasi.cuti.approve');
            Route::put('/simoja-kasi-cuti/reject', 'reject')->name('simoja.kasi.cuti.reject');

            Route::get('/simoja-kasi-cuti/filter', 'filter_kasi')->name('simoja.kasi.cuti.filter');
            Route::get('/simoja-kasi-cuti/export/excel', 'export_excel_kasi')->name('simoja.kasi.cuti.export.excel');
        });

        // KOORDINATOR
        Route::middleware('Koordinator')->group(function () {
            Route::get('/simoja-koordinator-cuti', 'my_index_koordinator')->name('simoja.koordinator.my-cuti');
            Route::get('/simoja-koordinator-cuti-create', 'create_koordinator')->name('simoja.koordinator.cuti-create');
            Route::post('/simoja-koordinator-cuti', 'store_koordinator')->name('simoja.cuti.koordinator.store');
            Route::delete('/simoja-koordinator-cuti', 'destroy_koordinator')->name('simoja.cuti.koordinator.destroy');
            Route::get('/simoja-koordinator-cuti-tim', 'tim_index_koordinator')->name('simoja.koordinator.cuti.tim');
        });

        // PJLP
        Route::middleware('PJLP')->group(function () {
            Route::get('/simoja-pjlp-cuti', 'my_index_pjlp')->name('simoja.pjlp.my-cuti');
            Route::get('/simoja-pjlp-cuti-create', 'create_pjlp')->name('simoja.pjlp.cuti-create');
            Route::post('/simoja-pjlp-cuti', 'store_pjlp')->name('simoja.cuti.pjlp.store');
            Route::delete('/simoja-pjlp-cuti', 'destroy_pjlp')->name('simoja.cuti.pjlp.destroy');

            Route::get('/simoja-pjlp-cuti/filter', 'filter_pjlp')->name('simoja.pjlp.cuti.filter');
        });

    });
    // END SIMOJA ----------------------------------------------------------------------



    // ASET
    Route::controller(AsetDashboardController::class)->group(function () {
        Route::get('/aset-kasi-index', 'kasi_index')->name('aset.kasi.index');
        Route::get('/aset-koordinator-index', 'koordinator_index')->name('aset.koordinator.index');
        Route::get('/aset-pjlp-index', 'pjlp_index')->name('aset.pjlp.index');
    });

    Route::controller(AsetKontrakController::class)->group(function () {
        // KASI
        Route::middleware('KepalaSeksi')->group(function () {
            Route::get('/aset-kasi-kontrak', 'index')->name('aset.kasi.kontrak-index');
            Route::get('/aset-kasi-create', 'create')->name('aset.kasi.kontrak-create');
            Route::post('/aset-kasi-store', 'store')->name('aset.kasi.kontrak-store');
            Route::get('/aset-kasi-kontrak/{uuid}/edit', 'edit')->name('aset.kasi.kontrak-edit');
            Route::get('/aset-kontrak/filter', 'filter')->name('aset.kasi.kontrak.filter');
            Route::put('/aset-kasi-kontrak/{uuid}/update', 'update')->name('aset.kasi.kontrak-update');
            Route::delete('/aset-kasi-kontrak', 'destroy')->name('aset.kasi.kontrak-delete');
        });
    });

    Route::controller(GudangBarangController::class)->group(function () {
        // KASI
        Route::middleware('KepalaSeksi')->group(function () {
            Route::get('/aset-gudang-utama', 'kasi_index')->name('aset.gudang-utama');
            Route::get('/aset-gudang-utama-create', 'kasi_create')->name('aset.gudang-utama.create');
            Route::post('/aset-gudang-utama-store', 'kasi_store')->name('aset.gudang-utama.store');
            Route::get('/aset-gudang-utama/{uuid}/edit', 'kasi_edit')->name('aset.gudang-utama.edit');
            Route::put('/aset-gudang-utama/{uuid}/update', 'kasi_update')->name('aset.gudang-utama.update');
            Route::get('/aset-gudang-pulau', 'kasi_gudang_pulau')->name('aset.gudang-pulau');
            Route::get('/aset-gudang-pulau/filter', 'kasi_gudang_pulau_filter')->name('aset.gudang-pulau.filter');
            Route::get('/aset-gudang-pulau-trans', 'kasi_gudang_pulau_trans')->name('aset.gudang-pulau-trans');
        });

        // KOORDINATOR
        Route::middleware('Koordinator')->group(function () {
            Route::get('/aset-koordinator-my-gudang', 'koordinator_index')->name('aset.koordinator.my-gudang');
            Route::get('/aset-koordinator-create-transaction', 'koordinator_form_pemakaian')->name('aset.koordinator.form-pemakaian');
            Route::post('/aset-koordinator-transaction', 'koordinator_store')->name('aset.koordinator.transaksi.store');
            Route::get('/aset-koordinator-my-transaction', 'koordinator_histori_transaksi')->name('aset.koordinator.my-transaction');
            Route::get('/aset-koordinator-tim-transaction', 'koordinator_histori_transaksi_tim')->name('aset.koordinator.tim-transaction');
        });

        // PJLP
        Route::middleware('PJLP')->group(function () {
            Route::get('/aset-pjlp-my-gudang', 'pjlp_index')->name('aset.pjlp.my-gudang');
            Route::get('/aset-pjlp-create-transaction', 'pjlp_form_pemakaian')->name('aset.pjlp.form-pemakaian');
            Route::post('/aset-pjlp-transaction', 'pjlp_store')->name('aset.pjlp.transaksi.store');
            Route::get('/aset-pjlp-my-transaction', 'pjlp_histori_transaksi')->name('aset.pjlp.my-transaction');
            Route::get('/aset-pjlp-my-transaction/filter', 'pjlp_histori_transaksi_filter')->name('aset.pjlp.my-transaction.filter');
        });
    });

    Route::controller(ShippingController::class)->group(function () {
        // KASI
        Route::middleware('KepalaSeksi')->group(function () {
            Route::get('/aset-pengiriman', 'index_pengiriman')->name('aset.pengiriman.index');
            Route::get('/aset-pengiriman-create', 'create_pengiriman')->name('aset.pengiriman.create');
            Route::post('/aset-pengiriman', 'store_pengiriman')->name('aset.pengiriman.store');
            Route::get('/aset-pengiriman/{no_resi}/detail', 'show_pengiriman')->name('aset.pengiriman.show');
            Route::get('/aset-pengiriman/filter', 'filter_pengiriman')->name('aset.pengiriman.filter');
        });

        // KOORDINATOR
        Route::middleware('Koordinator')->group(function () {
            Route::get('/aset-penerimaan', 'index_penerimaan')->name('aset.penerimaan.index');
            Route::put('/aset-penerimaan/terima', 'terima_barang')->name('aset.penerimaan.terima');
            Route::get('/aset-penerimaan/generate-BAST', 'generateBAST')->name('aset.penerimaan.BAST');
            Route::get('/aset-penerimaan/{no_resi}/detail', 'show_penerimaan')->name('aset.penerimaan.show');
        });
    });
});
