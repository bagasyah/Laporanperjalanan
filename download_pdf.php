<?php
require('inc/db.php');
require('fpdf.php');

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

if (isset($_GET['id'])) {
    // Mendapatkan data laporan berdasarkan ID
    $id = $_GET['id'];
    $query = "SELECT * FROM laporan INNER JOIN users ON laporan.user_id = users.id WHERE laporan.id = $id";
} else {
    // Mendapatkan semua data laporan
    $query = "SELECT * FROM laporan INNER JOIN users ON laporan.user_id = users.id";

    if (!empty($search_query)) {
        $query .= " WHERE tanggal LIKE '%$search_query%' OR alamat_awal LIKE '%$search_query%' OR alamat_tujuan LIKE '%$search_query%' OR username LIKE '%$search_query%'";
    }
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    if (isset($_GET['id'])) {
        // Mendownload satu dokumen laporan
        $row = $result->fetch_assoc();

        // Nama file
        $filename = "laporan_perjalanan_" . $row['id'] . ".pdf";

        // Buat objek PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Tulis data dari database
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'Laporan Perjalanan');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(30, 10, 'Nama: ' . $row['username']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Tanggal: ' . $row['tanggal']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Alamat Awal: ' . $row['alamat_awal']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Alamat Tujuan: ' . $row['alamat_tujuan']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'KM Awal: ' . $row['km_awal']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'KM Akhir: ' . $row['km_akhir']);
        $pdf->Ln();
        $total_km = $row['km_akhir'] - $row['km_awal'];
        $pdf->Cell(30, 10, 'Total KM: ' . $total_km);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'No Polisi: ' . $row['no_polisi']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Tipe Mobil: ' . $row['tipe_mobil']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Lampu Depan: ' . $row['lampu_depan']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Lampu Sen Depan: ' . $row['lampu_sen_depan']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Lampu Sen Belakang: ' . $row['lampu_sen_belakang']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Lampu Rem: ' . $row['lampu_rem']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Lampu Mundur: ' . $row['lampu_mundur']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Bodi: ' . $row['bodi']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Ban: ' . $row['ban']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Pedal: ' . $row['pedal']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Kopling: ' . $row['kopling']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Gas/Rem: ' . $row['gas_rem']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Oli Mesin: ' . $row['oli_mesin']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Klakson: ' . $row['klakson']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Weaper: ' . $row['weaper']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Air Weaper: ' . $row['air_weaper']);
        $pdf->Ln();
        $pdf->Cell(30, 10, 'Air Radiator: ' . $row['air_radiator']);
        $pdf->Ln();
        $pdf->Ln();

        // Tampilkan gambar KM Awal
        $imageFile1 = 'uploads/' . $row['foto'];
        if (file_exists($imageFile1)) {
            $pdf->Cell(40, 10, 'Foto KM Awal:');
            $pdf->Ln();
            $pdf->Image($imageFile1, null, null, 60);
            $pdf->Ln();
        }

        // Tampilkan gambar KM Akhir
        $imageFile2 = 'uploads/' . $row['foto2'];
        if (file_exists($imageFile2)) {
            $pdf->Cell(40, 10, 'Foto KM Akhir:');
            $pdf->Ln();
            $pdf->Image($imageFile2, null, null, 60);
            $pdf->Ln();
        }

        // Output PDF
        $pdf->Output('D', $filename); // Menampilkan dialog download

        exit;
    } else {
        // Mendownload semua dokumen laporan dalam satu PDF
        // Nama file
        $filename = "laporan_perjalanan_" . date('Ymd') . ".pdf";

        // Buat objek PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Tulis data dari database
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            if ($counter % 2 == 0 && $counter != 0) {
                $pdf->AddPage();
            }

            // Tulis header
            if ($counter % 2 == 0) {
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(30, 10, 'Laporan Perjalanan');
                $pdf->Ln();
            }

            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(30, 10, 'Nama: ' . $row['username']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Tanggal: ' . $row['tanggal']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Alamat Awal: ' . $row['alamat_awal']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Alamat Tujuan: ' . $row['alamat_tujuan']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'KM Awal: ' . $row['km_awal']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'KM Akhir: ' . $row['km_akhir']);
            $pdf->Ln();
            $total_km = $row['km_akhir'] - $row['km_awal'];
            $pdf->Cell(30, 10, 'Total KM: ' . $total_km);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'No Polisi: ' . $row['no_polisi']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Tipe Mobil: ' . $row['tipe_mobil']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Lampu Depan: ' . $row['lampu_depan']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Lampu Sen Depan: ' . $row['lampu_sen_depan']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Lampu Sen Belakang: ' . $row['lampu_sen_belakang']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Lampu Rem: ' . $row['lampu_rem']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Lampu Mundur: ' . $row['lampu_mundur']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Bodi: ' . $row['bodi']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Ban: ' . $row['ban']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Pedal: ' . $row['pedal']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Kopling: ' . $row['kopling']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Gas/Rem: ' . $row['gas_rem']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Oli Mesin: ' . $row['oli_mesin']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Klakson: ' . $row['klakson']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Weaper: ' . $row['weaper']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Air Weaper: ' . $row['air_weaper']);
            $pdf->Ln();
            $pdf->Cell(30, 10, 'Air Radiator: ' . $row['air_radiator']);
            $pdf->Ln();
            $pdf->Ln();

            // Tampilkan gambar KM Awal
            $imageFile1 = 'uploads/' . $row['foto'];
            if (file_exists($imageFile1)) {
                $pdf->Cell(40, 10, 'Foto KM Awal:');
                $pdf->Ln();
                $pdf->Image($imageFile1, null, null, 60);
                $pdf->Ln();
            }

            // Tampilkan gambar KM Akhir
            $imageFile2 = 'uploads/' . $row['foto2'];
            if (file_exists($imageFile2)) {
                $pdf->Cell(40, 10, 'Foto KM Akhir:');
                $pdf->Ln();
                $pdf->Image($imageFile2, null, null, 60);
                $pdf->Ln();
            }
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $counter++;
        }

        // Output PDF
        $pdf->Output('D', $filename); // Menampilkan dialog download

        exit;
    }
} else {
    echo "<p>Tidak ada data yang dapat diunduh.</p>";
}
?>