<?php

namespace App\Http\Controllers;

use App\Http\Controllers\data_essentials\MasterGudangController;
use App\Models\Area;
use App\Models\EmployeeType;
use App\Models\FormasiTim;
use App\Models\Gudang;
use App\Models\Jabatan;
use App\Models\JenisAbsensi;
use App\Models\JenisCuti;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\KonfigurasiAbsensi;
use App\Models\KonfigurasiCuti;
use App\Models\KonfigurasiGudang;
use App\Models\Kontrak;
use App\Models\KontrakBarang;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\UnitKerja;
use App\Models\Walikota;
use App\Models\Seksi;
use App\Models\Pulau;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Struktur;
use App\Models\Tim;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $jabatan_id = auth()->user()->jabatan->id;

        if($jabatan_id == 1) {
            return redirect()->route('aset.kasi.index');
        } elseif($jabatan_id == 2) {
            return redirect()->route('aset.kasi.index');
        } elseif($jabatan_id == 3) {
            return redirect()->route('aset.koordinator.index');
        } elseif($jabatan_id == 4) {
            return redirect()->route('aset.koordinator.index');
        } elseif($jabatan_id == 5) {
            return redirect()->route('aset.pjlp.index');
        }
        else {
            return view('superadmin.dashboard.index');
        }
    }

    public function data_essentials()
    {
        $provinsi = Provinsi::count();
        $walikota = Walikota::count();
        $unitkerja = UnitKerja::count();
        $seksi = Seksi::count();
        $kelurahan = Kelurahan::count();
        $kecamatan = Kecamatan::count();
        $pulau = Pulau::count();
        $role = Role::count();
        $users = User::count();
        $jabatan = Jabatan::count();
        $employee_type = EmployeeType::count();
        $tim = Tim::count();
        $kategori = Kategori::count();
        $jenis_cuti = JenisCuti::count();
        $jenis_absensi = JenisAbsensi::count();
        $kontrak = Kontrak::count();
        $gudang = Gudang::count();
        return view('admin.masterdata.data_essentials.index', compact([
            'provinsi',
            'walikota',
            'unitkerja',
            'seksi',
            'kelurahan',
            'kecamatan',
            'pulau',
            'role',
            'jabatan',
            'employee_type',
            'tim',
            'kategori',
            'jenis_cuti',
            'jenis_absensi',
            'users',
            'kontrak',
            'gudang',
        ]));
    }

    public function data_assets()
    {
        return view('admin.masterdata.data_assets.index');
    }

    public function data_relasi()
    {
        $role_user = RoleUser::count();
        $area = Area::count();
        $struktur = Struktur::count();
        $formasi_tim = FormasiTim::count();
        $konfigurasi_cuti = KonfigurasiCuti::count();
        $konfigurasi_absensi = KonfigurasiAbsensi::count();
        return view('admin.masterdata.data_relasi.index', compact([
            'role_user',
            'area',
            'struktur',
            'formasi_tim',
            'konfigurasi_cuti',
            'konfigurasi_absensi',
        ]));
    }

    public function pulau()
    {
        return view('admin.masterdata.data_assets.pulau.index');
    }

    public function aset_kasie()
    {
        return view('pages.home.aset_kasie');
    }

    public function aset_koordinator()
    {
        return view('pages.home.aset_koordinator');
    }

    public function aset_pjlp()
    {
        return view('pages.home.aset_pjlp');
    }
    public function simoja_kasie()
    {
        return view('pages.home.simoja_kasie');
    }

    public function simoja_koordinator()
    {
        return view('pages.home.simoja_koordinator');
    }

    public function simoja_pjlp()
    {
        return view('pages.home.simoja_pjlp');
    }

    public function landingpage()
    {
        return view('landingpage.index');
    }
}
