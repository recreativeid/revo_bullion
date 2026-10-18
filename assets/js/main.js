/**
 * REVO BULLION — Client-Side Logic
 * - Minimalist SaaS Architecture
 * - Clean Separated Collection Tabs (No Clutter, Never Mixed)
 * - Authentic High-Resolution Product Images
 * - Interactive Price Calculator & Direct WhatsApp Integration
 * - Scroll-Driven IntersectionObserver Animations
 * - Dynamic CMS Content Sync (from data/content.json)
 * - Zero unnecessary emojis / stickers
 */

document.addEventListener('DOMContentLoaded', () => {
  const WHATSAPP_NUMBER = '601111886654';

  // 1. Full Product Dataset with Real Product Image Assets (Default Fallback)
  const DEFAULT_PRODUCTS = [
    // --- BABY GOLD CARD (AU 999.9) ---
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
      image: './assets/images/products/card_gold_0001g.png',
      desc: 'Emas tulen Au 999.9 bersaiz mini dengan reka bentuk custom mengikut tema acara, jenama korporat atau nama anda.',
      active: true
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
      image: './assets/images/products/card_gold_0002g.png',
      desc: 'Pilihan paling popular untuk doorgift perkahwinan, majlis hari jadi dan cenderamata kenang-kenangan.',
      active: true
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
      image: './assets/images/products/card_gold_0005g.png',
      desc: 'Emas tulen nilai bermakna dengan cetakan kad eksklusif kalis luntur dan kemasan berkilat.',
      active: true
    },
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
      image: './assets/images/products/card_gold_001g.png',
      desc: 'Siri premium rekaan artistik khas Malaysia Madani, corak floral elegan dan tema kemegahan negara.',
      active: true
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
      image: './assets/images/products/card_gold_002g.png',
      desc: 'Hadiah penghargaan kakitangan cemerlang, cenderamata rakan niaga dan tanda ingatan abadi.',
      active: true
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
      image: './assets/images/products/card_gold_005g.png',
      desc: 'Kemasan eksklusif berkilauan emas tulen padu 0.05g berprestij tinggi untuk majlis rasmi.',
      active: true
    },

    // --- BABY SILVER CARD (AG 999.9) ---
    {
      id: 'silver-c-1',
      name: 'Baby Silver Card Custom 1g',
      metal: 'silver',
      category: 'silver',
      weight: '1g',
      purity: 'Ag 999.9',
      price: 22.22,
      moq: 50,
      series: 'Custom Series',
      image: './assets/images/products/card_silver_1g.png',
      desc: 'Perak tulen Ag 999.9 bersaiz kompak dengan reka bentuk moden, alternatif perak mampu milik.',
      active: true
    },
    {
      id: 'silver-c-2',
      name: 'Baby Silver Card Custom 2g',
      metal: 'silver',
      category: 'silver',
      weight: '2g',
      purity: 'Ag 999.9',
      price: 44.44,
      moq: 50,
      series: 'Custom Series',
      image: './assets/images/products/card_silver_2g.png',
      desc: 'Kepingan perak tulen 2g sesuai untuk cenderamata perkahwinan dan hadiah majlis penghargaan.',
      active: true
    },
    {
      id: 'silver-c-5',
      name: 'Baby Silver Card Custom 5g',
      metal: 'silver',
      category: 'silver',
      weight: '5g',
      purity: 'Ag 999.9',
      price: 111.11,
      moq: 50,
      series: 'Custom Series',
      image: './assets/images/products/card_silver_5g.png',
      desc: 'Ketulenan perak padu 5g dengan persembahan kad mewah untuk simpanan peribadi dan cenderamata korporat.',
      active: true
    },
    {
      id: 'silver-p-10',
      name: 'Baby Silver Card Premium 10g',
      metal: 'silver',
      category: 'silver',
      weight: '10g',
      purity: 'Ag 999.9',
      price: 222.22,
      moq: 50,
      series: 'Premium Series',
      image: './assets/images/products/card_silver_10g.png',
      desc: 'Siri perak premium berwajaran 10g dengan cetakan seni halus dan kad pelindung berkualiti tinggi.',
      active: true
    },
    {
      id: 'silver-p-25',
      name: 'Baby Silver Card Premium 25g',
      metal: 'silver',
      category: 'silver',
      weight: '25g',
      purity: 'Ag 999.9',
      price: 444.44,
      moq: 50,
      series: 'Premium Series',
      image: './assets/images/products/card_silver_25g.png',
      desc: 'Nilai simpanan perak fizikal yang padu dan bernilai seni tinggi, sesuai sebagai aset perlindungan.',
      active: true
    },
    {
      id: 'silver-p-50',
      name: 'Baby Silver Card Premium 50g',
      metal: 'silver',
      category: 'silver',
      weight: '50g',
      purity: 'Ag 999.9',
      price: 1222.22,
      moq: 50,
      series: 'Premium Series',
      image: './assets/images/products/card_silver_50g.png',
      desc: 'Barisan koleksi perak tulen terbesar dalam kad perlindungan rasmi Revo Bullion.',
      active: true
    },

    // --- SIRI VVIP EXCLUSIVE (AU / AG 999.9) ---
    {
      id: 'vvip-g-01',
      name: 'Baby Gold Card VVIP 0.1g',
      metal: 'gold',
      category: 'vvip',
      weight: '0.1g',
      purity: 'Au 999.9 (24K)',
      price: 111.11,
      moq: 50,
      series: 'VVIP Exclusive',
      image: './assets/images/products/card_gold_vvip_01g.png',
      desc: 'Siri terhad VVIP dengan ketelitian tinggi, pilihan terbaik bagi tetamu kehormat & VVIP majlis.',
      active: true
    },
    {
      id: 'vvip-g-02',
      name: 'Baby Gold Card VVIP 0.2g',
      metal: 'gold',
      category: 'vvip',
      weight: '0.2g',
      purity: 'Au 999.9 (24K)',
      price: 222.22,
      moq: 50,
      series: 'VVIP Exclusive',
      image: './assets/images/products/card_gold_vvip_02g.png',
      desc: 'Emas tulen berat 0.2g dengan packaging baldu bersalut emas dan Certificate of Authenticity.',
      active: true
    },
    {
      id: 'vvip-g-05',
      name: 'Baby Gold Card VVIP 0.5g',
      metal: 'gold',
      category: 'vvip',
      weight: '0.5g',
      purity: 'Au 999.9 (24K)',
      price: 444.44,
      moq: 50,
      series: 'VVIP Exclusive',
      image: './assets/images/products/card_gold_vvip_05g.png',
      desc: 'Puncak kemewahan siri VVIP Revo Bullion, bernilai tinggi sebagai aset warisan berpanjangan.',
      active: true
    },
    {
      id: 'vvip-s-5',
      name: 'Baby Silver Card VVIP 5g',
      metal: 'silver',
      category: 'vvip',
      weight: '5g',
      purity: 'Ag 999.9',
      price: 111.11,
      moq: 50,
      series: 'VVIP Exclusive',
      image: './assets/images/products/card_silver_vvip_5g.png',
      desc: 'Perak tulen 5g edisi khas VVIP dengan kemasan seni mewah, sijil ketulenan dan kotak baldu eksklusif.',
      active: true
    },
    {
      id: 'vvip-s-10',
      name: 'Baby Silver Card VVIP 10g',
      metal: 'silver',
      category: 'vvip',
      weight: '10g',
      purity: 'Ag 999.9',
      price: 222.22,
      moq: 50,
      series: 'VVIP Exclusive',
      image: './assets/images/products/card_silver_vvip_10g.png',
      desc: 'Perak tulen 10g VVIP berprestij tinggi, hadiah korporat terbaik untuk rakan niaga dan klien eksekutif.',
      active: true
    },
    {
      id: 'vvip-s-25',
      name: 'Baby Silver Card VVIP 25g',
      metal: 'silver',
      category: 'vvip',
      weight: '25g',
      purity: 'Ag 999.9',
      price: 444.44,
      moq: 50,
      series: 'VVIP Exclusive',
      image: './assets/images/products/card_silver_vvip_25g.png',
      desc: 'Koleksi perak padu 25g edisi terhad VVIP dengan pembungkusan berprestij tinggi dan nombor siri rasmi.',
      active: true
    }
  ];

  let currentProducts = [...DEFAULT_PRODUCTS];
  let currentCategory = 'gold';

  // 2. Render Products by Separated Category Tab
  const productContainer = document.getElementById('pureProductGrid');

  function renderCatalog(category) {
    if (!productContainer) return;
    currentCategory = category;
    
    // Strict separation: only show items belonging to the selected category
    const items = currentProducts.filter(p => p.category === category && p.active !== false);

    productContainer.innerHTML = items.map((p, idx) => {
      const isGold = p.metal === 'gold' || p.metal.includes('Au');
      const cardClass = isGold ? 'gold-card' : 'silver-card';
      const tagClass = isGold ? 'gold' : 'silver';
      const metalLabel = isGold ? 'Au 999.9' : 'Ag 999.9';
      
      const whatsappText = encodeURIComponent(`Salam Revo Bullion, saya ingin membuat tempahan / sebut harga produk:
- Produk: ${p.name}
- Berat: ${p.weight} (${p.purity || metalLabel})
- Harga: RM ${parseFloat(p.price).toFixed(2)} / unit
- Kuantiti Minimum: 50 keping

Mohon perincian tempahan dan rekaan mockup custom. Terima kasih!`);

      return `
        <article class="pure-product-card ${cardClass} reveal-on-scroll" style="animation-delay: ${idx * 60}ms;">
          <div class="pure-card-img-wrap">
            <img src="${p.image}" alt="${p.name}" class="pure-card-img" loading="lazy" onerror="this.src='./assets/images/favicon.png'">
          </div>

          <div class="pure-card-head">
            <span class="pure-metal-tag ${tagClass}">
              <span class="pure-metal-dot"></span>
              ${metalLabel}
            </span>
            <span class="pure-weight-badge">${p.weight}</span>
          </div>
          
          <span class="pure-series-label">${p.series || 'Koleksi Rasmi'}</span>
          <h3 class="pure-card-title">${p.name}</h3>
          <p class="pure-card-desc">${p.desc || ''}</p>

          <div class="pure-card-specs">
            <span class="spec-pill">Sijil Ketulenan Rasmi</span>
            <span class="spec-pill">MOQ ${p.moq || 50} Keping</span>
          </div>

          <div class="pure-price-panel">
            <span class="pure-price-unit">Harga Seunit (Min ${p.moq || 50} pcs):</span>
            <span class="pure-price-number">RM ${parseFloat(p.price).toFixed(2)}</span>
          </div>

          <a href="https://wa.me/${WHATSAPP_NUMBER}?text=${whatsappText}" target="_blank" rel="noopener" class="pure-card-btn-ws">
            <span>Tempah via WhatsApp</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </a>
        </article>
      `;
    }).join('');

    // Trigger reveal for newly rendered cards
    requestAnimationFrame(() => {
      productContainer.querySelectorAll('.pure-product-card').forEach(card => {
        card.classList.add('revealed');
      });
    });
  }

  // Filter Buttons - Switch between separated collections cleanly
  const tabButtons = document.querySelectorAll('.cat-tab-btn');
  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      tabButtons.forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-selected', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');
      renderCatalog(btn.dataset.category);
    });
  });

  // Default to Baby Gold Collection (clean 6 cards, never crowded)
  renderCatalog('gold');

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

  function updateCalculatorProducts() {
    if (calcSelect) {
      calcSelect.innerHTML = currentProducts.map(p => `
        <option value="${p.id}" data-price="${p.price}" data-purity="${p.purity || p.metal}">
          ${p.name} (${p.weight}) — RM ${parseFloat(p.price).toFixed(2)}/keping
        </option>
      `).join('');
      calculateQuote();
    }
  }

  updateCalculatorProducts();

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

  // 5. Scroll-Driven IntersectionObserver Animations
  function initScrollAnimations() {
    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('.reveal-on-scroll, .stagger-parent').forEach(el => el.classList.add('revealed'));
      return;
    }

    const observerOptions = {
      root: null,
      rootMargin: '0px 0px -40px 0px',
      threshold: 0.12
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Target major sections and containers
    const revealTargets = document.querySelectorAll(
      '.section, .ref-hero-section, .ref-bottom-section, .reveal-on-scroll, .stagger-parent, .product-cards-grid, .packaging-grid, .use-grid-12, .audience-grid, .steps-row-6, .pillars-grid, .order-guarantees-grid, .corp-events-grid, .vvip-spotlight-card, .calculator-box'
    );

    revealTargets.forEach(el => {
      el.classList.add('reveal-on-scroll');
      scrollObserver.observe(el);
    });

    // Also observe individual cards
    document.querySelectorAll(
      '.pillar-card, .pack-card, .use-item-box, .audience-card, .step-item, .corp-event-card, .guarantee-card, .legacy-pillar-card'
    ).forEach(el => {
      el.classList.add('reveal-on-scroll');
      scrollObserver.observe(el);
    });
  }

  initScrollAnimations();

  // 6. Dynamic Content Sync from data/content.json (if updated via Admin CMS)
  async function syncFromCMS() {
    try {
      const res = await fetch('./data/content.json?t=' + Date.now());
      if (!res.ok) return;
      const data = await res.json();

      if (data.products && Array.isArray(data.products) && data.products.length > 0) {
        currentProducts = data.products;
        renderCatalog(currentCategory);
        updateCalculatorProducts();
      }

      if (data.hero) {
        const pill = document.querySelector('.ref-hero-badge');
        if (pill && data.hero.pillTag) pill.textContent = data.hero.pillTag;

        const kicker = document.querySelector('.ref-hero-kicker');
        if (kicker && data.hero.kicker) kicker.textContent = data.hero.kicker;

        const motto = document.querySelector('.ref-hero-motto');
        if (motto && data.hero.motto) motto.textContent = data.hero.motto;
      }
    } catch (err) {
      // Offline or local file protocol fallback is already handled
    }
  }

  syncFromCMS();
});
