<!DOCTYPE html>
<html>

<head>
    <title>Laporan Perjalanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .navbar {
            background-color: #70cce1;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            width: 100%;
        }

        .navbar-brand .separator {
            border-right: 2px solid #fff;
            width: 100%;
        }

        .dropdown-toggle:focus {
            box-shadow: none;
        }

        table {}

        .details-content {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        @media (max-width: 576px) {
            .navbar-brand {
                margin-right: 0;
            }

            .navbar-nav.ml-auto.mt-1 {
                margin-top: 0;
            }

            .nav-link.btn.mb-1.text-light.btn-success {
                margin-bottom: 0.5rem;
            }

            .table-responsive-sm {
                overflow-x: auto;
            }

            table {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="dashboard.php">
            <img src="assets/pgn.png" width="130" height="30" class="mr-2">
            <span class="separator"></span>
            LAPORAN PERJALANAN
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
            </ul>
            <ul class="navbar-nav ml-auto mt-1">
                <?php
                echo "<li class='nav-item'><a class='nav-link btn mb-1 text-light btn-success' href='login.php'>Login</a></li>";
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <?php
        include 'inc/db.php';
        // Periksa koneksi
        if ($conn->connect_error) {
            die("Koneksi ke database gagal: " . $conn->connect_error);
        }

        // Kode query dan tampilan HTML
        $search_query = "";
        if (isset($_GET['search'])) {
            $search_query = $_GET['search'];
        }

        $query = "SELECT * FROM laporan INNER JOIN users ON laporan.user_id = users.id";

        if (!empty($search_query)) {
            $query .= " WHERE tanggal LIKE '%$search_query%' OR alamat_awal LIKE '%$search_query%' OR alamat_tujuan LIKE '%$search_query%' OR username LIKE '%$search_query%'";
        }

        $result = $conn->query($query);

        echo "<h2 class='mt-3'>Semua Laporan Perjalanan</h2>";

        echo "<div class='search-form'>";
        echo "<form method='GET' action='index.php'>";
        echo "<div class='input-group'>";
        echo "<input type='text' class='form-control' name='search' placeholder='Search' value='$search_query'>";
        echo "<div class='input-group-append'>";
        echo "<button class='btn btn-primary' type='submit'>Search</button>";
        echo "<button class='btn btn-danger ml-1' type='reset' onclick='window.location.href=\"index.php\"'>Reset</button>";
        echo "<a class='btn btn-success ml-1' href='download.php?search=$search_query'>Download PDF</a>";
        echo "</div>";
        echo "</div>";
        echo "</form>";
        echo "</div>";

        if ($result->num_rows > 0) {
            echo "<div class='table-responsive table-responsive-sm'>";
            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>User</th>";
            echo "<th>Tanggal</th>";
            echo "<th>Alamat Awal</th>";
            echo "<th>Alamat Tujuan</th>";
            echo "<th>KM Awal</th>";
            echo "<th>KM Akhir</th>";
            echo "<th>Total KM</th>";
            echo "<th>No Polisi</th>";
            echo "<th>Tipe Mobil</th>";
            //echo "<th>Status</th>";
            echo "<th>Foto KM Awal</th>";
            echo "<th>Foto KM Akhir</th>";
            echo "<th></th>"; // Kolom tambahan untuk tombol detail
            echo "<th>Actions</th>"; // Kolom tambahan untuk tombol download
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['tanggal'] . "</td>";
                echo "<td>" . $row['alamat_awal'] . "</td>";
                echo "<td>" . $row['alamat_tujuan'] . "</td>";
                echo "<td>" . $row['km_awal'] . "</td>";
                echo "<td>" . $row['km_akhir'] . "</td>";
                $total_km = $row['km_akhir'] - $row['km_awal']; // Menghitung total km
                echo "<td>" . $total_km . "</td>";
                echo "<td>" . $row['no_polisi'] . "</td>";
                echo "<td>" . $row['tipe_mobil'] . "</td>";
                //echo "<td>" . $row['status_lap'] . "</td>";
                echo "<td><a href='#' data-toggle='modal' data-target='#fotoModal' data-foto='uploads/" . $row['foto'] . "'><img src='uploads/" . $row['foto'] . "' width='100'></a></td>";
                echo "<td><a href='#' data-toggle='modal' data-target='#fotoModal' data-foto='uploads/" . $row['foto2'] . "'><img src='uploads/" . $row['foto2'] . "' width='100'></a></td>";
                echo "<td></td>"; // Kolom tambahan untuk tombol detail
                echo "<td><a class='btn btn-success ml-1' href='download.php?id=" . $row['id'] . "'>Download</a></td>";
                echo "</tr>"; // Tutup baris data saat ini
                echo "<tr>"; // Baris baru untuk menu detail
                echo "<td colspan='12'>"; // Menggabungkan sel menjadi 1 kolom
                echo "<details>";
                echo "<summary><i class='fas fa-search'></i> Detail</summary>";
                echo "<div class='details-content'>";
                echo "<p>Lampu Depan: " . $row['lampu_depan'] . "</p>";
                echo "<p>Lampu Sen Depan: " . $row['lampu_sen_depan'] . "</p>";
                echo "<p>Lampu Sen Belakang: " . $row['lampu_sen_belakang'] . "</p>";
                echo "<p>Lampu Rem: " . $row['lampu_rem'] . "</p>";
                echo "<p>Lampu Mundur: " . $row['lampu_mundur'] . "</p>";
                echo "<p>Bodi: " . $row['bodi'] . "</p>";
                echo "<p>Ban: " . $row['ban'] . "</p>";
                echo "<p>Pedal Gas: " . $row['pedal'] . "</p>";
                echo "<p>Pedal Kopling: " . $row['kopling'] . "</p>";
                echo "<p>Pedal Rem: " . $row['gas_rem'] . "</p>";
                echo "<p>Klakson: " . $row['klakson'] . "</p>";
                echo "<p>Weaper: " . $row['weaper'] . "</p>";
                echo "<p>Air Weaper: " . $row['air_weaper'] . "</p>";
                echo "<p>Air Radiator: " . $row['air_radiator'] . "</p>";
                echo "<p>Oli Mesin: " . $row['oli_mesin'] . "</p>";
                echo "<p>Note: " . $row['note'] . "</p>";
                echo "</div>";
                echo "</details>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>Belum ada laporan perjalanan.</p>";
        }
        ?>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" class="img-fluid" id="modalFoto">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#fotoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var foto = button.data('foto');
            var modal = $(this);
            modal.find('.modal-body #modalFoto').attr('src', foto);
        });
    </script>
</body>

</html>