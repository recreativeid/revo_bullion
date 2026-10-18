<?php
session_start();
if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — REVO BULLION CMS</title>
  <link rel="icon" type="image/png" href="../assets/images/favicon.png">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --bg-dark: #07070a;
      --bg-card: rgba(16, 16, 24, 0.85);
      --gold-primary: #D4AF37;
      --gold-light: #F3E5AB;
      --gold-dark: #997A15;
      --gold-border: rgba(212, 175, 55, 0.28);
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
      --radius-lg: 18px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: var(--bg-dark);
      background-image: 
        radial-gradient(ellipse 650px 380px at 50% 0%, rgba(212, 175, 55, 0.16), transparent 75%),
        radial-gradient(circle at 10% 90%, rgba(212, 175, 55, 0.05), transparent 40%);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
      background: var(--bg-card);
      border: 1px solid var(--gold-border);
      border-radius: var(--radius-lg);
      padding: 2.8rem 2.2rem;
      backdrop-filter: blur(20px);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(212, 175, 55, 0.08);
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 15%;
      right: 15%;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold-primary), transparent);
    }

    .login-emblem {
      width: 58px;
      height: 58px;
      margin: 0 auto 1.2rem;
      border-radius: 50%;
      border: 1.5px solid var(--gold-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle, rgba(212,175,55,0.25) 0%, rgba(0,0,0,0.6) 80%);
      box-shadow: 0 0 20px rgba(212, 175, 55, 0.35);
    }

    .login-emblem span {
      font-family: 'Cinzel', serif;
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--gold-primary);
    }

    .login-title {
      font-family: 'Cinzel', serif;
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      color: #fff;
      margin-bottom: 0.3rem;
    }

    .login-sub {
      font-size: 0.82rem;
      color: var(--text-muted);
      letter-spacing: 0.08em;
      text-transform: uppercase;
      margin-bottom: 2rem;
    }

    .form-group {
      text-align: left;
      margin-bottom: 1.3rem;
    }

    .form-label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--gold-light);
      margin-bottom: 0.4rem;
      letter-spacing: 0.05em;
    }

    .form-control {
      width: 100%;
      padding: 0.85rem 1.1rem;
      background: rgba(8, 8, 12, 0.8);
      border: 1px solid rgba(212, 175, 55, 0.22);
      border-radius: 10px;
      color: #fff;
      font-size: 0.95rem;
      outline: none;
      transition: all 0.25s ease;
    }

    .form-control:focus {
      border-color: var(--gold-primary);
      box-shadow: 0 0 15px rgba(212, 175, 55, 0.25);
      background: rgba(12, 12, 18, 0.95);
    }

    .login-btn {
      width: 100%;
      padding: 0.95rem 1.5rem;
      margin-top: 1rem;
      background: linear-gradient(135deg, #ECC440 0%, #D4AF37 50%, #A67C1E 100%);
      color: #050507;
      border: none;
      border-radius: 10px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(212, 175, 55, 0.35);
      transition: all 0.25s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
    }

    .login-btn:active {
      transform: translateY(0);
    }

    .alert-box {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 1.2rem;
      display: none;
      text-align: left;
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.4);
      color: #fca5a5;
    }

    .alert-success {
      background: rgba(34, 197, 94, 0.15);
      border: 1px solid rgba(34, 197, 94, 0.4);
      color: #86efac;
    }

    .back-link {
      display: inline-block;
      margin-top: 1.8rem;
      color: var(--text-muted);
      font-size: 0.85rem;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .back-link:hover {
      color: var(--gold-primary);
    }

    .creds-tip {
      margin-top: 1.5rem;
      padding: 0.8rem;
      background: rgba(255, 255, 255, 0.03);
      border: 1px dashed rgba(212, 175, 55, 0.2);
      border-radius: 8px;
      font-size: 0.78rem;
      color: var(--text-muted);
    }

    .creds-tip strong {
      color: var(--gold-light);
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-emblem">
      <span>R</span>
    </div>

    <h1 class="login-title">REVO BULLION</h1>
    <p class="login-sub">Sistem Pengurusan Kandungan (CMS)</p>

    <div id="alertBox" class="alert-box alert-danger"></div>

    <form id="loginForm" autocomplete="off">
      <div class="form-group">
        <label class="form-label" for="usernameInput">Nama Pengguna (Username)</label>
        <input type="text" id="usernameInput" class="form-control" placeholder="admin" required autofocus value="admin">
      </div>

      <div class="form-group">
        <label class="form-label" for="passwordInput">Kata Laluan (Password)</label>
        <input type="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
      </div>

      <button type="submit" class="login-btn" id="submitBtn">
        <span>Log Masuk ke Dashboard</span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
      </button>
    </form>

    <div class="creds-tip">
      Akaun Default: Pengguna: <strong>admin</strong> | Laluan: <strong>revobullion2026</strong>
    </div>

    <a href="../" class="back-link">← Kembali ke Laman Web Rasmi</a>
  </div>

  <script>
    const form = document.getElementById('loginForm');
    const alertBox = document.getElementById('alertBox');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.style.display = 'none';
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span>Memproses...</span>';

      const username = document.getElementById('usernameInput').value.trim();
      const password = document.getElementById('passwordInput').value.trim();

      try {
        const res = await fetch('api.php?action=login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password })
        });
        const data = await res.json();

        if (data.success) {
          alertBox.className = 'alert-box alert-success';
          alertBox.textContent = 'Log masuk berjaya! Mengalihkan ke dashboard...';
          alertBox.style.display = 'block';
          setTimeout(() => {
            window.location.href = 'index.php';
          }, 600);
        } else {
          alertBox.className = 'alert-box alert-danger';
          alertBox.textContent = data.message || 'Nama pengguna atau kata laluan tidak sah.';
          alertBox.style.display = 'block';
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<span>Log Masuk ke Dashboard</span> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
        }
      } catch (err) {
        alertBox.className = 'alert-box alert-danger';
        alertBox.textContent = 'Ralat sambungan ke pelayan.';
        alertBox.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>Log Masuk ke Dashboard</span>';
      }
    });
  </script>
</body>
</html>
