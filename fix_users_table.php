<?php
// Fix users table: add missing custom columns to bookstore database

$host = '127.0.0.1';
$port = 3306;
$dbname = 'bookstore';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check existing columns
    $cols = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
    echo "Existing columns: " . implode(', ', $cols) . "\n\n";

    $alterStatements = [];

    // Rename 'name' to 'nama' or add 'nama' if doesn't exist
    if (in_array('name', $cols) && !in_array('nama', $cols)) {
        $alterStatements[] = "CHANGE COLUMN `name` `nama` VARCHAR(100) NOT NULL DEFAULT ''";
        echo "Will rename 'name' -> 'nama'\n";
    } elseif (!in_array('nama', $cols)) {
        $alterStatements[] = "ADD COLUMN `nama` VARCHAR(100) NOT NULL DEFAULT '' AFTER `id`";
        echo "Will add 'nama'\n";
    } else {
        echo "'nama' already exists\n";
    }

    if (!in_array('nis', $cols)) {
        $alterStatements[] = "ADD COLUMN `nis` VARCHAR(30) NULL UNIQUE AFTER `nama`";
        echo "Will add 'nis'\n";
    } else {
        echo "'nis' already exists\n";
    }

    if (!in_array('telepon', $cols)) {
        $alterStatements[] = "ADD COLUMN `telepon` VARCHAR(20) NULL AFTER `email`";
        echo "Will add 'telepon'\n";
    } else {
        echo "'telepon' already exists\n";
    }

    if (!in_array('kelas', $cols)) {
        $alterStatements[] = "ADD COLUMN `kelas` VARCHAR(50) NULL DEFAULT 'Umum' AFTER `telepon`";
        echo "Will add 'kelas'\n";
    } else {
        echo "'kelas' already exists\n";
    }

    if (!in_array('role', $cols)) {
        $alterStatements[] = "ADD COLUMN `role` ENUM('admin','anggota') NOT NULL DEFAULT 'anggota' AFTER `kelas`";
        echo "Will add 'role'\n";
    } else {
        echo "'role' already exists\n";
    }

    if (!in_array('status_akun', $cols)) {
        $alterStatements[] = "ADD COLUMN `status_akun` ENUM('aktif','terkunci') NOT NULL DEFAULT 'aktif' AFTER `role`";
        echo "Will add 'status_akun'\n";
    } else {
        echo "'status_akun' already exists\n";
    }

    if (!empty($alterStatements)) {
        $sql = "ALTER TABLE `users` " . implode(", \n    ", $alterStatements);
        echo "\nExecuting:\n$sql\n\n";
        $pdo->exec($sql);
        echo "SUCCESS: users table altered!\n";
    } else {
        echo "\nAll columns already exist, no changes needed.\n";
    }

    // Show final structure
    $finalCols = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_ASSOC);
    echo "\nFinal table structure:\n";
    foreach ($finalCols as $col) {
        echo "  - {$col['Field']} ({$col['Type']}) " . ($col['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }

    // Also create missing tables if they don't exist
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "\nExisting tables: " . implode(', ', $tables) . "\n";

    if (!in_array('kategori', $tables)) {
        $pdo->exec("CREATE TABLE `kategori` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `nama_kategori` VARCHAR(100) NOT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: kategori\n";
    }

    if (!in_array('buku', $tables)) {
        $pdo->exec("CREATE TABLE `buku` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `kategori_id` INT UNSIGNED NOT NULL,
            `judul` VARCHAR(200) NOT NULL,
            `pengarang` VARCHAR(100) NOT NULL,
            `penerbit` VARCHAR(100) NULL,
            `tahun_terbit` YEAR NULL,
            `harga` DECIMAL(12,2) NOT NULL DEFAULT 0,
            `stok` INT NOT NULL DEFAULT 0,
            `deskripsi` TEXT NULL,
            `foto_sampul` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            FOREIGN KEY (`kategori_id`) REFERENCES `kategori`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: buku\n";
    }

    if (!in_array('orders', $tables)) {
        $pdo->exec("CREATE TABLE `orders` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `kode_order` VARCHAR(50) NOT NULL UNIQUE,
            `total_harga` DECIMAL(14,2) NOT NULL DEFAULT 0,
            `status` ENUM('menunggu','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
            `catatan` TEXT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: orders\n";
    }

    if (!in_array('order_items', $tables)) {
        $pdo->exec("CREATE TABLE `order_items` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `order_id` INT UNSIGNED NOT NULL,
            `buku_id` INT UNSIGNED NOT NULL,
            `jumlah` INT NOT NULL DEFAULT 1,
            `harga_satuan` DECIMAL(12,2) NOT NULL DEFAULT 0,
            `subtotal` DECIMAL(14,2) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
            FOREIGN KEY (`buku_id`) REFERENCES `buku`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: order_items\n";
    }

    if (!in_array('peminjaman_transaksi', $tables)) {
        $pdo->exec("CREATE TABLE `peminjaman_transaksi` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `kode_pinjam` VARCHAR(50) NOT NULL UNIQUE,
            `tanggal_pinjam` DATE NULL,
            `tanggal_kembali` DATE NULL,
            `status` ENUM('menunggu','disetujui','dikembalikan','ditolak') NOT NULL DEFAULT 'menunggu',
            `catatan` TEXT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: peminjaman_transaksi\n";
    }

    if (!in_array('peminjaman_detail', $tables)) {
        $pdo->exec("CREATE TABLE `peminjaman_detail` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `transaksi_id` INT UNSIGNED NOT NULL,
            `buku_id` INT UNSIGNED NOT NULL,
            `jumlah` INT NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            FOREIGN KEY (`transaksi_id`) REFERENCES `peminjaman_transaksi`(`id`),
            FOREIGN KEY (`buku_id`) REFERENCES `buku`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: peminjaman_detail\n";
    }

    if (!in_array('messages', $tables)) {
        $pdo->exec("CREATE TABLE `messages` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NULL,
            `nama_pengirim` VARCHAR(100) NOT NULL,
            `email_pengirim` VARCHAR(150) NOT NULL,
            `subjek` VARCHAR(200) NOT NULL,
            `isi_pesan` TEXT NOT NULL,
            `dibaca` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: messages\n";
    }

    if (!in_array('cart_items', $tables)) {
        $pdo->exec("CREATE TABLE `cart_items` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `buku_id` INT UNSIGNED NOT NULL,
            `jumlah` INT NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
            FOREIGN KEY (`buku_id`) REFERENCES `buku`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "Created: cart_items\n";
    }

    // Seed admin user if not exists
    $adminCheck = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'admin@bookora.com'")->fetchColumn();
    if ($adminCheck == 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
        $pdo->exec("INSERT INTO `users` (`nama`, `email`, `password`, `role`, `nis`, `kelas`, `telepon`, `status_akun`, `email_verified_at`, `created_at`, `updated_at`)
            VALUES ('Administrator', 'admin@bookora.com', '$hash', 'admin', 'ADMIN001', 'Umum', '08000000000', 'aktif', NOW(), NOW(), NOW())");
        echo "\nSeeded: admin@bookora.com / admin123\n";
    }

    // Seed sample kategori
    $ktrCount = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
    if ($ktrCount == 0) {
        $pdo->exec("INSERT INTO `kategori` (`nama_kategori`, `created_at`, `updated_at`) VALUES
            ('Fiksi', NOW(), NOW()),
            ('Non-Fiksi', NOW(), NOW()),
            ('Sains & Teknologi', NOW(), NOW()),
            ('Sejarah', NOW(), NOW()),
            ('Pengembangan Diri', NOW(), NOW())");
        echo "Seeded: 5 kategori\n";
    }

    echo "\n=== ALL DONE SUCCESSFULLY ===\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
