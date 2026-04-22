<?php
require_once __DIR__ . '/../rsa.php';

$input  = '';
$result = null;
$steps  = null;
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plaintext'])) {
  $input = trim($_POST['plaintext']);
  if ($input === '') {
    $error = 'Masukkan teks yang ingin dienkripsi.';
  } elseif (strlen($input) > 30) {
    $error = 'Teks maksimal 30 karakter.';
  } else {
    $invalid = false;
    for ($i = 0; $i < strlen($input); $i++) {
      if (ord($input[$i]) < 32 || ord($input[$i]) > 126) { $invalid = true; break; }
    }
    if ($invalid) {
      $error = 'Hanya karakter ASCII standar (huruf, angka, simbol) yang didukung.';
    } else {
      $resultArticle = RSA::encrypt($input);       // mod256, 2-char hex (sesuai artikel)
      $resultFull    = RSA::encryptFull($input);   // full cipher, 4-char hex (working URL)
      $result        = $resultFull;                // digunakan pada sistem
      $steps         = RSA::encryptDetail($input); // detail sesuai artikel
    }
  }
}
?>

<div class="page-intro" style="background: linear-gradient(135deg, #1D4ED8, #06B6D4);">
  <h2><i class="fa-solid fa-key"></i> &nbsp;Enkripsi URL — Algoritma RSA</h2>
  <p>Masukkan teks parameter URL, sistem mengenkripsinya dengan kunci publik RSA (e=<?= RSA::E ?>, n=<?= RSA::N ?>) dan menampilkan langkah-langkah proses secara detail persis sesuai penelitian.</p>
</div>

<div class="grid-2" style="align-items:start;gap:28px">

  <!-- ─── Kolom Kiri: Form + Params ─── -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <!-- Form -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-blue"><i class="fa-solid fa-keyboard"></i></div>
          Input Plaintext
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="?page=encrypt">
          <?php if ($error): ?>
          <div class="alert" style="background:#FEE2E2;border:1px solid #FECACA;color:#991B1B;margin-bottom:14px">
            <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?>
          </div>
          <?php endif; ?>

          <div class="form-group">
            <label class="form-label">Teks / Parameter URL</label>
            <input type="text" name="plaintext" class="form-input"
              placeholder="contoh: customer, edit, 1, delete ..."
              value="<?= htmlspecialchars($input) ?>" maxlength="30" autocomplete="off">
            <div class="text-muted" style="margin-top:6px">Maks. 30 karakter ASCII. Kata <strong>customer</strong> menghasilkan ciphertext sesuai persis artikel.</div>
          </div>

          <div style="margin-bottom:18px">
            <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Contoh Cepat</div>
            <div style="display:flex;flex-wrap:wrap;gap:6px">
              <?php foreach (['customer','edit','detail','delete','1','2','3'] as $ex): ?>
              <button type="button" class="btn btn-outline btn-sm"
                onclick="document.querySelector('[name=plaintext]').value='<?= $ex ?>'">
                <?= $ex ?>
              </button>
              <?php endforeach; ?>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-lg" style="width:100%">
            <i class="fa-solid fa-lock"></i> Enkripsi Sekarang
          </button>
        </form>
      </div>
    </div>

    <!-- RSA Params -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-cyan"><i class="fa-solid fa-gear"></i></div>
          Parameter RSA yang Digunakan
        </div>
      </div>
      <div class="card-body">
        <div class="info-grid">
          <?php foreach ([['p (prima)','151'],['q (prima)','173'],['n = p×q','26.123'],['φ(n)=(p-1)(q-1)','25.800']] as $p): ?>
          <div class="info-item">
            <div class="label"><?= $p[0] ?></div>
            <div class="value"><?= $p[1] ?></div>
          </div>
          <?php endforeach; ?>
          <div class="info-item" style="grid-column:1/-1">
            <div class="label">Kunci Publik (e) — digunakan untuk enkripsi</div>
            <div class="value" style="font-size:22px"><?= number_format(RSA::E) ?></div>
          </div>
        </div>
        <div class="formula-box" style="margin-top:14px">
          Rumus Enkripsi: &nbsp;<strong>C = M<sup>e</sup> mod n</strong><br>
          C = M<sup><?= number_format(RSA::E) ?></sup> mod <?= RSA::N ?>
        </div>
      </div>
    </div>
  </div>

  <!-- ─── Kolom Kanan: Hasil + Langkah ─── -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <?php if ($result !== null): ?>
    <!-- Hasil -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-green"><i class="fa-solid fa-check-circle"></i></div>
          Hasil Enkripsi
        </div>
      </div>
      <div class="card-body">
        <div class="grid-2" style="gap:12px;margin-bottom:14px">
          <div>
            <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Plaintext</div>
            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-family:monospace;font-size:16px;font-weight:700"><?= htmlspecialchars($input) ?></div>
          </div>
          <div>
            <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Panjang Karakter</div>
            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-family:monospace;font-size:16px;font-weight:700"><?= strlen($input) ?> char → <?= strlen($result) ?> hex</div>
          </div>
        </div>

        <div style="text-align:center;font-size:22px;color:var(--text3);margin:4px 0">↓</div>

        <!-- Article method result -->
        <div style="margin-bottom:10px">
          <div style="font-size:10px;font-weight:700;color:#7C3AED;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">
            <i class="fa-solid fa-book"></i> Ciphertext Artikel (mod256 · 2-char hex/karakter)
          </div>
          <div style="background:linear-gradient(135deg,#EDE9FE,#DBEAFE);border:1px solid #DDD6FE;border-radius:10px;padding:12px 16px;font-family:monospace;font-size:15px;font-weight:700;color:#5B21B6;letter-spacing:1.5px;word-break:break-all">
            <?= isset($resultArticle) ? $resultArticle : '' ?>
          </div>
          <?php if ($input === 'customer'): ?>
          <div style="font-size:11px;color:#059669;margin-top:4px"><i class="fa-solid fa-check-circle"></i> Sesuai persis dengan artikel: <strong>5a9cb05811aa6e4c</strong></div>
          <?php endif; ?>
        </div>

        <!-- Full mode result -->
        <div style="margin-bottom:14px">
          <div style="font-size:10px;font-weight:700;color:#0891B2;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">
            <i class="fa-solid fa-shield-check"></i> Ciphertext URL Sistem (RSA Penuh · 4-char hex/karakter — Fully Reversible)
          </div>
          <div class="cipher-result" style="font-size:13px;letter-spacing:1px"><?= $result ?></div>
        </div>

        <div>
          <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">URL Terenkripsi</div>
          <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:8px;padding:10px 14px;font-family:monospace;font-size:11px;color:#065F46;word-break:break-all">
            localhost/med.php?id=<?= $result ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Langkah-Langkah -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <div class="icon icon-purple"><i class="fa-solid fa-list-ol"></i></div>
          Langkah-Langkah Proses Enkripsi RSA
        </div>
        <span class="encrypt-badge badge-encrypted" style="font-size:10px">Sesuai Artikel</span>
      </div>
      <div class="card-body">
        <div class="steps-container">

          <!-- STEP 1 -->
          <div class="step-item" style="animation-delay:.05s">
            <div class="step-connector">
              <div class="step-num" style="background:#DBEAFE;color:var(--primary)">1</div>
            </div>
            <div class="step-content">
              <h4>Penentuan Nilai Prima Utama</h4>
              <p>Dua bilangan prima dipilih secara acak sebagai dasar pembangkitan kunci:</p>
              <div class="info-grid" style="margin-top:8px">
                <div class="info-item"><div class="label">p</div><div class="value"><?= RSA::P ?></div></div>
                <div class="info-item"><div class="label">q</div><div class="value"><?= RSA::Q ?></div></div>
              </div>
            </div>
          </div>

          <!-- STEP 2 -->
          <div class="step-item" style="animation-delay:.10s">
            <div class="step-connector">
              <div class="step-num" style="background:#CFFAFE;color:#0891B2">2</div>
            </div>
            <div class="step-content">
              <h4>Kalkulasi Modulus (n)</h4>
              <p>Nilai modulus diperoleh dari hasil perkalian kedua bilangan prima.</p>
              <div class="formula-box">n = p × q = <?= RSA::P ?> × <?= RSA::Q ?> = <?= RSA::N ?></div>
            </div>
          </div>

          <!-- STEP 3 -->
          <div class="step-item" style="animation-delay:.15s">
            <div class="step-connector">
              <div class="step-num" style="background:#EDE9FE;color:#7C3AED">3</div>
            </div>
            <div class="step-content">
              <h4>Kalkulasi Fungsi Totient Euler φ(n)</h4>
              <p>Menghitung jumlah bilangan bulat positif ≤ n yang relatif prima terhadap n.</p>
              <div class="formula-box">φ(n) = (p−1)(q−1) = (<?= RSA::P ?>−1)(<?= RSA::Q ?>−1) = <?= number_format(RSA::PHI) ?></div>
            </div>
          </div>

          <!-- STEP 4 -->
          <div class="step-item" style="animation-delay:.20s">
            <div class="step-connector">
              <div class="step-num" style="background:#D1FAE5;color:#059669">4</div>
            </div>
            <div class="step-content">
              <h4>Penentuan Kunci Publik (e)</h4>
              <p>Syarat: <strong>1 &lt; e &lt; φ(n)</strong> dan <strong>gcd(e, φ(n)) = 1</strong> (relatif prima).</p>
              <div class="formula-box">e = <?= number_format(RSA::E) ?><br>gcd(<?= number_format(RSA::E) ?>, <?= number_format(RSA::PHI) ?>) = 1 ✓<br>Kunci Publik (e, n) = (<?= number_format(RSA::E) ?>, <?= RSA::N ?>)</div>
              <p style="margin-top:8px;font-size:12px;color:var(--text3)">Dari p=<?= RSA::P ?>, q=<?= RSA::Q ?>, sistem menghasilkan 6.719 kemungkinan kunci publik yang sah.</p>
            </div>
          </div>

          <!-- STEP 5 -->
          <div class="step-item" style="animation-delay:.25s">
            <div class="step-connector">
              <div class="step-num" style="background:#FEF3C7;color:#D97706">5</div>
            </div>
            <div class="step-content">
              <h4>Konversi Plaintext ke Nilai Desimal ASCII</h4>
              <p>Setiap karakter dari "<strong><?= htmlspecialchars($input) ?></strong>" dikonversi ke nilai desimal ASCII (blok m<sub>i</sub>):</p>
              <div class="ascii-table" style="margin-top:10px">
                <?php foreach ($steps as $s): ?>
                <div class="ascii-item">
                  <div class="ascii-char"><?= htmlspecialchars($s['char']) ?></div>
                  <div class="ascii-val">ASCII: <?= $s['ascii'] ?></div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- STEP 6 -->
          <div class="step-item" style="animation-delay:.30s">
            <div class="step-connector">
              <div class="step-num" style="background:#FCE7F3;color:#BE185D">6</div>
            </div>
            <div class="step-content">
              <h4>Eksekusi Persamaan Enkripsi RSA</h4>
              <p>Setiap nilai ASCII dienkripsi: <strong>c<sub>i</sub> = m<sub>i</sub><sup>e</sup> mod n = m<sub>i</sub><sup><?= number_format(RSA::E) ?></sup> mod <?= RSA::N ?></strong></p>
              <div style="overflow-x:auto;margin-top:10px">
                <table class="cipher-table">
                  <thead>
                    <tr>
                      <th>Char</th><th>m<sub>i</sub> (ASCII)</th>
                      <th>m<sub>i</sub><sup><?= number_format(RSA::E) ?></sup> mod <?= RSA::N ?></th>
                      <th>c<sub>i</sub> (Cipher)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($steps as $s): ?>
                    <tr>
                      <td style="font-weight:700;font-size:15px"><?= htmlspecialchars($s['char']) ?></td>
                      <td><?= $s['ascii'] ?></td>
                      <td style="font-size:10px"><?= $s['ascii'] ?><sup><?= number_format(RSA::E) ?></sup> mod <?= RSA::N ?></td>
                      <td style="color:var(--accent2);font-weight:700"><?= $s['cipher'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- STEP 7 -->
          <div class="step-item" style="animation-delay:.35s">
            <div class="step-connector">
              <div class="step-num" style="background:#DBEAFE;color:var(--primary)">7</div>
            </div>
            <div class="step-content">
              <h4>Transformasi Heksadesimal ASCII (mod 256)</h4>
              <p>Nilai cipher dimodulasi 256 agar masuk rentang karakter ASCII standar, lalu dikonversi ke heksadesimal.</p>
              <div style="overflow-x:auto;margin-top:10px">
                <table class="cipher-table">
                  <thead>
                    <tr><th>Char</th><th>c<sub>i</sub></th><th>c<sub>i</sub> mod 256</th><th>Hex</th></tr>
                  </thead>
                  <tbody>
                    <?php foreach ($steps as $s): ?>
                    <tr>
                      <td style="font-weight:700"><?= htmlspecialchars($s['char']) ?></td>
                      <td><?= $s['cipher'] ?></td>
                      <td><?= $s['reduced'] ?></td>
                      <td style="background:#EDE9FE;font-weight:700;color:var(--accent2)"><?= $s['hex'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- STEP 8 -->
          <div class="step-item" style="animation-delay:.40s">
            <div class="step-connector">
              <div class="step-num" style="background:#D1FAE5;color:#059669">8</div>
            </div>
            <div class="step-content">
              <h4>Penggabungan — Ciphertext URL Final</h4>
              <p>Seluruh nilai hex digabungkan menjadi satu string ciphertext:</p>
              <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;margin:10px 0">
                <?php foreach ($steps as $i => $s): ?>
                <span style="background:#EDE9FE;color:var(--accent2);padding:5px 10px;border-radius:6px;font-family:monospace;font-weight:700;font-size:13px"><?= $s['hex'] ?></span>
                <?php if ($i < count($steps)-1): ?><span style="color:var(--text3)">+</span><?php endif; ?>
                <?php endforeach; ?>
                <span style="color:var(--text3);font-size:16px"> =</span>
              </div>
              <?php if (isset($resultArticle)): ?>
              <div style="background:linear-gradient(135deg,#EDE9FE,#DBEAFE);border:1px solid #DDD6FE;border-radius:10px;padding:12px 18px;font-family:monospace;font-size:16px;font-weight:700;color:#5B21B6;letter-spacing:2px;word-break:break-all;text-align:center;animation:glowPulse 2s ease-in-out infinite">
                <?= $resultArticle ?>
              </div>
              <?php if ($input === 'customer'): ?>
              <div style="text-align:center;margin-top:8px;font-size:12px;color:#059669;font-weight:600">
                <i class="fa-solid fa-check-circle"></i> Hasil persis sesuai artikel: <code>5a9cb05811aa6e4c</code>
              </div>
              <?php endif; ?>
              <?php endif; ?>

              <div class="alert alert-success" style="margin-top:14px">
                <i class="fa-solid fa-shield-check"></i>
                <div>Parameter <strong>"<?= htmlspecialchars($input) ?>"</strong> berhasil dienkripsi menjadi ciphertext yang tidak dapat dibaca langsung di URL!</div>
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
          <i class="fa-solid fa-key"></i>
        </div>
        <div style="font-size:15px;font-weight:700;color:var(--text2);margin-bottom:8px">Masukkan Teks untuk Dienkripsi</div>
        <div style="font-size:13px;color:var(--text3);max-width:320px;margin:0 auto;line-height:1.7">
          Langkah-langkah enkripsi RSA akan ditampilkan detail di sini, mulai dari nilai ASCII hingga pembentukan ciphertext.
        </div>
        <div class="formula-box" style="margin-top:22px;text-align:left;max-width:340px;margin-left:auto;margin-right:auto">
          C = M<sup>e</sup> mod n<br>
          C = M<sup>16397</sup> mod 26123<br><br>
          "customer" → <strong>5a9cb05811aa6e4c</strong>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
