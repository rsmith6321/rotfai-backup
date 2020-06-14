<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $list = DB::table('provinces')
        ->orderBy('name_th','asc')
        ->get();
        return view('home')->with('list',$list);
    }

    function fetchAmphures(Request $request){
        
        $id = $request->get('select');
        $result = array();
        $query = DB::table('provinces')
        ->join('amphures','provinces.id','=','amphures.province_id')
        ->select('amphures.id','amphures.name_th')

        ->where('provinces.id',$id)
        ->groupBy('amphures.id','amphures.name_th')
        //->groupBy('amphures.id')
        ->get();
        $output='<option value="">เลือกอำเภอของท่าน</option>';
        foreach ($query as $row) {
            $output.= '<option value="'.$row->id.'">'.$row->name_th.'</option>';
        }
        echo $output;
        //echo '<option>hello</option>';
    } 

    function fetchDistricts(Request $request){
        
        $id = $request->get('select');
        $result = array();
        $query = DB::table('amphures')
        ->join('districts','amphures.id','=','districts.amphure_id')
        ->select('districts.name_th','districts.id')
        ->where('amphures.id',$id)
        ->groupBy('districts.name_th','districts.id')
        ->get();
        $output='<option value="">เลือกตำบลของท่าน</option>';
        foreach ($query as $row) {
            $output.= '<option value="'.$row->id.'">'.$row->name_th.'</option>';
        }
        echo $output;
        echo '<option>hello</option>';
    }
}
