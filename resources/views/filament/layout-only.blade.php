<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Admin UI' }}</title>
    <style>
        body{font-family:ui-sans-serif,system-ui,Segoe UI,Roboto,Arial; background:#0b1220; color:#e5e7eb; margin:0;}
        .wrap{max-width:980px; margin:0 auto; padding:48px 20px;}
        .card{background:#111827; border:1px solid rgba(255,255,255,.08); border-radius:16px; padding:28px;}
        h1{font-size:22px; margin:0 0 12px;}
        p{margin:0 0 18px; color:#9ca3af; line-height:1.6;}
        .pill{display:inline-block; padding:6px 10px; border-radius:999px; background:#1f2937; border:1px solid rgba(255,255,255,.08); color:#e5e7eb; font-size:12px;}
        .grid{display:grid; grid-template-columns:1fr 1fr; gap:14px;}
        .box{background:#0f172a; border:1px solid rgba(255,255,255,.06); border-radius:14px; padding:14px;}
        .box strong{display:block; margin-bottom:6px;}
        .muted{color:#94a3b8; font-size:13px;}
        @media (max-width: 640px){.grid{grid-template-columns:1fr;}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <span class="pill">LAYOUT-ONLY MODE</span>
        <h1 style="margin-top:14px;">Filament Admin UI (No DB)</h1>
        <p>
            MySQL connection is failing (or intentionally disabled). This page is a safe HTML placeholder so you can
            validate the UI without backend/database calls.
        </p>

        <div class="grid">
            <div class="box">
                <strong>Tables</strong>
                <div class="muted">Empty placeholders (no queries executed).</div>
            </div>
            <div class="box">
                <strong>Widgets</strong>
                <div class="muted">Not executed while layout-only mode is ON.</div>
            </div>
            <div class="box">
                <strong>Actions</strong>
                <div class="muted">Disabled/neutralized to avoid DB writes.</div>
            </div>
            <div class="box">
                <strong>Fix</strong>
                <div class="muted">Connect MySQL + set FILAMENT_LAYOUT_ONLY=false to restore full functionality.</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

