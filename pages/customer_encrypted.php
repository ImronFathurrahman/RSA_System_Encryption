<?php
require_once __DIR__ . '/../rsa.php';

// Data dummy customer (nama disamarkan sesuai artikel)
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

<div class="page-intro" style="background: linear-gradient(135deg, #7C3AED, #2563EB);">
  <h2><i class="fa-solid fa-lock"></i> &nbsp;Data Customer — URL Terenkripsi RSA</h2>
  <p>Parameter URL pada halaman ini menggunakan enkripsi RSA. Setiap aksi edit/detail menghasilkan URL dengan parameter ciphertext, bukan ID asli yang mudah dibaca.</p>
</div>

<div class="alert alert-success" style="margin-bottom:20px">
  <i class="fa-solid fa-shield-check"></i>
  <div>
    <strong>Mode Aman:</strong> URL menggunakan parameter terenkripsi RSA.
    Contoh: <code style="background:rgba(16,185,129,0.1);padding:2px 6px;border-radius:4px">?page=customer_encrypted&amp;id=<?= RSA::encrypt('customer') ?>&amp;action=<?= RSA::encrypt('edit') ?></code>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-title">
      <div class="icon icon-purple"><i class="fa-solid fa-table"></i></div>
      Daftar Data Customer (Parameter URL Dienkripsi)
    </div>
    <div style="display:flex;gap:8px">
      <span style="font-size:12px;color:var(--text3);align-self:center"><?= count($customers) ?> records</span>
      <span class="encrypt-badge badge-encrypted"><i class="fa-solid fa-lock" style="font-size:9px"></i> Terenkripsi</span>
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
          <th>URL Parameter (Ciphertext)</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customers as $i => $c):
          $encId = RSA::encrypt((string)$c['id']);
          $encKode = RSA::encrypt($c['kode']);
          $encEdit = RSA::encrypt('edit');
          $encDetail = RSA::encrypt('detail');
          $encDelete = RSA::encrypt('delete');
          $delay = ($i * 0.05) . 's';
        ?>
        <tr style="animation-delay:<?= $delay ?>">
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
            <div class="td-cipher" title="id=<?= $c['id'] ?> → <?= $encId ?>">
              id=<?= $encId ?>
            </div>
          </td>
          <td>
            <div style="display:flex;gap:6px;flex-wrap:wrap">
              <a href="?page=customer_encrypted&id=<?= $encId ?>&action=<?= $encDetail ?>" class="btn btn-sm btn-outline" style="text-decoration:none" title="URL: id=<?= $encId ?>">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="?page=customer_encrypted&id=<?= $encId ?>&action=<?= $encEdit ?>" class="btn btn-sm btn-primary" style="text-decoration:none" title="URL: id=<?= $encId ?>">
                <i class="fa-solid fa-pen"></i>
              </a>
              <a href="?page=customer_encrypted&id=<?= $encId ?>&action=<?= $encDelete ?>" class="btn btn-sm" style="background:#FEE2E2;color:#DC2626;text-decoration:none" title="URL: id=<?= $encId ?>">
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

<!-- URL Inspector -->
<?php if (isset($_GET['id']) && isset($_GET['action'])): ?>
<div class="card mt-6">
  <div class="card-header">
    <div class="card-title">
      <div class="icon icon-green"><i class="fa-solid fa-magnifying-glass"></i></div>
      Inspector URL Parameter
    </div>
  </div>
  <div class="card-body">
    <div class="alert alert-info">
      <i class="fa-solid fa-circle-info"></i>
      <div>URL yang diterima server telah terenkripsi. Server melakukan dekripsi secara internal untuk memproses permintaan.</div>
    </div>
    <div class="grid-2">
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">Parameter Diterima (Ciphertext)</div>
        <div style="background:#1E3A8A;color:#fff;border-radius:10px;padding:14px;font-family:monospace;font-size:12px;word-break:break-all">
          id = <?= htmlspecialchars($_GET['id']) ?><br>
          action = <?= htmlspecialchars($_GET['action']) ?>
        </div>
      </div>
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">Setelah Dekripsi Server (Plaintext)</div>
        <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:10px;padding:14px;font-family:monospace;font-size:12px;color:#065F46;word-break:break-all">
          id = <?= RSA::decrypt(preg_replace('/[^0-9a-f]/i', '', $_GET['id'])) ?><br>
          action = <?= RSA::decrypt(preg_replace('/[^0-9a-f]/i', '', $_GET['action'])) ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Tabel perbandingan enkripsi ID -->
<div class="card mt-6">
  <div class="card-header">
    <div class="card-title">
      <div class="icon icon-cyan"><i class="fa-solid fa-arrows-left-right"></i></div>
      Tabel Enkripsi Parameter
    </div>
  </div>
  <div class="card-body">
    <table class="cipher-table">
      <thead>
        <tr>
          <th>Plaintext</th>
          <th>Ciphertext (Hex)</th>
          <th>Panjang</th>
          <th>URL Aman</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $samples = ['customer','edit','detail','delete','1','2','3','4','5'];
        foreach ($samples as $s):
          $enc = RSA::encrypt($s);
        ?>
        <tr>
          <td><strong><?= $s ?></strong></td>
          <td style="background:linear-gradient(90deg,#EDE9FE,#DBEAFE);font-family:monospace;letter-spacing:1px"><?= $enc ?></td>
          <td><?= strlen($enc) ?> chars</td>
          <td style="font-size:11px;color:var(--text3);max-width:200px;word-break:break-all">?id=<?= $enc ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
