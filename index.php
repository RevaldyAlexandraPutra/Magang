<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
=======
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Manajemen Klinik</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      display: flex;
      height: 100vh;
    }
    .sidebar {
      width: 200px;
      background-color: #2c3e50;
      color: white;
      padding: 20px;
    }
    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 20px;
    }
    .sidebar a {
      color: white;
      display: block;
      margin: 10px 0;
      text-decoration: none;
    }
    .main {
      flex-grow: 1;
      padding: 20px;
      background-color: #ecf0f1;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    table, th, td {
      border: 1px solid #bdc3c7;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    form input, form select {
      padding: 8px;
      margin: 5px 0;
      width: 100%;
      box-sizing: border-box;
    }
    form button {
      margin-top: 10px;
      padding: 10px 15px;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Klinik Sehat</h2>
    <a href="#">Dashboard</a>
    <a href="#">Data Pasien</a>
    <a href="#">Tambah Pasien</a>
    <a href="#">Dokter</a>
    <a href="#">Logout</a>
  </div>

  <div class="main">
    <h1>Dashboard</h1>

    <section>
      <h2>Data Pasien</h2>
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Usia</th>
            <th>Jenis Kelamin</th>
            <th>Keluhan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Andi</td>
            <td>32</td>
            <td>Laki-laki</td>
            <td>Demam</td>
            <td><button>Edit</button> <button>Hapus</button></td>
          </tr>
          <!-- Data lainnya -->
        </tbody>
      </table>
    </section>

    <section>
      <h2>Tambah Pasien Baru</h2>
      <form>
        <label>Nama:</label>
        <input type="text" name="nama" required>

        <label>Usia:</label>
        <input type="number" name="usia" min="0" max="20" required>

        <label>Jenis Kelamin:</label>
        <select name="gender">
          <option value="L">Laki-laki</option>
          <option value="P">Perempuan</option>
        </select>

        <label>Keluhan:</label>
        <input type="text" name="keluhan" required>

        <button type="submit">Simpan</button>
      </form>
    </section>
  </div>

</body>
</html>
>>>>>>> 8e1b7b3bf703a6343e12a64ef8a9a7616e08969f
