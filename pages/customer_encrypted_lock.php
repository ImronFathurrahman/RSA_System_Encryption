<style>
  /* ── 404 Page ── */
  .error-wrap{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:60vh;text-align:center;padding:40px 20px;animation:fadeUp .5s ease both}
  .error-code{font-family:'Montserrat',sans-serif;font-size:120px;font-weight:900;line-height:1;background:linear-gradient(135deg,#ef4444,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:8px}
  .error-title{font-family:'Montserrat',sans-serif;font-size:22px;font-weight:800;color:var(--text);margin-bottom:10px}
  .error-sub{font-size:14px;color:var(--text-muted);max-width:460px;line-height:1.7;margin-bottom:28px}
  .error-url-box{background:#0f172a;border-radius:10px;padding:14px 22px;margin-bottom:24px;max-width:520px;width:100%;text-align:left}
  .error-url-label{font-size:10px;color:#ef4444;letter-spacing:1px;text-transform:uppercase;margin-bottom:6px;font-weight:700}
  .error-url-value{font-family:'Courier New',monospace;font-size:12.5px;color:#f87171;word-break:break-all}
  .error-icon{width:90px;height:90px;background:linear-gradient(135deg,#fee2e2,#fecaca);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;margin-bottom:20px;box-shadow:0 8px 30px rgba(239,68,68,.2)}
  .error-hint{background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px 20px;max-width:520px;width:100%;font-size:12.5px;color:var(--text-muted);text-align:left;margin-bottom:22px}
  .error-hint strong{color:var(--text);display:block;margin-bottom:8px}
  .error-hint code{font-family:'Courier New',monospace;background:#f1f5f9;padding:2px 7px;border-radius:4px;font-size:11.5px}

  @keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
  @keyframes shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-8px)}40%,80%{transform:translateX(8px)}}
  .shake{animation:shake .5s ease .3s both}
</style>

<div class="error-wrap">
    <div class="error-icon shake">
    <i class="fas fa-file-circle-xmark" style="color:#ef4444"></i>
</div>

<div class="error-code">404</div>
    <div class="error-title">Halaman yang Anda cari tidak ditemukan</div>
    <div class="error-sub"></div>
    <a href="?page=dashboard" class="btn btn-primary mt-4"><i class="fa-solid fa-house"></i> Kembali ke Dashboard</a>
</div>