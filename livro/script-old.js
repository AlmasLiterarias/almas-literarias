// ========== MODO ESCURO ==========
const btn = document.getElementById('themeToggle');
const root = document.documentElement;

function enableDarkMode() {
  root.classList.add('dark');
  btn.setAttribute('aria-pressed', 'true');
  btn.textContent = '🌞';
  btn.setAttribute('aria-label', 'Desativar modo escuro');
}

function disableDarkMode() {
  root.classList.remove('dark');
  btn.setAttribute('aria-pressed', 'false');
  btn.textContent = '🌜';
  btn.setAttribute('aria-label', 'Ativar modo escuro');
}

function initThemeFromSystem() {
  if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    enableDarkMode();
  } else {
    disableDarkMode();
  }
}

btn.addEventListener('click', () => {
  if (root.classList.contains('dark')) disableDarkMode();
  else enableDarkMode();
});

if (window.matchMedia) {
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    e.matches ? enableDarkMode() : disableDarkMode();
  });
}

initThemeFromSystem();

// ========== CARRINHO ==========
const addToCartBtns = document.querySelectorAll('.add-to-cart, .botao button'); // garante que todos "Comprar" funcionem
const cartBtn = document.getElementById('cartBtn');
const cartModal = document.getElementById('cartModal');
const closeCart = document.getElementById('closeCart');
const cartItemsList = document.getElementById('cartItems');
const cartCount = document.getElementById('cartCount');
const cartTotal = document.getElementById('cartTotal');

let cart = [];

// Atualiza carrinho visualmente
function updateCart() {
  cartItemsList.innerHTML = '';

  let total = 0;
  let totalItems = 0;

  cart.forEach((item, index) => {
    const li = document.createElement('li');
    li.innerHTML = `
      ${item.name} - R$${item.price.toFixed(2)} x ${item.quantity}
      <button class="remove" data-index="${index}">🗑</button>
    `;
    cartItemsList.appendChild(li);
    total += item.price * item.quantity;
    totalItems += item.quantity;
  });

  cartTotal.textContent = `R$${total.toFixed(2)}`;
  cartCount.textContent = totalItems;
}

// Adiciona item ao carrinho
addToCartBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.card');
    const name = card.querySelector('h5').textContent.trim();

    // Extrai preço do texto (ex: "R$ 39,80" → 39.80)
    const priceText = card.querySelector('.botao p strong').textContent.replace(/[^\d,]/g, '').replace(',', '.');
    const price = parseFloat(priceText);

    if (isNaN(price)) return alert('Erro ao ler o preço do produto.');

    const existing = cart.find(item => item.name === name);
    if (existing) {
      existing.quantity++;
    } else {
      cart.push({ name, price, quantity: 1 });
    }

    updateCart();
  });
});

// Remover item
cartItemsList.addEventListener('click', (e) => {
  if (e.target.classList.contains('remove')) {
    const index = e.target.dataset.index;
    cart.splice(index, 1);
    updateCart();
  }
});

// Abrir / fechar carrinho
cartBtn.addEventListener('click', () => {
  cartModal.classList.add('active');
});

closeCart.addEventListener('click', () => {
  cartModal.classList.remove('active');
});

// Finalizar compra
document.getElementById('checkout').addEventListener('click', () => {
  if (cart.length === 0) {
    alert('Seu carrinho está vazio!');
    return;
  }
  alert('Compra finalizada com sucesso! 🛍️');
  cart = [];
  updateCart();
  cartModal.classList.remove('active');
});
