<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\PdfController;
use App\Livewire\Users\Inlistusers;
use App\Livewire\Master\PL\Index as PL;
use App\Livewire\Master\Cpl\Index as Cpl;
use App\Livewire\Master\Bk\Index as BK;
use App\Livewire\Master\Cpmk\Index as Cpmk;
use App\Livewire\Master\SubCpmk\Index as SubCpmk;
use App\Livewire\Master\Matakuliah\Index as Matkul;
use App\Livewire\Master\Dosen\Index as Dosen;
use App\Livewire\Master\ProgramStudi\Index as ProgramStudi;
use App\Livewire\Kurikulum\Index as Kurikulum;
use App\Livewire\Kurikulum\CreateUpdate as KurikulumCreateUpdate;
use App\Livewire\Kurikulum\MatriksData;
use App\Livewire\PerangkatAjar\Index as PerangkatAjar;
use App\Livewire\PerangkatAjar\KontrakKuliah\Index as KontrakKuliah;
use App\Livewire\PerangkatAjar\KontrakKuliah\CreateUpdate as KontrakKuliahCreateUpdate;
use App\Livewire\PerangkatAjar\KontrakKuliah\View as KontrakKuliahView;
use App\Livewire\PerangkatAjar\Rps\Index as Rps;
use App\Livewire\PerangkatAjar\Rps\Create as RpsCreate;
use App\Livewire\PerangkatAjar\Rps\Update as RpsUpdate;
use App\Livewire\PerangkatAjar\Rps\View as RpsView;
use App\Livewire\PerangkatAjar\RealisasiAjar\Index as RealisasiAjar;
use App\Livewire\PerangkatAjar\RealisasiAjar\Create as RealisasiAjarCreate;
use App\Livewire\PerangkatAjar\RealisasiAjar\Update as RealisasiAjarUpdate;
use App\Livewire\PerangkatAjar\RealisasiAjar\View as RealisasiAjarView;
use App\Livewire\PerangkatAjar\BebanAjar\Index as BebanAjar;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::view('master', 'pages.datamaster.profile-lulusan.list')->name('master.index');
    Route::get('user', Inlistusers::class)->name('user.index');
    // Route::view('users', 'pages.users.list')->name('user.index');

    Route::prefix('master')
        ->name('master.')
        ->group(function () {

            Route::get('profile-lulusan', PL::class)->name('pl.index');
            Route::get('cpl', Cpl::class)->name('cpl.index');
            Route::get('bk', BK::class)->name('bk.index');
            Route::get('cpmk', Cpmk::class)->name('cpmk.index');
            Route::get('subcpmk', SubCpmk::class)->name('subcpmk.index');
            Route::get('matakuliah', Matkul::class)->name('matkul.index');
            Route::get('dosen', Dosen::class)->name('dosen.index');
            Route::get('program-studi', ProgramStudi::class)->name('program-studi.index');

        });
    Route::prefix('kurikulum')
        ->name('kurikulum.')
        ->group(function () {
            Volt::route('/', Kurikulum::class)->name('index');
            Volt::route('create/{id?}', KurikulumCreateUpdate::class)->name('create');
            Volt::route('update/{id?}', KurikulumCreateUpdate::class)->name('update');
            Volt::route('matriks-data/{id}', MatriksData::class)->name('matriks-data');
        });

    Route::prefix('perangkat-ajar')
        ->name('perangkat-ajar.')
        ->group(function () {

            Volt::route('beban-ajar', BebanAjar::class)->name('beban-ajar.index');
            Volt::route('/', PerangkatAjar::class)->name('index');
            Volt::route('kontrak-kuliah', KontrakKuliah::class)->name('kontrak-kuliah.index');
            Volt::route('kontrak-kuliah/create/{id?}', KontrakKuliahCreateUpdate::class)->name('kontrak-kuliah.create');
            Volt::route('kontrak-kuliah/update/{id?}', KontrakKuliahCreateUpdate::class)->name('kontrak-kuliah.update');
            Volt::route('kontrak-kuliah/view/{id?}', KontrakKuliahView::class)->name('kontrak-kuliah.view');

            Volt::route('rps', Rps::class)->name('rps.index');
            Volt::route('rps/create/{id?}', RpsCreate::class)->name('rps.create');
            Volt::route('rps/update/{id?}', RpsUpdate::class)->name('rps.update');
            Volt::route('rps/view/{id?}', RpsView::class)->name('rps.view');

            Volt::route('realisasi-ajar', RealisasiAjar::class)->name('realisasi-ajar.index');
            Volt::route('realisasi-ajar/create/{id?}', RealisasiAjarCreate::class)->name('realisasi-ajar.create');
            Volt::route('realisasi-ajar/update/{id?}', RealisasiAjarUpdate::class)->name('realisasi-ajar.update');
            Volt::route('realisasi-ajar/view/{id?}', RealisasiAjarView::class)->name('realisasi-ajar.view');
        });

    Route::get('pdf/kontrak-kuliah/{id}', [PdfController::class, 'previewKontrakKuliah'])->name('pdf.preview.kontrak-kuliah');
    Route::get('pdf/realisasi-ajar/{id}', [PdfController::class, 'previewRealisasiAjar'])->name('pdf.preview.realisasi-ajar');
    Route::get('pdf/rps/{id}', [PdfController::class, 'previewRps'])->name('pdf.preview.rps');
    Route::get('pdf/kurikulum/{id}', [PdfController::class, 'previewKurikulum'])->name('pdf.preview.kurikulum');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
