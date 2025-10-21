<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kasir / Pembayaran - Klinik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    :root {
      --soft-blue: #007aff;
      --gradient-bg: linear-gradient(160deg, #e0f2ff 0%, #f8f9ff 50%, #e8e0ff 100%);
      --card-bg: rgba(255, 255, 255, 0.75);
      --hover-bg: rgba(255, 255, 255, 0.6);
      --shadow: 0 10px 40px rgba(0,0,0,0.1);
      --radius: 20px;
    }

    body {
      background: var(--gradient-bg);
      font-family: 'Poppins', sans-serif;
      color: #2d3748;
      min-height: 100vh;
      backdrop-filter: blur(16px);
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 0;
      animation: fadeInBody 1s ease-out forwards;
    }

    @keyframes fadeInBody {
      from { opacity: 0; transform: scale(0.98); }
      to { opacity: 1; transform: scale(1); }
    }

    .container {
      max-width: 1100px;
    }

    h3, h6 {
      font-weight: 600;
      letter-spacing: 0.3px;
      color: #1e293b;
    }

    .card {
      background: var(--card-bg);
      border: 1px solid rgba(255,255,255,0.4);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(20px) saturate(160%);
      transition: all 0.4s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 45px rgba(0,0,0,0.15);
    }

    table {
      border-radius: var(--radius);
      overflow: hidden;
    }

    thead th {
      background: linear-gradient(90deg, #007aff, #4f46e5);
      color: white;
      font-weight: 500;
      border: none;
    }

    tbody tr {
      transition: all 0.3s ease;
    }

    tbody tr:hover {
      background: rgba(0,122,255,0.05);
      transform: scale(1.01);
    }

    input.form-control, textarea, select.form-select {
      border-radius: var(--radius);
      border: 1px solid #d1d5db;
      transition: all 0.25s ease;
      background: rgba(255,255,255,0.95);
    }

    input.form-control:focus, textarea:focus, select:focus {
      border-color: var(--soft-blue);
      box-shadow: 0 0 0 4px rgba(0,122,255,0.15);
      transform: scale(1.02);
    }


    input.item-price::-webkit-inner-spin-button,
    input.item-price::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input.item-price {
      -moz-appearance: textfield;
    }

    input.item-qty::-webkit-inner-spin-button,
    input.item-qty::-webkit-outer-spin-button {
      -webkit-appearance: inner-spin-button !important;
    }

    /* Buttons */
    .btn {
      border-radius: 14px;
      transition: all 0.25s ease-in-out;
    }

    .btn:hover {
      transform: translateY(-2px) scale(1.03);
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .btn:active {
      transform: scale(0.98);
    }

    .btn-primary {
      background: linear-gradient(135deg, #007aff, #4f46e5);
      border: none;
    }

    .btn-success {
      background: linear-gradient(135deg, #10b981, #16a34a);
      border: none;
    }

    .btn-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      border: none;
    }

    .small-muted {
      color: #64748b;
      font-size: 0.9rem;
    }

    @keyframes fadeSlide {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-section {
      animation: fadeSlide 0.6s ease forwards;
    }

    [data-aos] {
      opacity: 0;
      transition-property: transform, opacity;
    }
  </style>
</head>
<body>

  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Kasir / Pembayaran</h3>
      <div>
        <button id="btn-new" class="btn btn-outline-primary btn-sm">Transaksi Baru</button>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <form id="form-patient" class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Nama Pasien</label>
            <input id="patient-name" class="form-control" placeholder="Masukan Nama" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">No. Telepon</label>
            <input id="patient-phone" class="form-control" placeholder="Masukan N.Telepon" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Nomor Rekam Medis</label>
            <input id="patient-rm" class="form-control" placeholder="Masukan N. Rekam Medis" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Tanggal</label>
            <input id="" type="date" class="form-control">
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6>Rincian Layanan & Obat</h6>
              <button id="add-row" class="btn btn-sm btn-success">Tambah Item</button>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-sm align-middle" id="items-table">
                <thead class="table-light">
                  <tr>
                    <th style="width:36%">Deskripsi</th>
                    <th style="width:16%">Harga (Rp)</th>
                    <th style="width:12%">Qty</th>
                    <th style="width:16%">Subtotal (Rp)</th>
                    <th style="width:12%">Tipe</th>
                    <th style="width:8%">Aksi</th>
                  </tr>
                </thead>
                <tbody id="items-body">
                </tbody>
              </table>
            </div>

            <div class="text-end mt-3">
              <button id="clear-all" class="btn btn-danger btn-sm">Hapus Semua Item</button>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="small-muted">Status Pembayaran</div>
              <div id="payment-status"><span class="badge bg-warning text-dark">Belum Lunas</span></div>
            </div>

            <div class="text-end">
              <div class="small-muted">Total</div>
              <h4 id="total-display">Rp.0</h4>
            </div>
          </div>
        </div>

      </div>

      <div class="col-lg-4">
        <div class="card mb-3">
          <div class="card-body">
            <label class="form-label">Metode Pembayaran</label>
            <select id="payment-method" class="form-select mb-3">
              <option value="cash">Tunai (Cash)</option>
            </select>

            <label class="form-label">Catatan</label>
            <textarea id="note" class="form-control mb-3" rows="3" placeholder="Opsional..."></textarea>

            <div class="d-grid gap-2">
              <button id="btn-pay" class="btn btn-primary">Proses Pembayaran & Simpan</button>
              <button id="btn-print" class="btn btn-outline-secondary">Cetak Nota (Fisik)</button>
              <button id="btn-download" class="btn btn-outline-success">Download Nota (PDF)</button>
              <button id="btn-wa" class="btn btn-success">Kirim WhatsApp</button>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h6>Riwayat Transaksi (Local)</h6>
            <ul id="history" class="list-group list-group-flush small"></ul>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center p-4">
        <h5 id="modalMessage" class="mb-3">Yakin ingin menghapus semua item?</h5>
        <div class="d-flex justify-content-center gap-3">
          <button id="modalYes" class="btn btn-danger">Ya</button>
          <button id="modalNo" class="btn btn-secondary" data-bs-dismiss="modal">Selesai</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    const id = ()=> 'TRX-'+Date.now();
    const rupiah = (n)=> n.toLocaleString('id-ID');

    const itemsBody = document.getElementById('items-body');
    const totalDisplay = document.getElementById('total-display');
    const btnAdd = document.getElementById('add-row');
    const btnPay = document.getElementById('btn-pay');
    const historyEl = document.getElementById('history');
    const statusEl = document.getElementById('payment-status');
    let items = [];
    let currentTrxId = null;

    function renderItems(){
      itemsBody.innerHTML = '';
      items.forEach((it, idx)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><input class="form-control form-control-sm item-desc" value="${it.desc||''}"></td>
          <td><input type="number" min="0" class="form-control form-control-sm item-price" value="${it.price||0}"></td>
          <td><input type="number" min="1" class="form-control form-control-sm item-qty" value="${it.qty||1}"></td>
          <td class="align-middle subt">${rupiah((it.price||0)*(it.qty||1))}</td>
          <td>
            <select class="form-select form-select-sm item-type">
              <option value="layanan" ${it.type==='layanan'?'selected':''}>Layanan</option>
              <option value="obat" ${it.type==='obat'?'selected':''}>Obat</option>
            </select>
          </td>
          <td><button class="btn btn-sm btn-danger btn-del">Hapus</button></td>
        `;

        tr.querySelector('.item-price').addEventListener('input', onChange);
        tr.querySelector('.item-qty').addEventListener('input', onChange);
        tr.querySelector('.item-desc').addEventListener('input', onChange);
        tr.querySelector('.item-type').addEventListener('change', onChange);
        tr.querySelector('.btn-del').addEventListener('click', ()=>{ items.splice(idx,1); renderItems(); calcTotal(); });
        itemsBody.appendChild(tr);
      });
    }

    function onChange(){
      const rows = itemsBody.querySelectorAll('tr');
      rows.forEach((r, i)=>{
        const desc = r.querySelector('.item-desc').value;
        const price = Number(r.querySelector('.item-price').value||0);
        const qty = Number(r.querySelector('.item-qty').value||0);
        const type = r.querySelector('.item-type').value;
        items[i] = {desc, price, qty, type};
        r.querySelector('.subt').textContent = rupiah(price*qty);
      });
      calcTotal();
    }

    function calcTotal(){
      const total = items.reduce((s,i)=> s + ((i.price||0)*(i.qty||0)), 0);
      totalDisplay.textContent = 'Rp ' + rupiah(total);
      return total;
    }

    btnAdd.addEventListener('click', ()=>{
      items.push({desc:'', price:'', qty:'0'});
      renderItems();
      calcTotal();
    });
  </script>
</body>
</html>
