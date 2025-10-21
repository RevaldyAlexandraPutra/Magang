<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Modul Farmasi / Apotek</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f9fc; }
    .container { max-width:800px; margin:50px auto; background:white; padding:20px; border-radius:12px; }
    h1 { text-align:center; color:#2b6777; }
    .menu { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; margin-top:30px; }
    .menu a { display:block; text-align:center; padding:20px; border-radius:10px; text-decoration:none; color:white; background:#2b6777; transition:.3s; }
    .menu a:hover { background:#1e4c57; }
    .back { display:block; margin-top:30px; text-align:center; }
    .back a { text-decoration:none; color:#2b6777; font-weight:bold; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Modul Farmasi / Apotek 💊</h1>
    <div class="menu">
      <a href="resep.php">📜 Resep Pasien</a>
      <a href="obat.php">💊 Data Obat</a>
      <a href="stok_update.php">📦 Update Stok</a>
      <a href="kirim_kasir.php">💰 Kirim ke Kasir</a>
    </div>
    <div class="back">
      <a href="../../index.php">⬅ Kembali ke Halaman Utama</a>
    </div>
  </div>
</body>
</html>
