<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>多重篩選</title>
    <!-- 使用bootstrap模板美化，如果你想用原生css，就把下面的link刪掉 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- 使用leaflet地圖套件，下為leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
      integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
      crossorigin=""/>
      <!-- Make sure you put this AFTER Leaflet's CSS -->
      <!-- 使用leaflet地圖套件，下為leaflet JS -->
      <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
      integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
      crossorigin=""></script>
      <style>
      #map { height: 360px; width: 100%;}
      </style>
    </head>
    <body>
      <?php include 'navbar.html';?>
      <div class="container">
        <div class="row w-100 h-100 justify-content-center align-items-center">
          <div class="col-md-6"><div class="carad w-75 mb-3">
            <form method="GET" action="ajax/getmap.php">
              <div class="card-body">
                <h5 class="card-title fw-bold fs-3">多重篩選－書店</h5>
                <hr>
                <div class="row">
                  <div class="col-md-6"><select id="city" class="form-select form-select-lg mb-3 border border-3 border-dark" aria-label="Default select example" name="city">
                    <option value="" selected>選擇城市</option>
                    <option value="臺北市">臺北市</option>
                    <option value="新北市">新北市</option>
                    <option value="高雄市">高雄市</option>
                  </select></div>
                  <div class="col-md-6"><select id="area" class="form-select form-select-lg mb-3 border border-3 border-dark" aria-label="Default select example" name="area">
                    <option value="" selected>選擇區域</option>
                    <option value="前金區">前金區</option>
                    <option value="鹽埕區">鹽埕區</option>
                    <option value="苓雅區">苓雅區</option>
                    <option value="前鎮區">前鎮區</option>
                  </select></div>
                </div>
                <div class="row">
                  <div class="col-12"><select id="name" class="form-select form-select-lg mb-3 border border-3 border-dark" aria-label="Default select example" name="sleep">
                    <option value="" selected>查詢有無營業(星期)</option>
                    <option value="1">星期一</option>
                    <option value="2">星期二</option>
                    <option value="3">星期三</option>
                    <option value="4">星期四</option>
                    <option value="5">星期五</option>
                    <option value="6">星期六</option>
                    <option value="7">星期天</option>
                  </select></div>
                  <div class="col-12"><input type="time" id="time"  class="fw-bold fs-3 border border-3 border-dark rounded w-50" name="time">
                   </div>
                </div>
                <!-- <button type="submit" class="btn btn-primary mt-2 w-75">查詢</button> -->
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6"><div id="map"></div></div>
      </div>
      <div class="row w-100 justify-content-center">
        <div class="col-10">
          <table class="table" id="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">店名</th>
                <th scope="col">地址</th>
                <th scope="col">營業時間</th>
              </tr>
            </thead>
            <tbody id="answer">
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
    /* leaflet建立 */
    // 使用leaflet地圖套件，建立map容器
    var map = L.map('map').setView([22.621821553303658, 120.29854085496999], 12);
    //在容器中加底圖，包含一些來源資訊
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    //此array宣告為var(全域變數)，用於存放使用者選種之書店資訊
    var arr = {
    select_city: "",
    select_area: "",
    select_sleep: "",
    select_time: ""
    };

    //宣告變數，將html物件綁定，分別為使用者"選擇城市"、"選擇區域"、"有無營業"、"選擇時間"，將用於偵測使用者鍵入了哪些資料
    const select_city = document.getElementById("city");
    const select_area = document.getElementById("area");
    const select_sleep = document.getElementById("name");
    const select_time = document.getElementById("time");

    //監聽使用者選擇城市的事件，並傳入arr存放，監聽事件為"change"，即使用者改變了就傳回值，並呼叫showBk()顯示回傳的地圖資料
    select_city.addEventListener('change', (e) => {
        arr['select_city'] = e.target.value;
        showBk();
    }, false);
    //監聽使用者選擇區域的事件，並傳入arr存放，監聽事件為"change"，即使用者改變了就傳回值，並呼叫showBk()顯示回傳的地圖資料
    select_area.addEventListener('change', (e) => {
        arr['select_area'] = e.target.value;
        showBk();
    }, false);
    //監聽使用者選擇營業的事件，並傳入arr存放，監聽事件為"change"，即使用者改變了就傳回值，並呼叫showBk()顯示回傳的地圖資料
    select_sleep.addEventListener('change', (e) => {
        arr['select_sleep'] = e.target.value;
        showBk();
    }, false);
    //監聽使用者選擇時間的事件，並傳入arr存放，監聽事件為"change"，即使用者改變了就傳回值，並呼叫showBk()顯示回傳的地圖資料
    select_time.addEventListener('change', (e) => {
        arr['select_time'] = e.target.value;
        showBk();
    }, false);





      //此showBK()方法作用於下方的顯示書店名單與地圖資料之回傳，使用ajax與ajax/getpam.php傳遞資料
      function showBk() {
        //資料還沒回傳前給他一個轉圈圈的特效
        document.getElementById("answer").innerHTML = "<div class=\"d-flex align-items-center\"><strong>Loading...</strong><div class=\"spinner-border ms-auto\" role=\"status\" aria-hidden=\"true\"></div></div>";
        let city = arr['select_city'];
        let area = arr['select_area'];
        let sleep = arr['select_sleep'];
        let time = arr['select_time'];
        //建立xmlhttp請求，取得遠端xml文件
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          //當請求已完成，響應已就緒(readystate=4)，與網頁回傳OK時(status=200)
          if (this.readyState == 4 && this.status == 200) {
            //將回傳的json解碼成JS陣列
            let obj = JSON.parse(this.responseText);
            //呼叫showMap()將回傳的書店資訊obj['mapData']顯示於網頁地圖上
            showMap(obj['mapData']);
            //將書店資訊顯示於網頁下方的table中
            document.getElementById("answer").innerHTML = obj['html'];
          }
        };
        //準備發送請求至ajax/getmap.php，給予參數"城市(city)、區域(area)、營業(sleep)、時間(time)"
        xmlhttp.open("GET", "ajax/getmap.php?city=" + city + "&area=" + area + "&sleep=" + sleep + "&time=" + time, true);
        //發送請求
        xmlhttp.send();
      }

    //選告items變數，用於存放動態變數的名字
    var items = [];
    //此方法為將資料顯示於地圖上，需要的資料有經緯度、書店名稱及地址
    function showMap(array) {
      //註銷上一次請求顯示在地圖上的資訊
      disableMap(items);
      //用loop的方式依array裡的資訊建立動態變數及顯示地圖，"此forEach類似於python的for i in apple"
      array.forEach(cFunc);

      function cFunc(value, index) {
        //動態變數之名字放數items中做紀錄ex: items['marker0', 'marker1', 'marker2']
        items.push('marker' + index);
        //用this[]語法建立動態變數，此變數將用於建立地圖資訊(地圖的標籤顯示等等)
        this['marker' + index] = L.marker([value[2], value[3]]).addTo(map).bindPopup("<h2>" + value[0] + "</h2><p>" + value[1] + "</p>").openPopup();
      }
    }

    //此方法用於註銷已宣告的leaflet地圖物件
    function disableMap(arr){
      for(let i in arr){
        map.removeLayer(this[arr[i]]);
      }
    }


    </script>
  </body>
</html>