<?php
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'customer_encrypted_lock', 'customer_encrypted', 'customer_plain', 'encrypt', 'decrypt'];
if (!in_array($page, $allowed_pages)) $page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RSA Crypto System — Bina Insani University</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root {
    --primary: #2563EB;
    --primary-light: #3B82F6;
    --primary-dark: #1D4ED8;
    --accent: #06B6D4;
    --accent2: #8B5CF6;
    --success: #10B981;
    --warning: #F59E0B;
    --danger: #EF4444;
    --bg: #F0F4FF;
    --bg2: #E8EFFE;
    --surface: #FFFFFF;
    --surface2: #F8FAFF;
    --border: #DBEAFE;
    --text: #1E293B;
    --text2: #475569;
    --text3: #94A3B8;
    --sidebar-w: 260px;
    --header-h: 68px;
    --radius: 16px;
    --shadow: 0 4px 24px rgba(37,99,235,0.10);
    --shadow-lg: 0 8px 40px rgba(37,99,235,0.15);
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; }
  body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    overflow-x: hidden;
  }
  /* BG Pattern */
  body::before {
    content: '';
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background:
      radial-gradient(circle at 10% 20%, rgba(37,99,235,0.06) 0%, transparent 50%),
      radial-gradient(circle at 90% 80%, rgba(6,182,212,0.06) 0%, transparent 50%),
      radial-gradient(circle at 50% 50%, rgba(139,92,246,0.04) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
  }

  /* SIDEBAR */
  .sidebar {
    position: fixed;
    top: 0; left: 0;
    width: var(--sidebar-w);
    height: 100vh;
    background: linear-gradient(175deg, #1E3A8A 0%, #1D4ED8 40%, #2563EB 100%);
    z-index: 100;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 30px rgba(37,99,235,0.25);
    overflow: hidden;
  }
  .sidebar::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
  }
  .sidebar::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(6,182,212,0.08);
    pointer-events: none;
  }
  .sidebar-logo {
    padding: 28px 24px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }
  .sidebar-logo .logo-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #06B6D4, #3B82F6);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff;
    box-shadow: 0 4px 16px rgba(6,182,212,0.4);
    margin-bottom: 12px;
    animation: logoFloat 3s ease-in-out infinite;
  }
  @keyframes logoFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
  }
  .sidebar-logo h2 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: 15px;
    color: #fff;
    letter-spacing: 0.5px;
    line-height: 1.3;
  }
  .sidebar-logo p {
    font-size: 10px;
    color: rgba(255,255,255,0.6);
    font-weight: 400;
    margin-top: 2px;
    letter-spacing: 0.3px;
  }
  .sidebar-nav {
    flex: 1;
    padding: 20px 16px;
    overflow-y: auto;
  }
  .nav-section-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
    padding: 0 8px 8px;
    margin-top: 8px;
  }
  .nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 12px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.25s ease;
    margin-bottom: 4px;
    position: relative;
    overflow: hidden;
  }
  .nav-item::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    border-radius: 0 3px 3px 0;
    background: #06B6D4;
    transform: scaleY(0);
    transition: transform 0.25s ease;
  }
  .nav-item:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    transform: translateX(4px);
  }
  .nav-item.active {
    background: linear-gradient(135deg, rgba(255,255,255,0.18), rgba(255,255,255,0.08));
    color: #fff;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  }
  .nav-item.active::before { transform: scaleY(1); }
  .nav-item .nav-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    transition: all 0.25s ease;
  }
  .nav-item:hover .nav-icon,
  .nav-item.active .nav-icon {
    background: rgba(255,255,255,0.15);
  }
  .sidebar-footer {
    padding: 16px 24px;
    border-top: 1px solid rgba(255,255,255,0.1);
  }
  .sidebar-footer .user-card {
    display: flex; align-items: center; gap: 10px;
  }
  .user-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #06B6D4, #8B5CF6);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #fff; font-weight: 700;
  }
  .user-info p { font-size: 12px; font-weight: 600; color: #fff; }
  .user-info span { font-size: 10px; color: rgba(255,255,255,0.5); }

  /* MAIN */
  .main {
    margin-left: var(--sidebar-w);
    min-height: 100vh;
    position: relative;
    z-index: 1;
  }
  .topbar {
    height: var(--header-h);
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 2px 16px rgba(37,99,235,0.06);
  }
  .topbar-left h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
  }
  .topbar-left p { font-size: 12px; color: var(--text3); margin-top: 1px; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .topbar-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
  }
  .badge-blue { background: #DBEAFE; color: var(--primary); }
  .badge-cyan { background: #CFFAFE; color: #0891B2; }

  /* CONTENT */
  .content {
    padding: 32px;
  }

  /* CARDS */
  .card {
    background: var(--surface);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
  }
  .card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
  }
  .card-title .icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
  }
  .icon-blue { background: #DBEAFE; color: var(--primary); }
  .icon-cyan { background: #CFFAFE; color: #0891B2; }
  .icon-purple { background: #EDE9FE; color: #7C3AED; }
  .icon-green { background: #D1FAE5; color: #059669; }
  .icon-orange { background: #FEF3C7; color: #D97706; }
  .icon-red { background: #FEE2E2; color: #DC2626; }
  .card-body { padding: 24px; }

  /* STATS GRID */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
  }
  .stat-card {
    background: var(--surface);
    border-radius: var(--radius);
    padding: 22px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: default;
    animation: fadeInUp 0.5s ease both;
  }
  .stat-card:nth-child(1) { animation-delay: 0.05s; }
  .stat-card:nth-child(2) { animation-delay: 0.10s; }
  .stat-card:nth-child(3) { animation-delay: 0.15s; }
  .stat-card:nth-child(4) { animation-delay: 0.20s; }
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
  .stat-card::after {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 80px; height: 80px;
    border-radius: 50%;
    transform: translate(30px, -30px);
    opacity: 0.08;
  }
  .stat-card.blue::after { background: var(--primary); }
  .stat-card.cyan::after { background: var(--accent); }
  .stat-card.purple::after { background: var(--accent2); }
  .stat-card.green::after { background: var(--success); }
  .stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 14px;
  }
  .stat-value {
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 4px;
    line-height: 1;
  }
  .stat-label { font-size: 12px; color: var(--text3); font-weight: 500; }
  .stat-change {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 8px;
    padding: 3px 8px;
    border-radius: 20px;
  }
  .stat-change.up { background: #D1FAE5; color: #059669; }
  .stat-change.info { background: #DBEAFE; color: var(--primary); }

  /* TABLE */
  .table-wrap { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  thead th {
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--text3);
    background: var(--surface2);
    border-bottom: 2px solid var(--border);
  }
  tbody tr {
    transition: background 0.2s;
    border-bottom: 1px solid var(--border);
    animation: fadeInRow 0.4s ease both;
  }
  @keyframes fadeInRow {
    from { opacity: 0; transform: translateX(-10px); }
    to { opacity: 1; transform: translateX(0); }
  }
  tbody tr:hover { background: var(--bg); }
  tbody td { padding: 14px 16px; color: var(--text2); vertical-align: middle; }
  .td-id { font-family: monospace; font-size: 12px; }
  .td-cipher {
    font-family: monospace;
    font-size: 12px;
    background: linear-gradient(90deg, #1E3A8A, #7C3AED);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 600;
    letter-spacing: 0.5px;
  }
  .td-name { font-weight: 600; color: var(--text); }
  .encrypt-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.3px;
  }
  .badge-encrypted { background: #EDE9FE; color: #7C3AED; }
  .badge-plain { background: #FEF3C7; color: #92400E; }

  /* BUTTONS */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
  }
  .btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    box-shadow: 0 4px 14px rgba(37,99,235,0.3);
  }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
  .btn-cyan {
    background: linear-gradient(135deg, #06B6D4, #0891B2);
    color: #fff;
    box-shadow: 0 4px 14px rgba(6,182,212,0.3);
  }
  .btn-cyan:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(6,182,212,0.4); }
  .btn-purple {
    background: linear-gradient(135deg, #8B5CF6, #7C3AED);
    color: #fff;
    box-shadow: 0 4px 14px rgba(139,92,246,0.3);
  }
  .btn-purple:hover { transform: translateY(-2px); }
  .btn-outline {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text2);
  }
  .btn-outline:hover { background: var(--bg); border-color: var(--primary); color: var(--primary); }
  .btn-sm { padding: 6px 12px; font-size: 12px; }
  .btn-lg { padding: 14px 28px; font-size: 14px; border-radius: 12px; }

  /* FORMS */
  .form-group { margin-bottom: 20px; }
  .form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text2);
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .form-input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    color: var(--text);
    background: var(--surface);
    transition: all 0.25s ease;
    outline: none;
  }
  .form-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(37,99,235,0.08);
  }

  /* PROCESS STEPS */
  .steps-container { display: flex; flex-direction: column; gap: 16px; }
  .step-item {
    display: flex;
    gap: 16px;
    animation: fadeInUp 0.5s ease both;
  }
  .step-num {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
  }
  .step-connector {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
  }
  .step-connector::after {
    content: '';
    width: 2px;
    flex: 1;
    background: linear-gradient(to bottom, var(--border), transparent);
    margin-top: 4px;
  }
  .step-content {
    flex: 1;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 4px;
  }
  .step-content h4 {
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 6px;
  }
  .step-content p, .step-content li {
    font-size: 12.5px;
    color: var(--text2);
    line-height: 1.7;
  }
  .step-content ul { padding-left: 16px; }
  .formula-box {
    background: linear-gradient(135deg, #1E3A8A, #1D4ED8);
    color: #fff;
    border-radius: 8px;
    padding: 10px 16px;
    font-family: monospace;
    font-size: 13px;
    margin: 8px 0;
    letter-spacing: 0.5px;
  }
  .result-box {
    background: linear-gradient(135deg, #F0F4FF, #E8EFFE);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 16px;
    font-family: monospace;
    font-size: 12px;
    color: var(--primary);
    margin-top: 8px;
    word-break: break-all;
  }
  .cipher-result {
    background: linear-gradient(135deg, #1E3A8A, #7C3AED);
    color: #fff;
    border-radius: 12px;
    padding: 16px 20px;
    font-family: monospace;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 2px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(37,99,235,0.3);
    word-break: break-all;
    animation: glowPulse 2s ease-in-out infinite;
  }
  @keyframes glowPulse {
    0%, 100% { box-shadow: 0 8px 30px rgba(37,99,235,0.3); }
    50% { box-shadow: 0 8px 40px rgba(37,99,235,0.5); }
  }

  /* INFO BOXES */
  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 12px;
  }
  .info-item {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px 14px;
  }
  .info-item .label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--text3);
    margin-bottom: 4px;
  }
  .info-item .value {
    font-family: monospace;
    font-size: 14px;
    font-weight: 700;
    color: var(--primary);
  }

  /* ASCII TABLE */
  .ascii-table {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin: 10px 0;
  }
  .ascii-item {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 8px;
    text-align: center;
    transition: all 0.2s;
  }
  .ascii-item:hover { background: #DBEAFE; border-color: var(--primary); }
  .ascii-char { font-size: 16px; font-weight: 700; color: var(--primary); }
  .ascii-val { font-size: 10px; color: var(--text3); font-family: monospace; }

  /* CIPHER TABLE */
  .cipher-table { width: 100%; border-collapse: collapse; }
  .cipher-table th, .cipher-table td {
    padding: 8px 12px;
    text-align: center;
    font-size: 12px;
    font-family: monospace;
    border: 1px solid var(--border);
  }
  .cipher-table th {
    background: var(--primary);
    color: #fff;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .cipher-table tr:nth-child(even) td { background: var(--surface2); }

  /* PAGE INTRO */
  .page-intro {
    background: linear-gradient(135deg, var(--primary), var(--accent2));
    border-radius: var(--radius);
    padding: 28px 32px;
    color: #fff;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.4s ease;
  }
  .page-intro::before {
    content: '';
    position: absolute;
    right: -30px; top: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
  }
  .page-intro::after {
    content: '';
    position: absolute;
    right: 60px; bottom: -40px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
  }
  .page-intro h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 6px;
  }
  .page-intro p { font-size: 13px; opacity: 0.85; max-width: 600px; line-height: 1.6; }

  /* MISC */
  .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
  .text-muted { color: var(--text3); font-size: 12px; }
  .mt-4 { margin-top: 16px; }
  .mt-6 { margin-top: 24px; }
  .mb-4 { margin-bottom: 16px; }
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
  .tag {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
  }

  /* Animations */
  .page-content { animation: fadeInPage 0.4s ease; }
  @keyframes fadeInPage {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Loading spinner for calc */
  .calculating {
    display: inline-block;
    width: 14px; height: 14px;
    border: 2px solid rgba(37,99,235,0.2);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* Alert */
  .alert {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 13px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 16px;
  }
  .alert-info { background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; }
  .alert-warning { background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; }
  .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }

  /* Collapse */
  .collapse-section { margin-top: 8px; }
  .collapse-toggle {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--primary);
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 0;
    font-family: 'Poppins', sans-serif;
  }
  .collapse-content { display: none; margin-top: 10px; }
  .collapse-content.open { display: block; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="fa-solid fa-shield-halved"></i></div>
    <h2>RSA Crypto System</h2>
    <p>Bina Insani University · 2026</p>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-label">Main Menu</div>
    <a href="?page=dashboard" class="nav-item <?= $page==='dashboard'?'active':'' ?>">
      <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
      Dashboard
    </a>
    <div class="nav-section-label" style="margin-top:12px">Data Customer</div>
    <a href="?page=customer_encrypted_lock" class="nav-item <?= $page==='customer_encrypted_lock'?'active':'' ?>">
      <span class="nav-icon"><i class="fa-solid fa-lock"></i></span>
      Customer (Locked)
    </a>
    <a href="?page=customer_encrypted" class="nav-item <?= $page==='customer_encrypted'?'active':'' ?>">
      <span class="nav-icon"><i class="fa-solid fa-lock"></i></span>
      Customer (Terenkripsi)
    </a>
    <a href="?page=customer_plain" class="nav-item <?= $page==='customer_plain'?'active':'' ?>">
      <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
      Customer (Plain)
    </a>
    <div class="nav-section-label" style="margin-top:12px">Algoritma RSA</div>
    <a href="?page=encrypt" class="nav-item <?= $page==='encrypt'?'active':'' ?>">
      <span class="nav-icon"><i class="fa-solid fa-key"></i></span>
      Enkripsi URL
    </a>
    <a href="?page=decrypt" class="nav-item <?= $page==='decrypt'?'active':'' ?>">
      <span class="nav-icon"><i class="fa-solid fa-unlock-keyhole"></i></span>
      Dekripsi URL
    </a>
  </nav>
  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">A</div>
      <div class="user-info">
        <p>Admin TanSoul</p>
        <span>Administrator</span>
      </div>
    </div>
  </div>
</aside>

<!-- MAIN CONTENT -->
<div class="main">
  <header class="topbar">
    <div class="topbar-left">
      <?php
      $titles = [
        'dashboard' => ['Dashboard', 'Selamat datang di sistem RSA Crypto'],
        'customer_encrypted_lock' => ['Data Customer (Locked)', 'Parameter URL terenkripsi dengan AES-256-CBC'],
        'customer_encrypted' => ['Data Customer (Terenkripsi)', 'Parameter URL dienkripsi dengan RSA'],
        'customer_plain' => ['Data Customer (Plain)', 'Parameter URL tanpa enkripsi — rentan!'],
        'encrypt' => ['Enkripsi URL — RSA', 'Proses enkripsi parameter GET menggunakan algoritma RSA'],
        'decrypt' => ['Dekripsi URL — RSA', 'Proses dekripsi ciphertext kembali ke plaintext'],
      ];
      $t = $titles[$page];
      ?>
      <h1><?= $t[0] ?></h1>
      <p><?= $t[1] ?></p>
    </div>
    <div class="topbar-right">
      <span class="topbar-badge badge-blue"><i class="fa-solid fa-circle" style="font-size:7px"></i> &nbsp;RSA Active</span>
      <span class="topbar-badge badge-cyan">p=151, q=173</span>
    </div>
  </header>

  <div class="content page-content">
    <?php include "pages/{$page}.php"; ?>
  </div>
</div>

<script>
function toggleCollapse(id) {
  const el = document.getElementById(id);
  el.classList.toggle('open');
  const btn = el.previousElementSibling;
  const icon = btn.querySelector('i');
  icon.classList.toggle('fa-chevron-down');
  icon.classList.toggle('fa-chevron-up');
}
</script>
</body>
</html>
