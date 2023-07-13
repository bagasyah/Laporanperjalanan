<!DOCTYPE html>
<html>

<head>
    <title>Akun Pengguna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Atur margin pada container */
        .navbar {
            background-color: #70cce1;
        }

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

        /* Responsif pada perangkat seluler */
        @media (max-width: 576px) {

            /* Atur ukuran font pada judul halaman untuk perangkat seluler */
            h2 {
                font-size: 20px;
            }

            /* Atur ukuran font pada teks konten halaman untuk perangkat seluler */
            p {
                font-size: 14px;
            }

            /* Atur margin pada navbar untuk perangkat seluler */
            .navbar-nav {
                margin: 0;
            }

            /* Atur margin pada tombol navigasi untuk perangkat seluler */
            .navbar-toggler {
                margin-right: 0;
            }

            /* Atur margin pada tombol navigasi di dalam navbar untuk perangkat seluler */
            .navbar-collapse {
                margin-top: 10px;
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
            <ul class="navbar-nav ml-auto mr-3">
                <?php
                session_start();
                if (!isset($_SESSION['user_id'])) {
                    header("Location: login.php");
                    exit();
                }

                $role = $_SESSION['role'];
                echo "<li class='nav-item'><a class='nav-link btn text-light mr-2 mb-1 btn-primary' href='dashboard.php'>Back</a></li>";
                echo "<li class='nav-item'><a class='nav-link btn btn-danger mr-2 mb-1 text-light' href='logout.php'>Logout</a></li>";
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="table-responsive">
            <?php
            include 'inc/db.php';

            if ($role == 'admin') {
                if (isset($_GET['delete'])) {
                    $delete_id = $_GET['delete'];

                    // Hapus laporan terlebih dahulu
                    $delete_laporan_query = "DELETE FROM laporan WHERE user_id='$delete_id'";
                    if ($conn->query($delete_laporan_query) === TRUE) {
                        // Setelah laporan dihapus, hapus akun pengguna
                        $delete_query = "DELETE FROM users WHERE id='$delete_id'";
                        if ($conn->query($delete_query) === TRUE) {
                            echo "<div class='alert alert-success'>Akun pengguna berhasil dihapus.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Gagal menghapus akun pengguna.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Gagal menghapus laporan terkait.</div>";
                    }
                }

                $query = "SELECT users.id, users.username, users.role, users.status, SUM(laporan.km_akhir - laporan.km_awal) AS total_km 
              FROM users LEFT JOIN laporan ON users.id = laporan.user_id 
              GROUP BY users.id, users.username, users.role, users.status";


                // Mengatur kata kunci pencarian jika ada
                if (isset($_GET['search'])) {
                    $search = $_GET['search'];
                    $query .= " HAVING users.username LIKE '%$search%'";
                }

                $result = $conn->query($query);

                echo "<h2 class='mt-3'>Akun Pengguna</h2>";

                // Tampilkan form pencarian
                echo "<form class='mb-3' method='GET'>";
                echo "<div class='input-group'>";
                echo "<input type='text' class='form-control' name='search' placeholder='Cari akun pengguna...'>";
                echo "<div class='input-group-append'>";
                echo "<button class='btn btn-primary' type='submit'>Cari</button>";
                echo "<button class='btn btn-danger ml-1' type='reset' onclick='window.location.href=\"akun_user.php\"'>Reset</button>";
                echo "</div>";
                echo "</div>";
                echo "</form>";

                if ($result->num_rows > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Username</th>";
                    echo "<th>Role</th>";
                    echo "<th>Status</th>";
                    echo "<th>Total KM</th>";
                    echo "<th>Actions</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['role'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['total_km'] . "</td>";
                        echo "<td>";
                        echo "<a href='edit_akun.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a> ";
                        echo "<a href='akun_user.php?delete=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus akun pengguna ini?\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "Belum ada akun pengguna terdaftar.";
                }
            }

            $conn->close();
            ?>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>