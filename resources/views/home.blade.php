<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{--  ここから  --}}
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-180x180.png">
    <link rel="icon" href="/img/favicon.ico" id="favicon">

    <style>
        @media screen and (min-width: 481px){
            body {
                height: 118%;
                background-color:ghostwhite;
            }
        }
        @media screen and (max-width: 480px){
            body {
                height: 141%;
                background-color:ghostwhite;
            }
        }
        html {
            height: 100%
        }

        .map_box{
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .map_area{
            height: 100%;
            width: 97%;
            position: absolute;

        }
        .map{
            height: 100%;
            width: 100%;
        }
        .page_box{
            display: flex;
            justify-content:space-between;
            flex-direction:column;
            align-items: center;
            height: 90%;
            width: 100%;
        }
        .page_right{
            flex-basis: 65%;
            height: 100%;
            width: 100%;
            padding: 1%;
        }
        .page_left{
            flex-basis: 33%;
            height: 100%;
            width: 90%;
            padding: 1%;
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

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    {{--  ここから  --}}
    {{--  ローディングの画面  --}}
    <div id="loading" style="background-color:grey; width: 100vw; height: 100vh;">
        <div style="display: flex; justify-content: center; align-items: center; height: 100%; width: 100%">
            <i class="loading-icon"></i>
        </div>
    </div>

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

            {{--  選択された場所  --}}
            <div id="select_information" style="display:none;">
                <h6>地点情報</h6>
                <table class="table table-striped">
                    <thead class="heading">
                    <tr>
                        <th>地点名</th>
                        <th>url</th>
                        <th>地点情報</th>
                        <th>シートA</th>
                        <th>シートB</th>
                        {{--  <th></th>  --}}
                    </tr>
                    </thead>
                    <tbody class="table_body">
                        <tr>
                            <td id="select_place" data-label="name:"></td>
                            <td id="select_url" data-label="map:"></td>
                            <td id="select_information_data" data-label="info:"></td>
                            <td id="select_sheet_A" data-label="sheet:"></td>
                            <td id="select_sheet_B" data-label="sheet:"></td>
                            {{--  <td id="select_move"></td>  --}}
                        </tr>
                    </tbody>
                </table>
            </div>

            {{--  選択された場所  --}}
            {{--  <div id="select_information" style="display:none;">
                <h6>地点情報</h6>
                <table class="table table-striped">
                    <tr>
                        <th>地点名</th>
                        <td id="select_place"></td>
                    </tr>
                    <tr>
                        <th>google_map</th>
                        <td id="select_url"></td>
                    </tr>
                    <tr>
                        <th>シートA</th>
                        <td id="select_sheet_A"></td>
                    </tr>
                    <tr>
                        <th>シートB</th>
                        <td id="select_sheet_B"></td>
                    </tr>
                    <tr>
                        <th>info</th>
                        <td id="select_information_data"></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td id="select_move"></td>
                    </tr>
                </table>
            </div>    --}}

            {{--  現在地への移動  --}}

            <button onclick="set_location()" class="btn btn-primary">現在地 ➡︎</button>
            {{--  R51027 追記  --}}
            <button onclick="set_lotation()" class="btn btn-primary" style="margin-left: 2%;">⬆︎⬇︎</button>

            <button class="btn btn-success" id="register_page" style="margin-left: 2%;">設定</button>


        </div>
    </div>

<script>

    const sites = @json($sites);
    let map;
    let latitude_data;
    let longitude_data;

    const $select_place = document.getElementById('select_place');
    const $select_url = document.getElementById('select_url');
    //const $select_move = document.getElementById('select_move');
    const $select_information_data = document.getElementById('select_information_data');
    const $select_sheet_A = document.getElementById('select_sheet_A');
    const $select_sheet_B = document.getElementById('select_sheet_B');
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
            scale: 12,                           //円のサイズ
            strokeColor: 'red',              //枠の色
            strokeWeight: 0.6,                   //枠の透過率
        };

        let icon_circle_2 = {
            fillColor: 'blue',                //塗り潰し色
            fillOpacity: 0.7,                    //塗り潰し透過率
            path: google.maps.SymbolPath.CIRCLE, //円を指定
            scale: 12,                           //円のサイズ
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
            zoom: 19,
            //初めの地図の中心位置
            center: {lat: center_lat, lng: center_lng},
        });

        const center_marker = new google.maps.Marker({
            position: { lat: center_lat, lng: center_lng },
            title: title,
            map: map,

            //icon: svgMarker,

        });

        let box = '<div class="box">' + area_name + '</div>';

        let center_infowindow = new google.maps.InfoWindow({
            content: box
        });
        center_infowindow.open(map, center_marker);

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

        //マップのマーカーをクリックした時の処理
        markers.map((marker,index)=>{
            google.maps.event.addListener(marker, 'click', (e)=>{
                //マーカーを表示させる処理
                infowindows[index].open(map,marker);
                //クリックされたマーカーに関する情報を表示させる処理
                $select_place.innerText = sites[index].information;
                if(sites[index].map_page != null){
                    $select_url.innerHTML = '<a href=' + sites[index].map_page + " target='_blank'>Google_Map</a>";
                }else{
                    $select_url.innerText = 'なし';
                }
                //$select_move.innerHTML = '<button onclick="place_position(' + sites[index].latitude + ',' + sites[index].longitude +')">▶︎</button>';
                if(sites[index].information_data != null){
                    $select_information_data.innerText = sites[index].information_data;
                }else{
                    $select_information_data.innerText = 'なし';
                }
                if(sites[index].sheet_A != null){
                    $select_sheet_A.innerHTML = '<a href=' + sites[index].sheet_A + " target='_blank'>シートA</a>";
                }else{
                    $select_sheet_A.innerText = 'なし';
                }
                if(sites[index].sheet_B != null){
                    $select_sheet_B.innerHTML = '<a href=' + sites[index].sheet_B + " target='_blank'>シートB</a>";
                }else{
                    $select_sheet_B.innerText = 'なし';
                }
                $select_information.style.display = 'block';
            });
        });

        map.addListener('click', (e) => {
            //現在選択している場所の情報の表示を消す。
            $select_information.style.display = 'none';
        });

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

    let password;
    document.getElementById('register_page').addEventListener('click',()=>{
        password = prompt("パスワードを入力してください","");
        if ( password == "191410" ){
            location.href="/index";
        }else{
            alert( "パスワードが違います。" );
        }
    });

    //R51027 追記
    let degree = 0;
    const set_lotation = () => {
        degree = degree + 180;
        document.getElementById('map_area').style.transform = 'rotate('+ degree +'deg)';
    }
    //追記終了

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfCsX2x-eclb4GzEeVa1gJDOPD-SlWZeA&callback=initMap"></script>


</body>
</html>
