/**
 * REVO BULLION — Client-Side Logic
 * - Pure typographic & CSS luxury product rendering
 * - Direct WhatsApp integration with prefilled quote messages
 * - Interactive Price Calculator
 * - Mobile Drawer Toggle
 */

document.addEventListener('DOMContentLoaded', () => {
  const WHATSAPP_NUMBER = '601111886654';

  // 1. Full Product Dataset from all 16 posters
  const PRODUCTS = [
    // Baby Gold Custom
    {
      id: 'gold-c-0001',
      name: 'Baby Gold Card Custom 0.001g',
      metal: 'gold',
      category: 'gold',
      weight: '0.001g',
      purity: 'Au 999.9 (24K)',
      price: 4.44,
      moq: 50,
      series: 'Custom Series',
      desc: 'Emas tulen Au 999.9 bersaiz mini dengan reka bentuk custom mengikut tema acara, jenama korporat atau nama anda.'
    },
    {
      id: 'gold-c-0002',
      name: 'Baby Gold Card Custom 0.002g',
      metal: 'gold',
      category: 'gold',
      weight: '0.002g',
      purity: 'Au 999.9 (24K)',
      price: 6.66,
      moq: 50,
      series: 'Custom Series',
      desc: 'Pilihan paling popular untuk doorgift perkahwinan, majlis hari jadi dan cenderamata kenang-kenangan.'
    },
    {
      id: 'gold-c-0005',
      name: 'Baby Gold Card Custom 0.005g',
      metal: 'gold',
      category: 'gold',
      weight: '0.005g',
      purity: 'Au 999.9 (24K)',
      price: 11.11,
      moq: 50,
      series: 'Custom Series',
      desc: 'Emas tulen nilai bermakna dengan cetakan kad eksklusif kalis luntur dan kemasan berkilat.'
    },

    // Baby Gold Premium
    {
      id: 'gold-p-001',
      name: 'Baby Gold Card Premium 0.01g',
      metal: 'gold',
      category: 'gold',
      weight: '0.01g',
      purity: 'Au 999.9 (24K)',
      price: 14.44,
      moq: 50,
      series: 'Premium Series',
      desc: 'Siri premium rekaan artistik khas Malaysia Madani, corak floral elegan dan tema kemegahan negara.'
    },
    {
      id: 'gold-p-002',
      name: 'Baby Gold Card Premium 0.02g',
      metal: 'gold',
      category: 'gold',
      weight: '0.02g',
      purity: 'Au 999.9 (24K)',
      price: 26.66,
      moq: 50,
      series: 'Premium Series',
      desc: 'Hadiah penghargaan kakitangan cemerlang, cenderamata rakan niaga dan tanda ingatan abadi.'
    },
    {
      id: 'gold-p-005',
      name: 'Baby Gold Card Premium 0.05g',
      metal: 'gold',
      category: 'gold',
      weight: '0.05g',
      purity: 'Au 999.9 (24K)',
      price: 55.55,
      moq: 50,
      series: 'Premium Series',
      desc: 'Kemasan eksklusif berkilauan emas tulen padu 0.05g berprestij tinggi untuk majlis rasmi.'
    },

    // Baby Gold VVIP
    {
      id: 'gold-v-01',
      name: 'Baby Gold Card VVIP 0.1g',
      metal: 'gold',
      category: 'vvip',
      weight: '0.1g',
      purity: 'Au 999.9 (24K)',
      price: 111.11,
      moq: 50,
      series: 'VVIP Exclusive',
      desc: 'Siri terhad VVIP dengan ketelitian tinggi, pilihan terbaik bagi tetamu kehormat & VVIP majlis.'
    },
    {
      id: 'gold-v-02',
      name: 'Baby Gold Card VVIP 0.2g',
      metal: 'gold',
      category: 'vvip',
      weight: '0.2g',
      purity: 'Au 999.9 (24K)',
      price: 222.22,
      moq: 50,
      series: 'VVIP Exclusive',
      desc: 'Emas tulen berat 0.2g dengan packaging baldu bersalut emas dan Certificate of Authenticity.'
    },
    {
      id: 'gold-v-05',
      name: 'Baby Gold Card VVIP 0.5g',
      metal: 'gold',
      category: 'vvip',
      weight: '0.5g',
      purity: 'Au 999.9 (24K)',
      price: 444.44,
      moq: 50,
      series: 'VVIP Exclusive',
      desc: 'Puncak kemewahan siri VVIP Revo Bullion, bernilai tinggi sebagai aset warisan berpanjangan.'
    },

    // Baby Silver Custom
    {
      id: 'silver-c-1',
      name: 'Baby Silver Card Custom 1g',
      metal: 'silver',
      category: 'silver',
      weight: '1g',
      purity: 'Ag 999.9 (Fine Silver)',
      price: 22.22,
      moq: 50,
      series: 'Custom Series',
      desc: 'Perak tulen Ag 999.9 murni dengan rekaan custom peribadi, bajet rendah dan berbaloi.'
    },
    {
      id: 'silver-c-2',
      name: 'Baby Silver Card Custom 2g',
      metal: 'silver',
      category: 'silver',
      weight: '2g',
      purity: 'Ag 999.9 (Fine Silver)',
      price: 44.44,
      moq: 50,
      series: 'Custom Series',
      desc: 'Pilihan hadiah perak padu moden berkilau tinggi untuk kenangan manis perkahwinan.'
    },
    {
      id: 'silver-c-5',
      name: 'Baby Silver Card Custom 5g',
      metal: 'silver',
      category: 'silver',
      weight: '5g',
      purity: 'Ag 999.9 (Fine Silver)',
      price: 111.11,
      moq: 50,
      series: 'Custom Series',
      desc: 'Perak 5 gram tulen dengan cetakan kad eksklusif corak bunga & motif antarabangsa.'
    },

    // Baby Silver Premium & VVIP
    {
      id: 'silver-p-10',
      name: 'Baby Silver Card Premium 10g',
      metal: 'silver',
      category: 'silver',
      weight: '10g',
      purity: 'Ag 999.9 (Fine Silver)',
      price: 222.22,
      moq: 50,
      series: 'Premium Series',
      desc: 'Koleksi perak 10 gram rekaan Malaysia Madani & Together Towards a Brighter Future.'
    },
    {
      id: 'silver-p-25',
      name: 'Baby Silver Card Premium 25g',
      metal: 'silver',
      category: 'silver',
      weight: '25g',
      purity: 'Ag 999.9 (Fine Silver)',
      price: 444.44,
      moq: 50,
      series: 'Premium Series',
      desc: 'Sesuai untuk simpanan perak fizikal berat 25g bernilai tinggi dan perlindungan nilai.'
    },
    {
      id: 'silver-p-50',
      name: 'Baby Silver Card Premium 50g',
      metal: 'silver',
      category: 'silver',
      weight: '50g',
      purity: 'Ag 999.9 (Fine Silver)',
      price: 1222.22,
      moq: 50,
      series: 'Premium Series',
      desc: 'Barisan perak berat padu 50g bernilai tinggi untuk pelaburan jangka panjang.'
    }
  ];

  // 2. Render Product Cards (Pure CSS Typographic without poster images)
  const productContainer = document.getElementById('pureProductGrid');
  
  function renderCatalog(filter = 'all') {
    if (!productContainer) return;
    
    let items = PRODUCTS;
    if (filter !== 'all') {
      items = PRODUCTS.filter(p => p.category === filter);
    }

    productContainer.innerHTML = items.map(p => {
      const isGold = p.metal === 'gold';
      const cardClass = isGold ? 'gold-card' : 'silver-card';
      const tagClass = isGold ? 'gold' : 'silver';
      
      const whatsappText = encodeURIComponent(`Salam Revo Bullion, saya ingin bertanya maklumat lanjut & sebut harga:
- Produk: ${p.name}
- Berat: ${p.weight} (${p.purity})
- Harga: RM ${p.price.toFixed(2)}/keping
- Kuantiti Minimum: 50 keping

Mohon perincian tempahan & rekaan custom. Terima kasih!`);

      return `
        <div class="pure-product-card ${cardClass}">
          <div class="pure-card-head">
            <span class="pure-metal-tag ${tagClass}">
              ${isGold ? '🟡 Au 999.9' : '⚪ Ag 999.9'}
            </span>
            <span class="pure-weight-badge">${p.weight}</span>
          </div>
          
          <span class="pure-series-label">${p.series}</span>
          <h3 class="pure-card-title">${p.name}</h3>
          <p class="pure-card-desc">${p.desc}</p>

          <div class="pure-price-panel">
            <span class="pure-price-unit">Harga Seunit (Min 50 pcs):</span>
            <span class="pure-price-number">RM ${p.price.toFixed(2)}</span>
          </div>

          <a href="https://wa.me/${WHATSAPP_NUMBER}?text=${whatsappText}" target="_blank" rel="noopener" class="pure-card-btn-ws">
            <span>Detail ke WhatsApp</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </a>
        </div>
      `;
    }).join('');
  }

  // Filter Buttons
  const tabButtons = document.querySelectorAll('.cat-tab-btn');
  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      tabButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      renderCatalog(btn.dataset.category);
    });
  });

  renderCatalog('all');

  // 3. Interactive Price Calculator
  const calcSelect = document.getElementById('calcSelectProd');
  const calcQty = document.getElementById('calcQtyInput');
  const calcMinus = document.getElementById('calcMinusBtn');
  const calcPlus = document.getElementById('calcPlusBtn');
  const calcPack = document.getElementById('calcSelectPack');
  const calcName = document.getElementById('calcInputName');
  const calcTheme = document.getElementById('calcInputTheme');

  // Outputs
  const outProd = document.getElementById('calcOutProd');
  const outUnit = document.getElementById('calcOutUnit');
  const outQty = document.getElementById('calcOutQty');
  const outPack = document.getElementById('calcOutPack');
  const outTotal = document.getElementById('calcOutTotal');
  const outWhatsappBtn = document.getElementById('calcWhatsappBtn');

  // Populate Dropdown
  if (calcSelect) {
    calcSelect.innerHTML = PRODUCTS.map(p => `
      <option value="${p.id}" data-price="${p.price}" data-purity="${p.purity}">
        ${p.name} (${p.purity}) — RM ${p.price.toFixed(2)}/keping
      </option>
    `).join('');
  }

  function calculateQuote() {
    if (!calcSelect || !calcQty) return;

    const selectedOption = calcSelect.options[calcSelect.selectedIndex];
    if (!selectedOption) return;

    const unitPrice = parseFloat(selectedOption.dataset.price) || 4.44;
    const purity = selectedOption.dataset.purity || 'Au 999.9';
    const prodName = selectedOption.textContent.split('—')[0].trim();

    let qty = parseInt(calcQty.value, 10);
    if (isNaN(qty) || qty < 50) {
      qty = 50;
      calcQty.value = 50;
    }

    const packPrice = parseFloat(calcPack ? calcPack.value : 0) || 0;
    const packOptionText = calcPack ? calcPack.options[calcPack.selectedIndex].text : 'Standard Pack';

    const subtotal = unitPrice * qty;
    const totalPack = packPrice * qty;
    const grandTotal = subtotal + totalPack;

    if (outProd) outProd.textContent = prodName;
    if (outUnit) outUnit.textContent = `RM ${unitPrice.toFixed(2)}`;
    if (outQty) outQty.textContent = `${qty} keping`;
    if (outPack) outPack.textContent = packOptionText.split('(')[0].trim();
    if (outTotal) {
      outTotal.textContent = `RM ${grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }

    if (outWhatsappBtn) {
      const custName = calcName?.value.trim() || 'Pelanggan Revo Bullion';
      const themeNote = calcTheme?.value.trim() || 'Custom Tema Acara';

      const msg = `*SEBUT HARGA TEMPAHAN REVO BULLION*
----------------------------------------
Nama / Syarikat: *${custName}*
Produk: *${prodName}*
Ketulenan: *${purity}*
Harga Seunit: *RM ${unitPrice.toFixed(2)}*
Kuantiti Tempahan: *${qty} keping*
Pakej Pembungkusan: *${packOptionText}*
----------------------------------------
*ANGGARAN JUMLAH: RM ${grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}*
----------------------------------------
Catatan / Tema Rekaan: _${themeNote}_

Mohon maklum balas pihak Revo Bullion untuk semakan mockup dan proses seterusnya. Terima kasih!`;

      outWhatsappBtn.href = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(msg)}`;
    }
  }

  if (calcSelect) calcSelect.addEventListener('change', calculateQuote);
  if (calcPack) calcPack.addEventListener('change', calculateQuote);
  if (calcQty) calcQty.addEventListener('input', calculateQuote);
  if (calcName) calcName.addEventListener('input', calculateQuote);
  if (calcTheme) calcTheme.addEventListener('input', calculateQuote);

  if (calcMinus && calcQty) {
    calcMinus.addEventListener('click', () => {
      let val = parseInt(calcQty.value, 10) || 50;
      if (val > 50) {
        calcQty.value = val - 10;
        calculateQuote();
      }
    });
  }

  if (calcPlus && calcQty) {
    calcPlus.addEventListener('click', () => {
      let val = parseInt(calcQty.value, 10) || 50;
      calcQty.value = val + 10;
      calculateQuote();
    });
  }

  calculateQuote();

  // 4. Mobile Drawer Menu
  const drawerBtn = document.getElementById('refHamburgerBtn');
  const drawer = document.getElementById('mobileDrawer');

  if (drawerBtn && drawer) {
    drawerBtn.addEventListener('click', () => {
      drawer.classList.toggle('active');
    });

    drawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        drawer.classList.remove('active');
      });
    });
  }
});
