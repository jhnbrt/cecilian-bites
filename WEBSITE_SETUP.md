# Cecilian Bites: customer, cashier and admin website

## Included

- Customer menu at `/`: image-inspired lime-to-blue surface, black header, lavender side cart, search/category filters, quantity updates, pickup checkout and browser-session order tracking.
- Cashier at `/dashboard`: reference-inspired POS menu and order panel; server-backed ordering, dine-in table selection, takeaway/delivery order types, cooking requests, order preparation status, cash collection and printable order receipts.
- Admin at `/admin`: existing menu/photo/category management, tables, staff accounts, order management and sales reports; added cash collected today and unpaid order totals.
- Real database orders shared between customers and staff. Current menu prices are used on the server. Customer checkout is session-locked and single-use. Cash payment is recorded under a database lock to reject duplicate collection, insufficient cash and canceled orders.

## Start on your computer / Herd

Back up your project and database, then copy these source files into your project. Preserve your existing `.env` and database. Dependencies and private environment files are excluded from this archive.

Run:

```sh
composer install
npm ci
php artisan migrate
php artisan storage:link
php artisan optimize:clear
npm run build
php artisan serve
```

On a new installation, first copy `.env.example` to `.env`, configure your database, and run `php artisan key:generate`. Use PHP compatible with composer.lock. Use `php artisan db:seed` only for a fresh demo database: it creates sample data and demo admin/cashier accounts (admin@example.com / cashier@example.com, password: password). Change these passwords before real use. The existing admin Staff page creates cashier accounts. Public staff registration is now disabled by default; leave PUBLIC_STAFF_REGISTRATION=false for production. Set RESTAURANT_CURRENCY=₱ in your environment to use pesos consistently. Upload your actual dish photos via admin.

## End-to-end check

1. Open `/` as a customer, add dishes and complete pickup checkout. Prices and order total come from the database.
2. Sign in as cashier, find that order in the order queue, and advance preparation status.
3. Open Unpaid / Collect payment, enter actual cash received and confirm. The receipt shows change. Refreshing or submitting again cannot collect payment twice.
4. Refresh the customer's My orders page to see preparation and payment status.
5. View the admin overview to compare cash collected with unpaid order totals.

## Verification and remaining limits

Frontend production build and JavaScript syntax check passed. Added PHP feature tests covering checkout pricing, duplicate checkout, unavailable dishes, order visibility and cash collection protections. PHP and Composer are unavailable in the editing environment, so run `php artisan test` locally and verify the rendered website before launch.

This is a runnable Laravel source project, not a publicly hosted URL. A PHP-capable hosting account/domain is needed for public access. Online card/GCash charging, automatic delivery booking, refunds, discounts and inventory depletion are not implemented. Customer orders use pickup/pay-at-pickup; cashier delivery is a recorded order type only. Staff record cash after receiving it. Paid orders cannot be canceled through the normal preparation control; a refund workflow would need to be added. Existing sales reports count non-canceled order totals and are distinct from cash collected.

Customer order history is browser-session based, so clearing cookies removes customer access to that history while staff retain database orders. The printable order receipt is a simple order/payment acknowledgment, not a configured tax invoice.

## Latest reference layout

Updated both ordering screens to compact horizontal dish cards, three menu columns on desktop, a lavender order rail, and an order queue at the top. Customer queue shows only orders placed in the current browser session. Smaller screens use fewer columns and stack the order panel to preserve readable controls. Actual dish images come from admin uploads; the reference dishes and sample orders are not copied as real inventory or transactions. Frontend build passed; Laravel rendering and PHP feature tests still need local verification.

## Current UI: white / orange food dashboard

Latest design replaces the prior lime/lavender layout with a white left navigation sidebar, gray workspace, orange category selection and order buttons, larger vertical photo cards and a white invoice panel. Both cashier and customer order screens use this style. Mobile navigation scrolls horizontally and the invoice stacks below the menu. Features shown only as decoration in the reference (wishlist, card-provider logos, reviews, fake discounts and taxes) are not presented as working payment methods. Existing ordering and cash-collection behavior remains. Frontend build and JS syntax checks passed; PHP/runtime visual verification is still pending.
