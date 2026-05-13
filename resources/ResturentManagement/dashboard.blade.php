<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BistroControl | Restaurant Management</title>

    <!-- Google Fonts & Bootstrap Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-dark: #0f172a;
            --card-dark: #1e293b;
            --accent-orange: #f97316;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --success: #22c55e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: var(--card-dark);
            border-right: 1px solid #334155;
            padding: 25px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--accent-orange);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-item {
            list-style: none;
            padding: 12px 15px;
            border-radius: 10px;
            color: var(--text-muted);
            margin-bottom: 8px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--accent-orange);
            color: white;
        }

        /* Main Workspace */
        .container {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Top KPI Stats */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-dark);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #334155;
        }

        .stat-card span { color: var(--text-muted); font-size: 0.9rem; }
        .stat-card h2 { font-size: 1.8rem; margin: 10px 0; }

        /* Tables & Orders Section */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .table-map {
            background: var(--card-dark);
            border-radius: 16px;
            padding: 25px;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .table-btn {
            aspect-ratio: 1;
            background: #334155;
            border: none;
            border-radius: 12px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .table-btn.occupied { border: 2px solid var(--accent-orange); }
        .table-btn.available { border: 2px solid var(--success); }

        /* Order Sidebar */
        .live-orders {
            background: var(--card-dark);
            border-radius: 16px;
            padding: 20px;
        }

        .order-item {
            background: #0f172a;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            border-left: 4px solid var(--accent-orange);
        }

        .order-item h4 { font-size: 0.9rem; margin-bottom: 5px; }
        .order-item p { font-size: 0.8rem; color: var(--text-muted); }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo"><i class="bi bi-egg-fried"></i> BistroControl</div>
        <ul>
            <li class="nav-item active"><i class="bi bi-house-door"></i> Floor Map</li>
            <li class="nav-item"><i class="bi bi-list-stars"></i> Menu Manager</li>
            <li class="nav-item"><i class="bi bi-journal-text"></i> Reservations</li>
            <li class="nav-item"><i class="bi bi-graph-up-arrow"></i> Sales Analytics</li>
            <li class="nav-item"><i class="bi bi-people"></i> Staff</li>
        </ul>
    </aside>

    <main class="container">
        <div class="header">
            <div>
                <h1>Floor Overview</h1>
                <p style="color: var(--text-muted)">Lunch Service | Shift: Main</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <button style="padding: 10px 20px; border-radius: 8px; border: none; background: #334155; color: white;">History</button>
                <button style="padding: 10px 20px; border-radius: 8px; border: none; background: var(--accent-orange); color: white;">New Walk-in</button>
            </div>
        </div>

        <section class="stats-row">
            <div class="stat-card">
                <span>Today's Revenue</span>
                <h2>$2,840.00</h2>
                <p style="color: var(--success); font-size: 0.8rem;">↑ 14% from last Tuesday</p>
            </div>
            <div class="stat-card">
                <span>Active Tables</span>
                <h2>12 / 20</h2>
                <p style="color: var(--text-muted); font-size: 0.8rem;">60% Occupancy</p>
            </div>
            <div class="stat-card">
                <span>Pending Orders</span>
                <h2>08</h2>
                <p style="color: var(--accent-orange); font-size: 0.8rem;">Avg. wait: 14 mins</p>
            </div>
        </section>

        <div class="content-grid">
            <!-- Floor Map -->
            <section class="table-map">
                <h3>Main Dining Room</h3>
                <div class="grid-layout">
                    <button class="table-btn occupied"><b>T-1</b><br><small>4 Guests</small></button>
                    <button class="table-btn available"><b>T-2</b><br><small>Ready</small></button>
                    <button class="table-btn occupied"><b>T-3</b><br><small>2 Guests</small></button>
                    <button class="table-btn available"><b>T-4</b><br><small>Ready</small></button>
                    <button class="table-btn available"><b>T-5</b><br><small>Ready</small></button>
                    <button class="table-btn occupied"><b>T-6</b><br><small>6 Guests</small></button>
                    <button class="table-btn occupied"><b>T-7</b><br><small>2 Guests</small></button>
                    <button class="table-btn available"><b>T-8</b><br><small>Ready</small></button>
                </div>
            </section>

            <!-- Order Stream -->
            <section class="live-orders">
                <h3>Live Kitchen Orders</h3>
                <div class="order-item">
                    <h4>Table #1</h4>
                    <p>2x Wagyu Burger, 1x Truffle Fries</p>
                    <small style="color: var(--accent-orange)">Ordered 8m ago</small>
                </div>
                <div class="order-item">
                    <h4>Table #6</h4>
                    <p>1x Salmon Fillet, 1x Caesar Salad</p>
                    <small style="color: var(--accent-orange)">Ordered 12m ago</small>
                </div>
                <div class="order-item">
                    <h4>Table #3</h4>
                    <p>3x Margherita Pizza (Large)</p>
                    <small style="color: var(--accent-orange)">Ordered 2m ago</small>
                </div>
            </section>
        </div>
    </main>

</body>
</html>
