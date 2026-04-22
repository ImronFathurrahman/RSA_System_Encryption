<?php
require_once __DIR__ . '/../rsa.php';

$input  = '';
$result = null;
$steps  = null;
$error  = '';
$mode   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ciphertext'])) {
  $raw   = trim($_POST['ciphertext']);
  $clean = strtolower(preg_replace('/[^0-9a-fA-F]/', '', $raw));

  if ($clean === '') {
    $error = 'Masukkan ciphertext dalam format heksadesimal.';
  } elseif (strlen($clean) % 2 !== 0) {
    $error = 'Panjang ciphertext harus genap (setiap karakter = 2 atau 4 digit hex).';
  } else {
    // Detect mode
    $isFullMode = false;
    if (strlen($clean) % 4 === 0 && strlen($clean) >= 4) {
      $c = hexdec(substr($clean, 0, 4));
      $m = RSA::modPow($c, RSA::D, RSA::N);
      $isFullMode = ($m >= 32 && $m <= 126 && $c > 255);
    }

    $mode  = $isFullMode ? 'full' : 'article';
    $result = $isFullMode ? RSA::decryptFull($clean) : RSA::decrypt($clean);
    $steps  = RSA::decryptDetail($clean);
    $input  = $clean;
  }
}

// Pre-built examples
$examples = [
  '5a9cb05811aa6e4c'              => 'customer (artikel)',
  RSA::encryptFull('customer')    => 'customer (sistem)',
  RSA::encryptFull('edit')        => 'edit',
  RSA::encryptFull('detail')      => 'detail',
  RSA::encryptFull('delete')      => 'delete',
  RSA::encryptFull('1')           => '1',
];
?>

<div class="page-intro" style="background: linear-gradient(135deg, #7C3AED, #EC4899);">
  <h2><i class="fa-solid fa-unlock-keyhole"></i> &nbsp;Dekripsi URL — Algoritma RSA</h2>
  <p>Masukkan ciphertext URL (format hex), sistem mendekripsinya dengan kunci privat RSA (d=<?= RSA::D ?>, n=<?= RSA::N ?>) dan menampilkan langkah-langkah proses secara detail.</p>
</div>

<div class="grid-2" style="align-items:start;gap:28px">

  <!-- ─── Kolom Kiri ─── -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <!-- Form -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-purple"><i class="fa-solid fa-keyboard"></i></div>
          Input Ciphertext
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="?page=decrypt">
          <?php if ($error): ?>
          <div class="alert" style="background:#FEE2E2;border:1px solid #FECACA;color:#991B1B;margin-bottom:14px">
            <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?>
          </div>
          <?php endif; ?>

          <div class="form-group">
            <label class="form-label">Ciphertext (Format Heksadesimal)</label>
            <input type="text" name="ciphertext" class="form-input"
              style="font-family:monospace;letter-spacing:1px"
              placeholder="contoh: 5a9cb05811aa6e4c"
              value="<?= htmlspecialchars($input) ?>" maxlength="200" autocomplete="off">
            <div class="text-muted" style="margin-top:6px">
              Mendukung dua format:<br>
              <span style="color:#7C3AED">• <strong>2-char/blok</strong> — metode artikel (mod256)</span><br>
              <span style="color:#0891B2">• <strong>4-char/blok</strong> — metode sistem (RSA penuh, fully reversible)</span>
            </div>
          </div>

          <div style="margin-bottom:18px">
            <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Contoh Ciphertext</div>
            <div style="display:flex;flex-direction:column;gap:6px">
              <?php foreach ($examples as $cipher => $label): ?>
              <button type="button" class="btn btn-outline btn-sm" style="justify-content:flex-start;text-align:left"
                onclick="document.querySelector('[name=ciphertext]').value='<?= $cipher ?>'">
                <span style="font-family:monospace;font-size:11px;color:var(--accent2);min-width:180px;word-break:break-all"><?= $cipher ?></span>
                <span style="color:var(--text3);font-size:11px;margin-left:6px">← <?= $label ?></span>
              </button>
              <?php endforeach; ?>
            </div>
          </div>

          <button type="submit" class="btn btn-purple btn-lg" style="width:100%">
            <i class="fa-solid fa-unlock-keyhole"></i> Dekripsi Sekarang
          </button>
        </form>
      </div>
    </div>

    <!-- Params Dekripsi -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-purple"><i class="fa-solid fa-gear"></i></div>
          Parameter RSA untuk Dekripsi
        </div>
      </div>
      <div class="card-body">
        <div class="info-grid">
          <div class="info-item"><div class="label">n (modulus)</div><div class="value"><?= RSA::N ?></div></div>
          <div class="info-item"><div class="label">φ(n)</div><div class="value"><?= number_format(RSA::PHI) ?></div></div>
          <div class="info-item"><div class="label">e (kunci publik)</div><div class="value"><?= number_format(RSA::E) ?></div></div>
          <div class="info-item" style="border-color:#FECACA">
            <div class="label" style="color:#DC2626">d (kunci privat)</div>
            <div class="value" style="color:#DC2626"><?= RSA::D ?></div>
          </div>
        </div>
        <div class="formula-box" style="margin-top:14px">
          Rumus Dekripsi: &nbsp;<strong>M = C<sup>d</sup> mod n</strong><br>
          M = C<sup><?= RSA::D ?></sup> mod <?= RSA::N ?>
        </div>
        <div class="alert alert-info" style="margin-top:12px">
          <i class="fa-solid fa-circle-info"></i>
          <div>Kunci privat d=<?= RSA::D ?> diturunkan via Extended Euclidean:<br>
          <strong>d × e ≡ 1 (mod φ(n))</strong><br>
          <?= RSA::D ?> × <?= number_format(RSA::E) ?> mod <?= number_format(RSA::PHI) ?> = <strong><?= (RSA::D * RSA::E) % RSA::PHI ?></strong> ✓</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ─── Kolom Kanan ─── -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <?php if ($result !== null): ?>

    <!-- Hasil -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-green"><i class="fa-solid fa-check-circle"></i></div>
          Hasil Dekripsi
        </div>
        <span class="encrypt-badge" style="background:<?= $mode==='full'?'#CFFAFE;color:#0E7490':'#EDE9FE;color:#6D28D9' ?>">
          Mode: <?= $mode === 'full' ? 'RSA Penuh (4-char)' : 'Artikel (2-char)' ?>
        </span>
      </div>
      <div class="card-body">
        <div style="margin-bottom:12px">
          <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Ciphertext Input</div>
          <div class="cipher-result" style="font-size:12px;letter-spacing:1px;word-break:break-all"><?= htmlspecialchars($input) ?></div>
        </div>
        <div style="text-align:center;font-size:22px;color:var(--text3);margin:8px 0">↓</div>
        <div>
          <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Plaintext (Hasil Dekripsi)</div>
          <div style="background:#ECFDF5;border:2px solid #A7F3D0;border-radius:12px;padding:18px;font-family:'Montserrat',sans-serif;font-size:26px;font-weight:800;color:#065F46;text-align:center;letter-spacing:3px">
            <?= htmlspecialchars($result) ?>
          </div>
        </div>
        <div class="alert alert-success" style="margin-top:14px">
          <i class="fa-solid fa-check-circle"></i>
          <div>Ciphertext <strong>"<?= htmlspecialchars($input) ?>"</strong> berhasil didekripsi menjadi <strong>"<?= htmlspecialchars($result) ?>"</strong>. Server dapat memproses permintaan!</div>
        </div>
      </div>
    </div>

    <!-- Langkah-Langkah Dekripsi -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-purple"><i class="fa-solid fa-list-ol"></i></div>
          Langkah-Langkah Proses Dekripsi RSA
        </div>
      </div>
      <div class="card-body">
        <div class="steps-container">

          <!-- STEP 1 -->
          <div class="step-item" style="animation-delay:.05s">
            <div class="step-connector">
              <div class="step-num" style="background:#EDE9FE;color:#7C3AED">1</div>
            </div>
            <div class="step-content">
              <h4>Pembangkitan Kunci Privat (d) via Extended Euclidean</h4>
              <p>Kunci privat d dihitung dari persamaan: <strong>d × e ≡ 1 (mod φ(n))</strong></p>
              <div class="formula-box">
                Diketahui: e = <?= number_format(RSA::E) ?>, φ(n) = <?= number_format(RSA::PHI) ?><br>
                Cari d: d × <?= number_format(RSA::E) ?> ≡ 1 (mod <?= number_format(RSA::PHI) ?>)<br>
                Hasil Extended Euclidean → d = <strong><?= RSA::D ?></strong>
              </div>
              <div class="result-box">
                Verifikasi: <?= RSA::D ?> × <?= number_format(RSA::E) ?> mod <?= number_format(RSA::PHI) ?> = <strong><?= (RSA::D * RSA::E) % RSA::PHI ?></strong> ✓<br>
                Kunci Privat (d, n) = (<strong><?= RSA::D ?></strong>, <strong><?= RSA::N ?></strong>)
              </div>
            </div>
          </div>

          <!-- STEP 2 -->
          <div class="step-item" style="animation-delay:.10s">
            <div class="step-connector">
              <div class="step-num" style="background:#FCE7F3;color:#BE185D">2</div>
            </div>
            <div class="step-content">
              <h4>Penerimaan Ciphertext dari URL</h4>
              <p>Server menerima parameter URL terenkripsi dari browser:</p>
              <div style="background:#1E3A8A;color:#fff;border-radius:8px;padding:10px 14px;font-family:monospace;font-size:12px;word-break:break-all;margin-top:8px">
                GET ?id=<?= htmlspecialchars($input) ?>
              </div>
              <p style="margin-top:8px;font-size:12px;color:var(--text3)">
                Pengguna di browser hanya melihat string hex ini — tidak mengetahui nilai plaintext aslinya.
              </p>
            </div>
          </div>

          <!-- STEP 3 -->
          <div class="step-item" style="animation-delay:.15s">
            <div class="step-connector">
              <div class="step-num" style="background:#FEF3C7;color:#D97706">3</div>
            </div>
            <div class="step-content">
              <h4>Pemisahan Ciphertext menjadi Blok</h4>
              <p>Ciphertext dipotong per <strong><?= $mode==='full'?'4':'2' ?> karakter</strong> (satu blok = satu karakter asli):</p>
              <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px">
                <?php foreach ($steps as $i => $s): ?>
                <div style="background:<?= $mode==='full'?'#CFFAFE;border-color:#A5F3FC;color:#0E7490':'#EDE9FE;border-color:#DDD6FE;color:#7C3AED' ?>;border:1px solid;border-radius:8px;padding:6px 12px;font-family:monospace;font-weight:700;font-size:13px">
                  Blok <?= $i+1 ?>: <strong><?= $s['hex'] ?></strong>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- STEP 4 -->
          <div class="step-item" style="animation-delay:.20s">
            <div class="step-connector">
              <div class="step-num" style="background:#DBEAFE;color:var(--primary)">4</div>
            </div>
            <div class="step-content">
              <h4>Konversi Hex → Desimal</h4>
              <p>Setiap blok hex dikonversi ke nilai desimal untuk diproses persamaan RSA:</p>
              <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px">
                <?php foreach ($steps as $s): ?>
                <div style="background:#DBEAFE;border:1px solid #BFDBFE;border-radius:8px;padding:6px 12px;font-family:monospace;font-size:12px;color:#1D4ED8">
                  0x<?= $s['hex'] ?> → <strong><?= $s['decimal'] ?></strong>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- STEP 5 -->
          <div class="step-item" style="animation-delay:.25s">
            <div class="step-connector">
              <div class="step-num" style="background:#D1FAE5;color:#059669">5</div>
            </div>
            <div class="step-content">
              <h4>Eksekusi Persamaan Dekripsi RSA</h4>
              <?php if ($mode === 'full'): ?>
              <p>Setiap blok didekripsi langsung: <strong>M = C<sup>d</sup> mod n = C<sup><?= RSA::D ?></sup> mod <?= RSA::N ?></strong></p>
              <?php else: ?>
              <p>Karena enkripsi artikel menggunakan mod 256, dekripsi dilakukan dengan <strong>reverse lookup</strong>: mencari nilai ASCII m (32–127) sehingga <strong>(m<sup>e</sup> mod n) mod 256 = nilai desimal blok</strong>.</p>
              <div class="formula-box" style="margin-top:8px">Cari m: (m<sup><?= number_format(RSA::E) ?></sup> mod <?= RSA::N ?>) mod 256 = nilai_blok</div>
              <?php endif; ?>
              <div style="overflow-x:auto;margin-top:12px">
                <table class="cipher-table">
                  <thead>
                    <tr>
                      <th>Blok Hex</th>
                      <th>Desimal (C)</th>
                      <?php if ($mode==='full'): ?>
                      <th>C<sup><?= RSA::D ?></sup> mod <?= RSA::N ?></th>
                      <?php else: ?>
                      <th>Cipher Asli (c<sub>i</sub>)</th>
                      <?php endif; ?>
                      <th>ASCII (m)</th>
                      <th>Karakter</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($steps as $s): ?>
                    <tr>
                      <td style="background:<?= $mode==='full'?'#CFFAFE;color:#0E7490':'#EDE9FE;color:#7C3AED' ?>;font-weight:700"><?= $s['hex'] ?></td>
                      <td><?= $s['decimal'] ?></td>
                      <td style="color:var(--primary)"><?= $s['cipher'] ?></td>
                      <td><?= $s['ascii'] ?></td>
                      <td style="font-size:20px;font-weight:800;color:var(--primary)"><?= htmlspecialchars($s['char']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- STEP 6 -->
          <div class="step-item" style="animation-delay:.30s">
            <div class="step-connector">
              <div class="step-num" style="background:#ECFDF5;color:#059669">6</div>
            </div>
            <div class="step-content">
              <h4>Penggabungan — Plaintext Dipulihkan</h4>
              <p>Seluruh karakter hasil dekripsi digabungkan membentuk plaintext asli:</p>
              <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;margin:10px 0">
                <?php foreach ($steps as $i => $s): ?>
                <span style="background:#ECFDF5;border:1px solid #A7F3D0;padding:6px 12px;border-radius:8px;font-family:'Montserrat',sans-serif;font-weight:700;font-size:18px;color:#065F46"><?= htmlspecialchars($s['char']) ?></span>
                <?php if ($i < count($steps)-1): ?><span style="color:var(--text3)">+</span><?php endif; ?>
                <?php endforeach; ?>
                <span style="color:var(--text3);font-size:16px"> =</span>
              </div>
              <div style="background:#ECFDF5;border:2px solid #A7F3D0;border-radius:12px;padding:16px 20px;font-family:'Montserrat',sans-serif;font-size:26px;font-weight:800;color:#065F46;text-align:center;letter-spacing:3px">
                <?= htmlspecialchars($result) ?>
              </div>
            </div>
          </div>

          <!-- STEP 7 -->
          <div class="step-item" style="animation-delay:.35s">
            <div class="step-connector">
              <div class="step-num" style="background:#DBEAFE;color:var(--primary)">7</div>
            </div>
            <div class="step-content">
              <h4>Eksekusi Query Database Server-Side</h4>
              <p>Setelah dekripsi internal, server dapat mengeksekusi operasi database menggunakan plaintext yang telah dipulihkan — tanpa pernah mengeksposnya di browser.</p>
              <div class="formula-box">
                // PHP server-side<br>
                $plain = RSA_Decrypt($url_param); // → "<?= htmlspecialchars($result) ?>"<br>
                $query = "SELECT * FROM customer WHERE menu = '$plain'";<br>
                // URL browser tetap: ?id=<?= htmlspecialchars(substr($input,0,20)) ?>...
              </div>
              <div class="alert alert-success" style="margin-top:10px">
                <i class="fa-solid fa-shield-check"></i>
                <div>Enkripsi URL dengan RSA memastikan data sensitif tidak pernah terekspos di sisi client. Hanya pemilik kunci privat (server) yang dapat memulihkan plaintext.</div>
              </div>
            </div>
          </div>

        </div><!-- /steps -->
      </div>
    </div>

    <?php else: ?>
    <!-- Placeholder -->
    <div class="card">
      <div class="card-body" style="text-align:center;padding:56px 24px">
        <div style="width:76px;height:76px;background:var(--bg2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:30px;color:var(--text3);margin:0 auto 18px;animation:logoFloat 3s ease-in-out infinite">
          <i class="fa-solid fa-unlock-keyhole"></i>
        </div>
        <div style="font-size:15px;font-weight:700;color:var(--text2);margin-bottom:8px">Masukkan Ciphertext untuk Didekripsi</div>
        <div style="font-size:13px;color:var(--text3);max-width:340px;margin:0 auto;line-height:1.7">
          Langkah-langkah dekripsi RSA ditampilkan detail: pembangkitan kunci privat, pemisahan blok hex, persamaan dekripsi, hingga pemulihan plaintext.
        </div>
        <div class="formula-box" style="margin-top:22px;text-align:left;max-width:340px;margin-left:auto;margin-right:auto">
          M = C<sup>d</sup> mod n<br>
          M = C<sup><?= RSA::D ?></sup> mod <?= RSA::N ?><br><br>
          "5a9cb05811aa6e4c" → <strong>customer</strong>
        </div>
      </div>
    </div>

    <!-- Alur Server-Side -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-cyan"><i class="fa-solid fa-diagram-project"></i></div>
          Alur Kerja Dekripsi di Sisi Server (PHP)
        </div>
      </div>
      <div class="card-body">
        <?php
        $flow = [
          ['<i class="fa-solid fa-globe"></i>', 'Browser kirim request', 'URL: ?id=5a9cb05811aa6e4c', '#D97706'],
          ['<i class="fa-solid fa-server"></i>', 'Server terima parameter', 'GET $_GET["id"] = "5a9cb05811aa6e4c"', '#2563EB'],
          ['<i class="fa-solid fa-key"></i>', 'Pisahkan blok hex', 'str_split("5a9c...", 2) → ["5a","9c",...]', '#7C3AED'],
          ['<i class="fa-solid fa-calculator"></i>', 'Konversi & reverse lookup', 'Cari m: (m^e mod n) mod 256 = decimal', '#BE185D'],
          ['<i class="fa-solid fa-check"></i>', 'Plaintext dipulihkan', '$plain = "customer"', '#059669'],
          ['<i class="fa-solid fa-database"></i>', 'Query database', 'SELECT * FROM ... WHERE page = "customer"', '#0891B2'],
        ];
        foreach ($flow as $i => $f):
        ?>
        <div style="display:flex;gap:12px;margin-bottom:10px;animation:fadeInUp .4s ease both;animation-delay:<?= $i*.05 ?>s">
          <div style="width:32px;height:32px;border-radius:50%;background:var(--bg);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:11px;color:<?= $f[3] ?>;flex-shrink:0"><?= $f[0] ?></div>
          <div style="flex:1;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:10px 14px">
            <div style="font-size:12px;font-weight:700;color:var(--text)"><?= $f[1] ?></div>
            <div style="font-size:11px;color:var(--text3);font-family:monospace;margin-top:2px"><?= $f[2] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
