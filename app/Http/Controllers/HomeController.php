<?php

namespace App\Http\Controllers;

use App\Models\MapModel;
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
        return view('home',['sites' => $sites],['datas' => $datas]);
    }
}
