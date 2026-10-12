const miniCart = document.getElementById('mini-cart');
const cartCount = document.getElementById('cart-count');
const cartLink = document.querySelector('.cart-link');
let hideTimer;

document.querySelectorAll('.form-addtocart').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const params = new URLSearchParams(new FormData(form));
        params.set('ajax', '1');

        try{
            const res = await fetch ('/addToCart?' + params);
            if (!res.ok) throw new Error();
            showMiniCart(await res.json());
        } catch {
            form.submit(); 
        }
    });
});

function showMiniCart(cart) {
    cartCount.textContent = cart.count;

    const list = miniCart.querySelector('.mini-cart-items');
    list.replaceChildren(...cart.items.map(item => {
        const li = document.createElement('li');
        li.className = 'mini-cart-item';
        li.append(
        makeSpan('mini-cart-name', item.name),
        makeSpan('mini-cart-qty', `* ${item.quantity}`),
        makeSpan('mini-cart-price', `${item.price.toFixed(2)} kr`)
        );
        return li;
    }));
    
    
    
    miniCart.querySelector('.mini-cart-total').textContent = 
    `Total: ${Number(cart.total). toFixed(2)} kr`;
    
   openMiniCart();
   closeMiniCartSoon(3500);
}

function makeSpan(className, text) {
    const span = document.createElement('span');
    span.className = className;
    span.textContent = text;
    return span;
}

function openMiniCart() {
    clearTimeout(hideTimer);
    miniCart.classList.add('is-open');
}

function closeMiniCartSoon(delay = 1500) {
    clearTimeout(hideTimer);
    hideTimer = setTimeout(() => miniCart.classList.remove('is-open'), delay);
}

miniCart.addEventListener('mouseenter', openMiniCart);
miniCart.addEventListener('mouseleave', () => closeMiniCartSoon());

cartLink.addEventListener('mouseenter', openMiniCart);
cartLink.addEventListener('mouseleave', () => closeMiniCartSoon());