document.querySelectorAll('[data-role-form]').forEach((form) => {
    const adminOnly = form.querySelectorAll('[data-admin-only]');

    const sync = () => {
        const isAdmin = form.querySelector('input[name="role"]:checked')?.value === 'admin';

        adminOnly.forEach((field) => {
            field.hidden = !isAdmin;
            field.querySelectorAll('input').forEach((input) => {
                input.disabled = !isAdmin;
            });
        });
    };

    form.querySelectorAll('input[name="role"]').forEach((radio) => radio.addEventListener('change', sync));

    sync();
});
