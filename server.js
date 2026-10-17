const express = require('express');
const cors = require('cors');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static frontend files
app.use(express.static(path.join(__dirname)));

// API: Company Info
app.get('/api/info', (req, res) => {
  res.json({
    brand: 'REVO BULLION',
    tagline: 'Precious Moments Last Forever',
    slogan: 'Small in Size, Meaningful in Value',
    domain: 'revobullion.my.id',
    website: 'https://www.revobullion.com',
    whatsapp: '01111886654',
    whatsappUrl: 'https://wa.me/601111886654',
    email: 'smgmh23@gmail.com',
    socials: {
      instagram: '@revobullion',
      facebook: 'Revo Bullion',
      tiktok: '@revobullion'
    },
    purity: ['Au 999.9 (24K Gold)', 'Ag 999.9 (Fine Silver)'],
    moq: 50
  });
});

// API: Products & Pricing Catalog
app.get('/api/products', (req, res) => {
  const products = [
    // Baby Gold Custom
    { id: 'gold-c-0001', series: 'Custom', metal: 'gold', weight: '0.001g', purity: 'Au 999.9', price: 4.44, moq: 50 },
    { id: 'gold-c-0002', series: 'Custom', metal: 'gold', weight: '0.002g', purity: 'Au 999.9', price: 6.66, moq: 50 },
    { id: 'gold-c-0005', series: 'Custom', metal: 'gold', weight: '0.005g', purity: 'Au 999.9', price: 11.11, moq: 50 },
    
    // Baby Gold Premium
    { id: 'gold-p-001', series: 'Premium', metal: 'gold', weight: '0.01g', purity: 'Au 999.9', price: 14.44, moq: 50 },
    { id: 'gold-p-002', series: 'Premium', metal: 'gold', weight: '0.02g', purity: 'Au 999.9', price: 26.66, moq: 50 },
    { id: 'gold-p-005', series: 'Premium', metal: 'gold', weight: '0.05g', purity: 'Au 999.9', price: 55.55, moq: 50 },
    
    // Baby Gold VVIP
    { id: 'gold-v-01', series: 'VVIP', metal: 'gold', weight: '0.1g', purity: 'Au 999.9', price: 111.11, moq: 50 },
    { id: 'gold-v-02', series: 'VVIP', metal: 'gold', weight: '0.2g', purity: 'Au 999.9', price: 222.22, moq: 50 },
    { id: 'gold-v-05', series: 'VVIP', metal: 'gold', weight: '0.5g', purity: 'Au 999.9', price: 444.44, moq: 50 },

    // Baby Silver Custom
    { id: 'silver-c-1', series: 'Custom', metal: 'silver', weight: '1g', purity: 'Ag 999.9', price: 22.22, moq: 50 },
    { id: 'silver-c-2', series: 'Custom', metal: 'silver', weight: '2g', purity: 'Ag 999.9', price: 44.44, moq: 50 },
    { id: 'silver-c-5', series: 'Custom', metal: 'silver', weight: '5g', purity: 'Ag 999.9', price: 111.11, moq: 50 },

    // Baby Silver Premium & VVIP
    { id: 'silver-p-10', series: 'Premium', metal: 'silver', weight: '10g', purity: 'Ag 999.9', price: 222.22, moq: 50 },
    { id: 'silver-p-25', series: 'Premium', metal: 'silver', weight: '25g', purity: 'Ag 999.9', price: 444.44, moq: 50 },
    { id: 'silver-p-50', series: 'Premium', metal: 'silver', weight: '50g', purity: 'Ag 999.9', price: 1222.22, moq: 50 }
  ];
  res.json(products);
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
  console.log(`✨ REVO BULLION Server Running on http://localhost:${PORT}`);
  console.log(`✨ WhatsApp: 011 1188 6654 | Email: smgmh23@gmail.com`);
  console.log(`==================================================\n`);
});
