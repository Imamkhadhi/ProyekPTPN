<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'ptpn4_monitoring';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle different pages
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Handle login
if ($_POST['action'] ?? '' === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple authentication (you should hash passwords in production)
    if ($email === 'admin@ptpn4.com' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_email'] = $email;
        header('Location: ?page=dashboard');
        exit;
    } else {
        $login_error = "Invalid email or password";
    }
}

// Handle logout
if ($_GET['action'] ?? '' === 'logout') {
    session_destroy();
    header('Location: ?page=login');
    exit;
}

// Handle form submission for monitoring data
if ($_POST['action'] ?? '' === 'submit_monitoring') {
    $stmt = $pdo->prepare("INSERT INTO monitoring_data (uap_masuk_pressure, uap_masuk_temp, uap_sisa_pressure, uap_sisa_temp, tekanan_oil, lub_oil, cooling_water_temp, cooling_water_keluar, voltage, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    $stmt->execute([
        $_POST['uap_masuk_pressure'],
        $_POST['uap_masuk_temp'],
        $_POST['uap_sisa_pressure'], 
        $_POST['uap_sisa_temp'],
        $_POST['tekanan_oil'],
        $_POST['lub_oil'],
        $_POST['cooling_water_temp'],
        $_POST['cooling_water_keluar'],
        $_POST['voltage']
    ]);
    
    header('Location: ?page=dashboard');
    exit;
}

// Function to get latest monitoring data
function getLatestMonitoringData($pdo) {
    $stmt = $pdo->query("SELECT * FROM monitoring_data ORDER BY created_at DESC LIMIT 1");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get all monitoring data for journal
function getAllMonitoringData($pdo) {
    $stmt = $pdo->query("SELECT * FROM monitoring_data ORDER BY created_at DESC LIMIT 50");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTPN4 Monitoring System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2e7d32, #4caf50);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(46, 125, 50, 0.9);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .nav-menu a:hover, .nav-menu a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-split {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-right {
            flex: 1;
            background: linear-gradient(135deg, #2e7d32, #4caf50);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .logo-large {
            text-align: center;
            color: white;
        }

        .logo-large .logo-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn {
            background: #4caf50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
            width: 100%;
        }

        .btn:hover {
            background: #45a049;
        }

        .btn-secondary {
            background: #666;
        }

        .btn-secondary:hover {
            background: #555;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .gauge-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .gauge {
            width: 120px;
            height: 120px;
            margin: 0 auto 15px;
            position: relative;
            border: 8px solid #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gauge.normal {
            border-color: #4caf50;
            color: #4caf50;
        }

        .gauge.warning {
            border-color: #ff9800;
            color: #ff9800;
        }

        .gauge.danger {
            border-color: #f44336;
            color: #f44336;
        }

        .gauge-value {
            font-size: 24px;
            font-weight: bold;
        }

        .gauge-legend {
            margin-top: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 12px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
            margin-right: 5px;
        }

        .legend-color.red { background: #f44336; }
        .legend-color.yellow { background: #ff9800; }
        .legend-color.green { background: #4caf50; }

        .data-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .data-table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .data-table tr:hover {
            background: #f9f9f9;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #f44336;
            margin-bottom: 15px;
            padding: 10px;
            background: #ffebee;
            border-radius: 4px;
            border-left: 4px solid #f44336;
        }

        .success-message {
            color: #4caf50;
            margin-bottom: 15px;
            padding: 10px;
            background: #e8f5e8;
            border-radius: 4px;
            border-left: 4px solid #4caf50;
        }

        @media (max-width: 768px) {
            .login-split {
                flex-direction: column;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-menu {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<?php if ($page === 'login' && !isset($_SESSION['logged_in'])): ?>
    <div class="login-split">
        <div class="login-left">
            <div class="login-box">
                <h2 style="margin-bottom: 30px; text-align: center; color: #333;">Sign Up</h2>
                
                <?php if (isset($login_error)): ?>
                    <div class="error-message"><?php echo $login_error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="Enter your password">
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="remember" style="width: auto; margin-right: 5px;">
                            Remember me
                        </label>
                    </div>
                    
                    <button type="submit" class="btn">Sign Up</button>
                </form>
                
                <p style="text-align: center; margin-top: 20px; color: #666;">
                    Don't have an account? <a href="#" style="color: #4caf50;">Register</a>
                </p>
            </div>
        </div>
        
        <div class="login-right">
            <div class="logo-large">
                <div class="logo-icon">P4</div>
                <h1>PTPN4</h1>
            </div>
        </div>
    </div>

<?php else: ?>
    <?php if (!isset($_SESSION['logged_in'])): ?>
        <?php header('Location: ?page=login'); exit; ?>
    <?php endif; ?>

    <div class="header">
        <div class="logo">
            <div class="logo-icon">P4</div>
            <span>PTPN4</span>
        </div>
        
        <div class="nav-menu">
            <a href="?page=dashboard" <?php echo $page === 'dashboard' ? 'class="active"' : ''; ?>>Monitoring</a>
            <a href="?page=input" <?php echo $page === 'input' ? 'class="active"' : ''; ?>>Pencatatan</a>
            <a href="?page=journal" <?php echo $page === 'journal' ? 'class="active"' : ''; ?>>Jurnal</a>
            <a href="?action=logout" style="background: rgba(255,255,255,0.2);">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if ($page === 'dashboard'): ?>
            <?php $data = getLatestMonitoringData($pdo); ?>
            
            <div class="dashboard-grid">
                <div class="gauge-container">
                    <h3>Uap Masuk</h3>
                    <div class="gauge <?php echo ($data['uap_masuk_pressure'] ?? 0) > 15 ? 'danger' : (($data['uap_masuk_pressure'] ?? 0) > 10 ? 'warning' : 'normal'); ?>">
                        <div class="gauge-value"><?php echo $data['uap_masuk_pressure'] ?? '0'; ?></div>
                    </div>
                    <div class="gauge-legend">
                        <div class="legend-item">
                            <span><div class="legend-color red"></div> 0 - 14,9 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color yellow"></div> 15 - 17,4 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color green"></div> 17,5 - 20 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color red"></div> 20,1 - 22 Bar</span>
                        </div>
                    </div>
                </div>

                <div class="gauge-container">
                    <h3>Uap Sisa</h3>
                    <div class="gauge <?php echo ($data['uap_sisa_pressure'] ?? 0) > 2.5 ? 'danger' : (($data['uap_sisa_pressure'] ?? 0) > 2 ? 'warning' : 'normal'); ?>">
                        <div class="gauge-value"><?php echo $data['uap_sisa_pressure'] ?? '0'; ?></div>
                    </div>
                    <div class="gauge-legend">
                        <div class="legend-item">
                            <span><div class="legend-color red"></div> 0 - 1,9 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color yellow"></div> 2 - 2,7 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color green"></div> 2.8 - 3 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color red"></div> 3,1 - 3,2 Bar</span>
                        </div>
                    </div>
                </div>

                <div class="gauge-container">
                    <h3>Tekanan Oil</h3>
                    <div class="gauge <?php echo ($data['tekanan_oil'] ?? 0) < 1 ? 'danger' : (($data['tekanan_oil'] ?? 0) < 1.5 ? 'warning' : 'normal'); ?>">
                        <div class="gauge-value"><?php echo $data['tekanan_oil'] ?? '0'; ?></div>
                    </div>
                    <div class="gauge-legend">
                        <div class="legend-item">
                            <span><div class="legend-color red"></div> < 1 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color yellow"></div> 1 - 1,5 Bar</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color green"></div> > 1,5 Bar</span>
                        </div>
                    </div>
                </div>

                <div class="gauge-container">
                    <h3>Temperature Cooling Water</h3>
                    <div class="gauge <?php echo ($data['cooling_water_temp'] ?? 0) > 65 ? 'danger' : (($data['cooling_water_temp'] ?? 0) > 60 ? 'warning' : 'normal'); ?>">
                        <div class="gauge-value"><?php echo $data['cooling_water_temp'] ?? '0'; ?></div>
                    </div>
                    <div class="gauge-legend">
                        <div class="legend-item">
                            <span><div class="legend-color green"></div> 0 - 60°C</span>
                        </div>
                        <div class="legend-item">
                            <span><div class="legend-color yellow"></div> > 60°C</span>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($page === 'journal'): ?>
            <h2 style="color: white; margin-bottom: 20px;">Jurnal Mesin Turbin</h2>
            
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Uap Masuk (P2)</th>
                            <th>Uap Masuk (T)</th>
                            <th>Uap Sisa (P2)</th>
                            <th>Uap Sisa (T)</th>
                            <th>Tekanan Oil</th>
                            <th>Lub Oil</th>
                            <th>Temp. Cooling Water (°C)</th>
                            <th>Keluar</th>
                            <th>Penunjukan di Panel Turbin</th>
                            <th>Voltage (V)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $allData = getAllMonitoringData($pdo);
                        foreach ($allData as $row): 
                        ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                            <td><?php echo date('H:i', strtotime($row['created_at'])); ?></td>
                            <td><?php echo $row['uap_masuk_pressure']; ?></td>
                            <td><?php echo $row['uap_masuk_temp']; ?></td>
                            <td><?php echo $row['uap_sisa_pressure']; ?></td>
                            <td><?php echo $row['uap_sisa_temp']; ?></td>
                            <td><?php echo $row['tekanan_oil']; ?></td>
                            <td><?php echo $row['lub_oil']; ?></td>
                            <td><?php echo $row['cooling_water_temp']; ?></td>
                            <td><?php echo $row['cooling_water_keluar']; ?></td>
                            <td>Normal</td>
                            <td><?php echo $row['voltage']; ?></td>
                            <td>-</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($page === 'input'): ?>
            <h2 style="color: white; margin-bottom: 20px;">Form Input Tabel Monitoring</h2>
            
            <div class="form-container">
                <form method="POST">
                    <input type="hidden" name="action" value="submit_monitoring">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Item</label>
                            <input type="text" value="Monitoring Data" readonly>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Uap Masuk</label>
                            <input type="number" name="uap_masuk_pressure" step="0.1" placeholder="Tekanan (kg/cm²)" required>
                        </div>
                        <div class="form-group">
                            <label>Temperatur Uap Masuk</label>
                            <input type="number" name="uap_masuk_temp" step="0.1" placeholder="Temperatur (°C)" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Uap Bekas (P2)</label>
                            <input type="number" name="uap_sisa_pressure" step="0.1" placeholder="Tekanan (kg/cm²)" required>
                        </div>
                        <div class="form-group">
                            <label>Temperatur Uap Sisa</label>
                            <input type="number" name="uap_sisa_temp" step="0.1" placeholder="Temperatur (°C)" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tekanan Oil</label>
                            <input type="number" name="tekanan_oil" step="0.1" placeholder="Lub (kg/cm²)" required>
                        </div>
                        <div class="form-group">
                            <label>Lub Oil</label>
                            <input type="number" name="lub_oil" step="0.1" placeholder="Value" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Temperatur Cooling Water (°C)</label>
                            <input type="number" name="cooling_water_temp" step="0.1" placeholder="Temperatur" required>
                        </div>
                        <div class="form-group">
                            <label>Keluar</label>
                            <input type="number" name="cooling_water_keluar" step="0.1" placeholder="Value" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Penunjukan di Panel Turbin</label>
                            <input type="number" name="voltage" step="0.1" placeholder="Voltage (V)" required>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <button type="submit" class="btn">Submit Data</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

</body>
</html>