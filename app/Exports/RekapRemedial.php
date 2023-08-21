<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Rombongan_belajar;
use App\Models\Semester;

class RekapRemedial implements FromView, ShouldAutoSize
{
    use Exportable;
    public function __construct($semester_id)
    {
        $this->semester_id = $semester_id;
    }
    public function view(): View
    {
        $semester = Semester::find($this->semester_id);
        $data = Rombongan_belajar::with(['wali_kelas'])->withCount([
            'pd as jml_remedial' => function($query){
                $query->whereHas('nilai', function($query){
                    $query->where('angka', 75);
                });
            },
        ])->where(function($query){
            $query->where('semester_id', $this->semester_id);
            $query->whereHas('pd', function($query){
                $query->whereHas('nilai', function($query){
                    $query->where('angka', 75);
                });
            });
        })->orderBy('nama')->get();
        return view('exports.rekap-remedial', [
            'data' => $data,
            'semester' => $semester,
        ]);
    }
}
