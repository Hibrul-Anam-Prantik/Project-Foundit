{{-- Shared look for every Categories page, scoped so public/css/style.css stays untouched.
     Uses the same CSS variables as the FoundIT design system (see :root in style.css). --}}
<style>
    .cat-page { max-width: 1000px; margin: 2rem auto; padding: 0 5%; }
    .cat-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .cat-header h1 { color: var(--dark-text); font-size: 1.8rem; margin-bottom: .35rem; }
    .cat-header p { color: #666; font-size: .95rem; }
    .cat-page .btn { display: inline-flex; align-items: center; gap: .45rem; padding: .6rem 1.1rem; border: none; border-radius: var(--border-radius); font-family: inherit; font-weight: 600; font-size: .95rem; cursor: pointer; text-decoration: none; transition: background .2s ease; }
    .cat-page .btn-primary { background: var(--primary-color); color: #FFF; }
    .cat-page .btn-primary:hover { background: #004a80; }
    .cat-page .btn-ghost { background: #FFF; color: var(--primary-color); border: 1px solid var(--primary-color); }
    .cat-page .btn-ghost:hover { background: var(--secondary-color); }
    .cat-page .btn-danger { background: #FFF; color: #c0392b; border: 1px solid #e0b4ae; }
    .cat-page .btn-danger:hover { background: #fdecea; }
    .cat-table { width: 100%; border-collapse: collapse; background: #FFF; border-radius: var(--border-radius); overflow: hidden; box-shadow: var(--shadow); }
    .cat-table th, .cat-table td { padding: .85rem 1rem; text-align: left; border-bottom: 1px solid var(--border-color); vertical-align: top; }
    .cat-table th { background: var(--secondary-color); color: var(--dark-text); font-size: .85rem; text-transform: uppercase; letter-spacing: .03em; }
    .cat-table tr:last-child td { border-bottom: none; }
    .cat-actions { display: flex; gap: .5rem; align-items: center; }
    .cat-actions form { margin: 0; }
    .cat-page .alert { padding: .85rem 1rem; border-radius: var(--border-radius); margin-bottom: 1rem; font-size: .95rem; }
    .cat-page .alert-success { background: #e8f6ec; color: #1e7b34; border: 1px solid #bfe3c8; }
    .cat-page .alert-error { background: #fdecea; color: #a02c20; border: 1px solid #f2c2bb; }
    .cat-page .alert ul { margin: 0; padding-left: 1.2rem; }
    .empty-state { background: #FFF; border: 1px dashed var(--border-color); border-radius: var(--border-radius); padding: 2rem; text-align: center; color: #666; }
    .cat-form { background: #FFF; border-radius: var(--border-radius); box-shadow: var(--shadow); padding: 1.5rem; max-width: 620px; }
    .form-field { margin-bottom: 1.2rem; }
    .form-field label { display: block; font-weight: 600; margin-bottom: .4rem; color: var(--dark-text); font-size: .95rem; }
    .form-field input, .form-field textarea { width: 100%; padding: .65rem .75rem; border: 1px solid var(--border-color); border-radius: var(--border-radius); font-family: inherit; font-size: 1rem; }
    .form-field input:focus, .form-field textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(0, 90, 156, .12); }
    .field-error { color: #a02c20; font-size: .85rem; margin-top: .35rem; }
    .form-actions { display: flex; gap: .6rem; align-items: center; margin-top: 1.2rem; }
</style>
