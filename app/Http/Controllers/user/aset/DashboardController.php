<?php

namespace App\Http\Controllers\user\aset;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function kasi_index()
    {
        $today = Carbon::now();
        $tanggal = Carbon::parse($today)->isoFormat('dddd, D MMMM Y');

        return view('user.aset.kasi.index', compact(['tanggal']));
    }

    public function koordinator_index()
    {
        $today = Carbon::now();
        $tanggal = Carbon::parse($today)->isoFormat('dddd, D MMMM Y');

        return view('user.aset.koordinator.index', compact(['tanggal']));
    }

    public function pjlp_index()
    {
        $today = Carbon::now();
        $tanggal = Carbon::parse($today)->isoFormat('dddd, D MMMM Y');

        return view('user.aset.pjlp.index', compact(['tanggal']));
    }
}
