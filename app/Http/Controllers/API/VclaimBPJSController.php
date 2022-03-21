<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VclaimBPJSController extends Controller
{
    public $baseUrl = 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/';

    public static function signature()
    {
        $cons_id =  env('CONS_ID');
        $secretKey = env('SECRET_KEY');
        $userkey = env('USER_KEY');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $cons_id . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);

        $response = array(
            'user_key' => $userkey,
            'x-cons-id' => $cons_id,
            'x-timestamp' => $tStamp,
            'x-signature' => $encodedSignature,
            'decrypt_key' => $cons_id . $secretKey . $tStamp,
        );
        return $response;
    }
    public static function stringDecrypt($key, $string)
    {
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        $output = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
        return $output;
    }
    public function get_peserta_nik($nik, $tanggal)
    {
        $url = $this->baseUrl . "Peserta/nik/" . $nik . "/tglSEP/" . $tanggal;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function get_peserta_kartu($kartu, $tanggal)
    {
        $url = $this->baseUrl . "Peserta/nokartu/" . $kartu . "/tglSEP/" . $tanggal;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function get_peserta_nik_V1($ktp)
    {
        $url = "http://192.168.2.46/ws/getpesertaktp";
        $response = Http::asForm()->post($url, [
            'ktp' => $ktp
        ]);
        $response = json_decode($response->getBody());
        return $response;
    }
    public function ref_diagnosa(Request $request)
    {
        $url = $this->baseUrl . "referensi/diagnosa/" . $request->kode;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function ref_poliklinik(Request  $request)
    {
        $url = "https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev/ref/poli/";
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        dd($response);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function ref_poli(Request  $request)
    {
        $url = $this->baseUrl . "referensi/poli/" . $request->search;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function ref_faskes(Request  $request)
    {
        $url = $this->baseUrl . "referensi/faskes/" . $request->nama . "/" . $request->jenis;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function ref_provinsi()
    {
        $url = $this->baseUrl . "/referensi/propinsi";
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function ref_kabupaten(Request $request)
    {
        $url = $this->baseUrl . "referensi/kabupaten/propinsi/" . $request->provinsi;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function ref_kecamatan(Request $request)
    {
        $url = $this->baseUrl . "referensi/kecamatan/kabupaten/" . $request->kabupaten;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function ref_dokter_dpjp(Request $request)
    {
        $url = $this->baseUrl . "referensi/dokter/pelayanan/" . $request->jnsPelayanan . "/tglPelayanan/" . $request->tglPelayanan  . "/Spesialis/" . $request->kodePoli;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function cari_rujukan($nomor_rujukan)
    {
        $url = $this->baseUrl . "Rujukan/" . $nomor_rujukan;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function histori_pelayanan_peserta($noKartu, $tglMulai, $tglAkhir)
    {
        $url = $this->baseUrl . "monitoring/HistoriPelayanan/NoKartu/" . $noKartu . "/tglMulai/" . $tglMulai . "/tglAkhir/" . $tglAkhir . "";
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function histori_rujukan_peserta($noKartu)
    {
        $url = $this->baseUrl . "Rujukan/RS/List/Peserta/" . $noKartu;
        $signature = $this->signature();
        $response = Http::withHeaders($signature)->get($url);
        $response = json_decode($response);
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function sep_insert($data)
    {
        $url = $this->baseUrl . "SEP/2.0/insert";
        $signature = $this->signature();
        $client = new Client();
        $response = $client->request('POST', $url, [
            'body' => json_encode($data),
            'headers' => $signature
        ]);
        $response = json_decode($response->getBody());
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function rencana_kontrol_insert($data)
    {
        $url = $this->baseUrl . "RencanaKontrol/InsertSPRI";
        $signature = $this->signature();
        $client = new Client();
        $response = $client->request('POST', $url, [
            'body' => json_encode($data),
            'headers' => $signature
        ]);
        $response = json_decode($response->getBody());
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
    public function spri_insert($data)
    {
        $url = $this->baseUrl . "RencanaKontrol/InsertSPRI";
        dd($url);
        $signature = $this->signature();
        $client = new Client();
        $response = $client->request('POST', $url, [
            'body' => json_encode($data),
            'headers' => $signature
        ]);
        $response = json_decode($response->getBody());
        if ($response->metaData->code == 200) {
            $decrypt = $this->stringDecrypt($signature['decrypt_key'], $response->response);
            $response->response = json_decode($decrypt);
        }
        return $response;
    }
}

