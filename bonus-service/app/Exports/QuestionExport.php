<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class QuestionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings() :array
    {
        return [
            'STT',
            'Title',
            'Content',
            'Total Votes',
            'Total Answer'
        ];
    }
    public function collection()
    {
        $questions = DB::table('question')
        ->select('id', 'questionTitle', 'questionContent', 'totalVotes', 'totalAnswer')
        ->get();
        
        return $questions;
    }

}
