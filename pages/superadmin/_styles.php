*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Inter', sans-serif;
    background: #070c18;
    color: #e2e8f0;
    min-height: 100vh;
}

/* ── Top nav ─────────────────────────────────────────────────────── */
.sa-nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(7,12,24,0.85);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    display: flex; align-items: center; gap: 0;
    padding: 0 28px;
    height: 60px;
}

.sa-nav-brand {
    display: flex; align-items: center; gap: 10px;
    font-weight: 800; font-size: 0.95rem; color: #fff;
    text-decoration: none; margin-right: 32px;
}
.sa-nav-brand .brand-icon {
    width: 32px; height: 32px; border-radius: 9px;
    background: linear-gradient(135deg,#5b21b6,#7c3aed);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; color: #fff;
}

.sa-nav-links { display: flex; gap: 2px; flex: 1; }
.sa-nav-link {
    display: flex; align-items: center; gap: 7px;
    padding: 7px 14px; border-radius: 9px;
    color: rgba(255,255,255,0.5); font-size: 0.83rem; font-weight: 500;
    text-decoration: none; transition: all 0.15s;
}
.sa-nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
.sa-nav-link.active { color: #a78bfa; background: rgba(88,28,220,0.15); }

.sa-nav-right { display: flex; align-items: center; gap: 12px; margin-left: auto; }
.sa-user { font-size: 0.82rem; color: rgba(255,255,255,0.4); }
.sa-logout { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 9px; background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: all 0.15s; }
.sa-logout:hover { background: rgba(239,68,68,0.2); }

/* ── Main wrap ───────────────────────────────────────────────────── */
.sa-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 36px 28px 60px;
}

/* ── Page header ─────────────────────────────────────────────────── */
.page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 32px; gap: 16px; flex-wrap: wrap;
}
.page-title { font-size: 1.9rem; font-weight: 800; color: #fff; letter-spacing: -0.03em; }
.page-sub { color: rgba(255,255,255,0.35); font-size: 0.88rem; margin-top: 4px; }

/* ── Buttons ─────────────────────────────────────────────────────── */
.btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 12px;
    background: linear-gradient(135deg,#5b21b6,#7c3aed);
    color: #fff; font-weight: 700; font-size: 0.88rem;
    text-decoration: none; border: none; cursor: pointer; font-family: 'Inter',sans-serif;
    box-shadow: 0 4px 20px rgba(88,28,220,0.4); transition: all 0.2s;
    white-space: nowrap;
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(88,28,220,0.55); }

.btn-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 12px;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7); font-weight: 600; font-size: 0.88rem;
    text-decoration: none; cursor: pointer; font-family: 'Inter',sans-serif; transition: all 0.2s;
}
.btn-secondary:hover { background: rgba(255,255,255,0.1); color: #fff; }

.btn-danger-sm {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 8px;
    background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);
    color: #f87171; font-size: 0.78rem; font-weight: 600; text-decoration: none; transition: all 0.15s;
}
.btn-danger-sm:hover { background: rgba(239,68,68,0.2); }

/* ── Stats grid ──────────────────────────────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap: 16px;
    margin-bottom: 36px;
}

.stat-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 18px;
    padding: 22px 24px;
    display: flex; align-items: center; gap: 16px;
    transition: all 0.2s;
}
.stat-card:hover { border-color: rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); }
.stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.stat-value { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -0.04em; line-height: 1; }
.stat-label { font-size: 0.78rem; color: rgba(255,255,255,0.35); font-weight: 500; margin-top: 4px; }

/* ── Section header ──────────────────────────────────────────────── */
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.section-title { font-size: 1.05rem; font-weight: 700; color: #fff; }
.link-all { font-size: 0.82rem; color: #a78bfa; text-decoration: none; display: flex; align-items: center; gap: 5px; }
.link-all:hover { color: #c4b5fd; }

/* ── Shop grid ───────────────────────────────────────────────────── */
.shop-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px,1fr));
    gap: 16px;
}

.shop-card {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    padding: 22px;
    transition: all 0.2s;
}
.shop-card:hover { border-color: rgba(255,255,255,0.12); transform: translateY(-2px); box-shadow: 0 16px 40px rgba(0,0,0,0.3); }

.shop-card-top { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.shop-avatar {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg,#5b21b6,#7c3aed);
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.9rem; color: #fff;
}
.shop-name { font-weight: 700; font-size: 1rem; color: #fff; }
.shop-slug { font-size: 0.75rem; color: rgba(255,255,255,0.3); margin-top: 2px; font-family: monospace; }

.shop-stats { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.shop-stat { font-size: 0.8rem; color: rgba(255,255,255,0.4); display: flex; align-items: center; gap: 8px; }
.shop-stat i { width: 14px; }

.shop-status { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: rgba(255,255,255,0.35); margin-bottom: 14px; }
.status-dot { width: 7px; height: 7px; border-radius: 50%; }
.status-dot.active { background: #34d399; box-shadow: 0 0 8px rgba(52,211,153,0.6); }
.status-dot.inactive { background: #94a3b8; }

.shop-actions { display: flex; gap: 6px; flex-wrap: wrap; }
.shop-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600;
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.6); text-decoration: none; transition: all 0.15s;
}
.shop-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
.shop-btn.btn-danger { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: #f87171; }
.shop-btn.btn-danger:hover { background: rgba(239,68,68,0.2); }
.shop-btn.btn-success { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.2); color: #34d399; }
.shop-btn.btn-success:hover { background: rgba(16,185,129,0.2); }

/* ── Badge ───────────────────────────────────────────────────────── */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 100px;
    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
    border: 1px solid transparent;
    white-space: nowrap;
}

/* ── Table ───────────────────────────────────────────────────────── */
.sa-table-wrap {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    overflow: hidden;
}

.sa-table { width: 100%; border-collapse: collapse; }
.sa-table th {
    padding: 13px 18px; text-align: left;
    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
    color: rgba(255,255,255,0.3); border-bottom: 1px solid rgba(255,255,255,0.06);
    background: rgba(255,255,255,0.02);
}
.sa-table td {
    padding: 14px 18px; font-size: 0.84rem; color: rgba(255,255,255,0.7);
    border-bottom: 1px solid rgba(255,255,255,0.04);
    vertical-align: middle;
}
.sa-table tr:last-child td { border-bottom: none; }
.sa-table tr:hover td { background: rgba(255,255,255,0.02); }
.sa-table .name-cell { color: #fff; font-weight: 600; }
.sa-table .mono { font-family: monospace; font-size: 0.78rem; color: rgba(255,255,255,0.4); }

/* ── Form card ───────────────────────────────────────────────────── */
.form-card {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    padding: 28px 32px;
    max-width: 680px;
}

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.form-grid .span2 { grid-column: span 2; }
@media(max-width:600px){ .form-grid { grid-template-columns: 1fr; } .form-grid .span2 { grid-column: span 1; } }

.field { margin-bottom: 0; }
.field label { display: block; font-size: 0.73rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: rgba(255,255,255,0.35); margin-bottom: 8px; }
.field input, .field select, .field textarea {
    width: 100%; background: rgba(255,255,255,0.05); border: 1.5px solid rgba(255,255,255,0.08);
    border-radius: 11px; color: #f1f5f9; font-size: 0.88rem; padding: 11px 14px;
    outline: none; transition: all 0.2s; font-family: 'Inter',sans-serif;
    appearance: none;
}
.field select option { background: #0f172a; }
.field input::placeholder, .field textarea::placeholder { color: rgba(255,255,255,0.2); }
.field input:focus, .field select:focus, .field textarea:focus {
    background: rgba(255,255,255,0.08); border-color: rgba(88,28,220,0.5);
    box-shadow: 0 0 0 3px rgba(88,28,220,0.12);
}
.field-hint { font-size: 0.73rem; color: rgba(255,255,255,0.25); margin-top: 5px; }
.field-error { font-size: 0.73rem; color: #f87171; margin-top: 5px; display: flex; align-items: center; gap: 4px; }

.section-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 24px 0; }

.cats-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(180px,1fr)); gap: 10px; }
.cat-toggle {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px; border-radius: 11px;
    border: 1.5px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.03);
    cursor: pointer; transition: all 0.15s;
}
.cat-toggle input { display: none; }
.cat-toggle:hover { border-color: rgba(88,28,220,0.4); background: rgba(88,28,220,0.06); }
.cat-toggle.checked { border-color: rgba(88,28,220,0.5); background: rgba(88,28,220,0.1); }
.cat-icon { width: 30px; height: 30px; border-radius: 8px; background: rgba(167,139,250,0.1); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #a78bfa; flex-shrink: 0; }
.cat-label { font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.7); }

/* ── Alert ───────────────────────────────────────────────────────── */
.alert { border-radius: 12px; padding: 13px 16px; font-size: 0.84rem; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7; }
.alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; }

/* ── Empty state ─────────────────────────────────────────────────── */
.empty-state {
    grid-column: 1/-1; text-align: center; padding: 60px 20px;
    color: rgba(255,255,255,0.2); font-size: 0.88rem;
}
.empty-state i { font-size: 2.5rem; display: block; margin-bottom: 12px; opacity: 0.3; }
.empty-state a { color: #a78bfa; text-decoration: none; }

/* ── Detail view ─────────────────────────────────────────────────── */
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media(max-width:768px){ .detail-grid { grid-template-columns: 1fr; } }
.detail-card { background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.07); border-radius: 18px; padding: 22px 24px; }
.detail-card h3 { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.3); margin-bottom: 16px; }
.detail-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.84rem; }
.detail-row:last-child { border-bottom: none; }
.detail-key { color: rgba(255,255,255,0.35); }
.detail-val { color: #fff; font-weight: 600; font-family: monospace; font-size: 0.8rem; }

/* ── Responsive ──────────────────────────────────────────────────── */
@media(max-width:768px) {
    .sa-wrap { padding: 20px 16px; }
    .page-header { flex-direction: column; align-items: flex-start; }
    .shop-grid { grid-template-columns: 1fr; }
    .sa-nav-links { display: none; }
}
