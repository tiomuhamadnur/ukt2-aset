<?php

namespace App\Http\Controllers\data_essentials;

use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\RoleUser;
use App\Models\Struktur;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use App\Models\KonfigurasiCuti;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $employee_type_id = $request->employee_type_id;
        switch($employee_type_id){
            case 1:
                $users = User::where('employee_type_id', 1)->get();
                break;
            case 2:
                $users = User::where('employee_type_id', 2)->get();
                break;
            case 3:
                $users = User::where('employee_type_id', 3)->get();
                break;
            default:
                $users = User::all();
                break;
        }

        return view('admin.masterdata.data_essentials.user.index', compact([
            'users',
        ]));
    }

    public function create()
    {
        $role = Role::all();
        $jabatan = Jabatan::all();
        $employee_type = EmployeeType::all();
        $struktur = Struktur::all();
        $area = Area::all();
        return view('admin.masterdata.data_essentials.user.create', compact([
            'role',
            'jabatan',
            'employee_type',
            'struktur',
            'area',
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role_id' => 'required',
            'phone' => 'required|numeric',
        ]);

        $password = $password = Hash::make('user123');

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $password,
            "nip" => $request->nip,
            "phone" => $request->phone,
            "gender" => $request->gender,
            "tempat_lahir" => $request->tempat_lahir,
            "tanggal_lahir" => $request->tanggal_lahir,
            "alamat" => $request->alamat,
            "role_id" => $request->role_id,
            "jabatan_id" => $request->jabatan_id,
            "employee_type_id" => $request->employee_type_id,
            "area_id" => $request->area_id,
            "struktur_id" => $request->struktur_id,
        ]);

        RoleUser::create([
            'user_id' => $user->id,
            'role_id' => $user->role_id,
        ]);

        return redirect()->route('user.index')->withNotify('Data berhasil ditambah!');
    }

    public function show(string $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        if(!$user)
        {
            return back()->withNotifyerror('Data tidak ditemukan');
        }
        $role = Role::all();
        $jabatan = Jabatan::all();
        $employee_type = EmployeeType::all();
        $struktur = Struktur::all();
        $area = Area::all();
        return view('admin.masterdata.data_essentials.user.edit', compact([
            'user',
            'role',
            'jabatan',
            'employee_type',
            'struktur',
            'area',
        ]));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role_id' => 'required',
            'phone' => 'required|numeric',
        ]);

        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "nip" => $request->nip,
            "phone" => $request->phone,
            "gender" => $request->gender,
            "tempat_lahir" => $request->tempat_lahir,
            "tanggal_lahir" => $request->tanggal_lahir,
            "alamat" => $request->alamat,
            "role_id" => $request->role_id,
            "jabatan_id" => $request->jabatan_id,
            "employee_type_id" => $request->employee_type_id,
            "area_id" => $request->area_id,
            "struktur_id" => $request->struktur_id,
        ]);
        return redirect()->route('user.index')->withNotify('Data berhasil diperbaharui!');
    }

    public function destroy(Request $request)
    {
        return back()->withError('Data tidak bisa dihapus');
    }

    public function user_profile()
    {
        $jumlah_cuti = KonfigurasiCuti::where('user_id', auth()->id())->first();

        if ($jumlah_cuti) {
            $sisa_cuti = $jumlah_cuti->jumlah;
        } else {
            $sisa_cuti = 0;
        }
        return view('pages.profile.index', compact(['sisa_cuti']));
    }

    public function edit_password()
    {
        return view('pages.profile.update_password');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required',
        ]);

        $user_id = auth()->user()->id;

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $confirm_new_password = $request->confirm_new_password;

        if($new_password != $confirm_new_password) {
            return back()->withError('Password Baru & Konfirmasi Password Baru tidak sesuai!');
        } else {
            $user = User::findOrFail($user_id);
            $cek = Hash::check($old_password, $user->password);
            if(!$cek){
                return back()->withError('Password Lama yang anda masukan tidak sesuai!');
            }

            $user->update([
                'password' => Hash::make($new_password),
            ]);

            Auth::logout();
            return redirect()->route('dashboard.index');
        }
    }

    public function update_photo(Request $request)
    {
        $request->validate([
            'photo' =>'required',
        ]);

        $user = User::findOrFail(auth()->user()->id);
        if($user->photo != null)
        {
            Storage::delete($user->photo);
        }

        if ($request->hasFile('photo') && $request->photo != '') {
            $image = Image::make($request->file('photo'));

            $imageName = time().'-'.$request->file('photo')->getClientOriginalName();
            $detailPath = 'user/profil/';
            $destinationPath = public_path('storage/'. $detailPath);

            $image->resize(null, 100, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save($destinationPath.$imageName);

            $user->photo = $detailPath.$imageName;
            $user->save();
        }

        return redirect()->route('user.profile')->withNotify('Data berhasil diubah!');
    }

    public function update_ttd(Request $request)
    {
        $request->validate([
            'photo' =>'required',
        ]);

        $user = User::findOrFail(auth()->user()->id);
        if($user->ttd != null)
        {
            Storage::delete($user->photo);
        }

        if ($request->hasFile('photo') && $request->photo != '') {
            $image = Image::make($request->file('photo'));

            $imageName = time().'-'.$request->file('photo')->getClientOriginalName();
            $detailPath = 'user/ttd/';
            $destinationPath = public_path('storage/'. $detailPath);

            $image->resize(null, 90, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save($destinationPath.$imageName);

            $user->ttd = $detailPath.$imageName;
            $user->save();
        }

        return redirect()->route('user.profile')->withNotify('Data berhasil diubah!');
    }
}
