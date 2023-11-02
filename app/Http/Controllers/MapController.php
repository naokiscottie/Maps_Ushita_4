<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Models\MapModel;
use App\Models\Setting;
use Auth;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;

class MapController extends Controller
{
    public function map(){

        $id = Auth::id();
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

        return view('map_index',['sites' => $sites],['datas' => $datas])->with(['select_sheet' => $select_sheet]);
    }

    public function map_registration(PlaceRequest $request){

        DB::beginTransaction();
        try{
            MapModel::create([
                'latitude' => $request->data_latitude,
                'longitude' => $request->data_longitude,
                'place_name' => $request->place_name,
                'url' => $request->location_url,
                'information' => $request->information,
                'sheet_A' => $request->sheet_A,
                'sheet_B' => $request->sheet_B,
                'marker' => $request->marker,
            ]);
            DB::commit();
        }catch(\Throwable $e){
            DB::rollback();
            abort(500);
        }

        return redirect('/index')->with('status', '登録が完了しました');;
    }

    public function map_delete($id){
        MapModel::where('id', $id)->delete();
        return redirect('/index');
    }

    public function map_update($id){
        $map_update_data = MapModel::find($id);

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

        return view('map_index_update',['sites' => $sites],['datas' => $datas])->with(['map_update_data' => $map_update_data]);

    }

    public function map_update_registration(Request $request){

        $validated = $request->validate([
            'id' => 'required',
            'data_latitude' => 'required',
            'data_longitude' => 'required',
            'place_name' => 'required',
        ]);

        $inputs = $request->all();
        DB::beginTransaction();
        try{
            $data = MapModel::find($inputs['id']);
            $data->fill([
                'latitude' => $inputs['data_latitude'],
                'longitude' => $inputs['data_longitude'],
                'place_name' => $inputs['place_name'],
                'url' => $inputs['location_url'],
                'information' => $inputs['information'],
                'sheet_A' => $inputs['sheet_A'],
                'sheet_B' => $inputs['sheet_B'],
                'marker' => $inputs['marker'],
            ]);
            $data->save();
            DB::commit();
        }catch(\Throwable $e){
            DB::rollback();
            abort(500);
        }

        //$request->session()->flash('status', '更新処理が完了しました');
        return redirect('/index')->with('status', '更新処理が完了しました');;
    }

    public function sheet_setting(Request $request){

        $id = Auth::id();
        $input = $request->all();
        $sheet = Setting::where('user', '=', $id)->first();

        DB::beginTransaction();
        try{
            $sheet->fill([
                'sheet' => $input['sheet'],
                'user' => $id,
            ]);
            $sheet->save();
            DB::commit();
        }catch(\Throwable $e){
            DB::rollback();
            abort(500);
        }

        return redirect('/index');
    }

}
