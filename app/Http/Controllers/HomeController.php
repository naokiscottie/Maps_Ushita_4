<?php

namespace App\Http\Controllers;

use App\Models\MapModel;
use App\Models\Setting;
use Auth;
use DB;
use Illuminate\Http\Request;

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
        //sheetに関する初期設定が終わっていない場合，defaultである0に設定を行う。
        $id = Auth::id();
        $sheet_setting = Setting::where('user', '=', $id)->first();
        if($sheet_setting == null){
            DB::beginTransaction();
            try{
                Setting::create([
                    'sheet' => 0,
                    'user' => $id,
                ]);
                DB::commit();
            }catch(\Throwable $e){
                DB::rollback();
                abort(500);
            }
        }

        //シートに関する設定を確認する。
        $sheet = Setting::where('user', '=', $id)->first();
        $select_sheet = $sheet['sheet'];

        $sites=[];
        $datas = MapModel::all();

        foreach($datas as $data){

            array_push($sites,array(
                'id' => $data->id,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'information' => $data->place_name,
                'map_page' => $data->url,
                'information_data' => $data->information,
                'sheet_A' => $data->sheet_A,
                'sheet_B' => $data->sheet_B,
                'marker' => $data->marker,
            ));
        }
        return view('home',['sites' => $sites],['datas' => $datas])->with(['select_sheet' => $select_sheet]);
    }
}
