<!doctype html>
<html lang="zh-Hant">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>生態動物寶庫</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <style>
      #map { height: 360px; width: 100%; }
      body { background-color: #f0f9f0; }
      .form-select, .form-control { margin-bottom: 1rem; }
    </style>
  </head>
  <body>
    <?php include 'navbar.html'; ?>
    <div class="container py-4">
      <h2 class="text-success fw-bold mb-4">生態動物-篩選寶庫</h2>
      <div class="row">
        <div class="col-md-6">
          <form id="ecoFilter">
            <label for="region" class="form-label">選擇縣市</label>
            <select class="form-select" id="region">
              <option value="">全部</option>
              <option value="台北市">台北市</option>
              <option value="新北市">新北市</option>
              <option value="台中市">台中市</option>
              <option value="高雄市">高雄市</option>
              <option value="台南市">台南市</option>
              <option value="桃園市">桃園市</option>
            </select>

            <label for="status" class="form-label">保育等級</label>
            <select class="form-select" id="status">
              <option value="">全部</option>
              <option value="珍貴稀有">珍貴稀有</option>
              <option value="瀕臨絕種">瀕臨絕種</option>
              <option value="已絕種">已絕種</option>
            </select>

            <label for="speciesType" class="form-label">動物類型</label>
            <select class="form-select" id="speciesType">
              <option value="">全部</option>
              <option value="哺乳類">哺乳類</option>
              <option value="鳥類">鳥類</option>
              <option value="爬蟲類">爬蟲類</option>
              <option value="兩棲類">兩棲類</option>
              <option value="昆蟲類">昆蟲類</option>
            </select>

            <label for="altitudeLevel" class="form-label">海拔分類</label>
            <select class="form-select" id="altitudeLevel">
              <option value="">全部</option>
              <option value="低">低</option>
              <option value="中">中</option>
              <option value="高">高</option>
            </select>

            <button type="button" class="btn btn-success mt-2" id="searchBtn">查詢</button>
          </form>
        </div>

        <div class="col-md-6">
          <div id="map"></div>
        </div>
      </div>

      <table class="table table-striped table-bordered mt-4">
        <thead class="table-success">
          <tr>
            <th>#</th>
            <th>動物名稱</th>
            <th>保育等級</th>
            <th>海拔分類</th>
          </tr>
        </thead>
        <tbody id="resultTable">
          <tr><td colspan="4">請按下查詢以顯示結果</td></tr>
        </tbody>
      </table>
    </div>

   <script>
  const filterParams = {
    region: '',
    status: '',
    speciesType: '',
    altitudeLevel: ''
  };

  const map = L.map('map').setView([23.6978, 120.9605], 7);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap 貢獻者'
  }).addTo(map);

  let markers = [];

  function fetchData() {
    const { region, status, speciesType, altitudeLevel } = filterParams;
    document.getElementById("resultTable").innerHTML = '<tr><td colspan="4">載入中...</td></tr>';

    fetch(`ajax/getanimals.php?region=${region}&status=${status}&speciesType=${speciesType}&altitudeLevel=${altitudeLevel}`)
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          document.getElementById("resultTable").innerHTML = `<tr><td colspan="4">${data.error}</td></tr>`;
          return;
        }

        if (!data.mapData || data.mapData.length === 0) {
          document.getElementById("resultTable").innerHTML = `<tr><td colspan="4">查無資料</td></tr>`;
          showMap([]); // 清空地圖標記
          return;
        }

        showMap(data.mapData);
        document.getElementById("resultTable").innerHTML = data.html;
      })
      .catch(err => {
        console.error("錯誤：", err);
        document.getElementById("resultTable").innerHTML = '<tr><td colspan="4">資料讀取失敗</td></tr>';
      });
  }

  function showMap(locations) {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    locations.forEach((loc) => {
      const marker = L.marker([loc.lat, loc.lng]).addTo(map)
        .bindPopup(`<strong>${loc.name}</strong><br>海拔分類：${loc.altitude_level}`);
      markers.push(marker);
    });
  }

  document.getElementById("region").addEventListener("change", e => filterParams.region = e.target.value);
  document.getElementById("status").addEventListener("change", e => filterParams.status = e.target.value);
  document.getElementById("speciesType").addEventListener("change", e => filterParams.speciesType = e.target.value);
  document.getElementById("altitudeLevel").addEventListener("change", e => filterParams.altitudeLevel = e.target.value);
  document.getElementById("searchBtn").addEventListener("click", fetchData);
</script>
</body>
</html>