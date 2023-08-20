<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class QuestionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $questions = DB::table('question')
        ->orderBy('postingTime', 'desc')
        ->get();
        
        return collect($questions);
    }
    public function headings(): array
    {
        return [
            'Name',
            'Email',
        ];
    }
}
