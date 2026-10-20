<?php
session_start();
$token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? $_COOKIE['revo_admin_token'] ?? '';
$remote = $_SERVER['REMOTE_ADDR'] ?? '';
$isLocal = in_array($remote, ['127.0.0.1', '::1', 'localhost']);
if (empty($_SESSION['admin_logged_in']) && $token !== 'revo_secret_admin_2026' && !$isLocal) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — REVO BULLION CMS</title>
  <link rel="icon" type="image/png" href="../assets/images/favicon.png">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-dark: #0e0f15;
      --bg-surface: #151722;
      --bg-card: #1c1f2e;
      --bg-card-hover: #24283b;
      --gold-primary: #D4AF37;
      --gold-light: #F3E5AB;
      --gold-dark: #997A15;
      --gold-border: rgba(212, 175, 55, 0.22);
      --gold-border-focus: rgba(212, 175, 55, 0.55);
      --text-main: #f8fafc;
      --text-secondary: #94a3b8;
      --text-muted: #64748b;
      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --danger: #ef4444;
      --success: #10b981;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background-color: var(--bg-dark);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Top Bar */
    .admin-navbar {
      background: var(--bg-surface);
      border-bottom: 1px solid var(--gold-border);
      padding: 0.85rem 1.8rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
      backdrop-filter: blur(16px);
    }

    .brand-wrap {
      display: flex;
      align-items: center;
      gap: 0.8rem;
    }

    .nav-emblem {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      border: 1.5px solid var(--gold-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle, rgba(212,175,55,0.2) 0%, rgba(0,0,0,0.5) 80%);
      font-family: 'Cinzel', serif;
      font-weight: 800;
      color: var(--gold-primary);
      font-size: 1.1rem;
    }

    .brand-title {
      font-family: 'Cinzel', serif;
      font-size: 1.15rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      color: #fff;
    }

    .brand-badge {
      font-size: 0.68rem;
      text-transform: uppercase;
      padding: 0.2rem 0.6rem;
      background: rgba(212, 175, 55, 0.15);
      border: 1px solid var(--gold-border);
      border-radius: 9999px;
      color: var(--gold-light);
      letter-spacing: 0.08em;
      font-weight: 700;
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 0.8rem;
    }

    .btn {
      padding: 0.55rem 1.1rem;
      border-radius: var(--radius-sm);
      font-size: 0.84rem;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      text-decoration: none;
      transition: all 0.2s ease;
      border: 1px solid transparent;
      outline: none;
    }

    .btn-gold {
      background: linear-gradient(135deg, #ECC440 0%, #D4AF37 50%, #A67C1E 100%);
      color: #000;
      font-weight: 700;
      box-shadow: 0 2px 10px rgba(212, 175, 55, 0.25);
    }

    .btn-gold:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(212, 175, 55, 0.4);
    }

    .btn-outline {
      background: transparent;
      border-color: var(--gold-border);
      color: var(--gold-light);
    }

    .btn-outline:hover {
      border-color: var(--gold-primary);
      background: rgba(212, 175, 55, 0.08);
      color: #fff;
    }

    .btn-danger {
      background: rgba(239, 68, 68, 0.15);
      border-color: rgba(239, 68, 68, 0.3);
      color: #fca5a5;
    }

    .btn-danger:hover {
      background: var(--danger);
      color: #fff;
    }

    /* Main Container & Tabs */
    .admin-main {
      flex: 1;
      max-width: 1500px;
      width: 100%;
      margin: 0 auto;
      padding: 1.5rem 1.8rem;
      display: flex;
      flex-direction: column;
    }

    .tabs-header {
      display: flex;
      gap: 0.8rem;
      border-bottom: 1px solid var(--gold-border);
      padding-bottom: 0.6rem;
      margin-bottom: 1.5rem;
    }

    .tab-btn {
      padding: 0.75rem 1.4rem;
      background: transparent;
      border: 1px solid transparent;
      color: var(--text-secondary);
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      gap: 0.6rem;
      transition: all 0.25s ease;
    }

    .tab-btn:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.04);
      border-color: rgba(212, 175, 55, 0.15);
    }

    .tab-btn.active {
      color: #000;
      background: var(--gold-primary);
      border-color: var(--gold-primary);
      font-weight: 700;
      box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    /* Content Panes */
    .tab-pane {
      display: none;
      animation: fadeIn 0.25s ease;
      flex: 1;
    }

    .tab-pane.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Panel Card */
    .panel-card {
      background: var(--bg-surface);
      border: 1px solid var(--gold-border);
      border-radius: var(--radius-lg);
      padding: 1.6rem;
      margin-bottom: 1.5rem;
    }

    .panel-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.4rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .panel-title {
      font-family: 'Cinzel', serif;
      font-size: 1.25rem;
      color: #fff;
    }

    .panel-sub {
      font-size: 0.82rem;
      color: var(--text-muted);
      margin-top: 0.2rem;
    }

    /* Filter Bar */
    .filter-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.2rem;
      flex-wrap: wrap;
    }

    .filter-pills {
      display: flex;
      gap: 0.4rem;
    }

    .pill-btn {
      padding: 0.45rem 1rem;
      border-radius: 9999px;
      border: 1px solid var(--gold-border);
      background: rgba(0, 0, 0, 0.3);
      color: var(--text-secondary);
      font-size: 0.8rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }

    .pill-btn:hover, .pill-btn.active {
      background: rgba(212, 175, 55, 0.2);
      border-color: var(--gold-primary);
      color: #fff;
    }

    .search-input {
      padding: 0.55rem 1rem;
      background: rgba(0, 0, 0, 0.4);
      border: 1px solid var(--gold-border);
      border-radius: var(--radius-sm);
      color: #fff;
      font-size: 0.84rem;
      outline: none;
      min-width: 240px;
    }

    .search-input:focus {
      border-color: var(--gold-primary);
    }

    /* Products Table */
    .table-responsive {
      width: 100%;
      overflow-x: auto;
    }

    table.data-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.86rem;
    }

    table.data-table th {
      padding: 0.85rem 1rem;
      background: rgba(0, 0, 0, 0.4);
      color: var(--gold-light);
      font-weight: 700;
      border-bottom: 1px solid var(--gold-border);
      white-space: nowrap;
    }

    table.data-table td {
      padding: 0.85rem 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      vertical-align: middle;
    }

    table.data-table tr:hover td {
      background: rgba(255, 255, 255, 0.02);
    }

    .prod-thumb {
      width: 48px;
      height: 48px;
      object-fit: contain;
      border-radius: var(--radius-sm);
      border: 1px solid var(--gold-border);
      background: #000;
      padding: 2px;
    }

    .badge {
      display: inline-block;
      padding: 0.2rem 0.55rem;
      border-radius: 9999px;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }

    .badge-gold { background: rgba(212, 175, 55, 0.18); color: var(--gold-light); border: 1px solid var(--gold-primary); }
    .badge-silver { background: rgba(192, 192, 192, 0.18); color: #e2e8f0; border: 1px solid #94a3b8; }
    .badge-vvip { background: rgba(236, 196, 64, 0.25); color: #fff; border: 1px solid #ECC440; }

    .price-tag {
      font-family: 'Cinzel', serif;
      font-weight: 800;
      color: var(--gold-primary);
    }

    /* Modal */
    .modal-backdrop {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(8px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 100;
      padding: 1.5rem;
    }

    .modal-backdrop.active { display: flex; }

    .modal-box {
      background: var(--bg-surface);
      border: 1px solid var(--gold-border);
      width: 100%;
      max-width: 680px;
      max-height: 90vh;
      overflow-y: auto;
      border-radius: var(--radius-lg);
      padding: 2rem;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9), 0 0 40px rgba(212, 175, 55, 0.15);
      position: relative;
    }

    .modal-close {
      position: absolute;
      top: 1.2rem;
      right: 1.2rem;
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-size: 1.4rem;
      cursor: pointer;
    }

    .modal-close:hover { color: #fff; }

    /* Forms */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .form-full { grid-column: span 2; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--gold-light); margin-bottom: 0.4rem; }

    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 0.68rem 0.95rem;
      background: rgba(0, 0, 0, 0.45);
      border: 1px solid var(--gold-border);
      border-radius: var(--radius-sm);
      color: #fff;
      font-size: 0.86rem;
      outline: none;
      transition: all 0.2s;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
      border-color: var(--gold-primary);
      background: rgba(0, 0, 0, 0.7);
    }

    .form-textarea { resize: vertical; min-height: 80px; font-family: inherit; }

    /* Drag & Drop Direct Image Upload Zone */
    .upload-dropzone {
      border: 2px dashed var(--gold-border);
      border-radius: var(--radius-md);
      background: rgba(0, 0, 0, 0.35);
      padding: 1.4rem 1rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.25s ease;
      position: relative;
    }

    .upload-dropzone:hover, .upload-dropzone.dragover {
      border-color: var(--gold-primary);
      background: rgba(212, 175, 55, 0.08);
    }

    .dropzone-preview {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1.5rem;
      flex-wrap: wrap;
    }

    .dropzone-preview img {
      max-width: 110px;
      max-height: 110px;
      object-fit: contain;
      border-radius: var(--radius-sm);
      border: 1px solid var(--gold-border);
      background: #000;
      padding: 4px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }

    .preview-actions {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      align-items: flex-start;
    }

    /* Visual Landing Page Live Editor */
    .visual-editor-container {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      height: calc(100vh - 160px);
      min-height: 650px;
    }

    .visual-toolbar {
      background: var(--bg-surface);
      border: 1px solid var(--gold-border);
      border-radius: var(--radius-md);
      padding: 0.8rem 1.4rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .toolbar-left {
      display: flex;
      align-items: center;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .editor-indicator {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.84rem;
      font-weight: 700;
      color: #fff;
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.4);
      padding: 0.35rem 0.8rem;
      border-radius: 9999px;
    }

    .pulse-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--success);
      box-shadow: 0 0 10px var(--success);
      animation: pulse 1.8s infinite;
    }

    @keyframes pulse {
      0% { opacity: 0.4; transform: scale(0.9); }
      50% { opacity: 1; transform: scale(1.15); }
      100% { opacity: 0.4; transform: scale(0.9); }
    }

    .toolbar-hint {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }

    .toolbar-actions {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .visual-frame-wrap {
      flex: 1;
      border: 1px solid var(--gold-border);
      border-radius: var(--radius-lg);
      overflow: hidden;
      background: #000;
      box-shadow: 0 10px 40px rgba(0,0,0,0.6);
      position: relative;
    }

    #visualEditorFrame {
      width: 100%;
      height: 100%;
      border: none;
      display: block;
      background: #060608;
    }

    /* Toast Notification */
    .toast-notice {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: rgba(16, 185, 129, 0.95);
      color: #000;
      padding: 0.85rem 1.4rem;
      border-radius: var(--radius-sm);
      font-weight: 700;
      font-size: 0.88rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
      z-index: 200;
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .toast-notice.show { transform: translateY(0); opacity: 1; }
    .toast-notice.error { background: rgba(239, 68, 68, 0.95); color: #fff; }

    @media (max-width: 768px) {
      .admin-navbar { padding: 0.8rem 1rem; }
      .brand-title { font-size: 1rem; }
      .brand-badge { display: none; }
      .admin-main { padding: 1rem; }
      .form-grid { grid-template-columns: 1fr; }
      .form-full { grid-column: span 1; }
      .visual-toolbar { flex-direction: column; align-items: stretch; }
    }
  </style>
</head>
<body>

  <!-- Top Navigation Bar -->
  <header class="admin-navbar">
    <div class="brand-wrap">
      <div class="nav-emblem">R</div>
      <div>
        <div class="brand-title">REVO BULLION</div>
        <div style="font-size: 0.72rem; color: var(--gold-light);">Sistem Pengurusan Kandungan</div>
      </div>
      <span class="brand-badge">SaaS Admin</span>
    </div>

    <div class="nav-actions">
      <a href="../" target="_blank" class="btn btn-outline" title="Lihat Laman Web Langsung">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
        <span>Lihat Web</span>
      </a>

      <button type="button" class="btn btn-gold" id="btnPublishStatic" title="Simpan dan Segerakkan Data">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
        <span>Simpan Data</span>
      </button>

      <button type="button" class="btn btn-danger" id="btnLogout" title="Log Keluar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        <span>Keluar</span>
      </button>
    </div>
  </header>

  <!-- Main Container -->
  <main class="admin-main">

    <!-- 2 Only Clean Navigation Tabs: Katalog Produk (CRUD) & Editor Visual Laman Web -->
    <nav class="tabs-header" role="tablist">
      <button type="button" class="tab-btn active" data-tab="tab-products">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 8.5 12 15 22 8.5 12 2"></polygon><polygon points="2 15.5 12 22 22 15.5"></polygon></svg>
        <span>Katalog Produk (CRUD)</span>
      </button>
      <button type="button" class="tab-btn" data-tab="tab-visual-editor">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
        <span>Sunting Visual Laman Web (Live In-Place Edit)</span>
      </button>
    </nav>

    <!-- TAB 1: PRODUK & HARGA (CRUD) -->
    <section id="tab-products" class="tab-pane active">
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Katalog Kad Emas & Perak</h2>
            <p class="panel-sub">Urus senarai produk, gramatur, harga seunit, muat naik gambar dan maklumat tempahan.</p>
          </div>
          <div style="display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap;">
            <button type="button" class="btn btn-outline" id="btnDownloadJson" title="Muat Turun Fail data/content.json">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
              <span>Eksport content.json</span>
            </button>
            <button type="button" class="btn btn-outline" id="btnResetDefault" title="Set Semula ke Asal Pelayan" style="color: var(--text-muted); border-color: rgba(255,255,255,0.15);">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
              <span>Set Semula</span>
            </button>
            <button type="button" class="btn btn-gold" id="btnOpenAddProd">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              <span>Tambah Produk Baru</span>
            </button>
          </div>
        </div>

        <div class="filter-bar">
          <div class="filter-pills">
            <button type="button" class="pill-btn active" data-filter="all">Semua (<span id="countAll">0</span>)</button>
            <button type="button" class="pill-btn" data-filter="gold">Baby Gold (<span id="countGold">0</span>)</button>
            <button type="button" class="pill-btn" data-filter="silver">Baby Silver (<span id="countSilver">0</span>)</button>
            <button type="button" class="pill-btn" data-filter="vvip">Siri VVIP (<span id="countVvip">0</span>)</button>
          </div>

          <input type="text" class="search-input" id="searchProdInput" placeholder="Cari produk atau gramatur...">
        </div>

        <div class="table-responsive">
          <table class="data-table" id="productsTable">
            <thead>
              <tr>
                <th style="width: 60px;">Gambar</th>
                <th>Nama Produk & Gramatur</th>
                <th>Kategori</th>
                <th>Siri</th>
                <th>Logam</th>
                <th>Harga Seunit</th>
                <th>MOQ</th>
                <th>Status</th>
                <th style="text-align: right;">Tindakan</th>
              </tr>
            </thead>
            <tbody id="productsTableBody"></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- TAB 2: SUNTING VISUAL LAMAN WEB (IN-PLACE LIVE EDITOR) -->
    <section id="tab-visual-editor" class="tab-pane">
      <div class="visual-editor-container">
        <!-- Floating Visual Editor Toolbar -->
        <div class="visual-toolbar">
          <div class="toolbar-left">
            <span class="editor-indicator">
              <span class="pulse-dot"></span>
              Mode Sunting Visual Langsung
            </span>
            <span class="toolbar-hint">Klik mana-mana teks pada paparan web di bawah untuk mengubahnya secara manual, kemudian klik Simpan Perubahan.</span>
          </div>

          <div class="toolbar-actions">
            <button type="button" class="btn btn-outline" id="btnReloadVisualEditor" title="Muat Semula Paparan">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
              <span>Muat Semula</span>
            </button>
            <button type="button" class="btn btn-gold" id="btnSaveVisualChanges" title="Simpan Semua Perubahan Langsung ke Laman Web">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
              <span>Simpan Perubahan Visual</span>
            </button>
          </div>
        </div>

        <!-- Frame Container -->
        <div class="visual-frame-wrap">
          <iframe id="visualEditorFrame" src="../index.html" title="Visual Landing Page Editor"></iframe>
        </div>
      </div>
    </section>

  </main>

  <!-- MODAL: PRODUK (WITH DIRECT IMAGE UPLOAD) -->
  <div class="modal-backdrop" id="productModal">
    <div class="modal-box">
      <button type="button" class="modal-close" id="btnCloseProdModal">&times;</button>
      <h3 style="font-family: 'Cinzel', serif; font-size: 1.3rem; margin-bottom: 1.2rem; color: #fff;" id="prodModalTitle">Tambah Produk Baru</h3>

      <form id="productForm">
        <input type="hidden" id="editProdId" value="">
        <div class="form-grid">
          <div class="form-group form-full">
            <label class="form-label">Nama Produk Lengkap</label>
            <input type="text" class="form-input" id="prodNameInput" required>
          </div>
          <div class="form-group">
            <label class="form-label">Kategori</label>
            <select class="form-select" id="prodCategorySelect" required>
              <option value="gold">Baby Gold (Au 999.9)</option>
              <option value="silver">Baby Silver (Ag 999.9)</option>
              <option value="vvip">Siri VVIP Exclusive</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Siri Rekaan</label>
            <select class="form-select" id="prodSeriesSelect" required>
              <option value="Custom Series">Custom Series</option>
              <option value="Premium Series">Premium Series</option>
              <option value="VVIP Exclusive">VVIP Exclusive</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Jenis Logam Mulia</label>
            <select class="form-select" id="prodMetalSelect" required>
              <option value="Au 999.9">Au 999.9 (24K Gold)</option>
              <option value="Ag 999.9">Ag 999.9 (Fine Silver)</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Berat Gramatur</label>
            <input type="text" class="form-input" id="prodWeightInput" required>
          </div>
          <div class="form-group">
            <label class="form-label">Harga Seunit (RM)</label>
            <input type="number" step="0.01" class="form-input" id="prodPriceInput" required>
          </div>
          <div class="form-group">
            <label class="form-label">Kuantiti Minimum (MOQ)</label>
            <input type="number" class="form-input" id="prodMoqInput" required value="50">
          </div>

          <!-- DIRECT IMAGE UPLOAD ZONE (Bisa Upload Gambar Langsung) -->
          <div class="form-group form-full">
            <label class="form-label">Gambar Produk (Muat Naik Terus / Upload Gambar)</label>
            <div class="upload-dropzone" id="uploadDropzone">
              <input type="file" id="prodImageFileInput" accept="image/png, image/jpeg, image/webp, image/svg+xml" style="display: none;">
              
              <div class="dropzone-content" id="dropzonePrompt">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: var(--gold-primary); margin-bottom: 0.4rem;">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">Klik untuk pilih fail gambar atau seret ke sini</div>
                <div style="font-size: 0.74rem; color: var(--text-muted); margin-top: 0.2rem;">Format: PNG, JPG, WEBP, SVG (Maks. 5MB)</div>
              </div>

              <div class="dropzone-preview" id="dropzonePreview" style="display: none;">
                <img id="imgPreviewTag" src="" alt="Pratonton Gambar">
                <div class="preview-actions">
                  <div style="font-size: 0.8rem; font-weight: 700; color: var(--gold-light);" id="previewFileName">gambar_produk.png</div>
                  <div style="display: flex; gap: 0.5rem; margin-top: 0.3rem;">
                    <button type="button" class="btn btn-outline" id="btnChangeImage" style="padding: 0.3rem 0.7rem; font-size: 0.75rem;">Tukar Gambar</button>
                    <button type="button" class="btn btn-danger" id="btnRemoveImage" style="padding: 0.3rem 0.7rem; font-size: 0.75rem;">Buang</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Value container storing final URL or base64 -->
            <input type="hidden" id="prodImageInput" value="">
          </div>

          <div class="form-group form-full">
            <label class="form-label">Penerangan Produk</label>
            <textarea class="form-textarea" id="prodDescInput" rows="3"></textarea>
          </div>
          <div class="form-group form-full" style="display: flex; align-items: center; gap: 0.6rem;">
            <input type="checkbox" id="prodActiveCheck" checked style="width: 18px; height: 18px; accent-color: var(--gold-primary);">
            <label for="prodActiveCheck" style="font-size: 0.85rem; color: #fff; cursor: pointer;">Paparkan produk ini di laman web (Aktif)</label>
          </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.8rem; margin-top: 1rem;">
          <button type="button" class="btn btn-outline" id="btnCancelProdModal">Batal</button>
          <button type="submit" class="btn btn-gold">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>

  <div id="adminToast" class="toast-notice">Kandungan berjaya disimpan!</div>

  <script>
    // Client auth guard
    if (!localStorage.getItem('revo_admin_auth')) {
      window.location.href = 'login.html';
    }

    let appData = null;
    let currentFilter = 'all';

    function showToast(message, isError = false) {
      const toast = document.getElementById('adminToast');
      toast.textContent = message;
      toast.className = 'toast-notice' + (isError ? ' error show' : ' show');
      setTimeout(() => { toast.className = 'toast-notice'; }, 3500);
    }

    // ==========================================
    // 1. INDEXEDDB ENGINE (Unlimited Local Storage)
    // ==========================================
    const REVO_DB = {
      name: 'RevoBullionDB',
      version: 1,
      storeName: 'products',

      async open() {
        if (!window.indexedDB) return null;
        return new Promise((resolve) => {
          const req = indexedDB.open(this.name, this.version);
          req.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains(this.storeName)) {
              db.createObjectStore(this.storeName, { keyPath: 'id' });
            }
          };
          req.onsuccess = () => resolve(req.result);
          req.onerror = () => resolve(null);
        });
      },

      async getAll() {
        const db = await this.open();
        if (!db) return null;
        return new Promise((resolve) => {
          try {
            const tx = db.transaction(this.storeName, 'readonly');
            const store = tx.objectStore(this.storeName);
            const req = store.getAll();
            req.onsuccess = () => resolve(req.result || null);
            req.onerror = () => resolve(null);
          } catch (e) {
            resolve(null);
          }
        });
      },

      async saveAll(products) {
        if (!Array.isArray(products) || products.length === 0) return false;
        const db = await this.open();
        if (!db) return false;
        return new Promise((resolve) => {
          try {
            const tx = db.transaction(this.storeName, 'readwrite');
            const store = tx.objectStore(this.storeName);
            products.forEach(p => store.put(p));
            tx.oncomplete = () => resolve(true);
            tx.onerror = () => resolve(false);
          } catch (e) {
            resolve(false);
          }
        });
      },

      async clear() {
        const db = await this.open();
        if (!db) return;
        try {
          const tx = db.transaction(this.storeName, 'readwrite');
          tx.objectStore(this.storeName).clear();
        } catch (e) {}
      }
    };

    // Tab Navigation Switcher
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const targetPane = document.getElementById(btn.getAttribute('data-tab'));
        if (targetPane) targetPane.classList.add('active');

        // When switching to visual editor, ensure editable nodes are initialized
        if (btn.getAttribute('data-tab') === 'tab-visual-editor') {
          initVisualEditorIframe();
        }
      });
    });

    // Default Products Fallback (for file:// protocol where fetch is restricted)
    function getDefaultProducts() {
      return [
        { id: 'gold-p-0001', name: 'Baby Gold Card Premium 0.001g', metal: 'Au 999.9', category: 'gold', weight: '0.001g', price: 9.99, moq: 50, series: 'Premium Series', image: './assets/images/products/card_gold_0001g.png', active: true },
        { id: 'gold-p-002', name: 'Baby Gold Card Premium 0.02g', metal: 'Au 999.9', category: 'gold', weight: '0.02g', price: 26.66, moq: 50, series: 'Premium Series', image: './assets/images/products/card_gold_002g.png', active: true },
        { id: 'gold-p-005', name: 'Baby Gold Card Premium 0.05g', metal: 'Au 999.9', category: 'gold', weight: '0.05g', price: 55.55, moq: 50, series: 'Premium Series', image: './assets/images/products/card_gold_005g.png', active: true },
        { id: 'silver-c-1', name: 'Baby Silver Card Custom 1g', metal: 'Ag 999.9', category: 'silver', weight: '1g', price: 22.22, moq: 50, series: 'Custom Series', image: './assets/images/products/card_silver_1g.png', active: true },
        { id: 'silver-c-2', name: 'Baby Silver Card Custom 2g', metal: 'Ag 999.9', category: 'silver', weight: '2g', price: 44.44, moq: 50, series: 'Custom Series', image: './assets/images/products/card_silver_2g.png', active: true },
        { id: 'silver-c-5', name: 'Baby Silver Card Custom 5g', metal: 'Ag 999.9', category: 'silver', weight: '5g', price: 111.11, moq: 50, series: 'Custom Series', image: './assets/images/products/card_silver_5g.png', active: true },
        { id: 'silver-p-10', name: 'Baby Silver Card Premium 10g', metal: 'Ag 999.9', category: 'silver', weight: '10g', price: 222.22, moq: 50, series: 'Premium Series', image: './assets/images/products/card_silver_10g.png', active: true },
        { id: 'silver-p-25', name: 'Baby Silver Card Premium 25g', metal: 'Ag 999.9', category: 'silver', weight: '25g', price: 444.44, moq: 50, series: 'Premium Series', image: './assets/images/products/card_silver_25g.png', active: true },
        { id: 'silver-p-50', name: 'Baby Silver Card Premium 50g', metal: 'Ag 999.9', category: 'silver', weight: '50g', price: 888.88, moq: 50, series: 'Premium Series', image: './assets/images/products/card_silver_50g.png', active: true }
      ];
    }

    // Load CMS Data & Merge IndexedDB + LocalStorage
    async function loadContent() {
      // 1. Server fetch
      try {
        const res = await fetch('../data/content.json?t=' + Date.now());
        if (res.ok) {
          appData = await res.json();
        }
      } catch (err) {
        console.warn('Could not fetch server content.json', err);
      }

      // 2. Local storage backup fallback
      if (!appData) {
        const savedBackup = localStorage.getItem('revo_content_backup');
        if (savedBackup) {
          try { appData = JSON.parse(savedBackup); } catch(e) {}
        }
      }

      // 3. Fallback to default template if still empty
      if (!appData || !Array.isArray(appData.products)) {
        appData = { site: { brand: 'REVO BULLION' }, products: getDefaultProducts() };
      }

      // 4. Merge from legacy localStorage first (if any existing items)
      const customProds = localStorage.getItem('revo_custom_products');
      if (customProds && appData && Array.isArray(appData.products)) {
        try {
          const parsed = JSON.parse(customProds);
          if (Array.isArray(parsed)) {
            parsed.forEach(cp => {
              const idx = appData.products.findIndex(p => p.id === cp.id);
              if (idx !== -1) {
                // Only merge if it's an uploaded custom base64 image
                if (cp.image && cp.image.startsWith('data:')) {
                  appData.products[idx] = { ...appData.products[idx], ...cp };
                }
              }
            });
          }
        } catch(e) {}
      }

      // 5. MASTER OVERRIDE: Merge from IndexedDB (highest authority and capacity)
      const dbProducts = await REVO_DB.getAll();
      if (dbProducts && Array.isArray(dbProducts) && dbProducts.length > 0) {
        dbProducts.forEach(dp => {
          const idx = appData.products.findIndex(p => p.id === dp.id);
          if (idx !== -1) {
            appData.products[idx] = { ...appData.products[idx], ...dp };
          } else {
            appData.products.unshift(dp);
          }
        });
      }

      renderProducts();

      // 6. Background self-healing: Compress any bloated >60KB images to free up space
      setTimeout(() => {
        autoCompressExistingProducts();
      }, 500);
    }

    // Save Data to IndexedDB, LocalStorage, and Server
    async function saveContent() {
      if (!appData || !Array.isArray(appData.products)) return;

      // 1. Save to IndexedDB (unlimited quota, 100% resilient)
      await REVO_DB.saveAll(appData.products);

      // 2. Try to save to localStorage
      try {
        localStorage.setItem('revo_custom_products', JSON.stringify(appData.products));
        localStorage.setItem('revo_content_backup', JSON.stringify(appData));
      } catch (storageErr) {
        console.warn('LocalStorage quota warning - data safely secured in IndexedDB:', storageErr);
      }

      // 3. Broadcast to all open tabs so landing page updates instantly
      try {
        if (window.BroadcastChannel) {
          const bc = new BroadcastChannel('revo_cms_channel');
          bc.postMessage({ type: 'PRODUCTS_UPDATED' });
        }
      } catch (e) {}

      // 4. Save to PHP API if server is reachable
      let serverSaved = false;
      try {
        const res = await fetch('api.php?action=save_content', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'X-Admin-Token': 'revo_secret_admin_2026'
          },
          body: JSON.stringify(appData)
        });
        const json = await res.json();
        if (json.success) {
          serverSaved = true;
          if (json.data && Array.isArray(json.data.products)) {
            appData.products = json.data.products;
            await REVO_DB.saveAll(appData.products);
            try {
              localStorage.setItem('revo_custom_products', JSON.stringify(appData.products));
            } catch(e) {}
          }
          showToast(json.message || 'Kandungan berjaya disimpan ke fail pelayan!');
        }
      } catch (err) {}

      if (!serverSaved) {
        showToast('Perubahan disimpan di storan pelayar & sedia dipratonton!');
      }

      renderProducts();
      refreshVisualEditor();
    }

    // Render Products Table
    function renderProducts() {
      if (!appData) return;
      const tbody = document.getElementById('productsTableBody');
      const search = (document.getElementById('searchProdInput').value || '').toLowerCase();
      let list = appData.products || [];

      document.getElementById('countAll').textContent = list.length;
      document.getElementById('countGold').textContent = list.filter(p => p.category === 'gold').length;
      document.getElementById('countSilver').textContent = list.filter(p => p.category === 'silver').length;
      document.getElementById('countVvip').textContent = list.filter(p => p.category === 'vvip').length;

      if (currentFilter !== 'all') list = list.filter(p => p.category === currentFilter);
      if (search) list = list.filter(p => (p.name + ' ' + p.weight + ' ' + p.series).toLowerCase().includes(search));

      if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-muted);">Tiada produk dijumpai.</td></tr>';
        return;
      }

      tbody.innerHTML = list.map(p => {
        let imgSrc = p.image || './assets/images/products/card_gold_0001g.png';
        if (imgSrc.startsWith('./')) imgSrc = '.' + imgSrc; // adjust relative to /admin
        return `
        <tr>
          <td><img src="${imgSrc}" alt="${p.name}" class="prod-thumb" onerror="this.src='../assets/images/favicon.png'"></td>
          <td>
            <strong style="color: #fff; font-size: 0.9rem;">${p.name}</strong>
            <div style="font-size: 0.74rem; color: var(--text-muted);">${p.desc ? p.desc.substring(0, 60) + '...' : ''}</div>
          </td>
          <td><span class="badge badge-${p.category}">${p.category}</span></td>
          <td><span style="color: var(--gold-light); font-size: 0.8rem;">${p.series}</span></td>
          <td><span style="font-weight: 600;">${p.metal}</span></td>
          <td><span class="price-tag">RM ${parseFloat(p.price).toFixed(2)}</span></td>
          <td><span>${p.moq || 50} keping</span></td>
          <td>
            <span style="color: ${p.active ? 'var(--success)' : 'var(--danger)'}; font-weight: 600; font-size: 0.8rem;">
              ${p.active ? '● Aktif' : '○ Draf'}
            </span>
          </td>
          <td style="text-align: right; white-space: nowrap;">
            <button type="button" class="btn btn-outline" style="padding: 0.35rem 0.7rem; font-size: 0.75rem;" onclick="editProduct('${p.id}')">Sunting</button>
            <button type="button" class="btn btn-danger" style="padding: 0.35rem 0.7rem; font-size: 0.75rem;" onclick="deleteProduct('${p.id}')">Padam</button>
          </td>
        </tr>
      `;
      }).join('');
    }

    document.querySelectorAll('.filter-pills .pill-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-pills .pill-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = btn.getAttribute('data-filter');
        renderProducts();
      });
    });

    document.getElementById('searchProdInput').addEventListener('input', renderProducts);

    // Modal & Direct Image Upload Setup
    const prodModal = document.getElementById('productModal');
    const uploadDropzone = document.getElementById('uploadDropzone');
    const prodImageFileInput = document.getElementById('prodImageFileInput');
    const dropzonePrompt = document.getElementById('dropzonePrompt');
    const dropzonePreview = document.getElementById('dropzonePreview');
    const imgPreviewTag = document.getElementById('imgPreviewTag');
    const previewFileName = document.getElementById('previewFileName');
    const prodImageInput = document.getElementById('prodImageInput');

    // Trigger file chooser on dropzone click or "Tukar Gambar"
    uploadDropzone.addEventListener('click', (e) => {
      if (e.target.closest('#btnRemoveImage')) return;
      prodImageFileInput.value = ''; // Always clear so selecting same file fires change
      prodImageFileInput.click();
    });

    document.getElementById('btnChangeImage').addEventListener('click', (e) => {
      e.stopPropagation();
      prodImageFileInput.value = '';
      prodImageFileInput.click();
    });

    // Drag and Drop handling
    ['dragenter', 'dragover'].forEach(eventName => {
      uploadDropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadDropzone.classList.add('dragover');
      });
    });

    ['dragleave', 'drop'].forEach(eventName => {
      uploadDropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadDropzone.classList.remove('dragover');
      });
    });

    uploadDropzone.addEventListener('drop', (e) => {
      const files = e.dataTransfer.files;
      if (files && files.length > 0) {
        handleImageFile(files[0]);
      }
    });

    prodImageFileInput.addEventListener('change', () => {
      if (prodImageFileInput.files && prodImageFileInput.files.length > 0) {
        handleImageFile(prodImageFileInput.files[0]);
      }
    });

    function downloadContentJson() {
      if (!appData) return;
      const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(appData, null, 2));
      const dlAnchor = document.createElement('a');
      dlAnchor.setAttribute("href", dataStr);
      dlAnchor.setAttribute("download", "content.json");
      document.body.appendChild(dlAnchor);
      dlAnchor.click();
      dlAnchor.remove();
      showToast('Fail content.json telah dimuat turun!');
    }

    async function resetToDefault() {
      if (confirm('Adakah anda pasti mahu memadamkan cache tempatan dan menetapkan semula ke kandungan asal fail pelayan?')) {
        await REVO_DB.clear();
        localStorage.removeItem('revo_custom_products');
        localStorage.removeItem('revo_content_backup');
        appData = null;
        await loadContent();
        showToast('Kandungan telah ditetapkan semula ke fail asal.');
      }
    }

    // High-efficiency Image Compression (HTML5 Canvas) to ~25KB WebP
    function compressImage(file, maxWidth = 640, maxHeight = 640, quality = 0.75) {
      return new Promise((resolve) => {
        if (file.type === 'image/svg+xml' || (file.name && file.name.endsWith('.svg'))) {
          const r = new FileReader();
          r.onload = () => resolve(r.result);
          r.onerror = () => resolve('');
          r.readAsDataURL(file);
          return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
          const img = new Image();
          img.onload = () => {
            try {
              let width = img.width;
              let height = img.height;
              if (width > maxWidth || height > maxHeight) {
                if (width > height) {
                  height = Math.round((height * maxWidth) / width);
                  width = maxWidth;
                } else {
                  width = Math.round((width * maxHeight) / height);
                  height = maxHeight;
                }
              }
              const canvas = document.createElement('canvas');
              canvas.width = width;
              canvas.height = height;
              const ctx = canvas.getContext('2d');
              ctx.imageSmoothingEnabled = true;
              ctx.imageSmoothingQuality = 'high';
              ctx.drawImage(img, 0, 0, width, height);

              let out = canvas.toDataURL('image/webp', quality);
              if (!out || !out.startsWith('data:image/webp')) {
                out = canvas.toDataURL('image/jpeg', quality);
              }
              resolve(out || e.target.result);
            } catch (err) {
              resolve(e.target.result);
            }
          };
          img.onerror = () => resolve(e.target.result);
          img.src = e.target.result;
        };
        reader.onerror = () => resolve('');
        reader.readAsDataURL(file);
      });
    }

    // Recompress existing base64 string
    function recompressDataUrl(dataUrl, maxWidth = 640, maxHeight = 640, quality = 0.75) {
      return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => {
          try {
            let width = img.width;
            let height = img.height;
            if (width > maxWidth || height > maxHeight) {
              if (width > height) {
                height = Math.round((height * maxWidth) / width);
                width = maxWidth;
              } else {
                width = Math.round((width * maxHeight) / height);
                height = maxHeight;
              }
            }
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            ctx.drawImage(img, 0, 0, width, height);

            let out = canvas.toDataURL('image/webp', quality);
            if (!out || !out.startsWith('data:image/webp')) {
              out = canvas.toDataURL('image/jpeg', quality);
            }
            resolve(out);
          } catch (err) {
            resolve(dataUrl);
          }
        };
        img.onerror = () => resolve(dataUrl);
        img.src = dataUrl;
      });
    }

    // Automatically shrink any bloated products (>80KB) in background
    async function autoCompressExistingProducts() {
      if (!appData || !Array.isArray(appData.products)) return;
      let needsSave = false;
      for (let p of appData.products) {
        if (p.image && p.image.startsWith('data:image') && p.image.length > 80000) {
          try {
            const shrunk = await recompressDataUrl(p.image, 640, 640, 0.75);
            if (shrunk && shrunk.length < p.image.length) {
              p.image = shrunk;
              needsSave = true;
            }
          } catch(e) {}
        }
      }
      if (needsSave) {
        await REVO_DB.saveAll(appData.products);
        try {
          localStorage.setItem('revo_custom_products', JSON.stringify(appData.products));
          localStorage.setItem('revo_content_backup', JSON.stringify(appData));
        } catch(e) {}
        renderProducts();
      }
    }

    async function handleImageFile(file) {
      if (!file) return;
      const isImage = (file.type && file.type.startsWith('image/')) || /\.(jpe?g|png|webp|svg|gif|bmp|jfif)$/i.test(file.name);
      if (!isImage) {
        showToast('Sila pilih fail berformat gambar (PNG, JPG, WEBP, SVG)', true);
        return;
      }

      previewFileName.textContent = file.name;
      showToast('Memproses gambar...');

      try {
        // 1. Instant preview so user immediately sees the change in modal
        const rawDataUrl = await new Promise((res) => {
          const r = new FileReader();
          r.onload = () => res(r.result);
          r.onerror = () => res('');
          r.readAsDataURL(file);
        });

        if (rawDataUrl) {
          imgPreviewTag.src = rawDataUrl;
          dropzonePrompt.style.display = 'none';
          dropzonePreview.style.display = 'flex';
          prodImageInput.value = rawDataUrl;
        }

        // 2. High-speed Canvas compression down to ~25KB WebP
        const compressed = await compressImage(file, 640, 640, 0.75);
        if (compressed) {
          imgPreviewTag.src = compressed;
          prodImageInput.value = compressed;
          const sizeKb = Math.round((compressed.length * 0.75) / 1024);
          previewFileName.textContent = `${file.name} (${sizeKb} KB)`;
        }

        showToast('Gambar sedia! Tekan "Simpan Produk".');

        // 3. Optional server upload if api.php is active
        try {
          const res = await fetch('api.php?action=upload_image', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Admin-Token': 'revo_secret_admin_2026'
            },
            body: JSON.stringify({
              base64: compressed || rawDataUrl,
              name: file.name
            })
          });
          const json = await res.json();
          if (json.success && json.data && json.data.url) {
            prodImageInput.value = json.data.url;
            showToast('Gambar dimuat naik & disimpan di pelayan!');
          }
        } catch (netErr) {}

      } catch (err) {
        showToast('Gagal memproses gambar: ' + err.message, true);
      }
    }

    document.getElementById('btnRemoveImage').addEventListener('click', (e) => {
      e.stopPropagation();
      resetImageUpload();
    });

    function resetImageUpload() {
      prodImageFileInput.value = '';
      prodImageInput.value = '';
      imgPreviewTag.src = '';
      dropzonePrompt.style.display = 'block';
      dropzonePreview.style.display = 'none';
    }

    function setImagePreview(url) {
      if (url) {
        prodImageInput.value = url;
        let displaySrc = url;
        if (displaySrc.startsWith('./')) displaySrc = '.' + displaySrc;
        imgPreviewTag.src = displaySrc;
        previewFileName.textContent = url.startsWith('data:') ? 'gambar_kustom.webp' : (url.split('/').pop() || 'gambar_produk.png');
        dropzonePrompt.style.display = 'none';
        dropzonePreview.style.display = 'flex';
      } else {
        resetImageUpload();
      }
    }

    document.getElementById('btnOpenAddProd').addEventListener('click', () => {
      document.getElementById('prodModalTitle').textContent = 'Tambah Produk Baru';
      document.getElementById('editProdId').value = '';
      document.getElementById('productForm').reset();
      document.getElementById('prodMoqInput').value = '50';
      document.getElementById('prodActiveCheck').checked = true;
      resetImageUpload();
      prodModal.classList.add('active');
    });

    document.getElementById('btnCloseProdModal').addEventListener('click', () => prodModal.classList.remove('active'));
    document.getElementById('btnCancelProdModal').addEventListener('click', () => prodModal.classList.remove('active'));

    window.editProduct = function(id) {
      const p = appData.products.find(item => item.id === id);
      if (!p) return;
      document.getElementById('prodModalTitle').textContent = 'Sunting Produk: ' + p.name;
      document.getElementById('editProdId').value = p.id;
      document.getElementById('prodNameInput').value = p.name;
      document.getElementById('prodCategorySelect').value = p.category;
      document.getElementById('prodSeriesSelect').value = p.series;
      document.getElementById('prodMetalSelect').value = p.metal;
      document.getElementById('prodWeightInput').value = p.weight;
      document.getElementById('prodPriceInput').value = p.price;
      document.getElementById('prodMoqInput').value = p.moq || 50;
      document.getElementById('prodDescInput').value = p.desc || '';
      document.getElementById('prodActiveCheck').checked = p.active !== false;
      setImagePreview(p.image);
      prodModal.classList.add('active');
    };

    window.deleteProduct = async function(id) {
      const p = appData.products.find(item => item.id === id);
      if (!p) return;
      if (confirm(`Adakah anda pasti mahu memadamkan produk "${p.name}"?`)) {
        appData.products = appData.products.filter(item => item.id !== id);
        renderProducts();
        await saveContent();
      }
    };

    document.getElementById('productForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('editProdId').value;
      const imageUrl = prodImageInput.value.trim() || './assets/images/products/card_gold_0001g.png';

      const prodData = {
        id: id || ('prod-' + Date.now()),
        name: document.getElementById('prodNameInput').value.trim(),
        category: document.getElementById('prodCategorySelect').value,
        series: document.getElementById('prodSeriesSelect').value,
        metal: document.getElementById('prodMetalSelect').value,
        weight: document.getElementById('prodWeightInput').value.trim(),
        price: parseFloat(document.getElementById('prodPriceInput').value) || 0,
        moq: parseInt(document.getElementById('prodMoqInput').value, 10) || 50,
        image: imageUrl,
        desc: document.getElementById('prodDescInput').value.trim(),
        active: document.getElementById('prodActiveCheck').checked
      };

      if (id) {
        const idx = appData.products.findIndex(item => item.id === id);
        if (idx !== -1) {
          appData.products[idx] = prodData;
        }
      } else {
        appData.products.unshift(prodData);
      }

      // Close modal immediately
      prodModal.classList.remove('active');

      // CRITICAL: Update table UI right away so the user immediately sees the new image!
      renderProducts();
      showToast('Gambar berjaya dikemaskini!');

      // Save to IndexedDB, localStorage & server
      await saveContent();
    });

    // ==========================================
    // TAB 2: IN-PLACE LIVE VISUAL LANDING PAGE EDITOR
    // ==========================================
    const visualFrame = document.getElementById('visualEditorFrame');

    function initVisualEditorIframe() {
      try {
        const frameDoc = visualFrame.contentDocument || visualFrame.contentWindow.document;
        if (!frameDoc || !frameDoc.body) return;

        // Inject Visual Editor interactive styling into iframe
        let styleTag = frameDoc.getElementById('revoVisualEditorStyles');
        if (!styleTag) {
          styleTag = frameDoc.createElement('style');
          styleTag.id = 'revoVisualEditorStyles';
          styleTag.textContent = `
            [data-editable-node="true"] {
              transition: all 0.2s ease;
            }
            [data-editable-node="true"]:hover {
              outline: 2px dashed #D4AF37 !important;
              outline-offset: 4px !important;
              background: rgba(212, 175, 55, 0.12) !important;
              border-radius: 4px !important;
              cursor: text !important;
            }
            [data-editable-node="true"]:focus {
              outline: 2.5px solid #F3E5AB !important;
              outline-offset: 4px !important;
              background: rgba(212, 175, 55, 0.22) !important;
              box-shadow: 0 0 16px rgba(212, 175, 55, 0.5) !important;
              border-radius: 4px !important;
            }
          `;
          frameDoc.head.appendChild(styleTag);
        }

        // Target all editable text selectors on the landing page
        const editableSelectors = [
          'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
          'p', '.ref-hero-badge', '.ref-hero-kicker', '.ref-hero-motto',
          '.section-kicker', '.section-title', '.section-desc',
          '.pillar-title', '.pillar-text', '.value-chip',
          '.use-compact-pill span', '.step-min-title',
          '.audience-title', '.audience-list li',
          '.guarantee-title', '.guarantee-text',
          '.pure-card-btn-ws span', '.legacy-pillar-title', '.legacy-pillar-desc',
          '.ref-footer-about', '.footer-link-group a', '.footer-bottom-copy'
        ];

        frameDoc.querySelectorAll(editableSelectors.join(', ')).forEach(node => {
          node.setAttribute('contenteditable', 'true');
          node.setAttribute('spellcheck', 'false');
          node.setAttribute('data-editable-node', 'true');
        });

        // Prevent navigation when clicking links inside iframe during editing
        frameDoc.querySelectorAll('a').forEach(link => {
          link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href && href.startsWith('#')) {
              // Allow smooth scroll anchors to work inside frame
              const target = frameDoc.querySelector(href);
              if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
              }
            } else {
              e.preventDefault(); // Don't leave page
            }
          });
        });

      } catch (err) {
        // Cross-origin fallback if running under restricted file:// protocol
      }
    }

    visualFrame.addEventListener('load', initVisualEditorIframe);

    function refreshVisualEditor() {
      visualFrame.src = '../index.html?t=' + Date.now();
    }

    document.getElementById('btnReloadVisualEditor').addEventListener('click', refreshVisualEditor);

    // Save In-Place Visual Landing Page Changes
    document.getElementById('btnSaveVisualChanges').addEventListener('click', async () => {
      try {
        const frameDoc = visualFrame.contentDocument || visualFrame.contentWindow.document;
        if (!frameDoc || !frameDoc.documentElement) {
          showToast('Gagal mengakses kandungan pratonton visual', true);
          return;
        }

        // Clone document to clean up editor temporary attributes
        const clonedDoc = frameDoc.documentElement.cloneNode(true);

        // Remove injected editor stylesheet
        const injectedStyle = clonedDoc.querySelector('#revoVisualEditorStyles');
        if (injectedStyle) injectedStyle.remove();

        // Strip contenteditable and editor markers
        clonedDoc.querySelectorAll('[data-editable-node="true"]').forEach(el => {
          el.removeAttribute('contenteditable');
          el.removeAttribute('spellcheck');
          el.removeAttribute('data-editable-node');
        });

        const fullHtml = '<!DOCTYPE html>\n<html lang="ms">\n' + clonedDoc.innerHTML + '\n</html>';

        // 1. Save directly to index.html and index.php via PHP API
        try {
          const res = await fetch('api.php?action=save_landing_html', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ html: fullHtml })
          });
          const json = await res.json();
          if (json.success) {
            showToast('Semua perubahan visual berjaya disimpan terus ke laman web!');
            localStorage.setItem('revo_landing_custom_html', fullHtml);
            return;
          }
        } catch (err) {}

        // Fallback: save to localStorage
        localStorage.setItem('revo_landing_custom_html', fullHtml);
        showToast('Semua perubahan visual disimpan di pelayar!');

      } catch (err) {
        showToast('Ralat semasa menyimpan perubahan visual', true);
      }
    });

    document.getElementById('btnPublishStatic').addEventListener('click', () => saveContent());
    document.getElementById('btnDownloadJson').addEventListener('click', downloadContentJson);
    document.getElementById('btnResetDefault').addEventListener('click', resetToDefault);
    document.getElementById('btnLogout').addEventListener('click', () => {
      localStorage.removeItem('revo_admin_auth');
      window.location.href = 'login.html';
    });

    loadContent();
  </script>
</body>
</html>
