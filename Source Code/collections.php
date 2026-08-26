<!doctype html>
<html lang="zh-Hant">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>物種分析平台</title>

  <!-- Bootstrap & DataTables CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />

  <!-- JS libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      background: linear-gradient(135deg, #d4edda, #e9f7ef);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #222;
    }
    .page-title {
      font-size: 2.5rem;
      font-weight: bold;
      color: #2c6b2f;
      margin-top: 2rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    .table-container {
      max-width: 1000px;
      margin: 0 auto;
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stats-cards {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }
    .card {
      border: none;
      background-color: #f0fdf4;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 1rem 2rem;
      border-radius: 10px;
      font-size: 1.2rem;
      min-width: 200px;
    }
    .card span {
      font-weight: bold;
      color: #2c6b2f;
    }
    .chart-container {
      max-width: 1000px;
      margin: 2rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .chart-label {
      text-align: center;
      font-size: 1.2rem;
      font-weight: bold;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <!-- 包含动态导航栏 -->
  <?php include 'navbar.html'; ?>

  <div class="page-title">物種數量分析平台</div>

  <!-- 卡片統計區 -->
  <div class="stats-cards" id="statsArea">
    <div class="card">🐾 總動物數：<span id="totalCount">0</span></div>
    <div class="card">📉 已絕種：<span id="extinctCount">0</span></div>
    <div class="card">🏞️ 高海拔動物：<span id="highAltitudeCount">0</span></div>
  </div>

  <!-- 表格區 -->
  <div class="table-container">
    <table id="animalTable" class="display" style="width:100%">
      <thead>
        <tr>
          <th>動物編號</th>
          <th>動物名稱</th>
          <th>動物類別</th>
          <th>棲息地</th>
          <th>海拔高度</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>動物編號</th>
          <th>動物名稱</th>
          <th>動物類別</th>
          <th>棲息地</th>
          <th>海拔高度</th>
        </tr>
      </tfoot>
    </table>
  </div>

  <!-- 圖表區 -->
  <div class="chart-container">
    <p class="chart-label">動物類別長條圖分析</p>
    <canvas id="typeChart" height="100"></canvas>
    <hr>

    <p class="chart-label">海拔等級長條圖分析</p>
    <canvas id="altitudeChart" height="100"></canvas>
  </div>

  <script>
    function updateStats(data) {
      let total = data.length;
      let extinct = data.filter(item => item.animal_type === '已絕種').length;
      let highAltitude = data.filter(item => item.altitude_level === '高').length;

      document.getElementById('totalCount').textContent = total;
      document.getElementById('extinctCount').textContent = extinct;
      document.getElementById('highAltitudeCount').textContent = highAltitude;
    }

    function drawCharts(data) {
      const typeCounts = { '珍貴稀有': 0, '瀕臨絕種': 0, '已絕種': 0 };
      const altitudeCounts = { '低': 0, '中': 0, '高': 0 };

      data.forEach(item => {
        typeCounts[item.animal_type]++;
        altitudeCounts[item.altitude_level]++;
      });

      new Chart(document.getElementById('typeChart'), {
        type: 'bar',
        data: {
          labels: Object.keys(typeCounts),
          datasets: [{
            label: '動物類別數量',
            data: Object.values(typeCounts),
            backgroundColor: ['#90be6d', '#f9c74f', '#f94144']
          }]
        }
      });

      new Chart(document.getElementById('altitudeChart'), {
        type: 'bar',
        data: {
          labels: Object.keys(altitudeCounts),
          datasets: [{
            label: '海拔等級數量',
            data: Object.values(altitudeCounts),
            backgroundColor: ['#43aa8b', '#577590', '#f3722c']
          }]
        }
      });
    }

    function loadTable(inputData) {
      updateStats(inputData);
      drawCharts(inputData);
      $('#animalTable').DataTable({
        data: inputData,
        columns: [
          { data: 'animal_id' },
          { data: 'animal_name' },
          { data: 'animal_type' },
          { data: 'habitat' },
          { data: 'altitude_level' }
        ],
        destroy: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese-traditional.json"
        }
      });
    }

    axios.get('action.php')
      .then(res => {
        loadTable(res.data);
      })
      .catch(error => {
        console.error("載入資料錯誤：", error);
      });
  </script>
</body>
</html>
