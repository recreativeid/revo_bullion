const express = require('express');
const cors = require('cors');
const path = require('path');
const fs = require('fs');
const crypto = require('crypto');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Paths
const DATA_PATH = path.join(__dirname, 'data', 'content.json');
const CONFIG_PATH = path.join(__dirname, 'data', 'admin_config.json');
const INDEX_HTML_PATH = path.join(__dirname, 'index.html');
const INDEX_PHP_PATH = path.join(__dirname, 'index.php');

// Serve static files
app.use(express.static(path.join(__dirname)));

// API: Get Full Content
app.get('/api/content', (req, res) => {
  try {
    if (fs.existsSync(DATA_PATH)) {
      const data = JSON.parse(fs.readFileSync(DATA_PATH, 'utf8'));
      return res.json({ success: true, data });
    }
    res.status(404).json({ success: false, message: 'Data file not found' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// API: Save Full Content (Admin CRUD)
app.post('/api/content', (req, res) => {
  try {
    const newContent = req.body;
    if (!newContent || typeof newContent !== 'object') {
      return res.status(400).json({ success: false, message: 'Invalid payload' });
    }

    // Backup
    if (fs.existsSync(DATA_PATH)) {
      fs.copyFileSync(DATA_PATH, DATA_PATH + '.bak');
    }

    fs.writeFileSync(DATA_PATH, JSON.stringify(newContent, null, 2), 'utf8');

    // Auto-update index.html hero texts if present
    if (fs.existsSync(INDEX_HTML_PATH) && newContent.hero) {
      let html = fs.readFileSync(INDEX_HTML_PATH, 'utf8');
      const h = newContent.hero;
      if (h.pillTag) html = html.replace(/<div class="ref-hero-badge">.*?<\/div>/s, `<div class="ref-hero-badge">${h.pillTag}</div>`);
      if (h.kicker) html = html.replace(/<span class="ref-hero-kicker">.*?<\/span>/s, `<span class="ref-hero-kicker">${h.kicker}</span>`);
      if (h.headlineLine1 && h.headlineLine2) {
        html = html.replace(/<h1 class="ref-hero-headline">.*?<\/h1>/s, `<h1 class="ref-hero-headline">${h.headlineLine1}<br><em>${h.headlineLine2}</em></h1>`);
      }
      if (h.description) html = html.replace(/<p class="ref-hero-desc">.*?<\/p>/s, `<p class="ref-hero-desc">${h.description}</p>`);
      if (h.motto) html = html.replace(/<div class="ref-hero-motto">.*?<\/div>/s, `<div class="ref-hero-motto">${h.motto}</div>`);
      fs.writeFileSync(INDEX_HTML_PATH, html, 'utf8');
      if (fs.existsSync(INDEX_PHP_PATH)) {
        fs.writeFileSync(INDEX_PHP_PATH, html, 'utf8');
      }
    }

    res.json({ success: true, message: 'Kandungan berjaya disimpan & dikemaskini!' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// API: Admin Login
app.post('/api/admin/login', (req, res) => {
  try {
    const { username, password } = req.body;
    if (fs.existsSync(CONFIG_PATH)) {
      const config = JSON.parse(fs.readFileSync(CONFIG_PATH, 'utf8'));
      const salt = config.salt || 'revobullion_salt_2026';
      const hash = crypto.createHash('sha256').update(password + salt).digest('hex');

      if (username === config.username && hash === config.passwordHash) {
        return res.json({ success: true, message: 'Log masuk berjaya' });
      }
    } else if (username === 'admin' && password === 'revobullion2026') {
      return res.json({ success: true, message: 'Log masuk berjaya' });
    }
    res.status(401).json({ success: false, message: 'Nama pengguna atau kata laluan tidak sah' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// Admin Route Redirect
app.get('/admin', (req, res) => {
  res.sendFile(path.join(__dirname, 'admin', 'index.html'));
});

// API: Company Info
app.get('/api/info', (req, res) => {
  if (fs.existsSync(DATA_PATH)) {
    const data = JSON.parse(fs.readFileSync(DATA_PATH, 'utf8'));
    return res.json(data.site);
  }
  res.json({
    brand: 'REVO BULLION',
    tagline: 'Precious Moments Last Forever',
    slogan: 'Small in Size, Meaningful in Value',
    domain: 'revobullion.my.id',
    website: 'https://www.revobullion.com',
    whatsapp: '011-1188 6654'
  });
});

// API: Products & Pricing Catalog
app.get('/api/products', (req, res) => {
  if (fs.existsSync(DATA_PATH)) {
    const data = JSON.parse(fs.readFileSync(DATA_PATH, 'utf8'));
    return res.json(data.products || []);
  }
  res.json([]);
});

// API: Calculate Quote
app.post('/api/calculate', (req, res) => {
  const { unitPrice = 4.44, qty = 50, packPrice = 0, prodName = 'Baby Gold 0.001g', packName = 'Standard Pack', custName = '', note = '' } = req.body;
  const quantity = Math.max(50, parseInt(qty, 10) || 50);
  const subtotal = (parseFloat(unitPrice) || 0) * quantity;
  const packTotal = (parseFloat(packPrice) || 0) * quantity;
  const grandTotal = subtotal + packTotal;

  const msg = `*TEMPAHAN REVO BULLION — SEBUT HARGA RASMI*
----------------------------------------
Nama / Syarikat: *${custName || 'Pelanggan Revo Bullion'}*
Produk: *${prodName}*
Kuantiti Tempahan: *${quantity} keping*
Pakej Pembungkusan: *${packName}*
----------------------------------------
*ANGGARAN JUMLAH: RM ${grandTotal.toFixed(2)}*
----------------------------------------
Catatan / Tema: _${note || 'Rekaan Custom'}_

Mohon maklum balas pihak Revo Bullion untuk pengesahan rekaan. Terima kasih!`;

  res.json({
    quantity,
    unitPrice,
    packPrice,
    subtotal,
    packTotal,
    grandTotal,
    formattedGrandTotal: `RM ${grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
    whatsappUrl: `https://wa.me/601111886654?text=${encodeURIComponent(msg)}`
  });
});

// Fallback to index.html
app.use((req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(PORT, () => {
  console.log(`\n==================================================`);
  console.log(`REVO BULLION Server Running on http://localhost:${PORT}`);
  console.log(`Admin CMS: http://localhost:${PORT}/admin`);
  console.log(`WhatsApp: 011 1188 6654 | Email: smgmh23@gmail.com`);
  console.log(`==================================================\n`);
});
