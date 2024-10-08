<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Murid;
use App\Models\Absensi;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AbsensiMuridController extends Controller
{
    public function index()
    {
        return view('absensi');
    }

    public function store(Request $request)
    {
        //cek data
        $murid = Murid::where([
            'nis' => $request->nis
        ])->first();

        if (!$murid) {
            Alert::error('Gagal!', 'Data tidak dikenali');
            return redirect('/absensi');
        }
        $murid_id = $murid->id;
        $cek = Absensi::where([
            'murid_id' => $murid_id,
            'tanggal_absen' => date('y-m-d')
        ])->first();

        if ($cek) {
            Alert::warning('Maaf!', 'Anda sudah melakukan absen');
            return redirect('/absensi');
        }

        

        $tanggal_absen = date('y-m-d');
        $jam_masuk = date("H:i:s");

        $hari_absen = Carbon::parse($tanggal_absen)->format('l');
        $status = '';
        $keterangan = '';

        if ($hari_absen == 'Sunday' || $hari_absen == 'Saturday') {
            Alert::error('Gagal!', 'Hari ini Libur');
            return redirect('/absensi');
        }

        if ($jam_masuk > '09:00:00') {
            Alert::error('Gagal!', 'Anda diluar jam absensi');
            return redirect('/absensi');
        }

        if ($jam_masuk < '06:00:00') {
            Alert::error('Gagal!', 'Anda diluar jam absensi');
            return redirect('/absensi');
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

        Absensi::create([
            'murid_id' => $murid_id,
            'tanggal_absen' => date('y-m-d'),
            'jam_absen_datang' => $jam_masuk,
            'jam_absen_pulang' => '-',
            // 'status' => '-',
            'keterangan' => '-',
            'status' => $status,
            // 'keterangan' => $keterangan
        ]);
        
        // $murid = Murid::whereNis($request->nis)->first();
        $no_hp_murid = $murid->no_hp;
        Alert::success('Silahkan Masuk '.$murid->nama_lengkap.'!', 'Data kehadiran anda direkam');
       
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

    
        $sid    = "AC2edc0c24fa1519aeedfed5d738b6a80d";
        $token  = "c73ed36216358371923b2864d8768bd2";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
        ->create("whatsapp:".$no_hp_murid."", // to
            array(
            "from" => "whatsapp:+14155238886",
            "body" => "Assalamualaikum. Siswa atas nama $murid->nama_murid Telah melakukan absensi pada pukul $jam_masuk Terima kasih"
            )
        );

        print($message->sid);

        return redirect('/absensi');
    }

    public function pulang (Request $request)
    {
        $tanggal_absen = date('y-m-d');
        $jam_pulang = date("H:i:s");
        $murid = Murid::where([
            'nis' => $request->nis,
        ])->first();
        if (!$murid) {
            Alert::warning('Maaf!', 'data tidak dikenali');
            return redirect('/absensi');
        }
        $murid_id = $murid->id;
        $cekData = Absensi::whereMuridId($murid_id)->whereTanggalAbsen($tanggal_absen)->first();
        if (!$cekData) {
            Alert::warning('Maaf!', 'Anda tidak melakukan absen Pagi');
            return redirect('/absensi');
        }
        // $cekData->where('jam_absen_pulang', '=', '15:00:00')->first();
        if ($cekData->jam_absen_pulang != '00:00:00') {
            Alert::warning('Maaf!', 'Anda sudah melakukan absen');
            return redirect('/absensi');
        } else {
        $checkOut = $jam_pulang;

        if ($checkOut > '18:00:00') {
            Alert::warning('Maaf!', 'Anda sudah berada diluar jam kerja');
            return redirect('/absensi');
        }

        if ($checkOut <= '15:00:00') {
            Alert::warning('Maaf, Absen Pulang dimulai jam 15:00 PM');
            return redirect('/absensi');
        }
        
        $hari_absen = Carbon::parse($tanggal_absen)->format('l');

        if ($hari_absen == 'Sunday' || $hari_absen == 'Saturday') {
            Alert::error('Gagal!', 'Hari ini Libur');
            return redirect('/absensi');
        }

        if ($hari_absen !== 'Saturday' || $hari_absen !== 'Sunday') {
            if ($checkOut >= '16:00:00' && $checkOut <= '16:30:00' ) {
                $keterangan = 'hadir';
            };
        }

        
        $requestData = [
            'jam_absen_pulang' =>  $checkOut,
            'keterangan' => $keterangan
        ];
        $model = Absensi::FindOrFail($cekData->id);
        $model->fill($requestData);
        $model->save();
        Alert::success('Terima kasih');
        return redirect('/absensi');
        }

        $no_hp_murid = $murid->no_hp;
        Alert::success('Silahkan Masuk '.$murid->nama_lengkap.'!', 'Data kehadiran anda direkam');
       
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

    
        $sid    = "AC2edc0c24fa1519aeedfed5d738b6a80d";
        $token  = "c73ed36216358371923b2864d8768bd2";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
        ->create("whatsapp:".$no_hp_murid."", // to
            array(
            "from" => "whatsapp:+14155238886",
            "body" => "Assalamualaikum. Siswa atas nama $murid->nama_murid Telah melakukan absensi pada pukul $jam_pulang Terima kasih"
            )
        );

        print($message->sid);

        return redirect('/absensi');

    }

}
