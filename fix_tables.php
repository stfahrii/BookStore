<?php
$host = '127.0.0.1';
$port = 3306;
$dbname = 'bookstore';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "\n\n";

    if (!in_array('buku', $tables)) {
        $pdo->exec("CREATE TABLE `buku` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `kategori_id` BIGINT UNSIGNED NOT NULL,
            `judul` VARCHAR(200) NOT NULL,
            `pengarang` VARCHAR(100) NOT NULL,
            `penerbit` VARCHAR(100) NULL,
            `tahun_terbit` YEAR NULL,
            `harga` DECIMAL(12,2) NOT NULL DEFAULT 0,
            `stok` INT NOT NULL DEFAULT 0,
            `deskripsi` TEXT NULL,
            `foto_sampul` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: buku\n";
    } else { echo "buku: exists\n"; }

    if (!in_array('orders', $tables)) {
        $pdo->exec("CREATE TABLE `orders` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NOT NULL,
            `kode_order` VARCHAR(50) NOT NULL UNIQUE,
            `total_harga` DECIMAL(14,2) NOT NULL DEFAULT 0,
            `status` ENUM('menunggu','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
            `catatan` TEXT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: orders\n";
    } else { echo "orders: exists\n"; }

    if (!in_array('order_items', $tables)) {
        $pdo->exec("CREATE TABLE `order_items` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `order_id` BIGINT UNSIGNED NOT NULL,
            `buku_id` BIGINT UNSIGNED NOT NULL,
            `jumlah` INT NOT NULL DEFAULT 1,
            `harga_satuan` DECIMAL(12,2) NOT NULL DEFAULT 0,
            `subtotal` DECIMAL(14,2) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: order_items\n";
    } else { echo "order_items: exists\n"; }

    if (!in_array('peminjaman_transaksi', $tables)) {
        $pdo->exec("CREATE TABLE `peminjaman_transaksi` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NOT NULL,
            `kode_pinjam` VARCHAR(50) NOT NULL UNIQUE,
            `tanggal_pinjam` DATE NULL,
            `tanggal_kembali` DATE NULL,
            `status` ENUM('menunggu','disetujui','dikembalikan','ditolak') NOT NULL DEFAULT 'menunggu',
            `catatan` TEXT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: peminjaman_transaksi\n";
    } else { echo "peminjaman_transaksi: exists\n"; }

    if (!in_array('peminjaman_detail', $tables)) {
        $pdo->exec("CREATE TABLE `peminjaman_detail` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `transaksi_id` BIGINT UNSIGNED NOT NULL,
            `buku_id` BIGINT UNSIGNED NOT NULL,
            `jumlah` INT NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: peminjaman_detail\n";
    } else { echo "peminjaman_detail: exists\n"; }

    if (!in_array('messages', $tables)) {
        $pdo->exec("CREATE TABLE `messages` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NULL,
            `nama_pengirim` VARCHAR(100) NOT NULL,
            `email_pengirim` VARCHAR(150) NOT NULL,
            `subjek` VARCHAR(200) NOT NULL,
            `isi_pesan` TEXT NOT NULL,
            `dibaca` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: messages\n";
    } else { echo "messages: exists\n"; }

    if (!in_array('cart_items', $tables)) {
        $pdo->exec("CREATE TABLE `cart_items` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NOT NULL,
            `buku_id` BIGINT UNSIGNED NOT NULL,
            `jumlah` INT NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: cart_items\n";
    } else { echo "cart_items: exists\n"; }

    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    // Seed admin
    $adminCheck = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'admin@bookora.com'")->fetchColumn();
    if ($adminCheck == 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT INTO `users` (`nama`, `email`, `password`, `role`, `nis`, `kelas`, `telepon`, `status_akun`, `email_verified_at`, `created_at`, `updated_at`) VALUES (?, ?, ?, 'admin', 'ADMIN001', 'Umum', '08000000000', 'aktif', NOW(), NOW(), NOW())");
        $stmt->execute(['Administrator', 'admin@bookora.com', $hash]);
        echo "\nSeeded admin: admin@bookora.com / admin123\n";
    } else {
        echo "\nAdmin already exists\n";
    }

    // Seed kategori
    $ktrCount = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
    if ($ktrCount == 0) {
        $pdo->exec("INSERT INTO `kategori` (`nama_kategori`, `created_at`, `updated_at`) VALUES
            ('Fiksi', NOW(), NOW()),
            ('Non-Fiksi', NOW(), NOW()),
            ('Sains dan Teknologi', NOW(), NOW()),
            ('Sejarah', NOW(), NOW()),
            ('Pengembangan Diri', NOW(), NOW())");
        echo "Seeded: 5 kategori\n";
    }

    // Seed sample buku
    $bukuCount = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    if ($bukuCount == 0) {
        $kategoriId = $pdo->query("SELECT id FROM kategori LIMIT 1")->fetchColumn();
        $pdo->exec("INSERT INTO `buku` (`kategori_id`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `harga`, `stok`, `deskripsi`, `created_at`, `updated_at`) VALUES
            ($kategoriId, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 85000, 10, 'Novel inspiratif tentang perjuangan anak-anak Belitong.', NOW(), NOW()),
            ($kategoriId, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, 95000, 8, 'Kisah Minke di era kolonial Belanda.', NOW(), NOW()),
            ($kategoriId, 'Atomic Habits', 'James Clear', 'Gramedia', 2019, 110000, 15, 'Panduan membangun kebiasaan baik secara ilmiah.', NOW(), NOW())");
        echo "Seeded: 3 buku\n";
    }

    echo "\n=== ALL DONE ===\n";
    $finalTables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables now: " . implode(', ', $finalTables) . "\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
