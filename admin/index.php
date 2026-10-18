<?php
session_start();
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
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
      --bg-dark: #07070a;
      --bg-surface: #0e0e14;
      --bg-card: #13131c;
      --bg-card-hover: #191924;
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
      padding: 0.9rem 1.8rem;
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
      max-width: 1400px;
      width: 100%;
      margin: 0 auto;
      padding: 1.8rem;
    }

    .tabs-header {
      display: flex;
      gap: 0.5rem;
      border-bottom: 1px solid var(--gold-border);
      padding-bottom: 0.5rem;
      margin-bottom: 1.8rem;
      overflow-x: auto;
    }

    .tab-btn {
      padding: 0.65rem 1.2rem;
      background: transparent;
      border: none;
      color: var(--text-secondary);
      font-size: 0.88rem;
      font-weight: 600;
      cursor: pointer;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      white-space: nowrap;
      transition: all 0.2s ease;
    }

    .tab-btn:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.04);
    }

    .tab-btn.active {
      color: #000;
      background: var(--gold-primary);
      font-weight: 700;
    }

    /* Content Panes */
    .tab-pane {
      display: none;
      animation: fadeIn 0.3s ease;
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
      padding: 0.4rem 0.9rem;
      border-radius: 9999px;
      border: 1px solid var(--gold-border);
      background: rgba(0, 0, 0, 0.3);
      color: var(--text-secondary);
      font-size: 0.78rem;
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
      padding: 0.5rem 0.9rem;
      background: rgba(0, 0, 0, 0.4);
      border: 1px solid var(--gold-border);
      border-radius: var(--radius-sm);
      color: #fff;
      font-size: 0.84rem;
      outline: none;
      min-width: 220px;
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
      font-size: 0.85rem;
    }

    table.data-table th {
      padding: 0.8rem 1rem;
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
      background: rgba(212, 175, 55, 0.03);
    }

    .prod-thumb {
      width: 44px;
      height: 60px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid var(--gold-border);
      background: #000;
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

    .badge-gold { background: rgba(212, 175, 55, 0.18); color: var(--gold-light); border: 1px solid rgba(212, 175, 55, 0.35); }
    .badge-silver { background: rgba(148, 163, 184, 0.18); color: #e2e8f0; border: 1px solid rgba(148, 163, 184, 0.35); }
    .badge-vvip { background: rgba(168, 85, 247, 0.18); color: #d8b4fe; border: 1px solid rgba(168, 85, 247, 0.35); }

    .price-tag {
      font-weight: 800;
      color: var(--gold-light);
      font-size: 0.95rem;
    }

    /* Modal */
    .modal-backdrop {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(8px);
      z-index: 100;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .modal-backdrop.active {
      display: flex;
    }

    .modal-box {
      width: 100%;
      max-width: 620px;
      max-height: 90vh;
      overflow-y: auto;
      background: var(--bg-surface);
      border: 1px solid var(--gold-primary);
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

    .form-full {
      grid-column: span 2;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-label {
      display: block;
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--gold-light);
      margin-bottom: 0.35rem;
      letter-spacing: 0.04em;
    }

    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 0.65rem 0.9rem;
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

    .form-textarea {
      resize: vertical;
      min-height: 80px;
      font-family: inherit;
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

    .toast-notice.show {
      transform: translateY(0);
      opacity: 1;
    }

    .toast-notice.error {
      background: rgba(239, 68, 68, 0.95);
      color: #fff;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .admin-navbar { padding: 0.8rem 1rem; }
      .brand-title { font-size: 1rem; }
      .brand-badge { display: none; }
      .admin-main { padding: 1rem; }
      .form-grid { grid-template-columns: 1fr; }
      .form-full { grid-column: span 1; }
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

      <button type="button" class="btn btn-gold" id="btnPublishStatic" title="Simpan dan Kemaskini Fail index.html untuk GitHub Pages">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
        <span>Simpan & Publish</span>
      </button>

      <button type="button" class="btn btn-danger" id="btnLogout" title="Log Keluar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        <span>Keluar</span>
      </button>
    </div>
  </header>

  <!-- Main Container -->
  <main class="admin-main">

    <!-- Navigation Tabs -->
    <nav class="tabs-header" role="tablist">
      <button type="button" class="tab-btn active" data-tab="tab-products">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 8.5 12 15 22 8.5 12 2"></polygon><polygon points="2 15.5 12 22 22 15.5"></polygon></svg>
        <span>Katalog Produk (CRUD)</span>
      </button>
      <button type="button" class="tab-btn" data-tab="tab-packaging">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line></svg>
        <span>Pembungkusan</span>
      </button>
      <button type="button" class="tab-btn" data-tab="tab-content">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
        <span>Teks & Kandungan Laman</span>
      </button>
      <button type="button" class="tab-btn" data-tab="tab-uses-faq">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        <span>Kegunaan & FAQ</span>
      </button>
      <button type="button" class="tab-btn" data-tab="tab-settings">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <span>Maklumat Syarikat</span>
      </button>
    </nav>

    <!-- ==============================================
         TAB 1: PRODUK & HARGA (CRUD)
         ============================================== -->
    <section id="tab-products" class="tab-pane active">
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Katalog Kad Emas & Perak</h2>
            <p class="panel-sub">Urus senarai produk, gramatur, harga seunit, gambar dan maklumat tempahan.</p>
          </div>
          <button type="button" class="btn btn-gold" id="btnOpenAddProd">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Tambah Produk Baru</span>
          </button>
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
            <tbody id="productsTableBody">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ==============================================
         TAB 2: PAKEJ PEMBUNGKUSAN (CRUD)
         ============================================== -->
    <section id="tab-packaging" class="tab-pane">
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">4 Pilihan Pembungkusan Premium</h2>
            <p class="panel-sub">Kemaskini nama pakej, tag tarikan, harga tambahan (RM), dan ciri-ciri pembungkusan.</p>
          </div>
          <button type="button" class="btn btn-gold btn-save-pack">
            <span>Simpan Pembungkusan</span>
          </button>
        </div>

        <div id="packagingFormContainer" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
          <!-- Rendered via JS -->
        </div>
      </div>
    </section>

    <!-- ==============================================
         TAB 3: TEKS & KANDUNGAN LAMAN
         ============================================== -->
    <section id="tab-content" class="tab-pane">
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Bahagian Hero (Laman Utama)</h2>
            <p class="panel-sub">Ubah teks utama yang terpapar pada skrin pertama pelawat.</p>
          </div>
          <button type="button" class="btn btn-gold btn-save-hero">
            <span>Simpan Hero</span>
          </button>
        </div>

        <form id="heroForm">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Teks Tag Pill Atas</label>
              <input type="text" class="form-input" id="heroPillTag" placeholder="REVO BULLION GOLD & SILVER CARD">
            </div>

            <div class="form-group">
              <label class="form-label">Teks Kicker Emas</label>
              <input type="text" class="form-input" id="heroKicker" placeholder="KAD EMAS DAN PERAK EKSKLUSIF UNTUK SETIAP MOMEN BERMAKNA">
            </div>

            <div class="form-group">
              <label class="form-label">Tajuk Utama Baris 1</label>
              <input type="text" class="form-input" id="heroHeadLine1" placeholder="Setiap Detik Istimewa,">
            </div>

            <div class="form-group">
              <label class="form-label">Tajuk Utama Baris 2 (Italik)</label>
              <input type="text" class="form-input" id="heroHeadLine2" placeholder="Diabadikan dengan...">
            </div>

            <div class="form-group form-full">
              <label class="form-label">Huraian Perenggan Utama</label>
              <textarea class="form-textarea" id="heroDescription" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Teks Butang Tindakan (CTA)</label>
              <input type="text" class="form-input" id="heroCtaText" placeholder="Terokai Koleksi">
            </div>

            <div class="form-group">
              <label class="form-label">Motto Jenama Bawah</label>
              <input type="text" class="form-input" id="heroMotto" placeholder='"PRECIOUS MOMENTS LAST FOREVER"'>
            </div>
          </div>
        </form>
      </div>

      <!-- Visi & Misi Korporat -->
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Pengenalan, Visi & Misi</h2>
            <p class="panel-sub">Falsafah perniagaan dan matlamat korporat REVO BULLION.</p>
          </div>
          <button type="button" class="btn btn-gold btn-save-about">
            <span>Simpan Visi & Misi</span>
          </button>
        </div>

        <form id="aboutForm">
          <div class="form-group">
            <label class="form-label">Huraian Pengenalan</label>
            <textarea class="form-textarea" id="aboutDesc" rows="3"></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">Visi Korporat</label>
            <textarea class="form-textarea" id="aboutVisi" rows="2"></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">Misi Korporat (Satu baris untuk setiap poin)</label>
            <textarea class="form-textarea" id="aboutMisi" rows="4"></textarea>
          </div>
        </form>
      </div>
    </section>

    <!-- ==============================================
         TAB 4: 12 KEGUNAAN & FAQ
         ============================================== -->
    <section id="tab-uses-faq" class="tab-pane">
      <!-- 12 Kegunaan Produk -->
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">12 Kegunaan Produk REVO BULLION</h2>
            <p class="panel-sub">Urus senarai kegunaan emas & perak untuk inspirasi pelanggan.</p>
          </div>
          <button type="button" class="btn btn-gold btn-save-uses">
            <span>Simpan Kegunaan</span>
          </button>
        </div>

        <div id="usesListContainer" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
          <!-- Rendered via JS -->
        </div>
      </div>

      <!-- FAQ Soalan Lazim -->
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Soalan Lazim (FAQ)</h2>
            <p class="panel-sub">Pertanyaan umum pelanggan mengenai ketulenan, MOQ dan rekaan custom.</p>
          </div>
          <button type="button" class="btn btn-outline" id="btnAddFaq">
            <span>+ Tambah Soalan FAQ</span>
          </button>
          <button type="button" class="btn btn-gold btn-save-faq">
            <span>Simpan FAQ</span>
          </button>
        </div>

        <div id="faqListContainer" style="display: flex; flex-direction: column; gap: 1rem;">
          <!-- Rendered via JS -->
        </div>
      </div>
    </section>

    <!-- ==============================================
         TAB 5: MAKLUMAT SYARIKAT & HUBUNGAN
         ============================================== -->
    <section id="tab-settings" class="tab-pane">
      <div class="panel-card">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Maklumat Syarikat & Hubungan Rasmi</h2>
            <p class="panel-sub">Nombor WhatsApp, alamat e-mel, nama domain dan pautan media sosial rasmi.</p>
          </div>
          <button type="button" class="btn btn-gold btn-save-settings">
            <span>Simpan Maklumat</span>
          </button>
        </div>

        <form id="settingsForm">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Nama Jenama</label>
              <input type="text" class="form-input" id="setBrand" placeholder="REVO BULLION">
            </div>

            <div class="form-group">
              <label class="form-label">Tagline Utama</label>
              <input type="text" class="form-input" id="setTagline" placeholder="Precious Moments Last Forever">
            </div>

            <div class="form-group">
              <label class="form-label">Nombor WhatsApp (Paparan)</label>
              <input type="text" class="form-input" id="setWhatsapp" placeholder="011-1188 6654">
            </div>

            <div class="form-group">
              <label class="form-label">Nombor WhatsApp (Antarabangsa)</label>
              <input type="text" class="form-input" id="setWhatsappRaw" placeholder="601111886654">
            </div>

            <div class="form-group">
              <label class="form-label">Alamat E-mel</label>
              <input type="email" class="form-input" id="setEmail" placeholder="smgmh23@gmail.com">
            </div>

            <div class="form-group">
              <label class="form-label">Nama Domain Utama</label>
              <input type="text" class="form-input" id="setDomain" placeholder="revobullion.my.id">
            </div>

            <div class="form-group">
              <label class="form-label">Instagram Handle</label>
              <input type="text" class="form-input" id="setIg" placeholder="@revobullion">
            </div>

            <div class="form-group">
              <label class="form-label">TikTok Handle</label>
              <input type="text" class="form-input" id="setTiktok" placeholder="@revobullion">
            </div>

            <div class="form-group">
              <label class="form-label">Facebook Page</label>
              <input type="text" class="form-input" id="setFb" placeholder="Revo Bullion">
            </div>

            <div class="form-group">
              <label class="form-label">URL Laman Web Rasmi</label>
              <input type="url" class="form-input" id="setWebsite" placeholder="https://www.revobullion.com">
            </div>
          </div>
        </form>
      </div>

      <!-- Tukar Kata Laluan Admin -->
      <div class="panel-card" style="max-width: 500px;">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Tukar Kata Laluan Admin</h2>
            <p class="panel-sub">Pastikan akaun pentadbir anda kekal selamat.</p>
          </div>
        </div>

        <form id="changePasswordForm">
          <div class="form-group">
            <label class="form-label">Kata Laluan Semasa</label>
            <input type="password" class="form-input" id="oldPassword" required placeholder="••••••••">
          </div>
          <div class="form-group">
            <label class="form-label">Kata Laluan Baru (Min. 6 aksara)</label>
            <input type="password" class="form-input" id="newPassword" required placeholder="••••••••">
          </div>
          <button type="submit" class="btn btn-gold">
            <span>Kemas Kini Kata Laluan</span>
          </button>
        </form>
      </div>
    </section>

  </main>

  <!-- ==============================================
       MODAL: TAMBAH / SUNTHING PRODUK
       ============================================== -->
  <div class="modal-backdrop" id="productModal">
    <div class="modal-box">
      <button type="button" class="modal-close" id="btnCloseProdModal">&times;</button>
      <h3 style="font-family: 'Cinzel', serif; font-size: 1.3rem; margin-bottom: 1.2rem; color: #fff;" id="prodModalTitle">Tambah Produk Baru</h3>

      <form id="productForm">
        <input type="hidden" id="editProdId" value="">

        <div class="form-grid">
          <div class="form-group form-full">
            <label class="form-label">Nama Produk Lengkap</label>
            <input type="text" class="form-input" id="prodNameInput" required placeholder="Cth: Baby Gold Card Custom 0.001g">
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
            <input type="text" class="form-input" id="prodWeightInput" required placeholder="Cth: 0.001g atau 5g">
          </div>

          <div class="form-group">
            <label class="form-label">Harga Seunit (RM)</label>
            <input type="number" step="0.01" class="form-input" id="prodPriceInput" required placeholder="4.44">
          </div>

          <div class="form-group">
            <label class="form-label">Kuantiti Minimum (MOQ)</label>
            <input type="number" class="form-input" id="prodMoqInput" required value="50">
          </div>

          <div class="form-group form-full">
            <label class="form-label">Pautan / Fail Gambar Produk</label>
            <input type="text" class="form-input" id="prodImageInput" placeholder="./assets/images/products/card_gold_0001g.png">
            <div style="font-size: 0.74rem; color: var(--text-muted); margin-top: 0.3rem;">
              Boleh gunakan pautan fail sedia ada, atau pilih fail gambar baru di bawah untuk dimuat naik automatik.
            </div>
            <input type="file" id="prodImageFile" accept="image/*" style="margin-top: 0.4rem; font-size: 0.8rem; color: var(--gold-light);">
          </div>

          <div class="form-group form-full">
            <label class="form-label">Penerangan Produk</label>
            <textarea class="form-textarea" id="prodDescInput" rows="3" placeholder="Penerangan ringkas keistimewaan produk..."></textarea>
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

  <!-- Toast Notice -->
  <div id="adminToast" class="toast-notice">Kandungan berjaya disimpan!</div>

  <!-- Admin JavaScript Logic -->
  <script>
    let appData = null;
    let currentFilter = 'all';

    // Show Toast Helper
    function showToast(message, isError = false) {
      const toast = document.getElementById('adminToast');
      toast.textContent = message;
      toast.className = 'toast-notice' + (isError ? ' error show' : ' show');
      setTimeout(() => {
        toast.className = 'toast-notice';
      }, 3500);
    }

    // Tab Switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const targetId = btn.getAttribute('data-tab');
        document.getElementById(targetId).classList.add('active');
      });
    });

    // Fetch Content
    async function loadContent() {
      try {
        const res = await fetch('api.php?action=get_content');
        const json = await res.json();
        if (json.success) {
          appData = json.data;
          renderAll();
        } else {
          showToast('Gagal memuat turun data kandungan', true);
        }
      } catch (err) {
        showToast('Ralat sambungan fail data', true);
      }
    }

    // Save Content Back to API
    async function saveContent() {
      try {
        const res = await fetch('api.php?action=save_content', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(appData)
        });
        const json = await res.json();
        if (json.success) {
          showToast(json.message || 'Perubahan berjaya disimpan!');
          renderAll();
        } else {
          showToast(json.message || 'Gagal menyimpan', true);
        }
      } catch (err) {
        showToast('Ralat sambungan API pelayan', true);
      }
    }

    // Render Everything
    function renderAll() {
      if (!appData) return;
      renderProducts();
      renderPackaging();
      renderHero();
      renderAbout();
      renderUses();
      renderFaq();
      renderSettings();
    }

    // --- PRODUCTS LOGIC ---
    function renderProducts() {
      const tbody = document.getElementById('productsTableBody');
      const search = document.getElementById('searchProdInput').value.toLowerCase();
      let list = appData.products || [];

      // Counts
      document.getElementById('countAll').textContent = list.length;
      document.getElementById('countGold').textContent = list.filter(p => p.category === 'gold').length;
      document.getElementById('countSilver').textContent = list.filter(p => p.category === 'silver').length;
      document.getElementById('countVvip').textContent = list.filter(p => p.category === 'vvip').length;

      // Filter
      if (currentFilter !== 'all') {
        list = list.filter(p => p.category === currentFilter);
      }
      if (search) {
        list = list.filter(p => (p.name + ' ' + p.weight + ' ' + p.series).toLowerCase().includes(search));
      }

      if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-muted);">Tiada produk dijumpai.</td></tr>';
        return;
      }

      tbody.innerHTML = list.map(p => `
        <tr>
          <td>
            <img src=".${p.image}" alt="${p.name}" class="prod-thumb" onerror="this.src='../assets/images/favicon.png'">
          </td>
          <td>
            <strong style="color: #fff; font-size: 0.9rem;">${p.name}</strong>
            <div style="font-size: 0.74rem; color: var(--text-muted);">${p.desc ? p.desc.substring(0, 60) + '...' : ''}</div>
          </td>
          <td>
            <span class="badge badge-${p.category}">${p.category}</span>
          </td>
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
      `).join('');
    }

    // Filter pills
    document.querySelectorAll('.filter-pills .pill-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-pills .pill-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = btn.getAttribute('data-filter');
        renderProducts();
      });
    });

    document.getElementById('searchProdInput').addEventListener('input', renderProducts);

    // Modal Add / Edit
    const prodModal = document.getElementById('productModal');
    document.getElementById('btnOpenAddProd').addEventListener('click', () => {
      document.getElementById('prodModalTitle').textContent = 'Tambah Produk Baru';
      document.getElementById('editProdId').value = '';
      document.getElementById('productForm').reset();
      document.getElementById('prodMoqInput').value = '50';
      document.getElementById('prodActiveCheck').checked = true;
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
      document.getElementById('prodImageInput').value = p.image || '';
      document.getElementById('prodDescInput').value = p.desc || '';
      document.getElementById('prodActiveCheck').checked = p.active !== false;
      prodModal.classList.add('active');
    };

    window.deleteProduct = function(id) {
      const p = appData.products.find(item => item.id === id);
      if (!p) return;
      if (confirm(`Adakah anda pasti mahu memadamkan produk "${p.name}"?`)) {
        appData.products = appData.products.filter(item => item.id !== id);
        saveContent();
      }
    };

    // Product Form Submit
    document.getElementById('productForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('editProdId').value;
      const fileInput = document.getElementById('prodImageFile');

      let imageUrl = document.getElementById('prodImageInput').value.trim();

      // If a file was selected, upload it first
      if (fileInput.files.length > 0) {
        const formData = new FormData();
        formData.append('image', fileInput.files[0]);
        try {
          const upRes = await fetch('api.php?action=upload_image', {
            method: 'POST',
            body: formData
          });
          const upJson = await upRes.json();
          if (upJson.success) {
            imageUrl = upJson.data.url;
          } else {
            alert('Gagal muat naik gambar: ' + upJson.message);
          }
        } catch (err) {
          alert('Ralat semasa muat naik fail gambar.');
        }
      }

      if (!imageUrl) {
        imageUrl = './assets/images/products/card_gold_0001g.png';
      }

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
        // Update existing
        const idx = appData.products.findIndex(item => item.id === id);
        if (idx !== -1) appData.products[idx] = prodData;
      } else {
        // Add new
        appData.products.unshift(prodData);
      }

      prodModal.classList.remove('active');
      saveContent();
    });

    // --- PACKAGING LOGIC ---
    function renderPackaging() {
      const container = document.getElementById('packagingFormContainer');
      const packList = appData.packaging || [];
      container.innerHTML = packList.map((pack, idx) => `
        <div class="panel-card" style="margin-bottom: 0;">
          <h4 style="font-family: 'Cinzel', serif; color: var(--gold-light); margin-bottom: 0.8rem;">${pack.name}</h4>
          <div class="form-group">
            <label class="form-label">Tag Lencana</label>
            <input type="text" class="form-input pack-tag" data-idx="${idx}" value="${pack.tag}">
          </div>
          <div class="form-group">
            <label class="form-label">Harga Tambahan (RM)</label>
            <input type="number" step="0.5" class="form-input pack-price" data-idx="${idx}" value="${pack.priceExtra}">
          </div>
          <div class="form-group">
            <label class="form-label">Huraian</label>
            <textarea class="form-textarea pack-desc" data-idx="${idx}">${pack.desc}</textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Ciri-Ciri (Satu per baris)</label>
            <textarea class="form-textarea pack-features" data-idx="${idx}" rows="3">${(pack.features || []).join('\n')}</textarea>
          </div>
        </div>
      `).join('');
    }

    document.querySelectorAll('.btn-save-pack').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.pack-tag').forEach(el => {
          const idx = el.getAttribute('data-idx');
          appData.packaging[idx].tag = el.value.trim();
        });
        document.querySelectorAll('.pack-price').forEach(el => {
          const idx = el.getAttribute('data-idx');
          appData.packaging[idx].priceExtra = parseFloat(el.value) || 0;
        });
        document.querySelectorAll('.pack-desc').forEach(el => {
          const idx = el.getAttribute('data-idx');
          appData.packaging[idx].desc = el.value.trim();
        });
        document.querySelectorAll('.pack-features').forEach(el => {
          const idx = el.getAttribute('data-idx');
          appData.packaging[idx].features = el.value.split('\n').map(s => s.trim()).filter(Boolean);
        });
        saveContent();
      });
    });

    // --- HERO LOGIC ---
    function renderHero() {
      const h = appData.hero || {};
      document.getElementById('heroPillTag').value = h.pillTag || '';
      document.getElementById('heroKicker').value = h.kicker || '';
      document.getElementById('heroHeadLine1').value = h.headlineLine1 || '';
      document.getElementById('heroHeadLine2').value = h.headlineLine2 || '';
      document.getElementById('heroDescription').value = h.description || '';
      document.getElementById('heroCtaText').value = h.ctaText || '';
      document.getElementById('heroMotto').value = h.motto || '';
    }

    document.querySelector('.btn-save-hero').addEventListener('click', () => {
      appData.hero = {
        pillTag: document.getElementById('heroPillTag').value.trim(),
        kicker: document.getElementById('heroKicker').value.trim(),
        headlineLine1: document.getElementById('heroHeadLine1').value.trim(),
        headlineLine2: document.getElementById('heroHeadLine2').value.trim(),
        description: document.getElementById('heroDescription').value.trim(),
        ctaText: document.getElementById('heroCtaText').value.trim(),
        ctaTarget: '#koleksi',
        motto: document.getElementById('heroMotto').value.trim()
      };
      saveContent();
    });

    // --- ABOUT LOGIC ---
    function renderAbout() {
      const a = appData.about || {};
      document.getElementById('aboutDesc').value = a.desc || '';
      document.getElementById('aboutVisi').value = a.visi || '';
      document.getElementById('aboutMisi').value = (a.misi || []).join('\n');
    }

    document.querySelector('.btn-save-about').addEventListener('click', () => {
      appData.about.desc = document.getElementById('aboutDesc').value.trim();
      appData.about.visi = document.getElementById('aboutVisi').value.trim();
      appData.about.misi = document.getElementById('aboutMisi').value.split('\n').map(s => s.trim()).filter(Boolean);
      saveContent();
    });

    // --- USES LOGIC ---
    function renderUses() {
      const container = document.getElementById('usesListContainer');
      const uses = appData.uses || [];
      container.innerHTML = uses.map((u, idx) => `
        <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--gold-border); border-radius: 8px; padding: 0.9rem;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
            <strong style="color: var(--gold-light); font-size: 0.85rem;">Poin ${u.num}</strong>
          </div>
          <input type="text" class="form-input use-title" data-idx="${idx}" value="${u.title}" style="margin-bottom: 0.4rem; font-weight: 700;">
          <textarea class="form-textarea use-desc" data-idx="${idx}" rows="2" style="font-size: 0.78rem;">${u.desc}</textarea>
        </div>
      `).join('');
    }

    document.querySelector('.btn-save-uses').addEventListener('click', () => {
      document.querySelectorAll('.use-title').forEach(el => {
        const idx = el.getAttribute('data-idx');
        appData.uses[idx].title = el.value.trim();
      });
      document.querySelectorAll('.use-desc').forEach(el => {
        const idx = el.getAttribute('data-idx');
        appData.uses[idx].desc = el.value.trim();
      });
      saveContent();
    });

    // --- FAQ LOGIC ---
    function renderFaq() {
      const container = document.getElementById('faqListContainer');
      const faqs = appData.faq || [];
      container.innerHTML = faqs.map((f, idx) => `
        <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--gold-border); border-radius: 8px; padding: 1rem; position: relative;">
          <button type="button" onclick="deleteFaq(${idx})" style="position: absolute; top: 0.8rem; right: 0.8rem; background: transparent; border: none; color: var(--danger); cursor: pointer; font-size: 0.8rem;">Padam</button>
          <div class="form-group" style="margin-bottom: 0.5rem;">
            <label class="form-label">Soalan ${idx + 1}</label>
            <input type="text" class="form-input faq-q" data-idx="${idx}" value="${f.q}" style="font-weight: 700;">
          </div>
          <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Jawapan</label>
            <textarea class="form-textarea faq-a" data-idx="${idx}" rows="2">${f.a}</textarea>
          </div>
        </div>
      `).join('');
    }

    document.getElementById('btnAddFaq').addEventListener('click', () => {
      if (!appData.faq) appData.faq = [];
      appData.faq.push({
        q: 'Soalan Baru?',
        a: 'Jawapan ringkas di sini...'
      });
      renderFaq();
    });

    window.deleteFaq = function(idx) {
      if (confirm('Padamkan soalan FAQ ini?')) {
        appData.faq.splice(idx, 1);
        renderFaq();
      }
    };

    document.querySelector('.btn-save-faq').addEventListener('click', () => {
      document.querySelectorAll('.faq-q').forEach(el => {
        const idx = el.getAttribute('data-idx');
        appData.faq[idx].q = el.value.trim();
      });
      document.querySelectorAll('.faq-a').forEach(el => {
        const idx = el.getAttribute('data-idx');
        appData.faq[idx].a = el.value.trim();
      });
      saveContent();
    });

    // --- SETTINGS LOGIC ---
    function renderSettings() {
      const s = appData.site || {};
      document.getElementById('setBrand').value = s.brand || '';
      document.getElementById('setTagline').value = s.tagline || '';
      document.getElementById('setWhatsapp').value = s.whatsapp || '';
      document.getElementById('setWhatsappRaw').value = s.whatsappRaw || '';
      document.getElementById('setEmail').value = s.email || '';
      document.getElementById('setDomain').value = s.domain || '';
      document.getElementById('setIg').value = s.instagram || '';
      document.getElementById('setTiktok').value = s.tiktok || '';
      document.getElementById('setFb').value = s.facebook || '';
      document.getElementById('setWebsite').value = s.website || '';
    }

    document.querySelector('.btn-save-settings').addEventListener('click', () => {
      appData.site.brand = document.getElementById('setBrand').value.trim();
      appData.site.tagline = document.getElementById('setTagline').value.trim();
      appData.site.whatsapp = document.getElementById('setWhatsapp').value.trim();
      appData.site.whatsappRaw = document.getElementById('setWhatsappRaw').value.trim();
      appData.site.email = document.getElementById('setEmail').value.trim();
      appData.site.domain = document.getElementById('setDomain').value.trim();
      appData.site.instagram = document.getElementById('setIg').value.trim();
      appData.site.tiktok = document.getElementById('setTiktok').value.trim();
      appData.site.facebook = document.getElementById('setFb').value.trim();
      appData.site.website = document.getElementById('setWebsite').value.trim();
      saveContent();
    });

    // Change Password
    document.getElementById('changePasswordForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const old_password = document.getElementById('oldPassword').value;
      const new_password = document.getElementById('newPassword').value;
      try {
        const res = await fetch('api.php?action=change_password', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ old_password, new_password })
        });
        const json = await res.json();
        if (json.success) {
          showToast(json.message);
          document.getElementById('changePasswordForm').reset();
        } else {
          showToast(json.message, true);
        }
      } catch (err) {
        showToast('Ralat sambungan pelayan', true);
      }
    });

    // Publish / Save Static
    document.getElementById('btnPublishStatic').addEventListener('click', () => {
      saveContent();
    });

    // Logout
    document.getElementById('btnLogout').addEventListener('click', async () => {
      try {
        await fetch('api.php?action=logout');
        window.location.href = 'login.php';
      } catch (err) {
        window.location.href = 'login.php';
      }
    });

    // Init
    loadContent();
  </script>
</body>
</html>
