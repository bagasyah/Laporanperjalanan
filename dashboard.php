<!DOCTYPE html>
<html>

<head>
    <title>Laporan Perjalanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Atur margin pada navbar */
        .navbar {
            background-color: #70cce1;
        }

        .navbar {
            margin-bottom: 20px;
        }

        /* Atur ukuran font pada tombol navbar */
        .navbar .btn {
            font-size: 14px;
        }

        /* Atur margin pada container */
        .container {
            margin-top: 20px;
        }

        /* Atur ukuran font pada judul halaman */
        h2 {
            font-size: 24px;
        }

        /* Atur ukuran font pada teks konten halaman */
        p {
            font-size: 16px;
        }

        /* Atur margin pada tombol edit dan delete */
        .table .btn {
            margin: 2px;
        }

        /* Atur lebar gambar pada tabel */
        .table td img {
            max-width: 100px;
            height: auto;
        }

        /* Atur margin pada form pencarian */

        /* Atur margin pada tombol search */

        .search-form input[type="text"] {
            width: 100%;
        }

        .search-form {
            margin-bottom: 20px;
        }

        /* Atur tata letak navbar pada perangkat mobile */
        @media (max-width: 576px) {
            .navbar-brand {
                margin-right: 0;
            }

            .navbar-nav.ml-auto.mt-1 {
                margin-top: 0;
            }

            .nav-link.btn.mb-1.text-light.btn-primary {
                margin-bottom: 0.5rem;
            }

            /* Atur lebar navbar collapse pada perangkat mobile */
            .navbar-collapse {
                width: 100%;
            }

            /* Atur tata letak tombol navbar pada perangkat mobile */
            .navbar-toggler {
                margin-top: 0.5rem;
            }

            /* Atur tata letak tombol pada perangkat mobile */
            .navbar-nav .btn {
                display: block;
                margin-bottom: 0.5rem;
                width: 100%;
            }
        }

        .navbar-brand .separator {
            border-right: 2px solid #fff;
            width: 100%;
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
            <ul class="navbar-nav ml-auto mr-3 mt-1">
                <?php
                session_start();
                if (!isset($_SESSION['user_id'])) {
                    header("Location: login.php");
                }

                $role = $_SESSION['role'];

                if ($role == 'user') {
                    echo "<li class='nav-item'><a class='nav-link btn mr-2 mb-1 text-light btn-primary' href='data_perjalanan.php'>Data Perjalanan</a></li>";
                    echo "<li class='nav-item'><a class='nav-link btn mr-2 mb-1 text-light btn-primary' href='create.php'>Buat Laporan</a></li>";
                } elseif ($role == 'admin') {
                    echo "<li class='nav-item'><a class='nav-link btn  mr-2 mb-1 text-light btn-primary' href='akun_user.php'>Akun users</a></li>";
                }

                echo "<li class='nav-item'><a class='nav-link btn btn-danger text-light' href='logout.php'>Logout</a></li>";
                ?>
            </ul>
        </div>
    </nav>

    <div class="container table-responsive">
        <?php
        include 'inc/db.php';

        $user_id = $_SESSION['user_id'];

        if ($role == 'user') {
            $user_query = "SELECT username FROM users WHERE id='$user_id'";
            $user_result = $conn->query($user_query);
            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                $username = $user_row['username'];

                $total_km_query = "SELECT SUM(km_akhir - km_awal) AS total_km FROM laporan WHERE user_id='$user_id'";
                $total_km_result = $conn->query($total_km_query);
                $total_km_row = $total_km_result->fetch_assoc();
                $total_km = $total_km_row['total_km'];

                echo "<h2>Profil User</h2>";
                echo "<p>Nama: $username</p>";
                echo "<p>Total Jarak Tempuh: $total_km KM</p>";
            }

            $query = "SELECT * FROM laporan WHERE user_id='$user_id'";
            $result = $conn->query($query);
            echo "<h2>Laporan Perjalanan</h2>";
            if ($result->num_rows > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Tanggal</th>";
                echo "<th>Alamat Awal</th>";
                echo "<th>Alamat Tujuan</th>";
                echo "<th>KM Awal</th>";
                echo "<th>KM Akhir</th>";
                echo "<th>Total KM</th>";
                echo "<th>No Polisi</th>";
                echo "<th>Tipe Mobil</th>";
                echo "<th>Foto KM Awal</th>";
                echo "<th>Foto KM Akhir</th>";
                //echo "<th>Status</th>";
                echo "<th>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['tanggal'] . "</td>";
                    echo "<td>" . $row['alamat_awal'] . "</td>";
                    echo "<td>" . $row['alamat_tujuan'] . "</td>";
                    echo "<td>" . $row['km_awal'] . "</td>";
                    echo "<td>" . $row['km_akhir'] . "</td>";
                    $total_km = $row['km_akhir'] - $row['km_awal']; // Menghitung total km
                    echo "<td>" . $total_km . "</td>";
                    echo "<td>" . $row['no_polisi'] . "</td>";
                    echo "<td>" . $row['tipe_mobil'] . "</td>";
                    echo "<td><img src='uploads/" . $row['foto'] . "' width='100' data-toggle='modal' data-target='#fotoModal' data-foto='uploads/" . $row['foto'] . "'></td>";
                    echo "<td><img src='uploads/" . $row['foto2'] . "' width='100' data-toggle='modal' data-target='#fotoModal' data-foto='uploads/" . $row['foto2'] . "'></td>";
                    //echo "<td>" . $row['status_lap'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a> ";
                    echo "<a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>";
                    echo "<a href='download.php?id=" . $row['id'] . "' class='btn btn-success'>download</a>";
                    echo "<a href='#' class='btn btn-info' data-toggle='modal' data-target='#detailModal" . $row['id'] . "'>Detail</a>";
                    echo "</td>";
                    echo "</tr>";
                    echo "<div class='modal fade' id='detailModal" . $row['id'] . "' tabindex='-1' role='dialog' aria-labelledby='detailModalLabel" . $row['id'] . "' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='detailModalLabel" . $row['id'] . "'>Detail Laporan</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
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
                    echo "<div class='modal-footer'>";
                    echo "<button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "Belum ada laporan perjalanan.";
            }
        } elseif ($role == 'admin') {
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
            echo "<form method='GET' action='dashboard.php'>";
            echo "<div class='input-group'>";
            echo "<input type='text' class='form-control' name='search' placeholder='Search' value='$search_query'>";
            echo "<div class='input-group-append'>";
            echo "<button class='btn btn-primary' type='submit'>Search</button>";
            echo "<button class='btn btn-danger ml-1' type='reset' onclick='window.location.href=\"dashboard.php\"'>Reset</button>";
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
                echo "<th>Foto KM Awal</th>";
                echo "<th>Foto KM Akhir</th>";
                //echo "<th>Status</th>";
                echo "<th>Action</th>";
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
                    echo "<td><img src='uploads/" . $row['foto'] . "' width='100' data-toggle='modal' data-target='#fotoModal' data-foto='uploads/" . $row['foto'] . "'></td>";
                    echo "<td><img src='uploads/" . $row['foto2'] . "' width='100' data-toggle='modal' data-target='#fotoModal' data-foto='uploads/" . $row['foto2'] . "'></td>";
                    echo "<td><a class='btn btn-success ml-1' href='download.php?id=" . $row['id'] . "'>Download</a></td>";
                    echo "<td>";
                    //echo "<form method='POST' action='edit_status.php'>";
                    //echo "<input type='hidden' name='laporan_id' value='" . $row['id'] . "'>";
                    //echo "<select name='status' onchange='this.form.submit()'>";
                    //echo "<option value='Pending'" . ($row['status_lap'] == 'Pending' ? 'selected' : '') . ">Pending</option>";
                    //echo "<option value='Approved'" . ($row['status_lap'] == 'Approved' ? 'selected' : '') . ">Approved</option>";
                    //echo "<option value='Rejected'" . ($row['status_lap'] == 'Rejected' ? 'selected' : '') . ">Rejected</option>";
                    echo "</select>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</tr>"; // Tutup baris data saat ini
                    echo "<tr>"; // Baris baru untuk menu detail
                    echo "<td colspan='11'>"; // Menggabungkan sel menjadi 1 kolom
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
        }

        $conn->close();
        ?>
    </div>

    <!-- Modal untuk tampilan foto -->
    <div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="modalFoto" src="" width="100%">
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Menampilkan foto pada modal saat foto ditekan
        $(document).ready(function () {
            $('#fotoModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var fotoPath = button.data('foto');
                var modal = $(this);
                modal.find('#modalFoto').attr('src', fotoPath);
            });
        });
    </script>
</body>

</html>