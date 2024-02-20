<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\UnitKerja;
use App\Models\Walikota;
use App\Models\Seksi;
use App\Models\Pulau;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
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
        return view('admin.masterdata.data_essentials.index', compact(['provinsi', 'walikota', 'unitkerja', 'seksi', 'kelurahan', 'kecamatan', 'pulau']));
    }

    public function data_assets()
    {
        return view('admin.masterdata.data_assets.index');
    }

    public function pulau()
    {
        return view('admin.masterdata.data_assets.pulau.index');
    }

}
