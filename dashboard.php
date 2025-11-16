<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Data user (dalam aplikasi nyata, ini biasanya dari database)
$user_data = [
    'username' => $_SESSION['username'],
    'nama_lengkap' => 'Dina el',
    'email' => 'de311@gmail.com',
    'no_telepon' => '+62 812-3456-7890',
    'alamat' => 'Jl. Pendidikan No. 123, Medan',
    'total_transaksi' => rand(15, 50)
];

// Commit 5: Array produk
$produk = [
    ['kode_barang' => 'P001', 'nama_barang' => 'Buku Tulis', 'harga_barang' => 5000],
    ['kode_barang' => 'P002', 'nama_barang' => 'Pensil', 'harga_barang' => 2000],
    ['kode_barang' => 'P003', 'nama_barang' => 'Penghapus', 'harga_barang' => 1000],
    ['kode_barang' => 'P004', 'nama_barang' => 'Penggaris', 'harga_barang' => 3000],
    ['kode_barang' => 'P005', 'nama_barang' => 'Spidol', 'harga_barang' => 7000]
];

// Commit 6: Array untuk penjualan random
$beli = [];
$grand_total = 0;
$jumlah_pembelian = rand(3, 8); // Random 3-8 item

for ($i = 0; $i < $jumlah_pembelian; $i++) {
    $produk_acak = $produk[array_rand($produk)];
    $jumlah = rand(1, 5);
    $total = $produk_acak['harga_barang'] * $jumlah;
    
    $beli[] = [
        'kode_barang' => $produk_acak['kode_barang'],
        'nama_barang' => $produk_acak['nama_barang'],
        'harga_barang' => $produk_acak['harga_barang'],
        'jumlah' => $jumlah,
        'total' => $total
    ];
    
    $grand_total += $total;
}

// Commit 9: Perhitungan diskon
if ($grand_total < 50000) {
    $diskon = 5;
} elseif ($grand_total >= 50000 && $grand_total <= 100000) {
    $diskon = 10;
} else {
    $diskon = 15;
}

$jumlah_diskon = ($grand_total * $diskon) / 100;
$total_bayar = $grand_total - $jumlah_diskon;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLGAN MART - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e6e6fa 0%, #d8bfd8 50%, #e6e6fa 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(147, 112, 219, 0.15);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .welcome {
            font-size: 1.3rem;
            color: #333;
            font-weight: 600;
        }

        .welcome i {
            color: #9370db;
            margin-right: 10px;
        }

        .header-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(147, 112, 219, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-btn {
            background: linear-gradient(135deg, #6a5acd, #9370db);
            color: white;
        }

        .logout-btn {
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(147, 112, 219, 0.5);
        }

        .profile-btn:hover {
            background: linear-gradient(135deg, #5d4cb1, #8367c7);
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #8367c7, #c8a8d8);
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(147, 112, 219, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            margin-bottom: 25px;
        }

        .profile-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(147, 112, 219, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            margin-bottom: 25px;
            display: none;
        }

        .store-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f8ff, #ffffff);
            border-radius: 15px;
            border: 2px solid #e6e6fa;
        }

        .store-icon {
            font-size: 3rem;
            color: #9370db;
            margin-bottom: 15px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5rem;
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .store-tagline {
            color: #666;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            background: white;
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(147, 112, 219, 0.1);
        }

        .tab-btn {
            padding: 12px 30px;
            background: none;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            color: white;
            box-shadow: 0 5px 15px rgba(147, 112, 219, 0.3);
        }

        .tab-btn:hover:not(.active) {
            background: #f8f8ff;
            color: #9370db;
        }

        /* Dashboard Sales Styles */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(147, 112, 219, 0.1);
            border: 1px solid #e6e6fa;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2rem;
            color: #9370db;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(147, 112, 219, 0.1);
            border: 1px solid #e6e6fa;
        }

        th {
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        th i {
            margin-right: 8px;
        }

        td {
            border-bottom: 1px solid #f0f0f0;
            padding: 15px;
            color: #555;
        }

        tr:hover {
            background-color: #f8f8ff;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .total-section {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            border-radius: 15px;
            color: white;
            box-shadow: 0 10px 25px rgba(147, 112, 219, 0.3);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .total-label {
            font-weight: 500;
        }

        .total-label i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .total-value {
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* Profile Section Styles */
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e6e6fa;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            color: white;
            box-shadow: 0 10px 25px rgba(147, 112, 219, 0.3);
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(147, 112, 219, 0.1);
            border: 1px solid #e6e6fa;
        }

        .info-group {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-group:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #9370db;
            margin-bottom: 5px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="welcome">
            <i class="fas fa-user-cog"></i> Selamat datang, <?php echo $_SESSION['username']; ?>!
        </div>
        <div class="header-buttons">
            <a href="#" class="btn profile-btn" onclick="showProfile()">
                <i class="fas fa-user"></i> Profil Saya
            </a>
            <a href="logout.php" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-btn active" onclick="showDashboard()">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </button>
        <button class="tab-btn" onclick="showProfile()">
            <i class="fas fa-user"></i> Profil User
        </button>
    </div>

    <!-- Dashboard Section -->
    <div class="dashboard-container" id="dashboardSection">
        <div class="store-header">
            <div class="store-icon">
                <i class="fas fa-store-alt"></i>
            </div>
            <h1>POLGAN MART</h1>
            <div class="store-tagline">
                <i class="fas fa-map-marker-alt"></i> Your Neighborhood Supermarket
            </div>
        </div>

        <!-- Commit 5-9: Dashboard Penjualan -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <div class="stat-value"><?php echo $jumlah_pembelian; ?></div>
                <div class="stat-label">Total Items</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="stat-value">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></div>
                <div class="stat-label">Total Belanja</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-value"><?php echo $diskon; ?>%</div>
                <div class="stat-label">Diskon</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-value">Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></div>
                <div class="stat-label">Total Bayar</div>
            </div>
        </div>

        <h2 style="text-align: center; color: #666; margin-bottom: 30px; font-weight: 500;">
            <i class="fas fa-list-alt"></i> Detail Transaksi Penjualan
        </h2>
        
        <!-- Commit 7: Tabel detail pembelian -->
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> No</th>
                    <th><i class="fas fa-barcode"></i> Kode</th>
                    <th><i class="fas fa-cube"></i> Nama Barang</th>
                    <th><i class="fas fa-tag"></i> Harga Satuan</th>
                    <th><i class="fas fa-boxes"></i> Qty</th>
                    <th><i class="fas fa-calculator"></i> Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($beli as $item): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo $item['kode_barang']; ?></strong></td>
                    <td><?php echo $item['nama_barang']; ?></td>
                    <td>Rp <?php echo number_format($item['harga_barang'], 0, ',', '.'); ?></td>
                    <td><?php echo $item['jumlah']; ?></td>
                    <td><strong>Rp <?php echo number_format($item['total'], 0, ',', '.'); ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Commit 8 & 9: Total belanja dan diskon -->
        <div class="total-section">
            <div class="total-row">
                <span class="total-label"><i class="fas fa-shopping-cart"></i> Total Belanja:</span>
                <span class="total-value">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span class="total-label"><i class="fas fa-gift"></i> Diskon (<?php echo $diskon; ?>%):</span>
                <span class="total-value">Rp <?php echo number_format($jumlah_diskon, 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span class="total-label"><i class="fas fa-credit-card"></i> Total Bayar:</span>
                <span class="total-value">Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="profile-container" id="profileSection">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1>Profil User</h1>
            <div class="store-tagline">
                Informasi lengkap akun Anda
            </div>
        </div>

        <div class="profile-info">
            <div class="info-card">
                <h3 style="color: #9370db; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-id-card"></i> Informasi Pribadi
                </h3>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-user"></i> Username</div>
                    <div class="info-value"><?php echo $user_data['username']; ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-signature"></i> Nama Lengkap</div>
                    <div class="info-value"><?php echo $user_data['nama_lengkap']; ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                    <div class="info-value"><?php echo $user_data['email']; ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-phone"></i> No. Telepon</div>
                    <div class="info-value"><?php echo $user_data['no_telepon']; ?></div>
                </div>
            </div>

            <div class="info-card">
                <h3 style="color: #9370db; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-map-marker-alt"></i> Alamat
                </h3>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-home"></i> Alamat Lengkap</div>
                    <div class="info-value"><?php echo $user_data['alamat']; ?></div>
                </div>
            </div>

            <div class="info-card">
                <h3 style="color: #9370db; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-bar"></i> Statistik
                </h3>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-shopping-cart"></i> Total Transaksi</div>
                    <div class="info-value"><?php echo $user_data['total_transaksi']; ?> transaksi</div>
                </div>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-calendar-alt"></i> Member Sejak</div>
                    <div class="info-value">Januari 2024</div>
                </div>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-star"></i> Status</div>
                    <div class="info-value">Aktif</div>
                </div>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value"><?php echo $user_data['total_transaksi']; ?></div>
                <div class="stat-label">Total Transaksi</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">6 bulan</div>
                <div class="stat-label">Lama Member</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-value">Gold</div>
                <div class="stat-label">Level Member</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-value">15%</div>
                <div class="stat-label">Diskon Khusus</div>
            </div>
        </div>
    </div>

    <script>
        function showDashboard() {
            document.getElementById('dashboardSection').style.display = 'block';
            document.getElementById('profileSection').style.display = 'none';
            
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn')[0].classList.add('active');
        }

        function showProfile() {
            document.getElementById('dashboardSection').style.display = 'none';
            document.getElementById('profileSection').style.display = 'block';
            
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn')[1].classList.add('active');
        }

        // Default show dashboard
        document.addEventListener('DOMContentLoaded', function() {
            showDashboard();
        });
    </script>
</body>
</html>