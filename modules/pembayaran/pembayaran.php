<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kasir / Pembayaran - Klinik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <style>
    body{background:#f7f9fc}
    .container{max-width:1100px}
    .table-fixed tbody {height:200px; overflow-y:auto; display:block}
    .table-fixed thead, .table-fixed tbody tr {display:table; width:100%; table-layout:fixed}
    .small-muted{font-size:0.85rem;color:#6b7280}
    .badge-green{background:#16a34a;color:white}
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
              <h4 id="total-display">Rp 0</h4>
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
              <option value="transfer">Transfer Bank</option>
              <option value="qris">QRIS</option>
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

  <script>
    const id = ()=> 'TRX-'+Date.now();
    const rupiah = (n)=> n.toLocaleString('id-ID');

    const itemsBody = document.getElementById('items-body');
    const totalDisplay = document.getElementById('total-display');
    const btnAdd = document.getElementById('add-row');
    const btnPay = document.getElementById('btn-pay');
    const btnPrint = document.getElementById('btn-print');
    const btnDownload = document.getElementById('btn-download');
    const btnWA = document.getElementById('btn-wa');
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

    btnAdd.addEventListener('click', ()=>{ items.push({desc:'', price:0, qty:1, type:'layanan'}); renderItems(); });

    btnPay.addEventListener('click', (e)=>{
      e.preventDefault();
      if(!document.getElementById('patient-name').value) return alert('Isi nama pasien');
      if(items.length===0) return alert('Tambah minimal 1 item');
      const trx = buildTransaction();
      trx.status = 'Lunas';
      saveTransaction(trx);
      currentTrxId = trx.id;
      statusEl.innerHTML = '<span class="badge bg-success">Lunas</span>';
      renderHistory();
      alert('Transaksi tersimpan (local). Kamu bisa mengunduh nota PDF atau kirim WhatsApp.');
    });

    btnPrint.addEventListener('click', ()=> window.print());

    btnDownload.addEventListener('click', async ()=>{
      if(!currentTrxId) {
        const temp = buildTransaction();
        await generatePDF(temp, true);
        return;
      }
      const trx = loadTransaction(currentTrxId);
      if(!trx) return alert('Transaksi tidak ditemukan di riwayat. Simpan dulu.');
      await generatePDF(trx, true);
    });

    btnWA.addEventListener('click', ()=>{
      const phone = document.getElementById('patient-phone').value.trim();
      if(!phone) return alert('Masukkan nomor telepon pasien terlebih dahulu');
      const total = calcTotal();
      const message = `Terima kasih ${document.getElementById('patient-name').value}. Pembayaran Anda sebesar Rp ${rupiah(total)} telah kami terima. ID Transaksi: ${currentTrxId || '—'}.`;
      const wa = `https://wa.me/${phone.replace(/\D/g,'')}?text=${encodeURIComponent(message)}`;
      window.open(wa, '_blank');
    });

    function buildTransaction(){
      const trx = {
        id: currentTrxId || id(),
        patient: {
          name: document.getElementById('patient-name').value,
          phone: document.getElementById('patient-phone').value,
          rm: document.getElementById('patient-rm').value,
        },
        date: document.getElementById('trx-date').value || new Date().toISOString().slice(0,10),
        items: JSON.parse(JSON.stringify(items)),
        method: document.getElementById('payment-method').value,
        note: document.getElementById('note').value,
        total: calcTotal(),
        status: 'Belum Lunas'
      };
      return trx;
    }

    function saveTransaction(trx){
      let db = JSON.parse(localStorage.getItem('kasir_db_v1')||'[]');
      const idx = db.findIndex(d=>d.id===trx.id);
      if(idx>=0) db[idx]=trx; else db.push(trx);
      localStorage.setItem('kasir_db_v1', JSON.stringify(db));
    }

    function loadTransaction(id){
      const db = JSON.parse(localStorage.getItem('kasir_db_v1')||'[]');
      return db.find(d=>d.id===id);
    }

    function renderHistory(){
      const db = JSON.parse(localStorage.getItem('kasir_db_v1')||'[]').reverse();
      historyEl.innerHTML = '';
      db.forEach(t=>{
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-start';
        li.innerHTML = `
          <div>
            <div class="fw-semibold">${t.patient.name}</div>
            <div class="small-muted">ID: ${t.id} · ${t.date}</div>
          </div>
          <div class="text-end">
            <div>${t.status==='Lunas'?'<span class="badge bg-success">Lunas</span>':'<span class="badge bg-warning text-dark">Belum Lunas</span>'}</div>
            <div class="fw-bold">Rp ${rupiah(t.total)}</div>
          </div>
        `;
        li.addEventListener('click', ()=> loadIntoForm(t));
        historyEl.appendChild(li);
      });
    }

function loadIntoForm(t){
  document.getElementById('patient-name').value = t.patient.name;
  document.getElementById('patient-phone').value = t.patient.phone;
  document.getElementById('patient-rm').value = t.patient.rm;
  document.getElementById('trx-date').value = t.date;
  document.getElementById('payment-method').value = t.method;
  document.getElementById('note').value = t.note;
  items = JSON.parse(JSON.stringify(t.items));
  currentTrxId = t.id;
  renderItems();
  calcTotal();
  statusEl.innerHTML = t.status === 'Lunas'
    ? '<span class="badge bg-success">Lunas</span>'
    : '<span class="badge bg-warning text-dark">Belum Lunas</span>';
}

    async function generatePDF(trx, download=true){
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF({unit:'pt', format:'a4'});
      const left = 40; let y = 40;
      doc.setFontSize(14); doc.text('KLINIK - NOTA PEMBAYARAN', left, y);
      y += 20;
      doc.setFontSize(10);
      doc.text(`ID: ${trx.id}`, left, y); doc.text(`Tanggal: ${trx.date}`, 420, y);
      y += 20;
      doc.text(`Nama: ${trx.patient.name}`, left, y);
      y += 20;
      doc.autoTable({
        startY: y,
        head: [['Deskripsi','Harga','Qty','Subtotal','Tipe']],
        body: trx.items.map(it=>[
          it.desc || '-', rupiah(it.price), String(it.qty), rupiah(it.price*it.qty), it.type
        ]),
        theme: 'grid',
        styles: { fontSize: 10 },
        headStyles: { fillColor: [220,220,220] }
      });

      y = doc.lastAutoTable ? doc.lastAutoTable.finalY + 20 : 300;
      doc.setFontSize(12);
      doc.text(`Total: Rp ${rupiah(trx.total)}`, left, y);
      y += 20;
      doc.setFontSize(9);
      doc.text(`Metode: ${trx.method}`, left, y);
      y += 14;
      if(trx.note) doc.text(`Catatan: ${trx.note}`, left, y);

      if(download){
        doc.save(`${trx.id}.pdf`);
      } else {

        const blob = doc.output('blob');
        const url = URL.createObjectURL(blob);
        window.open(url, '_blank');
      }
    }

    (function(){
      items.push({'desc':'Contoh Layanan','price':50000,'qty':1,'type':'layanan'});
      renderItems(); calcTotal(); renderHistory();
      document.getElementById('trx-date').value = new Date().toISOString().slice(0,10);

      document.getElementById('btn-new').addEventListener('click', ()=>{
        items = [];
        currentTrxId = null;
        renderItems(); calcTotal();
        document.getElementById('form-patient').reset();
        statusEl.innerHTML = '<span class="badge bg-warning text-dark">Belum Lunas</span>';
      });
    })();
    
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
