<?php
include 'inc/db.php';

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

$query = "SELECT * FROM laporan INNER JOIN users ON laporan.user_id = users.id";

if (!empty($search_query)) {
    $query .= " WHERE tanggal LIKE '%$search_query%' OR alamat_awal LIKE '%$search_query%' OR alamat_tujuan LIKE '%$search_query%' OR username LIKE '%$search_query%'";
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Nama file
    $filename = "laporan_perjalanan_" . date('Ymd') . ".csv";

    // Set header untuk file Excel
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Buka file output untuk ditulis
    $output = fopen("php://output", "w");

    // Tulis baris header
    fputcsv($output, array('User', 'Tanggal', 'Alamat Awal', 'Alamat Tujuan', 'KM Awal', 'KM Akhir', 'Total KM', 'No. Polisi', 'Tipe Mobil', 'Jenis Perjalanan', 'Perkiraan BBM', 'Foto', 'Foto 2', 'Lampu Depan', 'Lampu Sen Depan', 'Lampu Sen Belakang', 'Lampu Rem', 'Lampu Mundur', 'Bodi', 'Ban', 'Pedal', 'Kopling', 'Gas Rem', 'Klakson', 'Weaper', 'Air Weaper', 'Air Radiator', 'Oli Mesin', 'Note'));

    // Fungsi untuk menghitung perkiraan BBM
    function calculateBBM($jenis_perjalanan, $tipe_mobil, $total_km)
    {
        $bbm_per_km = 0;

        if ($jenis_perjalanan == 'luar') {
            if ($tipe_mobil == 'innova') {
                $bbm_per_km = 1 / 8;
            } elseif ($tipe_mobil == 'avanza veloz') {
                $bbm_per_km = 1 / 10;
            } elseif ($tipe_mobil == 'triton') {
                $bbm_per_km = 1 / 12;
            } elseif ($tipe_mobil == 'avanza putih') {
                $bbm_per_km = 1 / 12;
            }
        } elseif ($jenis_perjalanan == 'dalam') {
            if ($tipe_mobil == 'innova') {
                $bbm_per_km = 1 / 10;
            } elseif ($tipe_mobil == 'avanza veloz') {
                $bbm_per_km = 1 / 12;
            } elseif ($tipe_mobil == 'triton') {
                $bbm_per_km = 1 / 10;
            } elseif ($tipe_mobil == 'avanza putih') {
                $bbm_per_km = 1 / 13;
            }
        }

        $perkiraan_bbm = round($total_km * $bbm_per_km); // Bulatkan hasil jika koma
        return $perkiraan_bbm;
    }

    // Tulis data dari database
    while ($row = $result->fetch_assoc()) {
        $total_km = $row['km_akhir'] - $row['km_awal'];
        $perkiraan_bbm = calculateBBM($row['jenis_perjalanan'], $row['tipe_mobil'], $total_km); // Memanggil fungsi calculateBBM()
        fputcsv(
            $output,
            array(
                $row['username'],
                $row['tanggal'],
                $row['alamat_awal'],
                $row['alamat_tujuan'],
                $row['km_awal'],
                $row['km_akhir'],
                $total_km,
                $row['no_polisi'],
                $row['tipe_mobil'],
                $row['jenis_perjalanan'],
                $perkiraan_bbm,
                $row['foto'],
                $row['foto2'],
                $row['lampu_depan'],
                $row['lampu_sen_depan'],
                $row['lampu_sen_belakang'],
                $row['lampu_rem'],
                $row['lampu_mundur'],
                $row['bodi'],
                $row['ban'],
                $row['pedal'],
                $row['kopling'],
                $row['gas_rem'],
                $row['klakson'],
                $row['weaper'],
                $row['air_weaper'],
                $row['air_radiator'],
                $row['oli_mesin'],
                $row['note'],
            )
        );
    }

    // Tutup file output
    fclose($output);
    exit;
} else {
    echo "<p>Tidak ada data yang dapat diunduh.</p>";
}
?>