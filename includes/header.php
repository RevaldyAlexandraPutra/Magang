<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Manajemen Klinik</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- CSS Kustom -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ======= NAVBAR / HEADER ======= -->
<header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <strong class="text-primary">SIM Klinik</strong>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="modules/pendaftaran/pendaftaran.php">📝 Pendaftaran</a></li>
        <li class="nav-item"><a class="nav-link" href="rekam_form.php">⚕️ Rekam Medis</a></li>
        <li class="nav-item"><a class="nav-link" href="modules/farmasi/farmasi.php">💊 Apotek</a></li>
        <li class="nav-item"><a class="nav-link" href="modules/pembayaran/pembayaran.php">💰 Kasir</a></li>
        <li class="nav-item"><a class="nav-link" href="modules/notifikasi/index.php">📱 Notifikasi</a></li>
      </ul>
    </div>
  </div>
</header>

<main class="container py-5">
