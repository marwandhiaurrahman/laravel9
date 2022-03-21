<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;

class AntrianBPJSController extends BaseController
{
    // function WS RS
    public function token(Request $request)
    {
        if (Auth::attempt(['email' => $request->header('x-username'), 'password' => $request->header('x-password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.',  'Unauthorised.', 201);
        }
    }
    public function status_antrian(Request $request)
    {
    }
    public function ambil_antrian(Request $request)
    {
    }
    public function sisa_antrian(Request $request)
    {
    }
    public function batal_antrian(Request $request)
    {
    }
    public function checkin_antrian(Request $request)
    {
    }
    public function info_pasien_baru(Request $request)
    {
    }
    public function jadwal_operasi(Request $request)
    {
    }
    public function jadwal_operasi_pasien(Request $request)
    {
    }

    // function WS BPJS
    public function ref_poli(Request $request)
    {
    }
    public function ref_dokter(Request $request)
    {
    }
    public function ref_jadwal_dokter(Request $request)
    {
    }
    public function update_jadwal_dokter(Request $request)
    {
    }
    public function tambah_antrian(Request $request)
    {
    }
    public function update_waktu_antrian(Request $request)
    {
    }
    public function list_waktu_task(Request $request)
    {
    }
    public function dashboard_tanggal(Request $request)
    {
    }
    public function dashboard_bulan(Request $request)
    {
    }
}
