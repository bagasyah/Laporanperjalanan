<!DOCTYPE html>
<html>

<head>
    <title>Edit Status Laporan</title>
    <!-- Tambahkan CSS atau pengaturan tampilan lainnya -->
</head>

<body>
    <h2>Edit Status Laporan</h2>

    <?php
    session_start();

    // Periksa apakah pengguna sudah login dan memiliki peran admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

    include 'inc/db.php';

    $laporan_data = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Periksa apakah id laporan dikirim melalui metode POST
        if (isset($_POST['laporan_id']) && isset($_POST['status'])) {
            $laporan_id = $_POST['laporan_id'];
            $status = $_POST['status'];

            // Lakukan operasi update status di database sesuai dengan id laporan
            $update_query = "UPDATE laporan SET status_lap='$status' WHERE id='$laporan_id'";
            $conn->query($update_query);

            // Setelah proses update selesai, Anda dapat mengarahkan pengguna kembali ke halaman dashboard
            header("Location: dashboard.php");
            exit();
        }
    } else {
        // Periksa apakah id laporan dikirim melalui metode GET
        if (isset($_GET['id'])) {
            $laporan_id = $_GET['id'];

            // Lakukan operasi pengambilan data laporan dari database berdasarkan id laporan
            $select_query = "SELECT * FROM laporan WHERE id='$laporan_id'";
            $result = $conn->query($select_query);

            if ($result->num_rows > 0) {
                $laporan_data = $result->fetch_assoc();
            } else {
                // Jika data laporan tidak ditemukan, maka arahkan pengguna kembali ke halaman dashboard
                header("Location: dashboard.php");
                exit();
            }
        } else {
            // Jika id laporan tidak ditemukan, maka arahkan pengguna kembali ke halaman dashboard
            header("Location: dashboard.php");
            exit();
        }
    }

    $conn->close();
    ?>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="laporan_id"
            value="<?php echo isset($laporan_data['id']) ? $laporan_data['id'] : ''; ?>">
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="PENDING" <?php if (isset($laporan_data['status_lap']) && $laporan_data['status_lap'] == 'PENDING')
                echo 'selected'; ?>>PENDING</option>
            <option value="APPROVED" <?php if (isset($laporan_data['status_lap']) && $laporan_data['status_lap'] == 'APPROVED')
                echo 'selected'; ?>>APPROVED</option>
            <option value="REJECTED" <?php if (isset($laporan_data['status_lap']) && $laporan_data['status_lap'] == 'REJECTED')
                echo 'selected'; ?>>REJECTED</option>
        </select>
        <button type="submit">Simpan</button>
    </form>
</body>

</html>