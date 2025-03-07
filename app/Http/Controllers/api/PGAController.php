<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\TargetLemburModel;

class PGAController extends Controller
{

            // $user = auth()->user();
            // $request->user()->name; // user name
            // $dept = $request->user()->departments->pluck('dept'); // departemen
            // $group = $request->user()->departments->pluck('group'); // group
             // $section = $request->user()->departments->pluck('section'); // section
            //  $section = $request->user()->departments->pluck('section')->toArray(); // section
            // $request->user()->hasDepartment('CASTING') //userLogin

    public function grafik_lembur (Request $request){
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $periode = date_format(date_create($tgl_awal), 'Y-m');
        $periode2 = date_format(date_create($tgl_akhir), 'Y-m');

        $lembur = DB::select("SELECT
        dept as nama_departemen,
        CASE
            WHEN section = 'OFFICE BOY' THEN 'PGA'
            WHEN section IN ('PRODUCTION TECHNOLOGY & KAIZEN', 'IT') THEN 'TOOLINGS CONTROL'
            ELSE section
        END AS dept_section,
        SUM(totmember) AS total_member,
        SUM(jam) AS total_jam,
        SUM(target_jam) AS total_target_jam
    FROM (
        SELECT
            a.dept,
            a.section,
            ISNULL(SUM(d.jmlh), 0) AS totmember,
            ISNULL((b.total_jam), 0) AS jam,
            ISNULL((c.target_jam), 0) AS target_jam
        FROM
            (SELECT kode_section, dept, section FROM departments WHERE section NOT IN ('Admin')) a
            LEFT JOIN
            (SELECT dept, section, SUM(JUMLAH_JAM_LEMBUR) AS total_jam FROM tb_lembur WHERE TANGGAL_LEMBUR between ? AND ? GROUP BY dept, section) b ON a.section = b.section
            LEFT JOIN
            (SELECT section, SUM(target_jam) AS target_jam FROM tb_target_lembur WHERE periode between ? AND ? GROUP BY section) c ON a.section = c.section
            LEFT JOIN
            (SELECT kode_section, COUNT(kode_section) AS jmlh FROM tb_karyawan WHERE kode_section <> 9999 AND status_karyawan <> 'Off' AND kode_jabatan NOT IN (60, 65, 70) GROUP BY kode_section) d ON a.kode_section = d.kode_section
        GROUP BY
            a.dept, a.section, b.total_jam, c.target_jam
    ) AS subquery
    GROUP BY
        dept,
        CASE
            WHEN section = 'OFFICE BOY' THEN 'PGA'
            WHEN section IN ('PRODUCTION TECHNOLOGY & KAIZEN', 'IT') THEN 'TOOLINGS CONTROL'
            ELSE section
        END
    ORDER BY
        dept", [$tgl_awal, $tgl_akhir, $periode, $periode2]);

        // dd($lembur);

        return $lembur;
    }

    public function planning_lembur (Request $request){
        $periode = $request->periode;

        $sect = DB::select("select a.kode_section, a.section, a.dept, b.pertama, b.kedua, b.temp_1, b.temp_2, b.status_lembur, b.target_jam, b.id_number from
            (select kode_section, section, dept from departments where section not in ('Admin','OFFICE BOY','IT','PRODUCTION TECHNOLOGY & KAIZEN'))a
            left join
            (select id_number, section_cd, pertama, kedua, target_jam, temp_1, temp_2, status_lembur from tb_target_lembur where periode = ?)b on a.kode_section = b.section_cd
            order by a.dept", [$periode]);
        return $sect;
    }

    public function detail_lembur (Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->input('awal');
        $tgl_akhir = $request->input('akhir');
        $dept = $request->input('dept');

        $Datas = DB::select(
            "select a.NIK, a.NAMA, a.SECTION, c.nama_jabatan, sum(a.JUMLAH_JAM_LEMBUR) as total_jam 
            from tb_lembur a join tb_karyawan b on b.NIK = a.NIK join tb_jabatan c on b.kode_jabatan = c.kode_jabatan
            where a.SECTION = ? and a.JUMLAH_JAM_LEMBUR is not NULL and a.TANGGAL_LEMBUR between ? and ? 
            and (a.nik like '%$search%' or a.nama like '%$search%')
            group by a.NIK, a.NAMA, a.SECTION, c.nama_jabatan order by a.nik asc OFFSET " .
                $start .
                ' ROWS FETCH NEXT ' .
                $length .
                ' ROWS ONLY', [$dept, $tgl_awal, $tgl_akhir]
        );

        $count = DB::select(
            "select count(*) as total from (select NIK, NAMA, SECTION, sum(JUMLAH_JAM_LEMBUR) as total_jam from tb_lembur 
            where SECTION = ? and JUMLAH_JAM_LEMBUR is not NULL and TANGGAL_LEMBUR between ? and ?
            and (nik like '%$search%' or nama like '%$search%') group by NIK, NAMA, SECTION) a", [$dept, $tgl_awal, $tgl_akhir]
        )[0]->total;

        //dd($detail);
        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function create_planning_lembur (Request $request)
    {
        // dd($request->all());
        $periode = $request->pl_periode;
        $kode_section = $request->pl_kode;
        $dept_planning = $request->pl_dept;
        $dept = $request->user()->departments->pluck('dept');
        $jenis = $request->user()->departments->pluck('jenis');

        if($dept[0] == $dept_planning || $dept[0] == 'Admin')
        {
            $cek_double = DB::table('tb_target_lembur')
            ->select('section_cd')
            ->where('section_cd', $kode_section)
            ->where('periode', $periode)
            ->count();

            if ($cek_double > 0)
            {
                return [
                    'message' => 'Data Planning lembur sudah ada .',
                    'success' => false,
                ];
            }

                $ins = TargetLemburModel::create([
                    'id_number' => Str::uuid(),
                    'section_cd' => $kode_section,
                    'section' => $request->pl_sect,
                    'target_jam' =>
                        $request->pl_awal + $request->pl_akhir,
                    'periode' => $request->pl_periode,
                    'inputor' => $request->user()->name,
                    'pertama' => $request->pl_awal,
                    'kedua' => $request->pl_akhir,
                    'ket' => $jenis[0],
                ]);

                if($ins)
                {
                    return [
                        'message' => 'Planning lembur Berhasil ditambah .',
                        'success' => true,
                    ];
                } else {
                    return [
                        'message' => 'Planning lembur gagal ditambah .',
                        'success' => false,
                    ];
                }


        } else {
            return [
                'message' => 'Access denied .',
                'success' => false,
            ];
        }
    }

    public function edit_planning_lembur (Request $request)
    {
        // dd($request->all());
        $id_lembur = $request->etl_id;
        $dept_planning = $request->etl_dept;

        $dept = $request->user()->departments->pluck('dept');

        if($dept[0] == $dept_planning || $dept[0] == 'Admin')
        {
            $upd = TargetLemburModel::where('id_number',$id_lembur)->update([
               'temp_1' => $request->etl_temp1,
                'temp_2' => $request->etl_temp2,
                'status_lembur' => 'Permohonan Edit',
            ]);

            if ($upd){
                return [
                    'message' => 'Planning lembur Berhasil dirubah .',
                    'success' => true,
                ];
            } else {
                return [
                    'message' => 'Planning lembur gagal dirubah .',
                    'success' => false,
                ];
            }
        } else {
            return [
                'message' => 'Access denied .',
                'success' => false,
            ];
        }
    }
}
