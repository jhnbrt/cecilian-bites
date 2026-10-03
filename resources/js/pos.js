const STORAGE_KEY = 'cecilian-bites:cart';
const MAX_QUANTITY = 99;

const root = document.getElementById('pos');

if (root) {
    const currency = root.dataset.currency ?? '$';
    const money = (value) => `${currency}${Number(value).toFixed(2)}`;

    const rowTemplate = document.getElementById('cart-row-template');
    const cartList = root.querySelector('[data-cart-items]');
    const emptyState = root.querySelector('[data-cart-empty]');
    const countLabel = root.querySelector('[data-cart-count]');
    const totalLabel = root.querySelector('[data-cart-total]');
    const errorBox = root.querySelector('[data-cart-error]');
    const placeOrderButton = root.querySelector('[data-place-order]');
    const customerInput = root.querySelector('[data-customer]');
    const tableSelect = root.querySelector('[data-table]');
    const tableField = root.querySelector('[data-table-field]');
    const notesInput = root.querySelector('[data-notes]');
    const typeInputs = root.querySelectorAll('input[name="type"]');

    let cart = loadCart();
    let isSubmitting = false;

    function loadCart() {
        try {
            const stored = JSON.parse(localStorage.getItem(STORAGE_KEY) ?? '{}');
            const entries = Object.values(stored).filter(
                (item) => item && item.id && item.name && Number.isFinite(item.price) && item.qty > 0,
            );

            return Object.fromEntries(entries.map((item) => [item.id, item]));
        } catch {
            return {};
        }
    }

    function persist() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
        } catch {
            // Storage can be unavailable (private mode); the cart still works for this page view.
        }
    }

    const selectedType = () => root.querySelector('input[name="type"]:checked')?.value;

    function itemFromCard(card) {
        return {
            id: card.dataset.id,
            name: card.dataset.name,
            price: Number(card.dataset.price),
        };
    }

    function changeQuantity(item, delta) {
        const quantity = Math.min((cart[item.id]?.qty ?? 0) + delta, MAX_QUANTITY);

        if (quantity <= 0) {
            delete cart[item.id];
        } else {
            cart[item.id] = { ...item, qty: quantity };
        }

        persist();
        render();
    }

    function removeItem(id) {
        delete cart[id];
        persist();
        render();
    }

    function render() {
        const items = Object.values(cart);
        const total = items.reduce((sum, item) => sum + item.price * item.qty, 0);
        const count = items.reduce((sum, item) => sum + item.qty, 0);

        root.querySelectorAll('[data-menu-item]').forEach((card) => {
            const quantity = cart[card.dataset.id]?.qty ?? 0;

            card.dataset.active = quantity > 0 ? 'true' : 'false';
            card.querySelector('[data-qty]').textContent = quantity;
        });

        cartList.replaceChildren(
            ...items.map((item) => {
                const row = rowTemplate.content.firstElementChild.cloneNode(true);

                row.dataset.id = item.id;
                row.querySelector('[data-name]').textContent = item.name;
                row.querySelector('[data-qty]').textContent = item.qty;
                row.querySelector('[data-line-total]').textContent = money(item.price * item.qty);

                return row;
            }),
        );

        emptyState.hidden = items.length > 0;
        countLabel.textContent = count;
        totalLabel.textContent = money(total);
        placeOrderButton.disabled = items.length === 0 || isSubmitting;
        placeOrderButton.textContent = isSubmitting ? 'Placing order…' : 'Place Order';
    }

    function showError(message) {
        errorBox.textContent = message;
        errorBox.hidden = false;
    }

    function clearError() {
        errorBox.hidden = true;
        errorBox.textContent = '';
    }

    function syncTableField() {
        const isDineIn = selectedType() === 'dine_in';

        tableField.hidden = !isDineIn;

        if (!isDineIn) {
            tableSelect.value = '';
        }
    }

    async function placeOrder() {
        clearError();

        const items = Object.values(cart).map((item) => ({
            menu_item_id: Number(item.id),
            quantity: item.qty,
        }));

        isSubmitting = true;
        render();

        try {
            const response = await fetch(root.dataset.storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    type: selectedType(),
                    customer_name: customerInput.value.trim(),
                    dining_table_id: tableSelect.value || null,
                    notes: notesInput.value.trim() || null,
                    items,
                }),
            });

            if (response.ok) {
                cart = {};
                persist();
                window.location.reload();

                return;
            }

            const data = await response.json().catch(() => ({}));

            showError(
                response.status === 422
                    ? Object.values(data.errors ?? {}).flat().join(' ')
                    : (data.message ?? 'Something went wrong. Please try again.'),
            );
        } catch {
            showError('Could not reach the server. Check your connection and try again.');
        } finally {
            isSubmitting = false;
            render();
        }
    }

    root.addEventListener('click', (event) => {
        const button = event.target.closest('[data-action]');

        if (!button) {
            return;
        }

        const card = button.closest('[data-menu-item]');
        const row = button.closest('[data-row]');
        const item = card ? itemFromCard(card) : cart[row?.dataset.id];

        if (!item) {
            return;
        }

        switch (button.dataset.action) {
            case 'add':
            case 'inc':
                changeQuantity(item, 1);
                break;
            case 'dec':
                changeQuantity(item, -1);
                break;
            case 'remove':
                removeItem(item.id);
                break;
        }
    });

    root.querySelector('[data-clear]').addEventListener('click', () => {
        cart = {};
        persist();
        clearError();
        render();
    });

    root.querySelector('[data-toggle-notes]').addEventListener('click', () => {
        notesInput.hidden = !notesInput.hidden;

        if (!notesInput.hidden) {
            notesInput.focus();
        }
    });

    typeInputs.forEach((input) => input.addEventListener('change', syncTableField));
    placeOrderButton.addEventListener('click', placeOrder);

    root.querySelectorAll('[data-menu-item]').forEach((card) => {
        const item = itemFromCard(card);

        if (cart[item.id]) {
            cart[item.id] = { ...cart[item.id], ...item };
        }
    });

    syncTableField();
    render();
}
