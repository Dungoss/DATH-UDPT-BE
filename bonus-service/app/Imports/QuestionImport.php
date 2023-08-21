<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        DB::table('question')->insert([
            'userID' => $row['userID'],
            'questionTitle' => $row['questionTitle'],
            'questionContent' => $row['questionContent'],
            'categoryID' => $row['categoryID'],
            'totalVotes' => $row['totalVotes'],
            'postingTime' => $row['postingTime'],
            'totalAnswer' => $row['totalAnswer'],
            'statusApproved' => $row['statusApproved'],
            'tagID' => $row['tagID'],
            'spam' => $row['spam'],
        ]);
    }
}
