<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NMDPRA Incident Tracker')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        :root{
            /* Base Theme Colors */
            --base:#0d0f10;
            --surface:#141719;
            --panel:#1c2025;
            --rule:#252c33;
            --rule-dim:#1c2229;
            --muted:#8b9eb0; /* Lightened slightly for better readability on dark */
            --body:#b8c4ce;
            --bright:#ffffff;

            /* Status Colors */
            --accent:#2563eb; /* Swapped to a modern blue, or change back to #d95f1a if orange is required */
            --accent-bg:rgba(37, 99, 235, 0.15);
            --danger:#ef4444;
            --danger-bg:rgba(239, 68, 68, 0.15);
            --warn:#f59e0b;
            --warn-bg:rgba(245, 158, 11, 0.15);
            --ok:#10b981;
            --ok-bg:rgba(16, 185, 129, 0.15);
            --info:#0ea5e9;
            --info-bg:rgba(14, 165, 233, 0.15);

            /* Modern Structural Variables */
            --radius:12px; /* Global soft corners */
            --btn-radius:8px;
            --fs:14px; /* Slightly larger base font for accessibility */
        }

        *{ box-sizing:border-box; }
        body{
            margin:0;
            font-family:'IBM Plex Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            font-size:var(--fs);
            background:var(--base);
            color:var(--body);
            min-height:100vh;
            -webkit-font-smoothing: antialiased;
        }

        a{ color:inherit; text-decoration:none; }
        .plex-mono{ font-family:'IBM Plex Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .uppercase{ text-transform:uppercase; }

        /* Layout */
        .app-shell{
            display:flex;
            min-height:100vh;
            width:100%;
        }

        /* Sidebar */
        .sidebar{
            width:240px; /* Slightly wider for comfort */
            background:var(--surface);
            border-right:1px solid var(--rule);
            padding:20px 16px;
            display:flex;
            flex-direction:column;
        }

        .brand{
            margin-bottom:32px;
            display:flex;
            align-items:center;
            gap:12px;
            padding: 0 8px;
        }

        .brand-mark{
            width:40px;
            height:40px;
            background:var(--accent);
            border-radius: 8px; /* Softer logo container */
            clip-path: polygon(0 0, 80% 0, 100% 20%, 100% 100%, 0 100%);
        }

        .brand-text{
            line-height:1.2;
        }
        .brand-text .top{
            font-weight:700;
            color:var(--bright);
            font-size:14px;
            margin:0;
        }
        .brand-text .sub{
            color:var(--muted);
            font-size:12px;
            margin:2px 0 0 0;
        }

        .nav-group-label{
            font-size:10px;
            font-weight: 600;
            letter-spacing:0.12em;
            text-transform: uppercase;
            color:var(--muted);
            margin:14px 8px 8px;
        }

        /* Modern Rounded Navigation Links */
        .nav-link{
            display:flex;
            align-items:center;
            gap:12px;
            padding:10px 14px;
            margin-bottom: 4px;
            color:var(--muted);
            border-radius: 8px; /* Modern Pill/Box */
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .nav-link:hover{
            color:var(--bright);
            background:rgba(255,255,255,0.05);
        }
        .nav-link.active{
            color:var(--bright);
            background:var(--accent-bg);
            box-shadow: inset 2px 0 0 var(--accent); /* Subtle left highlight within the box */
        }

        /* Main */
        .main{
            flex:1;
            padding:32px;
            overflow-y: auto;
        }

        /* Panels/cards */
        .panel{
            background:var(--panel);
            border:1px solid var(--rule);
            border-radius:var(--radius);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .panel-header{
            border-bottom:1px solid var(--rule);
            padding:16px 20px;
            display:flex;
            align-items:baseline;
            justify-content:space-between;
            gap:10px;
        }

        .panel-title{
            margin:0;
            font-size:11px;
            font-weight: 600;
            letter-spacing:0.10em;
            color:var(--muted);
            text-transform:uppercase;
            font-family:'IBM Plex Mono', monospace;
        }

        /* Alerts */
        .alert{
            border:1px solid var(--rule);
            padding:14px 16px;
            margin-bottom:20px;
            border-radius: var(--btn-radius);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-success{
            border-color:rgba(16, 185, 129, 0.4);
            color:var(--ok);
            background:var(--ok-bg);
        }
        .alert-error{
            border-color:rgba(239, 68, 68, 0.4);
            color:var(--danger);
            background:var(--danger-bg);
        }

        /* Buttons */
        .btn{
            border-radius:var(--btn-radius);
            border:0;
            cursor:pointer;
            font-size:13px;
            font-weight:600;
            padding:10px 18px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            line-height:1;
            transition: all 0.2s ease;
        }

        .btn-primary{
            background:var(--accent);
            color:#fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .btn-primary:hover{
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        .btn-ghost{
            background:transparent;
            border:1px solid var(--rule);
            color:var(--body);
        }
        .btn-ghost:hover{
            border-color:var(--muted);
            color:var(--bright);
            background:rgba(255,255,255,0.05);
        }

        /* Tables */
        table{
            width:100%;
            border-collapse:collapse;
        }
        .data-table th{
            font-size:11px;
            text-transform:uppercase;
            letter-spacing:0.10em;
            color:var(--muted);
            padding:12px 16px;
            border-bottom:1px solid var(--rule);
            font-family:'IBM Plex Mono', monospace;
            background: rgba(0,0,0,0.1);
            text-align:left;
        }
        .data-table td{
            padding:14px 16px;
            border-bottom:1px solid var(--rule-dim);
            font-size: 13px;
        }
        .data-table tr:hover td{
            background:rgba(255,255,255,0.02);
        }

        .ref-link{
            font-family:'IBM Plex Mono', monospace;
            color:var(--accent);
            font-weight: 500;
        }
        .ref-link:hover{
            color:var(--bright);
            text-decoration: underline;
        }

        /* Badges */
        .badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:4px 8px;
            border-radius: 6px;
            font-size:10px;
            letter-spacing:0.05em;
            text-transform:uppercase;
            font-weight:600;
            font-family:'IBM Plex Mono', monospace;
            border:1px solid transparent;
            line-height:1;
        }

        .badge-fatal{ background:var(--danger-bg); color:var(--danger); border-color:rgba(239,68,68,0.4); }
        .badge-critical{ background:var(--warn-bg); color:var(--warn); border-color:rgba(245,158,11,0.4); }
        .badge-high{ background:rgba(245,158,11,0.05); color:var(--warn); border-color:rgba(245,158,11,0.2); }
        .badge-compliant{ background:var(--ok-bg); color:var(--ok); border-color:rgba(16,185,129,0.4); }
        .badge-non-compliant{ background:var(--danger-bg); color:var(--danger); border-color:rgba(239,68,68,0.4); }
        .badge-status{ background:rgba(255,255,255,0.05); color:var(--body); border-color:rgba(255,255,255,0.1); }

        /* Leaflet overrides */
        .leaflet-container{
            background:var(--base);
            border-radius: var(--radius);
        }
        .leaflet-popup-content-wrapper,
        .leaflet-popup-tip{
            border-radius: 8px !important;
        }
        .leaflet-popup-content-wrapper{
            background:var(--panel) !important;
            border:1px solid var(--rule) !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3) !important;
            color:var(--bright) !important;
        }
        .leaflet-popup-content{
            margin: 12px !important;
        }
        .leaflet-popup-content b{
            color:var(--bright);
            font-family:'IBM Plex Mono', monospace;
        }
    </style>
</head>

<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark"></div>
            <div class="brand-text">
                <p class="top">NMDPRA</p>
                <p class="sub">Incident Registry</p>
            </div>
        </div>

        @auth
            <div class="nav-group-label">Navigation</div>

            @php
                $role = Auth::user()->role ?? '';
                $dashboardRoute = in_array($role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor'])
                    ? route('dashboard.admin')
                    : route('dashboard.operator');
                $isDashboardActive = request()->routeIs('dashboard.admin') || request()->routeIs('dashboard.operator');
            @endphp

            <a href="{{ $dashboardRoute }}" class="nav-link {{ $isDashboardActive ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('incidents.index') }}" class="nav-link {{ request()->routeIs('incidents.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Incident Registry
            </a>

            <div class="nav-group-label" style="margin-top:24px;">Account</div>
            <div style="background: rgba(0,0,0,0.2); border-radius: 8px; padding:12px; border:1px solid var(--rule);">
                <div style="font-family:'IBM Plex Mono', monospace; font-size: 11px; color:var(--bright); margin-bottom: 4px;">
                    {{ Auth::user()->role }}
                </div>
                <div style="color:var(--muted); font-size: 12px;">
                    {{ Auth::user()->organisation->licence_number ?? 'No Licence' }}
                </div>
            </div>

            <div style="margin-top:auto; padding-top:20px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="width:100%;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    <main class="main">
        @if(session('success'))
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>