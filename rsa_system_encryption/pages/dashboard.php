<?php require_once __DIR__ . '/../rsa.php'; ?>

<!-- Page Intro -->
<div class="page-intro">
  <h2><i class="fa-solid fa-shield-halved"></i> &nbsp;Sistem Kriptografi RSA — URL Encryption</h2>
  <p>Penerapan Algoritma Rivest-Shamir-Adleman pada enkripsi parameter URL website. Sistem ini dibangun berdasarkan penelitian dari Program Studi Teknik Informatika, Universitas Bina Insani Bekasi, 2026.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
  <div class="stat-card blue">
    <div class="stat-icon icon-blue"><i class="fa-solid fa-users"></i></div>
    <div class="stat-value">1.964</div>
    <div class="stat-label">Total Customer Aktif</div>
    <span class="stat-change up"><i class="fa-solid fa-arrow-trend-up"></i> Data tersimpan</span>
  </div>
  <div class="stat-card cyan">
    <div class="stat-icon icon-cyan"><i class="fa-solid fa-database"></i></div>
    <div class="stat-value">9.820</div>
    <div class="stat-label">Informasi Pribadi</div>
    <span class="stat-change info"><i class="fa-solid fa-circle-info"></i> Dilindungi RSA</span>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon icon-purple"><i class="fa-solid fa-key"></i></div>
    <div class="stat-value">6.719</div>
    <div class="stat-label">Kunci Publik Valid</div>
    <span class="stat-change info"><i class="fa-solid fa-lock"></i> p=151, q=173</span>
  </div>
  <div class="stat-card green">
    <div class="stat-icon icon-green"><i class="fa-solid fa-shield-check"></i></div>
    <div class="stat-value">100%</div>
    <div class="stat-label">URL Terenkripsi</div>
    <span class="stat-change up"><i class="fa-solid fa-check"></i> RSA aktif</span>
  </div>
</div>

<div class="grid-2">
  <!-- RSA Key Info -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="icon icon-blue"><i class="fa-solid fa-key"></i></div>
        Parameter Kunci RSA
      </div>
      <span class="tag" style="background:#DBEAFE;color:var(--primary)">Aktif</span>
    </div>
    <div class="card-body">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <?php
        $params = [
          ['Bilangan Prima p', RSA::P, 'icon-blue'],
          ['Bilangan Prima q', RSA::Q, 'icon-cyan'],
          ['Modulus n = p×q', RSA::N, 'icon-purple'],
          ['Fungsi Totient φ(n)', RSA::PHI, 'icon-orange'],
          ['Kunci Publik (e)', RSA::E, 'icon-green'],
          ['Kunci Privat (d)', RSA::D, 'icon-red'],
        ];
        foreach ($params as $p): ?>
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px">
          <div style="font-size:10px;color:var(--text3);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px"><?= $p[0] ?></div>
          <div style="font-family:monospace;font-size:18px;font-weight:800;color:var(--primary)"><?= number_format($p[1]) ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="alert alert-info mt-4">
        <i class="fa-solid fa-circle-info"></i>
        <div>Kunci publik (e,n) = <strong>(16397, 26123)</strong> &nbsp;|&nbsp; Kunci privat (d,n) = <strong>(6533, 26123)</strong></div>
      </div>
    </div>
  </div>

  <!-- Demo Enkripsi -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="icon icon-purple"><i class="fa-solid fa-flask"></i></div>
        Demo Enkripsi "customer"
      </div>
    </div>
    <div class="card-body">
      <?php
      $demo = RSA::encryptDetail('customer');
      $cipher = RSA::encrypt('customer');
      ?>
      <div class="ascii-table">
        <?php foreach ($demo as $d): ?>
        <div class="ascii-item">
          <div class="ascii-char"><?= $d['char'] ?></div>
          <div class="ascii-val">ASCII: <?= $d['ascii'] ?></div>
          <div class="ascii-val" style="color:var(--accent2)">→ <?= $d['hex'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top:16px">
        <div style="font-size:11px;color:var(--text3);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">Hasil Ciphertext URL</div>
        <div class="cipher-result"><?= $cipher ?></div>
      </div>
      <div style="display:flex;gap:10px;margin-top:14px">
        <a href="?page=encrypt" class="btn btn-primary btn-sm" style="text-decoration:none"><i class="fa-solid fa-key"></i> Coba Enkripsi</a>
        <a href="?page=decrypt" class="btn btn-purple btn-sm" style="text-decoration:none"><i class="fa-solid fa-unlock-keyhole"></i> Coba Dekripsi</a>
      </div>
    </div>
  </div>
</div>

<!-- Perbandingan URL -->
<div class="card mt-6">
  <div class="card-header">
    <div class="card-title">
      <div class="icon icon-orange"><i class="fa-solid fa-code-compare"></i></div>
      Perbandingan URL — Sebelum vs Sesudah Enkripsi
    </div>
  </div>
  <div class="card-body">
    <div class="grid-2">
      <div>
        <div class="alert alert-warning">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <div><strong>Tanpa Enkripsi (Rentan)</strong><br>Parameter langsung terbaca di browser</div>
        </div>
        <div style="background:#FEF3C7;border:1px solid #FDE68A;border-radius:10px;padding:14px;font-family:monospace;font-size:13px;color:#92400E;word-break:break-all">
          localhost/med.php?<strong>id=customer</strong>
        </div>
      </div>
      <div>
        <div class="alert alert-success">
          <i class="fa-solid fa-shield-check"></i>
          <div><strong>Dengan Enkripsi RSA (Aman)</strong><br>Parameter diubah menjadi ciphertext</div>
        </div>
        <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:10px;padding:14px;font-family:monospace;font-size:13px;color:#065F46;word-break:break-all">
          localhost/med.php?<strong>id=5a9cb05811aa6e4c</strong>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- About Research -->
<div class="card mt-6">
  <div class="card-header">
    <div class="card-title">
      <div class="icon icon-cyan"><i class="fa-solid fa-book-open"></i></div>
      Tentang Penelitian
    </div>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
      <div>
        <p style="font-size:13.5px;color:var(--text2);line-height:1.8">
          Penelitian ini mengimplementasikan algoritma <strong>Rivest-Shamir-Adleman (RSA)</strong> pada enkripsi parameter URL website guna meningkatkan keamanan data. Latar belakang penelitian adalah celah keamanan pada metode GET dimana parameter dikirimkan secara transparan pada URL, membuka potensi pencurian dan manipulasi data.
        </p>
        <p style="font-size:13.5px;color:var(--text2);line-height:1.8;margin-top:10px">
          Hasil penelitian membuktikan bahwa parameter URL yang semula dapat dibaca secara langsung berhasil diubah menjadi ciphertext yang tidak mudah dipahami oleh pihak tidak berwenang.
        </p>
      </div>
      <div>
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:16px">
          <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px">Tim Peneliti</div>
          <?php
          $authors = [
            ['Aulia Naufal Nurhaqiqi', '2023310062'],
            ['Imron Fathurrahman', '2023310116'],
            ['Agung Prasetyo', '2023310086'],
          ];
          foreach ($authors as $a): ?>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0"><?= $a[0][0] ?></div>
            <div>
              <div style="font-size:12px;font-weight:600;color:var(--text)"><?= $a[0] ?></div>
              <div style="font-size:10px;color:var(--text3);font-family:monospace"><?= $a[1] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
          <div style="font-size:10px;color:var(--text3);margin-top:8px;padding-top:8px;border-top:1px solid var(--border)">
            <i class="fa-solid fa-university"></i> Universitas Bina Insani — Bekasi, 2026
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
