// ========== MODO ESCURO ==========
const btn = document.getElementById('themeToggle');
const root = document.documentElement;

function enableDarkMode() {
  root.classList.add('dark');
  btn.setAttribute('aria-pressed', 'true');
  const icon = document.getElementById('theme-icon');
  icon.src = './imagens/Jerluciacordando.png';
  // btn.textContent = '🌞';
  // btn.setAttribute('aria-label', 'Desativar modo escuro');
}

function disableDarkMode() {
  root.classList.remove('dark');
  btn.setAttribute('aria-pressed', 'false');
  const icon = document.getElementById('theme-icon');
  icon.src = './imagens/Jerlucidormindo.png';
  // btn.textContent = '🌜';
  // btn.setAttribute('aria-label', 'Ativar modo escuro');
  //
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
const addToCartBtns = document.querySelectorAll('.add-to-cart');
const cartBtn = document.getElementById('cartBtn');
const cartModal = document.getElementById('cartModal');
const closeCart = document.getElementById('closeCart');
const cartItemsList = document.getElementById('cartItems');
const cartCount = document.getElementById('cartCount');
const cartTotal = document.getElementById('cartTotal');
const checkoutBtn = document.getElementById('checkout');

let cart = []; // Armazena os itens temporariamente

// Atualiza visualmente o carrinho
function updateCart() {
  cartItemsList.innerHTML = '';

  let total = 0;
  let totalItems = 0;

  cart.forEach((item, index) => {
    const li = document.createElement('li');
    li.innerHTML = `
      <span>${item.name}</span>
      <span>R$ ${item.price.toFixed(2)}</span>
      <span>Qtd: ${item.quantity}</span>
      <button class="remove" data-index="${index}">🗑</button>
    `;
    cartItemsList.appendChild(li);
    total += item.price * item.quantity;
    totalItems += item.quantity;
  });

  cartTotal.textContent = `R$ ${total.toFixed(2)}`;
  cartCount.textContent = totalItems;
}

// Adiciona item ao carrinho
addToCartBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.produto');
    const name = card.querySelector('h2').textContent;
    const priceText = card.querySelector('.card-preco .preco strong').textContent.trim();
    const price = parseFloat(priceText.replace('R$', '').replace(',', '.'));
    const quantity = card.querySelector(`input`).value;

    if (isNaN(price)) {
      alert('Erro ao ler o preço do produto.');
      return;
    }

    const existing = cart.find(item => item.name === name);
    if (existing) {
      existing.quantity++;
    } else {
      cart.push({ name, price, quantity });
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

// Abrir / fechar modal
cartBtn.addEventListener('click', () => cartModal.classList.add('active'));
closeCart.addEventListener('click', () => cartModal.classList.remove('active'));

// Finalizar compra
checkoutBtn.addEventListener('click', () => {
  if (cart.length === 0) {
    alert('Seu carrinho está vazio!');
    return;
  }

  // Aqui você pode substituir pelo envio via formulário, WhatsApp etc.
  alert('Compra finalizada com sucesso! 🛍️');

  // Limpa o carrinho
  cart = [];
  updateCart();
  cartModal.classList.remove('active');
});
