<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkresultModel;
use Illuminate\Support\Facades\Session;

class PPICController extends Controller
{
    public function upd_expres(Request $request)
    {
        // dd($request->all());
        $path = $request->file('file_expres')->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $Data = $reader->load($path);
        $sheetdata = $Data->getActiveSheet()->toArray(null, true, true, true);
        unset($sheetdata[1]);
        // dd($sheetdata);
        // Ambil kolom 'A' dari setiap baris dan ubah nilainya menjadi string
        $columnA = array_map('strval', array_column($sheetdata, 'A'));
        $counts = array_count_values($columnA);
        // $counts = array_count_values(array_column($sheetdata, 'A'));

        $duplicates = array_filter($counts, function ($count) {
            return $count > 1; // Memeriksa apakah ada data yang muncul lebih dari satu kali
        });

        if (!empty($duplicates)) {
            Session::flash(
                'alert-danger',
                'Terdapat Lot No yang sama dalam file upload . '
            );
        } else {
            foreach ($sheetdata as $row) {
                $upd_exp = WorkresultModel::where('lot_no', $row['A'])->update([
                    'warna_tag' => $row['B'],
                    'tag' => $row['C'],
                    'finish' => $row['D'],
                ]);
            }
            Session::flash('alert-success', 'Update data Expres berhasil');
        }
        return redirect()->route('list_shikakari');
    }
}
