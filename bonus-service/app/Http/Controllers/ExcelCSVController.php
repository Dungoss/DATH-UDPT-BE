<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use App\Exports\QuestionExport;
use App\Exports\QuestionImport;
 
 
use Maatwebsite\Excel\Facades\Excel;
 
 
class ExcelCSVController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function index()
    {
       return view('excel-csv-import');
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExcelCSV(Request $request) 
    {
        $validatedData = $request->validate([
 
           'file' => 'required',
 
        ]);
 
        Excel::import(new QuestionImport,$request->file('file'));
 
            
        return redirect('excel-csv-file')->with('status', 'The file has been excel/csv imported to database in laravel 9');
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function exportExcelCSV() 
    {
        return Excel::download(new QuestionExport, 'questions.xlsx');
    }
    
}