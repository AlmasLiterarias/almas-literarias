// ========== MODO ESCURO ===================
const btn = document.getElementById('themeToggle');
const root = document.documentElement;

function enableDarkMode() {
  root.classList.add('dark');
  root.classList.remove('light');
 
  if (btn) {
    btn.setAttribute('aria-pressed', 'true');
    btn.textContent = '🌞';
    btn.setAttribute('aria-label', 'Desativar modo escuro');
  }
 
  localStorage.setItem('theme', 'dark');
}

function disableDarkMode() {
  root.classList.remove('dark');
  root.classList.add('light');
 
  if (btn) {
    btn.setAttribute('aria-pressed', 'false');
    btn.textContent = '🌜';
    btn.setAttribute('aria-label', 'Ativar modo escuro');
  }
 
  localStorage.setItem('theme', 'light');
}

function checkThemePreference() {
  const savedTheme = localStorage.getItem('theme');
 
  if (savedTheme === 'dark') {
    enableDarkMode();
  } else if (savedTheme === 'light') {
    disableDarkMode();
  } else {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      enableDarkMode();
    } else {
      disableDarkMode();
    }
  }
}

if (btn) {
  btn.addEventListener('click', () => {
    if (root.classList.contains('dark')) {
      disableDarkMode();
    } else {
      enableDarkMode();
    }
  });
}

if (window.matchMedia) {
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      e.matches ? enableDarkMode() : disableDarkMode();
    }
  });
}

checkThemePreference();

// ========== AVALIAÇÃO =====================
const estrelas = document.querySelectorAll(".avaliacao span");
const nota = document.getElementById("nota");

if (estrelas.length > 0 && nota) {
  estrelas.forEach((estrela) => {
    estrela.addEventListener("click", () => {
      const valor = estrela.getAttribute("data-value");

      estrelas.forEach(e => e.classList.remove("ativo"));

      for (let i = 0; i < valor; i++) {
        estrelas[i].classList.add("ativo");
      }

      nota.textContent = "Nota: " + valor;
    });
  });
}

// ========== CARRINHO ======================
const addToCartBtns = document.querySelectorAll('.add-to-cart, .botao button');
const cartBtn = document.getElementById('cartBtn');
const cartModal = document.getElementById('cartModal');
const closeCart = document.getElementById('closeCart');
const cartItemsList = document.getElementById('cartItems');
const cartCount = document.getElementById('cartCount');
const cartTotal = document.getElementById('cartTotal');

let cart = JSON.parse(localStorage.getItem('livraria_cart')) || [];

// Adiciona item ao carrinho
addToCartBtns.forEach(button => {
  button.addEventListener('click', (event) => {
    const card = event.target.closest('.card, .infos-produto');
   
    if (card) {
      const inputQtd = card.querySelector('input[type="number"]');
      let quantidadeDesejada = 1;

      if (inputQtd) {
        quantidadeDesejada = parseInt(inputQtd.value);

        if (isNaN(quantidadeDesejada) || quantidadeDesejada <= 0) {
          alert('Por favor, informe uma quantidade válida antes de comprar!');
          return;
        }
      }

      // Pegamos o texto e usamos o .split('-')[0] para ignorar qualquer subtítulo após o hífen
      const elementoTitulo = card.querySelector('h5, .descricao h2');
      let nomeLivro = elementoTitulo ? elementoTitulo.innerText.split('-')[0].trim() : "Livro";

      const precoTexto = card.querySelector('.botao p strong, .card-preco .preco strong').innerText;
      const precoNumerico = parseFloat(precoTexto.replace('R$', '').replace(',', '.').trim());

      const itemExistente = cart.find(item => item.name === nomeLivro);
     
      if (itemExistente) {
        itemExistente.quantity += quantidadeDesejada;
      } else {
        cart.push({ name: nomeLivro, price: precoNumerico, quantity: quantidadeDesejada });
      }
     
      updateCart();      
     
      if (inputQtd) {
        inputQtd.value = '';
      }
    }
  });
});

// Atualiza carrinho visualmente com os botões de mais e menos
function updateCart() {
  if (!cartItemsList || !cartTotal || !cartCount) return;

  cartItemsList.innerHTML = '';
  let total = 0;
  let totalItems = 0;

  cart.forEach((item, index) => {
    const li = document.createElement('li');
    li.innerHTML = `
      <span>${item.name} - R$ ${item.price.toFixed(2).replace('.', ',')} x ${item.quantity}</span>
      <div class="cart-actions">
        <button class="decrease" data-index="${index}">-</button>
        <span>${item.quantity}</span>
        <button class="increase" data-index="${index}">+</button>
        <button class="remove" data-index="${index}">🗑</button>
      </div>
    `;
    cartItemsList.appendChild(li);
    total += item.price * item.quantity;
    totalItems += item.quantity;
  });

  cartTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
  cartCount.textContent = totalItems + "🛒";

  localStorage.setItem('livraria_cart', JSON.stringify(cart));
}

// Gerenciamento dos cliques dentro da lista do carrinho (Remover, Aumentar e Diminuir)
if (cartItemsList) {
  cartItemsList.addEventListener('click', (e) => {
    const index = e.target.dataset.index;

    if (index === undefined) return;

    if (e.target.classList.contains('remove')) {
      cart.splice(index, 1);
    }
    else if (e.target.classList.contains('increase')) {
      cart[index].quantity += 1;
    }
    else if (e.target.classList.contains('decrease')) {
      cart[index].quantity -= 1;
      if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
      }
    }

    updateCart();
  });
}

// Abrir / fechar carrinho
if (cartBtn && cartModal) {
  cartBtn.addEventListener('click', () => {
    cartModal.classList.add('active');
  });

  if (closeCart) {
    closeCart.addEventListener('click', () => {
      cartModal.classList.remove('active');
    });
  }
}

// Finalizar compra
const checkoutBtn = document.getElementById('checkout');
if (checkoutBtn) {
  checkoutBtn.addEventListener('click', () => {
    if (cart.length === 0) {
      alert('Seu carrinho está vazio!');
      return;
    }
    alert('Compra finalizada com sucesso! 🛍️');
    cart = [];
    updateCart();
    cartModal.classList.remove('active');
  });
}

updateCart();