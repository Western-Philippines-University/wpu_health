<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WPU Health Services - Admin Portal</title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <meta name="theme-color" content="#2563eb">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --secondary: #059669;
            --danger: #dc2626;
            --warning: #d97706;
            --dark: #1f2937;
            --light: #f9fafb;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
            --gray-dark: #374151;
            --spacing: 12px;
            --radius: 6px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(180deg, #f8fbff 0%, #f3f7ff 40%, #f9fafb 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--dark);
            line-height: 1.5;
            font-size: 13px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing);
        }
        
        /* Compact Header */
        .admin-header {
            background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 35%, #1e40af 100%);
            color: white;
            padding: calc(var(--spacing) * 1.25) 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid var(--primary-dark);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: var(--spacing);
        }
        
        .logo img {
            height: 42px;
            width: 42px;
            border-radius: 50%;
            background: white;
            padding: 4px;
            object-fit: contain;
        }
        
        .logo-text h1 {
            font-size: 18px;
            font-weight: 600;
            line-height: 1.2;
        }
        
        .logo-text p {
            font-size: 11px;
            opacity: 0.85;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .admin-details h3 {
            font-size: 16px;
            margin-bottom: 3px;
        }
        
        .admin-details p {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Compact Main Content */
        .admin-main {
            padding: calc(var(--spacing) * 2) 0;
            flex: 1 0 auto;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: calc(var(--spacing) * 2);
            background: white;
            padding: calc(var(--spacing) * 1.75);
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.08);
            border: 1px solid var(--gray-light);
        }
        
        .welcome-section h2 {
            color: var(--primary);
            font-size: 20px;
            margin-bottom: var(--spacing);
            font-weight: 600;
        }
        
        .welcome-section p {
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
            font-size: 13px;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: var(--spacing);
            margin-bottom: calc(var(--spacing) * 2);
        }
        
        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: var(--spacing);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-light);
            display: flex;
            align-items: center;
            gap: var(--spacing);
            transition: transform 0.15s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
        
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            flex-shrink: 0;
        }
        
        .health-stat .stat-icon {
            background: var(--primary);
        }
        
        .dental-stat .stat-icon {
            background: var(--secondary);
        }
        
        .user-stat .stat-icon {
            background: var(--warning);
        }
        
        .appointment-stat .stat-icon {
            background: var(--danger);
        }
        
        .stat-info h3 {
            font-size: 20px;
            margin-bottom: 2px;
            font-weight: 600;
        }
        
        .stat-info p {
            color: var(--gray);
            font-size: 11px;
        }
        
        /* Compact Admin Panels */
        .panels-section {
            margin-bottom: calc(var(--spacing) * 2);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: calc(var(--spacing) * 1.5);
        }
        
        .section-title h2 {
            color: var(--primary);
            font-size: 18px;
            margin-bottom: 6px;
            font-weight: 600;
        }
        
        .section-title p {
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
            font-size: 12px;
        }
        
        .panels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: calc(var(--spacing) * 1.25);
        }
        
        .panel-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--gray-light);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .panel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12);
        }
        
        .panel-header {
            padding: calc(var(--spacing) * 1.25);
            color: white;
            display: flex;
            align-items: center;
            gap: var(--spacing);
        }
        
        .health-panel .panel-header {
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }
        
        .dental-panel .panel-header {
            background: linear-gradient(135deg, #10b981, #0f766e);
        }
        
        .admin-panel .panel-header {
            background: linear-gradient(135deg, #f59e0b, #b45309);
        }
        
        .panel-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            backdrop-filter: blur(2px);
        }
        
        .panel-title h3 {
            font-size: 15px;
            margin-bottom: 2px;
            font-weight: 600;
        }
        
        .panel-title p {
            font-size: 11px;
            opacity: 0.9;
        }
        
        .panel-body {
            padding: calc(var(--spacing) * 1.25);
        }
        
        .panel-features {
            list-style: none;
            margin-bottom: var(--spacing);
        }
        
        .panel-features li {
            padding: 8px 0;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
        }
        
        .panel-features li:last-child {
            border-bottom: none;
        }
        
        .panel-features i {
            color: var(--primary);
            font-size: 12px;
        }
        
        .panel-btn {
            display: block;
            width: 100%;
            padding: 10px 12px;
            text-align: center;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            font-size: 12px;
            letter-spacing: 0.2px;
        }
        
        .health-panel .panel-btn {
            background: #2563eb;
            color: white;
        }
        
        .health-panel .panel-btn:hover {
            background: var(--primary-dark);
        }
        
        .dental-panel .panel-btn {
            background: #0ea5a4;
            color: white;
        }
        
        .dental-panel .panel-btn:hover {
            background: #047857;
        }
        
        .admin-panel .panel-btn {
            background: #d97706;
            color: white;
        }
        
        .admin-panel .panel-btn:hover {
            background: #b45309;
        }
        
        /* Recent Activity */
        .activity-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .health-activity .activity-icon {
            background: var(--primary-light);
        }
        
        .dental-activity .activity-icon {
            background: var(--secondary);
        }
        
        .admin-activity .activity-icon {
            background: var(--warning);
        }
        
        .activity-content h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .activity-content p {
            font-size: 14px;
            color: var(--gray);
        }
        
        .activity-time {
            margin-left: auto;
            font-size: 12px;
            color: var(--gray);
        }
        
        /* Compact Footer */
        .admin-footer {
            background: var(--dark);
            color: white;
            padding: var(--spacing) 0;
            text-align: center;
            margin-top: 0;
            border-top: 1px solid var(--gray-dark);
        }
        
        .footer-content {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .copyright {
            font-size: 11px;
            opacity: 0.7;
        }
        
        /* Compact Responsive */
        @media (max-width: 992px) {
            .panels-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .logo img {
                height: 32px;
            }
            
            .logo-text h1 {
                font-size: 14px;
            }
        }
        
        @media (max-width: 576px) {
            .welcome-section h2 {
                font-size: 18px;
            }
            
            .section-title h2 {
                font-size: 16px;
            }
            
            .panel-features li {
                font-size: 11px;
            }
        }
        
        /* Custom Icons */
        .icon {
            display: inline-block;
            width: 1em;
            height: 1em;
            stroke-width: 0;
            stroke: currentColor;
            fill: currentColor;
        }
        
        .icon-stethoscope:before {
            content: "🩺";
        }
        
        .icon-tooth:before {
            content: "🦷";
        }
        
        .icon-heartbeat:before {
            content: "💓";
        }
        
        .icon-check:before {
            content: "✓";
            font-weight: bold;
        }
        
        .icon-arrow-right:before {
            content: "→";
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container header-content">
            <div class="logo">
                <img src="assets/images/logo.png" alt="WPU Logo" />
                <div class="logo-text">
                    <h1>WPU Health Services</h1>
                    <p>Administrative Portal</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="container">
           

            <!-- Unified Admin Panel Section -->
            <section class="panels-section">
                <div class="section-title">
                    <h2>Unified Administrative Panel</h2>
                    <p>Access all health service modules in one unified interface</p>
                </div>
                <div class="panels-grid">
                    <!-- Unified Admin Panel -->
                    <div class="panel-card health-panel" style="grid-column: 1 / -1; max-width: 600px; margin: 0 auto;">
                        <div class="panel-header">
                            <div class="panel-icon">
                                <i class="icon-heartbeat"></i>
                            </div>
                            <div class="panel-title">
                                <h3>WPU Health Services - Unified Admin</h3>
                                <p>All modules in one place</p>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="panel-features">
                                <li><i class="icon-check"></i> Medical Certificates & Referrals</li>
                                <li><i class="icon-check"></i> Dental Records Management</li>
                                <li><i class="icon-check"></i> Health Records Management</li>
                                <li><i class="icon-check"></i> Patient Records & Reports</li>
                                <li><i class="icon-check"></i> Unified Search & Statistics</li>
                            </ul>
                            <a href="../admin/workspace/admin/admin.php" class="panel-btn">
                                Access Unified Admin Panel <i class="icon-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
        <div class="container footer-content">
            <div class="copyright">
                &copy; 2025 WPU Health Services. Administrative Use Only.
            </div>
        </div>
    </footer>

    <script>
        // Simple logout functionality
        
        // Add some interactive elements
        document.querySelectorAll('.panel-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>