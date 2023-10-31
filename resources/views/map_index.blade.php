<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-180x180.png">
    <link rel="icon" href="/img/favicon.ico" id="favicon">
    <style>
        html {
            height: 100%
        }
        @media screen and (min-width: 481px){
            body {
                height: 100%;
                background-color:ghostwhite;
            }
            .page_box{
                display: flex;
                justify-content:space-between;
                height: 80%;
                width: 100%;
            }
            .page_right{
                height: 100%;
                width: 100%;
                padding: 3%;
            }
            .page_left{
                height: 100%;
                width: 100%;
                padding: 3%;
            }
        }
        @media screen and (max-width: 480px){
            body {
                background-color:ghostwhite;
            }
            .page_box{
                display: flex;
                flex-direction: column;
                height: 200%;
                width: 100%;
            }
            .page_right{
                height: 50vh;
                width: 100%;
                padding: 1%;

            }
            .page_left{
                height: 50vh;
                width: 100%;
                padding: 5%;
            }
        }
        .map_box{
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .map_area{
            height: 100%;
            width: 90%;
        }
        .map{
            height: 100%;
            width: 100%;
        }
        @media screen and (max-width: 480px){
            .heading {
                display: none;
            }
            .table_body td{
                display: block;
            }
            .table_body td::before{
                content: attr(data-label);
                display: block;
            }

        }

      </style>
    <title>Document</title>
</head>
<body>

    {{--  ローディングの画面  --}}
    <div id="loading" style="background-color:grey; width: 100vw; height: 100vh;">
        <div style="display: flex; justify-content: center; align-items: center; height: 100%; width: 100%">
            <i class="loading-icon"></i>
        </div>
    </div>

    {{--  タイトル  --}}
    <h3 style="margin-left: 5%;margin-top: 3%;">Maps</h3>

    <div class="page_box">
        <div class="page_right">

            {{--  地図のエリア  --}}
            <div class="map_box">
                {{--  地図表示  --}}
                <div id="map_area" class="map_area" style="display: none;">
                    <div id="map" class="map"></div>
                </div>

            </div>

        </div>



        <div class="page_left">
            {{--  現在地への移動  --}}
            <div style="display:flex; justify-content: space-between;">
                <button onclick="set_location()" class="btn btn-primary">現在地</button>
                <button onclick="location.href='/home'" class="btn btn-success">home</button>
            </div>

            <br />

            {{--  選択された場所  --}}
            <div id="select_information" style="display:none;">
                <h4>地点情報</h4>

                <table class="table table-striped">
                    <thead class="heading">
                    <tr>
                        <th>地点名</th>
                        <th>map</th>
                        <th style="white-space: normal;">地点情報</th>
                        <th>A</th>
                        <th>B</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="table_body">
                        <tr>
                            <td id="select_place" data-label="name:"></td>
                            <td id="select_url" data-label="map:"></td>
                            <td id="select_information_data" data-label="info:"></td>
                            <td id="select_sheet_A" data-label="シートA:"></td>
                            <td id="select_sheet_B" data-label="シートB:"></td>
                            <td id="select_move"></td>
                            <td id="select_update"></td>
                            <td id="select_delete"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{--  地点の登録  --}}
            <h4>地点の登録</h4>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            {{--  バリデーションメッセージの表示  --}}
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color: red;">{{$error}}</li>
                @endforeach
            </ul>
            <label>地図上の地点：</label><span id="place" style="color: red">未設定</span><br/>
            <form action="/map_registration" method="POST" style="margin-top: 10px">
                @csrf
                <input id="latitude" type="hidden" name="data_latitude" value="">
                <input id="longitude" type="hidden" name="data_longitude" value="">

                <div class="form-group">
                    <label for="Location_Name">地点名:</label>
                    <input name="place_name" id="Place_Name" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="Location_Url">url:</label>
                    <input name="location_url" id="Location_Url" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="information">地点情報:</label>
                    <input name="information" id="information" class="form-control"/>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="sheet" id="check_box" onchange="sheet_function()"/>
                    <label class="form-check-label" for="check_box">スプレッドシートの有無</label>
                </div>


                <div class="form-group" id="Sheet_A" style="display:none">
                    <label for="sheet_A">シートA:</label>
                    <input name="sheet_A" id="sheet_A" class="form-control"/>
                </div>

                <div class="form-group" id="Sheet_B" style="display:none">
                    <label for="sheet_B">シートB:</label>
                    <input name="sheet_B" id="sheet_B" class="form-control"/>
                </div>

                <p class="mt-3">ピンの種類：
                    <select name="marker" class="form-group form-select" style="width: 50%">
                    <option value="0" selected>default</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    </select>
                </p>


                <button type="submit" class="btn btn-primary" style="margin-top: 10px">登録</button>
            </form>
            <br />

            {{--  登録地点の表  --}}
            <table class="table table-striped">
                <thead class="heading">
                <tr>
                    <th scope="col" style="width: 20%">地点名</th>
                    <th scope="col" style="width: 10%">url</th>
                    <th scope="col" style="width: 10%">A</th>
                    <th scope="col" style="width: 10%">B</th>
                    <th scope="col" style="width: 30%; white-space: normal;">地点情報</th>
                    {{--  <th scope="col" style="width: 10%"></th>  --}}
                    <th scope="col" style="width: 10%"></th>
                    <th scope="col" style="width: 10%"></th>
                </tr>
                </thead>
                <tbody class="table_body">

                    @foreach ($datas as $data)
                    <tr>
                        <td data-label="name:">{{ $data->place_name }}</td>
                        <td data-label="map:">
                            @if($data->url == null)
                            なし
                            @else
                            <a href="{{ $data->url }}" target="_blank">map</a>
                            @endif
                        </td>
                        <td data-label="シートA:">
                            @if($data->sheet_A == null)
                            なし
                            @else
                            <a href="{{ $data->sheet_A }}" target="_blank">A</a>
                            @endif
                        </td>
                        <td data-label="シートB:">
                            @if($data->sheet_B == null)
                            なし
                            @else
                            <a href="{{ $data->sheet_B }}" target="_blank">B</a>
                            @endif
                        </td>
                        <td data-label="info:">
                            @if($data->information == null)
                            なし
                            @else
                            {{ $data->information }}
                            @endif
                        </td>
                        {{--  <td><button onclick="place_position({{ $data->latitude }},{{ $data->longitude }})">➡︎</button></td>  --}}
                        <td><a href="/map_update/{{ $data->id }}" class="btn btn-primary btn-sm">更新</a></td>
                        <td><a href="/map_delete/{{ $data->id }}" class="btn btn-primary btn-sm">削除</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


<script>
    const sites = @json($sites);
    let map;
    let latitude_data;
    let longitude_data;
    const $place = document.getElementById('place');
    const $select_place = document.getElementById('select_place');
    const $select_url = document.getElementById('select_url');
    const $select_information_data = document.getElementById('select_information_data');
    const $select_sheet_A = document.getElementById('select_sheet_A');
    const $select_sheet_B = document.getElementById('select_sheet_B');
    const $select_move = document.getElementById('select_move');
    const $select_delete = document.getElementById('select_delete');
    const $select_update = document.getElementById('select_update');
    const $select_information = document.getElementById('select_information');

    /*
    icon_1 = 'https://maps.google.com/mapfiles/ms/micons/blue-dot.png';
    icon_2 = 'https://maps.google.com/mapfiles/ms/micons/green-dot.png';
    icon_3 = 'https://maps.google.com/mapfiles/ms/micons/orange-dot.png';
    */

    initMap = (center_lat = 0 ,center_lng = 0 ,title = 'map_center', area_name='') =>{
        let icon;

        //アイコンの種類
        icon_nomal = '';

        let icon_circle_1 = {
            fillColor: 'red',                //塗り潰し色
            fillOpacity: 0.7,                    //塗り潰し透過率
            path: google.maps.SymbolPath.CIRCLE, //円を指定
            scale: 10,                           //円のサイズ
            strokeColor: 'red',              //枠の色
            strokeWeight: 0.6,                   //枠の透過率
        };

        let icon_circle_2 = {
            fillColor: 'blue',                //塗り潰し色
            fillOpacity: 0.7,                    //塗り潰し透過率
            path: google.maps.SymbolPath.CIRCLE, //円を指定
            scale: 10,                           //円のサイズ
            strokeColor: 'blue',              //枠の色
            strokeWeight: 0.6,                   //枠の透過率
        };

        let icon_circle_3 = {
            fillColor: 'green',                //塗り潰し色
            fillOpacity: 0.6,                    //塗り潰し透過率
            path: google.maps.SymbolPath.CIRCLE, //円を指定
            scale: 10,                           //円のサイズ
            strokeColor: 'green',              //枠の色
            strokeWeight: 0.6,                   //枠の透過率
        };

        let icon_circle_4 = {
            fillColor: 'orange',                //塗り潰し色
            fillOpacity: 0.6,                    //塗り潰し透過率
            path: google.maps.SymbolPath.CIRCLE, //円を指定
            scale: 10,                           //円のサイズ
            strokeColor: 'orange',              //枠の色
            strokeWeight: 0.6,                   //枠の透過率
        };

        /*
        let svgMarker = {
            //path: "M-1.547 12l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM0 0q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z',
            fillColor: "blue",
            fillOpacity: 0.6,
            strokeWeight: 0,
            rotation: 0,
            scale: 2,
            anchor: new google.maps.Point(0, 20),
        };
        */

        //現在選択している場所の情報の表示を消す。
        $select_information.style.display = 'none';

        //let center_position = new google.maps.LatLng(center_lat,center_lng);
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            //初めの地図の中心位置
            center: {lat: center_lat, lng: center_lng},

        });

        const center_marker = new google.maps.Marker({
            position: { lat: center_lat, lng: center_lng },
            title: title,
            map: map,
        });

        let box = '<div class="box">' + area_name + '</div>';
        let center_infowindow = new google.maps.InfoWindow({
            content: box
        });
        center_infowindow.open(map, center_marker);

        google.maps.event.addListener(center_marker, 'click', (e)=>{
            marker_position.setMap(null);
        });

        let markers = sites.map((site)=> {

            if(site.marker==0){
                icon = icon_circle_1;
            }else if(site.marker==1){
                icon = icon_circle_2;
            }else if(site.marker==2){
                icon = icon_circle_3;
            }else{
                icon = icon_circle_4;
            }

            return new google.maps.Marker({
            position: {lat: site.latitude, lng: site.longitude},
            map: map,
            icon: icon,
            });

        });

        infowindows = sites.map((site)=>{
            let content;
            if(site.map_page != null){
                content = '<div class="box">' + "<a href='"+ site.map_page + "' target='_blank'>"+site.information+'</a>' + '</div>';
            }else{
                content = '<div class="box">' +site.information + '</div>';
            }
            return new google.maps.InfoWindow({
                content: content,
            });
        });

        //クリック時のマーカー位置を格納する変数
        var marker_position = new google.maps.Marker();
        map.addListener('click', (e) => {

            latitude_data = e.latLng.lat();
            longitude_data = e.latLng.lng();

            $place.innerText = '地点が設定されました。';
            $place.style.color = "blue";

            document.getElementById('latitude').value = latitude_data;
            document.getElementById('longitude').value = longitude_data;
            //クリック時のmarkerの位置を地図に表示
            marker_position.setPosition(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
            marker_position.setMap(map);
            //現在選択している場所の情報の表示を消す。
            $select_information.style.display = 'none';
        });

        //マップのマーカーをクリックした時の処理
        markers.map((marker,index)=>{
            google.maps.event.addListener(marker, 'click', (e)=>{
                //マーカーを表示させる処理
                infowindows[index].open(map,marker);
                //クリックされたマーカーに関する情報を表示させる処理
                $select_place.innerText = sites[index].information;
                if(sites[index].map_page != null){
                    $select_url.innerHTML = '<a href=' + sites[index].map_page + " target='_blank'>map</a>";
                }else{
                    $select_url.innerText = 'なし';
                }
                if(sites[index].information_data != null){
                    $select_information_data.innerText = sites[index].information_data;
                }else{
                    $select_information_data.innerText = 'なし';
                }
                if(sites[index].sheet_A != null){
                    $select_sheet_A.innerHTML = '<a href=' + sites[index].sheet_A + " target='_blank'>A</a>";
                }else{
                    $select_sheet_A.innerText = 'なし';
                }
                if(sites[index].sheet_B != null){
                    $select_sheet_B.innerHTML = '<a href=' + sites[index].sheet_B + " target='_blank'>B</a>";
                }else{
                    $select_sheet_B.innerText = 'なし';
                }
                $select_move.innerHTML = '<button onclick="place_position(' + sites[index].latitude + ',' + sites[index].longitude +')">➡︎</button>';
                $select_update.innerHTML = '<a href="/map_update/' + sites[index].id + '"' + 'class="btn btn-primary btn-sm">更新</a>';
                $select_delete.innerHTML = '<a href="/map_delete/' + sites[index].id + '"' + 'class="btn btn-primary btn-sm">削除</a>'
                $select_information.style.display = 'block';
                //ポイントの削除
                marker_position.setMap(null);

            });
        })

    }

    //現在地
    let present_location_latitude;
    let present_location_longitude;
    const $loading = document.getElementById('loading');
    const $map_area = document.getElementById('map_area');

    window.onload = ()=>{

        const asyncFunc = async () => {

            const first_task = await new Promise ((resolve)=>{
                navigator.geolocation.getCurrentPosition((position)=>{
                    present_location_latitude = position.coords.latitude;
                    present_location_longitude = position.coords.longitude;
                    resolve();
                });
            })

            const second_task = await new Promise ((resolve)=>{
                initMap(present_location_latitude,present_location_longitude,'map_center','現在地');
                $loading.style.display = 'none';
                $map_area.style.display = 'block';
                resolve();
            });

            const third_task = await new Promise ((resolve)=>{
                setTimeout(() => {
                    let latlng = new google.maps.LatLng(present_location_latitude, present_location_longitude);
                    map.setCenter(latlng);
                    resolve();
                }, 300);
            });
        }

        asyncFunc();

    }

    //再度現在地を取得して，地図の位置を調整する。
    const set_location = async() => {
        //現在地の取得
        const task_1 = await new Promise ((resolve)=>{
            navigator.geolocation.getCurrentPosition((position)=>{
                present_location_latitude = position.coords.latitude;
                present_location_longitude = position.coords.longitude;
                resolve();
            });
        });
        //地図に反映
        const task_2 = await new Promise ((resolve)=>{
            initMap(present_location_latitude,present_location_longitude,'map_center','現在地');
            resolve();
        });
        //地図の中心を現在地に移動
        const task_3 = await new Promise ((resolve)=>{
            setTimeout(() => {
                let latlng = new google.maps.LatLng(present_location_latitude, present_location_longitude);
                map.setCenter(latlng);
                resolve();
            }, 300);
        });

    };

    const place_position = (latitude,longitude) => {
        let latlng = new google.maps.LatLng(latitude, longitude);
        map.setCenter(latlng);
    }

    const sheet_function = () => {
        $sheet_A = document.getElementById('Sheet_A');
        $sheet_B = document.getElementById('Sheet_B');

        if($sheet_A.style.display == 'none'){
            $sheet_A.style.display = 'block';
        }else{
            $sheet_A.style.display = 'none';
        }
        if($sheet_B.style.display == 'none'){
            $sheet_B.style.display = 'block';
        }else{
            $sheet_B.style.display = 'none';
        }
    }

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfCsX2x-eclb4GzEeVa1gJDOPD-SlWZeA&callback=initMap"></script>
</body>
</html>
