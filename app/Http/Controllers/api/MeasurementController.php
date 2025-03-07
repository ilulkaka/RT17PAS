<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\MeasurementModel;
use App\Models\MeasurementStModel;
use App\Models\MeasurementAbnormalitasModel;

class MeasurementController extends Controller
{
    public function listApprove (Request $request)
    {
        // dd($request->all());
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $section = $request->user()->departments->pluck('section')->toArray();

        $Datas = DB::table('tb_meas_st as a')
            ->leftJoin(
                'tb_meas as b',
                'a.no_registrasi',
                '=',
                'b.no_registrasi'
            )
            ->select(
                'a.id_meas_st',
                'a.id_meas',
                'a.no_registrasi',
                'a.lokasi',
                'a.section',
                'a.rak_no',
                'a.warna_identifikasi',
                'a.tgl_penyerahan',
                'b.nama_alat_ukur',
                'b.ukuran',
                'b.jenis',
                'b.range_cek'
            )
            ->where('a.penerima', '=', null)
            ->whereIn('a.section', $section)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.no_registrasi', 'like', '%' . $search . '%')
                    ->orwhere('a.lokasi', 'like', '%' . $search . '%')
                    ->orwhere('b.nama_alat_ukur', 'like', '%' . $search . '%')
                    ->orwhere('b.ukuran', 'like', '%' . $search . '%')
                    ->orwhere('a.tgl_penyerahan', 'like', '%' . $search . '%');
            })
            ->orderBy('tgl_penyerahan', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = DB::table('tb_meas_st as a')
            ->leftJoin(
                'tb_meas as b',
                'a.no_registrasi',
                '=',
                'b.no_registrasi'
            )
            ->select(
                'a.id_meas_st',
                'a.id_meas',
                'a.no_registrasi',
                'a.lokasi',
                'a.section',
                'a.rak_no',
                'a.warna_identifikasi',
                'a.tgl_penyerahan',
                'b.nama_alat_ukur',
                'b.ukuran',
                'b.jenis',
                'b.range_cek'
            )
            ->where('a.penerima', '=', null)
            ->whereIn('a.section', $section)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.no_registrasi', 'like', '%' . $search . '%')
                    ->orwhere('a.lokasi', 'like', '%' . $search . '%')
                    ->orwhere('b.nama_alat_ukur', 'like', '%' . $search . '%')
                    ->orwhere('b.ukuran', 'like', '%' . $search . '%')
                    ->orwhere('a.tgl_penyerahan', 'like', '%' . $search . '%');
            })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function updSelectedApprove(Request $request)
    {
        // dd($request->all());
        $nama = $request->user()->name;
        $countIDs = $request->input('selectedIDs');

        if (!empty($countIDs)) {
            $coun = count($countIDs);

            foreach ($countIDs as $IDs) {
                for ($i = 0; $i < $coun; $i++) {
                    MeasurementStModel::where(
                        'id_meas_st',
                        $request->selectedIDs[$i]
                    )->update([
                        'penerima' => $nama,
                        'tgl_terima' => date('Y-m-d'),
                    ]);
                }
            }

            $idMeasRecords = MeasurementStModel::select('id_meas')
                ->whereIn('id_meas_st', $countIDs)
                ->get();

            // Ubah $idMeasRecords menjadi array id_meas
            $idMeasArray = $idMeasRecords->pluck('id_meas')->toArray();

            // Periksa apakah $idMeasArray tidak kosong
            if (!empty($idMeasArray)) {
                $dataToUpdate = [
                    'status_meas' => 'Production',
                ];

                MeasurementModel::whereIn('id_meas', $idMeasArray)->update(
                    $dataToUpdate
                );

                return response()->json([
                    'success' => true,
                    'message' =>
                        'Approve Berhasil dan status berhasil diperbarui.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Tidak ada id_meas yang ditemukan untuk diperbarui.',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada ID yang dipilih untuk diperbarui.',
            ]);
        }
    }

    // ========================= Production ============================
    public function listProduction(Request $request)
    {
        // dd($request->all());
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $section = $request->user()->departments->pluck('section')->toArray();

        $Datas = DB::table('tb_meas_st as a')
            ->leftJoin(
                'tb_meas as b',
                'a.no_registrasi',
                '=',
                'b.no_registrasi'
            )
            ->select(
                'a.id_meas_st',
                'a.id_meas',
                'a.no_registrasi',
                'a.lokasi',
                'a.section',
                'a.rak_no',
                'a.warna_identifikasi',
                'a.tgl_penyerahan',
                'a.tgl_kalibrasi',
                'a.abnormalitas',
                'b.nama_alat_ukur',
                'b.ukuran',
                'b.jenis',
                'b.range_cek'
            )
            ->where('a.penerima', '!=', null)
            ->where('a.tgl_penarikan', '=', null)
            ->where('a.abnormalitas', '!=', 99)
            ->whereIn('a.section', $section)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.no_registrasi', 'like', '%' . $search . '%')
                    ->orwhere('a.lokasi', 'like', '%' . $search . '%')
                    ->orwhere('b.nama_alat_ukur', 'like', '%' . $search . '%')
                    ->orwhere('b.ukuran', 'like', '%' . $search . '%')
                    ->orwhere('a.tgl_penyerahan', 'like', '%' . $search . '%');
            })
            ->orderBy('tgl_penyerahan', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = DB::table('tb_meas_st as a')
            ->leftJoin(
                'tb_meas as b',
                'a.no_registrasi',
                '=',
                'b.no_registrasi'
            )
            ->select(
                'a.id_meas_st',
                'a.id_meas',
                'a.no_registrasi',
                'a.lokasi',
                'a.section',
                'a.rak_no',
                'a.warna_identifikasi',
                'a.tgl_penyerahan',
                'a.tgl_kalibrasi',
                'a.abnormalitas',
                'b.nama_alat_ukur',
                'b.ukuran',
                'b.jenis',
                'b.range_cek'
            )
            ->where('a.penerima', '!=', null)
            ->where('a.tgl_penarikan', '=', null)
            ->where('a.abnormalitas', '!=', 99)
            ->whereIn('a.section', $section)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.no_registrasi', 'like', '%' . $search . '%')
                    ->orwhere('a.lokasi', 'like', '%' . $search . '%')
                    ->orwhere('b.nama_alat_ukur', 'like', '%' . $search . '%')
                    ->orwhere('b.ukuran', 'like', '%' . $search . '%')
                    ->orwhere('a.tgl_penyerahan', 'like', '%' . $search . '%');
            })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function updReportAbnormal (Request $request){
        // dd($request->all());
        $nama = $request->user()->name;

        $lama_pemakaian = Carbon::parse(date('Y-m-d'))->diffInDays(
            Carbon::parse($request->ra_tglPenyerahan)
        );

        $insAbnormalitas = MeasurementAbnormalitasModel::create([
            'id_meas_abnormalitas' => str::uuid(),
            'id_meas' => $request->ra_idMeas,
            'id_meas_st' => $request->ra_idMeasST,
            'tgl_temuan' => date('Y-m-d'),
            'tgl_pemakaian' => $request->ra_tglPenyerahan,
            'lama_pemakaian' => $lama_pemakaian,
            'masalah' => $request->ra_masalah,
            'penyebab' => $request->ra_penyebab,
            'dibuat' => $nama,
        ]);

        if ($insAbnormalitas) {
            $updMeas = MeasurementModel::where('id_meas', $request->ra_idMeas)->update(
                [
                    'status_meas' => 'Abnormal',
                ]
            );

            $updMeasST = MeasurementStModel::where(
                'id_meas_st',
                $request->ra_idMeasST
            )->update([
                'abnormalitas' => 1,
            ]);

            return [
                'success' => true,
                'message' => 'Pengajuan alat abnormal berhasil dikirim .',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Pengajuan gagal dikirim .',
            ];
        }
    }
}
