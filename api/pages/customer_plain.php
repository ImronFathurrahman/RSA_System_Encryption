<?php
// Same data as encrypted page
$customers = [
  ['id' => 1, 'kode' => 'C-00101', 'nama' => 'Budi Santoso', 'alamat' => 'Jl. Mawar No.12, Bekasi Barat', 'telepon' => '0741-XXXXX', 'saldo' => 1500000, 'status' => 'Aktif'],
  ['id' => 2, 'kode' => 'C-00102', 'nama' => 'Siti Rahayu', 'alamat' => 'Jl. Melati No.8, Bekasi Timur', 'telepon' => '0741-XXXXXX', 'saldo' => 2300000, 'status' => 'Aktif'],
  ['id' => 3, 'kode' => 'C-00103', 'nama' => 'Ahmad Fauzi', 'alamat' => 'Jl. Flamboyan Blok A5, Bekasi', 'telepon' => '0741-XXXXX', 'saldo' => 750000, 'status' => 'Aktif'],
  ['id' => 4, 'kode' => 'C-00104', 'nama' => 'Dewi Kusuma', 'alamat' => 'Perum. Harapan Indah B3/12', 'telepon' => '0741-XXXXX', 'saldo' => 5100000, 'status' => 'Aktif'],
  ['id' => 5, 'kode' => 'C-00105', 'nama' => 'Riko Pratama', 'alamat' => 'Jl. Anggrek No.3, Cikarang', 'telepon' => '0852XXXXXXXX', 'saldo' => 900000, 'status' => 'Non-Aktif'],
  ['id' => 6, 'kode' => 'C-00106', 'nama' => 'Nurul Hidayah', 'alamat' => 'Komplek Bumi Waras F-7', 'telepon' => '0741-XXXXX', 'saldo' => 3200000, 'status' => 'Aktif'],
  ['id' => 7, 'kode' => 'C-00107', 'nama' => 'Hendra Wijaya', 'alamat' => 'Jl. Sudirman No.45, Bekasi', 'telepon' => '0741-XXXXX', 'saldo' => 4600000, 'status' => 'Aktif'],
  ['id' => 8, 'kode' => 'C-00108', 'nama' => 'Rina Agustina', 'alamat' => 'Perum. Griya Asri D2/5', 'telepon' => '0852XXXXXXXX', 'saldo' => 1200000, 'status' => 'Aktif'],
];
?>

<div class="page-intro" style="background: linear-gradient(135deg, #D97706, #F59E0B);">
  <h2><i class="fa-solid fa-triangle-exclamation"></i> &nbsp;Data Customer — URL Tidak Terenkripsi (Rentan!)</h2>
  <p>Halaman ini menampilkan data customer tanpa enkripsi URL. Parameter GET langsung terbaca di browser, ini adalah celah keamanan yang diatasi oleh algoritma RSA dalam penelitian ini.</p>
</div>

<div class="alert alert-warning">
  <i class="fa-solid fa-triangle-exclamation"></i>
  <div>
    <strong>Peringatan Keamanan:</strong> URL pada halaman ini mengekspos parameter secara langsung.
    Contoh: <code style="background:rgba(217,119,6,0.1);padding:2px 6px;border-radius:4px">?page=customer_plain&amp;id=<strong>1</strong>&amp;action=<strong>edit</strong></code>
    — mudah dibaca, dimanipulasi, dan disalahgunakan!
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-title">
      <div class="icon icon-orange"><i class="fa-solid fa-table"></i></div>
      Daftar Data Customer (Parameter URL Tidak Terenkripsi)
    </div>
    <div style="display:flex;gap:8px;align-items:center">
      <span style="font-size:12px;color:var(--text3)"><?= count($customers) ?> records</span>
      <span class="encrypt-badge badge-plain"><i class="fa-solid fa-lock-open" style="font-size:9px"></i> Plain / Tidak Aman</span>
    </div>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Nama Customer</th>
          <th>Alamat</th>
          <th>Telepon</th>
          <th>Status</th>
          <th>URL Parameter (Plaintext)</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customers as $i => $c): ?>
        <tr style="animation-delay:<?= ($i*0.05) ?>s">
          <td class="td-id"><?= $i+1 ?></td>
          <td><code style="background:var(--bg);padding:3px 6px;border-radius:4px;font-size:11px"><?= $c['kode'] ?></code></td>
          <td class="td-name"><?= $c['nama'] ?></td>
          <td style="font-size:12px;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= $c['alamat'] ?></td>
          <td style="font-family:monospace;font-size:12px"><?= $c['telepon'] ?></td>
          <td>
            <span class="tag" style="background:<?= $c['status']==='Aktif'?'#D1FAE5;color:#065F46':'#FEE2E2;color:#991B1B' ?>">
              <i class="fa-solid fa-circle" style="font-size:6px"></i> <?= $c['status'] ?>
            </span>
          </td>
          <td>
            <!-- URL parameter langsung terbaca -->
            <div style="font-family:monospace;font-size:12px;color:#D97706;background:#FEF3C7;padding:4px 8px;border-radius:6px;display:inline-block">
              id=<?= $c['id'] ?>
            </div>
          </td>
          <td>
            <div style="display:flex;gap:6px">
              <a href="?page=customer_plain&id=<?= $c['id'] ?>&action=detail" class="btn btn-sm btn-outline" style="text-decoration:none">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="?page=customer_plain&id=<?= $c['id'] ?>&action=edit" class="btn btn-sm" style="background:#FEF3C7;color:#D97706;text-decoration:none;border:1px solid #FDE68A">
                <i class="fa-solid fa-pen"></i>
              </a>
              <a href="?page=customer_plain&id=<?= $c['id'] ?>&action=delete" class="btn btn-sm" style="background:#FEE2E2;color:#DC2626;text-decoration:none">
                <i class="fa-solid fa-trash"></i>
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Celah Keamanan -->
<?php if (isset($_GET['id']) && isset($_GET['action'])): ?>
<div class="card mt-6" style="border-color:#FDE68A">
  <div class="card-header" style="background:#FFFBEB">
    <div class="card-title">
      <div class="icon icon-orange"><i class="fa-solid fa-bug"></i></div>
      Demonstrasi Celah Keamanan
    </div>
  </div>
  <div class="card-body">
    <div class="alert alert-warning">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <div>Parameter URL ini dapat dilihat dan dimanipulasi langsung di browser address bar oleh siapapun!</div>
    </div>
    <div class="grid-2">
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">URL di Browser (Terlihat Jelas)</div>
        <div style="background:#FEF3C7;border:1px solid #FDE68A;border-radius:10px;padding:14px;font-family:monospace;font-size:13px;color:#92400E;word-break:break-all">
          ?page=customer_plain<br>
          &amp;<strong>id=<?= htmlspecialchars($_GET['id']) ?></strong><br>
          &amp;<strong>action=<?= htmlspecialchars($_GET['action']) ?></strong>
        </div>
      </div>
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">Risiko Manipulasi</div>
        <div style="font-size:13px;color:var(--text2);line-height:1.7">
          <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px">
            <i class="fa-solid fa-xmark" style="color:#DC2626;margin-top:3px;flex-shrink:0"></i>
            Penyerang dapat mengubah <code>id=<?= htmlspecialchars($_GET['id']) ?></code> menjadi ID lain untuk mengakses data customer berbeda
          </div>
          <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px">
            <i class="fa-solid fa-xmark" style="color:#DC2626;margin-top:3px;flex-shrink:0"></i>
            Mengubah <code>action=<?= htmlspecialchars($_GET['action']) ?></code> untuk memicu operasi yang tidak diizinkan
          </div>
          <div style="display:flex;align-items:flex-start;gap:8px">
            <i class="fa-solid fa-xmark" style="color:#DC2626;margin-top:3px;flex-shrink:0"></i>
            Informasi sensitif tersimpan dalam browser history dan server log
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Solusi -->
<div class="card mt-6">
  <div class="card-header">
    <div class="card-title">
      <div class="icon icon-green"><i class="fa-solid fa-lightbulb"></i></div>
      Solusi — Gunakan Enkripsi RSA
    </div>
  </div>
  <div class="card-body">
    <div style="display:flex;align-items:center;justify-content:space-between">
      <p style="font-size:13px;color:var(--text2);max-width:500px;line-height:1.7">
        Untuk mengamankan parameter URL seperti yang terlihat di atas, implementasikan enkripsi RSA agar parameter berubah menjadi ciphertext yang tidak dapat dibaca atau dimanipulasi oleh pihak tidak berwenang.
      </p>
      <div style="display:flex;gap:10px;flex-shrink:0">
        <a href="?page=customer_encrypted" class="btn btn-purple" style="text-decoration:none"><i class="fa-solid fa-lock"></i> Lihat Versi Aman</a>
        <a href="?page=encrypt" class="btn btn-primary" style="text-decoration:none"><i class="fa-solid fa-key"></i> Coba Enkripsi</a>
      </div>
    </div>
  </div>
</div>
