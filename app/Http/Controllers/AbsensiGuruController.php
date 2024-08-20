<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\AbsensiGuru;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AbsensiGuruController extends Controller
{
    public function index()
    {
        return view('absensi_guru');
    }

    public function store(Request $request)
    {
        //cek data
        $guru = Guru::where([
            'nik' => $request->nik
        ])->first();

        if (!$guru) {
            Alert::error('Gagal!', 'Data tidak dikenali');
            return redirect('/absensi_guru');
        }
        $guru_id = $guru->id;
        $cek = AbsensiGuru::where([
            'guru_id' => $guru_id,
            'tanggal_absen_guru' => date('y-m-d')
        ])->first();

        if ($cek) {
            Alert::warning('Maaf!', 'Anda sudah melakukan absen');
            return redirect('/absensi_guru');
        }

        

        $tanggal_absen = date('y-m-d');
        $jam_masuk = date("H:i:s");

        $hari_absen = Carbon::parse($tanggal_absen)->format('l');
        $status = '';
        $keterangan = '';

        if ($hari_absen == 'Sunday' || $hari_absen == 'Saturday') {
            Alert::error('Gagal!', 'Hari ini Libur');
            return redirect('/absensi_guru');
        }

        if ($jam_masuk > '09:00:00') {
            Alert::error('Gagal!', 'Anda diluar jam absensi');
            return redirect('/absensi_guru');
        }

        if ($jam_masuk < '06:00:00') {
            Alert::error('Gagal!', 'Anda diluar jam absensi');
            return redirect('/absensi_guru');
        }


        if ($hari_absen == 'Monday') {
            if ($jam_masuk >= '06:00:00' && $jam_masuk <= '07:00:00' ) {
                $status = 'hadir';
                $keterangan = 'hadir';
            }else{
                $status = 'terlambat';
                $keterangan = 'hadir';
            };
        }

        if ($hari_absen == 'Tuesday' || $hari_absen == 'Wednesday' || $hari_absen == 'Thursday' || $hari_absen == 'Friday') {
            if ($jam_masuk >= '06:00:00' && $jam_masuk <= '07:15:00' ) {
                $status = 'ontime';
                // $keterangan = 'hadir';
            }else{
                $status = 'terlambat';
                // $keterangan = 'hadir';
            };
        }

        AbsensiGuru::create([
            'guru_id' => $guru_id,
            'tanggal_absen_guru' => date('y-m-d'),
            'jam_absen_datang_guru' => $jam_masuk,
            'jam_absen_pulang_guru' => '00:00:00',
            // 'status_ke' => '-',
            'keterangan_kehadiran_guru' => '-',
            'status_kehadiran_guru' => $status,
            // 'keterangan' => $keterangan
        ]);
        
        // $murid = Murid::whereNis($request->nis)->first();
        // $no_hp_murid = $murid->no_hp;
        Alert::success('Silahkan Masuk '.$guru->nama_guru.'!', 'Data kehadiran anda direkam');
       
        // $sid    = "AC2edc0c24fa1519aeedfed5d738b6a80d";
        // $token  = "c73ed36216358371923b2864d8768bd2";
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages
        //   ->create($no_hp_murid, // to
        //     array(
        //       "from" => "+17202631880",
        //       "body" => 'Assalamualaikum. Siswa atas nama '.$murid->nama_lengkap.' Telah melakukan absensi pada pukul '.$jam_masuk.' Terima kasih'
        //     )
        //   );

        // print($message->sid);
        //--------------------------------------------------------------------------------------
    
        // $sid    = "AC2edc0c24fa1519aeedfed5d738b6a80d";
        // $token  = "c73ed36216358371923b2864d8768bd2";
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages
        // ->create("whatsapp:".$no_hp_murid."", // to
        //     array(
        //     "from" => "whatsapp:+14155238886",
        //     "body" => "Assalamualaikum. Siswa atas nama $murid->nama_murid Telah melakukan absensi pada pukul $jam_masuk Terima kasih"
        //     )
        // );

        // print($message->sid);

        return redirect('/absensi_guru');
    }

    public function pulang (Request $request)
    {
        $keterangan = '';
        $tanggal_absen = date('y-m-d');
        $jam_pulang = date("H:i:s");
        $guru = Guru::where([
            'nik' => $request->nik,
        ])->first();
        if (!$guru) {
            Alert::warning('Maaf!', 'data tidak dikenali');
            return redirect('/absensi_guru');
        }
        $guru_id = $guru->id;
        $cekData = AbsensiGuru::whereGuruId($guru_id)->whereTanggalAbsenGuru($tanggal_absen)->first();
        if (!$cekData) {
            Alert::warning('Maaf!', 'Anda tidak melakukan absen Pagi');
            return redirect('/absensi_guru');
        }

        if ($cekData->jam_absen_pulang_guru != '00:00:00') {
            Alert::warning('Maaf!', 'Anda sudah melakukan absen');
            return redirect('/absensi_guru');
        } else {
        $checkOut = $jam_pulang;

        if ($checkOut > '18:00:00') {
            Alert::warning('Maaf!', 'Anda sudah berada diluar jam kerja');
            return redirect('/absensi_guru');
        }

        if ($checkOut <= '15:00:00') {
            Alert::warning('Maaf, Absen Pulang dimulai jam 15:00 PM');
            return redirect('/absensi_guru');
        }
        
        $hari_absen = Carbon::parse($tanggal_absen)->format('l');

        if ($hari_absen == 'Sunday' || $hari_absen == 'Saturday') {
            Alert::error('Gagal!', 'Hari ini Libur');
            return redirect('/absensi_guru');
        }

        if ($hari_absen !== 'Saturday' || $hari_absen !== 'Sunday') {
            if ($checkOut >= '14:00:00' && $checkOut <= '17:30:00' ) {
                $keterangan = 'hadir';
            };
        }

        
        $requestData = [
            'jam_absen_pulang_guru' =>  $checkOut,
            'keterangan_kehadiran_guru' => $keterangan
        ];
        $model = AbsensiGuru::FindOrFail($cekData->id);
        $model->fill($requestData);
        $model->save();
        Alert::success('Terima kasih');
        return redirect('/absensi_guru');
        }

        // $no_hp_murid = $murid->no_hp;
        Alert::success('Silahkan Masuk '.$guru->nama_guru.'!', 'Data kehadiran anda direkam');
       
        // $sid    = "AC2edc0c24fa1519aeedfed5d738b6a80d";
        // $token  = "c73ed36216358371923b2864d8768bd2";
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages
        //   ->create($no_hp_murid, // to
        //     array(
        //       "from" => "+17202631880",
        //       "body" => 'Assalamualaikum. Siswa atas nama '.$murid->nama_lengkap.' Telah melakukan absensi pada pukul '.$jam_masuk.' Terima kasih'
        //     )
        //   );

        // print($message->sid);
        //---------------------------------------------------------------------------
    
        // $sid    = "AC2edc0c24fa1519aeedfed5d738b6a80d";
        // $token  = "c73ed36216358371923b2864d8768bd2";
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages
        // ->create("whatsapp:".$no_hp_murid."", // to
        //     array(
        //     "from" => "whatsapp:+14155238886",
        //     "body" => "Assalamualaikum. Siswa atas nama $murid->nama_murid Telah melakukan absensi pada pukul $jam_pulang Terima kasih"
        //     )
        // );

        // print($message->sid);

        return redirect('/absensi_guru');

    }
}
