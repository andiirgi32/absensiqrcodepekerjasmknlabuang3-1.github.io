<?php
include "koneksi.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location:../login.php");
} else if (!isset($_SESSION['kodeakses'])) {
    header("Location:../login_akses.php");
}

// Check for theme in session or cookies
if (isset($_SESSION['theme'])) {
    $theme = $_SESSION['theme'];
} elseif (isset($_COOKIE['theme'])) {
    $theme = $_COOKIE['theme'];
    $_SESSION['theme'] = $theme;
} else {
    $theme = 'default';
}
?>

<?php
if ($_SESSION['role'] != "Admin" && $_SESSION['role'] != "Kepala Sekolah") {
    header("Location:index.php");
    exit();
}

date_default_timezone_set('Asia/Makassar');
$TanggalHariIni = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="js/color-modes.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="logo/smkn_labuang.png" rel="icon">
    <title>Ruang<?= $_SESSION['role'] ?> - Table Jabatan</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->
    <!-- <link rel="stylesheet" href="css/iconbootstrap.css"> -->
    <link rel="stylesheet" href="css/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/sweetalert.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/sweetalert.min.js"></script>

    <?php if ($theme == 'default') : ?>
        <link rel="stylesheet" href="theme/default.css">
    <?php elseif ($theme == 'theme1') : ?>
        <link rel="stylesheet" href="theme/theme1.css">
    <?php elseif ($theme == 'theme2') : ?>
        <link rel="stylesheet" href="theme/theme2.css">
    <?php elseif ($theme == 'theme3') : ?>
        <link rel="stylesheet" href="theme/theme3.css">
    <?php elseif ($theme == 'theme4') : ?>
        <link rel="stylesheet" href="theme/theme4.css">
    <?php elseif ($theme == 'theme5') : ?>
        <link rel="stylesheet" href="theme/theme5.css">
    <?php endif; ?>

    <style>
        .berita-user-profile {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
        }

        .berita-user-profile-utama {
            width: 125px;
            height: 125px;
            border-radius: 50%;
            object-fit: cover;
            /* position: relative;
            left: 50%;
            transform: translateX(-50%); */
        }

        .berita-user-profile-nav {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .space-between-body {
            display: flex;
            justify-content: space-between;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar-fixed {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1;
        }

        .container-margin {
            margin-top: 100px;
        }

        .space-between-card {
            display: flex;
            justify-content: space-between;
            flex-direction: column;
            min-height: 100%;
        }

        .container-grid {
            display: grid;
            grid-template-areas: 'tentang-akun' 'semua-post';
        }

        .tentang-akun {
            grid-area: tentang-akun;
        }

        .semua-post {
            grid-area: semua-post;
        }

        /* =============================================================== */
        a {
            text-decoration: none;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="vendor/jquery/jquery.min.js"></script>

</head>

<body id="page-top">

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
        </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 star-0 mb-3 ml-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>

    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light bg-body-tertiary accordion" id="accordionSidebar">
            <a class="sidebar-brand bg-sidebar-theme d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <img src="logo/smkn_labuang.png" style="border-radius: 50%;">
                </div>
                <div class="sidebar-brand-text mx-2">SMKN Labuang</div>
            </a>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Modal Pop Up
            </div>
            <li class="nav-item">
                <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#cetakHarian">
                    <i class="fas fa-fw fa-print"></i>
                    <span>Cetak</span></a>
            </li>
            <?php
            if ($_SESSION['role'] == "Admin") {
            ?>
                <li class="nav-item">
                    <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#buatQRCode">
                        <i class="fas fa-fw fa-qrcode"></i>
                        <span>Buat QR Code</span></a>
                </li>
            <?php
            }
            ?>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Halaman
            </div>
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-fw fa-qrcode"></i>
                    <span>E-Absensi</span></a>
            </li>
            <?php
            if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Kepala Sekolah" || $_SESSION['role'] == "Kepala Tata Usaha" || $_SESSION['role'] == "STAF") {
            ?>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    Daftar Absensi Card
                </div>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTableAbsensiPelajar" aria-expanded="true" aria-controls="collapseTable">
                        <i class="fas fa-fw fa-address-card"></i>
                        <span>Card Absensi</span>
                    </a>
                    <div id="collapseTableAbsensiPelajar" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                        <div class="card py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Absensi Pekerja</h6>
                            <a class="collapse-item" style="border: 0px;" href="absensi_harian.php">
                                Absensi Harian
                            </a>
                            <a class="collapse-item" href="absensi_bulanan.php">
                                Absensi Bulanan
                            </a>
                        </div>
                    </div>
                </li>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    Daftar Absensi table
                </div>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTableAbsensiPekerjaTable" aria-expanded="true" aria-controls="collapseTable">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Table Absensi</span>
                    </a>
                    <div id="collapseTableAbsensiPekerjaTable" class="collapse show" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                        <div class="card py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Absensi Pekerja</h6>
                            <a class="collapse-item" style="border: 0px;" href="table_absensi_harian.php">
                                Absensi Harian
                            </a>
                            <a class="collapse-item btn-light active" href="table_absensi_mingguan.php">
                                Absensi Mingguan
                            </a>
                        </div>
                    </div>
                </li>
            <?php
            }
            ?>
            <?php
            if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Kepala Sekolah") {
            ?>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    Features 001
                </div>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true" aria-controls="collapseTable">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Tables</span>
                    </a>
                    <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                        <div class="card py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Tables</h6>
                            <a class="collapse-item" href="table_jabatan.php">Table Jabatan</a>
                            <a class="collapse-item" href="table_pangkat.php">Table Pangkat</a>
                            <a class="collapse-item" href="table_Golongan.php">Table Golongan</a>
                            <a class="collapse-item" href="table_pekerja.php">Table Pekerja</a>
                            <a class="collapse-item" href="table_aksesmasuk.php">Table Akses</a>
                            <a class="collapse-item" href="table_outprint.php">Table Out Print</a>
                            <a class="collapse-item" href="table_user.php">Table User</a>
                        </div>
                    </div>
                </li>
            <?php
            }
            ?>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Features 002
            </div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTablePekerja" aria-expanded="true" aria-controls="collapseTable">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables Pekerja</span>
                </a>
                <div id="collapseTablePekerja" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                    <div class="card py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tables</h6>
                        <?php
                        $selectedjabatanid = isset($_GET['jabatanid']) ? $_GET['jabatanid'] : '';
                        $sql_kelas = mysqli_query($conn, "SELECT * FROM jabatan");
                        while ($data_kelas = mysqli_fetch_array($sql_kelas)) {
                            $activeClass = ($data_kelas['jabatanid'] == $selectedjabatanid) ? 'active btn-light' : '';
                        ?>
                            <a class="collapse-item <?= $activeClass ?>" href="data_jabatan_pekerja.php?jabatanid=<?= $data_kelas['jabatanid'] ?>&jabatan=<?= $data_kelas['jabatan'] ?>"><?= $data_kelas['jabatan'] ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </li>
            <!-- <hr class="sidebar-divider">
      <div class="version" id="version-ruangadmin"></div> -->
        </ul>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column bg-body-tertiary">
            <div id="content">
                <!-- TopBar -->
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <div class="nav-link dropdown-toggle">
                                <i class="fas fa-user-tie fa-fw text-user"></i>&nbsp;
                                <div class="text-user"><?= $_SESSION['role'] ?></div>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
                                $fotoPath = 'fotouser/' . $_SESSION['fotouser'];
                                // Jika file tidak ada atau nama file tidak valid, gunakan gambar default
                                if (!file_exists($fotoPath) || empty($_SESSION['fotouser'])) {
                                    $fotoPath = 'default/user.png'; // Ganti dengan path gambar default
                                }
                                ?>
                                <img class="img-profile rounded-circle" src="<?= $fotoPath ?>" style="max-width: 60px; object-fit: cover;">
                                <span class="ml-2 d-none d-lg-inline text-user small"><?= $_SESSION['namalengkap'] ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#myProfile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#setting">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal" id="buttonModalLogout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid bg-body-tertiary" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0">DataTable Jabatan</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Tables</li>
                            <li class="breadcrumb-item active" aria-current="page">DataTable Jabatan</li>
                        </ol>
                    </div>
                    <?php
                    // Mengambil data filter dari form
                    $filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
                    $filter_jabatan = isset($_GET['filter_jabatan']) ? $_GET['filter_jabatan'] : '';
                    ?>
                    <form action="#absensi_harian" method="GET">
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" name="filter_date" value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
                            <select class="form-select" name="filter_jabatan">
                                <?php
                                $jabatanid_wali = $_SESSION['jabatanid'];
                                $sql_jabatan_wali = mysqli_query($conn, "SELECT * FROM jabatan WHERE jabatanid='$jabatanid_wali'");
                                if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Kepala Sekolah") {
                                ?>
                                    <option value="">Semua Jabatan</option>
                                <?php
                                }
                                ?>

                                <?php
                                if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Kepala Sekolah") {
                                    $sql_jabatan = mysqli_query($conn, "SELECT * FROM jabatan");
                                    while ($data_jabatan = mysqli_fetch_array($sql_jabatan)) {
                                ?>
                                        <option value="<?= $data_jabatan['jabatanid'] ?>" <?php echo isset($_GET['filter_jabatan']) && $_GET['filter_jabatan'] == $data_jabatan['jabatan'] ? 'selected' : ''; ?>><?= $data_jabatan['jabatan'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Table Jabatan</h6>
                                    <?php
                                    if ($_SESSION['role'] == "Admin") {
                                    ?>
                                        <!-- <a href="" data-bs-toggle="modal" data-bs-target="#tambahKelas" class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-plus-fill"></i></a> -->
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="table-responsive p-3">
                                    <style>
                                        th {
                                            text-align: center;
                                            /* Pusatkan teks secara horizontal */
                                            vertical-align: middle;
                                            /* Pusatkan teks secara vertikal */
                                            padding: 8px;
                                            /* Opsional: menambah ruang di sekitar teks */
                                        }
                                    </style>
                                    <table class="table align-items-center table-flush table-striped table-hover" id="dataTable">
                                        <thead class="table-primary">
                                            <tr bgcolor="skyblue">
                                                <th align="center" rowspan="4" width="25px">
                                                    No
                                                </th>
                                                <th align="center" rowspan="4" width="150px">
                                                    Nama/NIP
                                                </th>
                                                <th align="center" rowspan="4" width="110px">
                                                    Pangkat/Golongan
                                                </th>
                                                <th align="center" rowspan="4" width="80px">
                                                    Jabatan
                                                </th>
                                                <th align="center" rowspan="4" width="80px">
                                                    Jenis Kelamin
                                                </th>
                                                <th align="center" colspan="24" width="850px">
                                                    Hari
                                                </th>
                                                <th align="center" rowspan="4" width="100px">
                                                    Keterangan
                                                </th>
                                            </tr>
                                            <tr bgcolor="skyblue">
                                                <th align="center" colspan="4">Senin</th>
                                                <th align="center" colspan="4">Selasa</th>
                                                <th align="center" colspan="4">Rabu</th>
                                                <th align="center" colspan="4">Kamis</th>
                                                <th align="center" colspan="4">Jumat</th>
                                                <th align="center" colspan="4">Sabtu</th>
                                            </tr>
                                            <tr bgcolor="skyblue">
                                                <th align="center" colspan="2">Datang</th>
                                                <th align="center" colspan="2">Pulang</th>
                                                <th align="center" colspan="2">Datang</th>
                                                <th align="center" colspan="2">Pulang</th>
                                                <th align="center" colspan="2">Datang</th>
                                                <th align="center" colspan="2">Pulang</th>
                                                <th align="center" colspan="2">Datang</th>
                                                <th align="center" colspan="2">Pulang</th>
                                                <th align="center" colspan="2">Datang</th>
                                                <th align="center" colspan="2">Pulang</th>
                                                <th align="center" colspan="2">Datang</th>
                                                <th align="center" colspan="2">Pulang</th>
                                            </tr>
                                            <tr bgcolor="skyblue">
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                                <th align="center">Jam</th>
                                                <th align="center">✓</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;

                                            // Mengatur tanggal default jika tidak ada filter tanggal
                                            $tanggal = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');
                                            $jabatanid = isset($_GET['filter_jabatan']) && !empty($_GET['filter_jabatan']) ? $_GET['filter_jabatan'] : '';

                                            // Menghitung tanggal awal dan akhir minggu
                                            $timestamp = strtotime($tanggal);

                                            // Jika hari ini adalah Senin, gunakan tanggal hari ini sebagai awal minggu
                                            if (date('N', $timestamp) == 1) {
                                                $start_date = $timestamp;
                                            } else {
                                                $start_date = strtotime('last Monday', $timestamp);
                                            }

                                            $end_date = strtotime('next Saturday', $start_date);

                                            // Mendapatkan tanggal-tanggal dalam minggu
                                            $dates = [];
                                            for ($i = $start_date; $i <= $end_date; $i = strtotime('+1 day', $i)) {
                                                $dates[] = date('Y-m-d', $i);
                                            }

                                            // Query untuk mendapatkan data absensi
                                            $sql = "SELECT pekerja.*, pangkat.*, golongan.*, jabatan.*, absen.id, absen.tanggal, absen.waktudatang, absen.keterangandatang, absen.waktupulang, absen.keteranganpulang, absen.keterangantakabsen, absen.keterangankhusus,
                                            GROUP_CONCAT(DISTINCT DATE_FORMAT(absen.tanggal, '%Y-%m-%d') SEPARATOR ',') AS attendance_dates,
                                            GROUP_CONCAT(DISTINCT IF(absen.waktudatang != '00:00:00', CONCAT(DATE_FORMAT(absen.tanggal, '%Y-%m-%d'), '|', absen.waktudatang, '|', IF(absen.keterangandatang IS NOT NULL, absen.keterangandatang, ''), '|', absen.id), NULL) SEPARATOR ',') AS hadir_datang,
                                            GROUP_CONCAT(DISTINCT IF(absen.waktupulang != '00:00:00', CONCAT(DATE_FORMAT(absen.tanggal, '%Y-%m-%d'), '|', absen.waktupulang, '|', IF(absen.keteranganpulang IS NOT NULL, absen.keteranganpulang, ''), '|', absen.id), NULL) SEPARATOR ',') AS hadir_pulang,
                                            GROUP_CONCAT(DISTINCT IF(absen.keterangantakabsen IS NOT NULL, CONCAT(DATE_FORMAT(absen.tanggal, '%Y-%m-%d'), '|', absen.keterangantakabsen), NULL) SEPARATOR ',') AS keterangan_tak_absen
                                            FROM pekerja
                                            LEFT JOIN absen ON pekerja.nip = absen.nip
                                            INNER JOIN pangkat ON pekerja.pangkatid = pangkat.pangkatid
                                            INNER JOIN golongan ON pekerja.golonganid = golongan.golonganid
                                            INNER JOIN jabatan ON pekerja.jabatanid = jabatan.jabatanid
                                            WHERE ('$jabatanid' = '' OR pekerja.jabatanid = '$jabatanid')
                                            GROUP BY pekerja.nip
                                            ORDER BY pekerja.nama ASC";

                                            $result = mysqli_query($conn, $sql);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $attendance_dates = explode(',', $row['attendance_dates']);
                                                $attendance_dates = array_map('trim', $attendance_dates);

                                                $hadir_datang = explode(',', $row['hadir_datang']);
                                                $hadir_datang = array_map('trim', $hadir_datang);

                                                $hadir_pulang = explode(',', $row['hadir_pulang']);
                                                $hadir_pulang = array_map('trim', $hadir_pulang);

                                                $keterangan_tak_absen = explode(',', $row['keterangan_tak_absen']);
                                                $keterangan_tak_absen = array_map('trim', $keterangan_tak_absen);

                                                $pangkat = $row['pangkat'] == "Tidak Memiliki" ? "-" : $row['pangkat'];
                                                $golongan = $row['golongan'] == "Tidak Memiliki" ? "-" : $row['golongan'];
                                                $nipCleaned = str_replace(' ', '', $row['nip']);
                                                $nip = is_numeric($nipCleaned) ? $row['nip'] : "-";
                                            ?>
                                                <tr style="font-size: 15px;">
                                                    <td align="center"><?= $no++ ?></td>
                                                    <td align="left">
                                                        <div style="width: 200px;"><?= htmlspecialchars($row['nama']) ?><br>NIP: <?= htmlspecialchars($nip) ?></div>
                                                    </td>
                                                    <td align="center"><?= htmlspecialchars($pangkat) ?>/<?= htmlspecialchars($golongan) ?></td>
                                                    <td align="center"><?= htmlspecialchars($row['jabatan']) ?></td>
                                                    <td align="center"><?= htmlspecialchars($row['jk']) ?></td>

                                                    <?php
                                                    // Loop melalui setiap hari dalam minggu
                                                    foreach ($dates as $date) {
                                                        $hadir_datang_check = '';
                                                        $hadir_pulang_check = '';

                                                        $waktu_datang = '';
                                                        $hadir_datang_keterangan = '';
                                                        $datang_id = '';

                                                        $waktu_pulang = '';
                                                        $hadir_pulang_keterangan = '';
                                                        $pulang_id = '';

                                                        $keterangan_tak_absen_value = '';
                                                        $keterangan_deskripsi_check = '';

                                                        foreach ($hadir_datang as $datang) {
                                                            $datang_parts = explode('|', $datang);
                                                            if (isset($datang_parts[0]) && isset($datang_parts[1]) && isset($datang_parts[2]) && isset($datang_parts[3])) {
                                                                list($datang_date, $datang_time, $datang_status, $datang_id) = $datang_parts;
                                                                if ($datang_date === $date) {
                                                                    $waktu_datang = $datang_time;
                                                                    $hadir_datang_keterangan = $datang_status;
                                                                    switch ($datang_status) {
                                                                        case 'Datang Cepat':
                                                                            $hadir_datang_check = '<span style="color: orange;">✓</span>';
                                                                            break;
                                                                        case 'Datang Tepat Waktu':
                                                                            $hadir_datang_check = '<span style="color: green;">✓</span>';
                                                                            break;
                                                                        case 'Datang Terlambat':
                                                                            $hadir_datang_check = '<span style="color: red;">✓</span>';
                                                                            break;
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                        }

                                                        foreach ($hadir_pulang as $pulang) {
                                                            $pulang_parts = explode('|', $pulang);
                                                            if (isset($pulang_parts[0]) && isset($pulang_parts[1]) && isset($pulang_parts[2]) && isset($pulang_parts[3])) {
                                                                list($pulang_date, $pulang_time, $pulang_status, $pulang_id) = $pulang_parts;
                                                                if ($pulang_date === $date) {
                                                                    $waktu_pulang = $pulang_time;
                                                                    $hadir_pulang_keterangan = $pulang_status;
                                                                    switch ($pulang_status) {
                                                                        case 'Pulang Cepat':
                                                                            $hadir_pulang_check = '<span style="color: orange;">✓</span>';
                                                                            break;
                                                                        case 'Pulang Tepat Waktu':
                                                                            $hadir_pulang_check = '<span style="color: green;">✓</span>';
                                                                            break;
                                                                        case 'Pulang Terlambat':
                                                                            $hadir_pulang_check = '<span style="color: red;">✓</span>';
                                                                            break;
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                        }

                                                        foreach ($keterangan_tak_absen as $keterangan) {
                                                            $keterangan_parts = explode('|', $keterangan);
                                                            if (isset($keterangan_parts[0]) && isset($keterangan_parts[1])) {
                                                                list($keterangan_date, $keterangan_value) = $keterangan_parts;
                                                                if ($keterangan_date === $date) {
                                                                    $keterangan_tak_absen_value = $keterangan_value;
                                                                    switch ($keterangan_value) {
                                                                        case 'Sakit':
                                                                            $keterangan_deskripsi_check = 'S';
                                                                            break;
                                                                        case 'Izin':
                                                                            $keterangan_deskripsi_check = 'i';
                                                                            break;
                                                                        case 'Alpa':
                                                                            $keterangan_deskripsi_check = 'A';
                                                                            break;
                                                                        case 'Dinas Luar':
                                                                            $keterangan_deskripsi_check = 'DL';
                                                                            break;
                                                                        default:
                                                                            $keterangan_deskripsi_check = '';
                                                                            break;
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                        <style>
                                                            .form-absen {
                                                                border: 0.5px solid #0d6efd;
                                                                /* border: none; */
                                                                border-radius: 5px;
                                                                padding: 5px 5px;
                                                                text-align: center;
                                                            }

                                                            .form-absen:focus {
                                                                border: none;
                                                                outline: none;
                                                            }
                                                        </style>
                                                        <td align="center">
                                                            <?php
                                                            if ($waktu_datang == '') {
                                                            ?>
                                                                <form action="update_absensi_mingguan.php" method="post">
                                                                    <input class="form-absen" type="text" name="keterangantakabsen" value="<?= htmlspecialchars($keterangan_tak_absen_value) ?>" pattern="Sakit|Izin|Alpa|Dinas Luar" title="Masukkan salah satu dari: Sakit, Izin, Alpa, Dinas Luar" placeholder="Sakit, Izin, Alpa, Dinas Luar">
                                                                    <input class="form-absen" type="hidden" name="tanggal" value="<?= $date ?>">
                                                                    <input class="form-absen" type="hidden" name="nip" value="<?= $row['nip'] ?>">
                                                                    <input class="form-absen" type="hidden" name="id1" value="<?= $datang_id ?>">
                                                                    <input class="form-absen" type="hidden" name="id2" value="<?= $pulang_id ?>">
                                                                    <button type="submit" name="ketakabsen" class="btn btn-primary btn-sm mt-2">ubah</button>
                                                                </form>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <form action="update_absensi_mingguan.php" method="post">
                                                                    <input class="form-absen timeInput" type="text" name="waktudatang" value="<?= $waktu_datang ?>" pattern="\d{2}:\d{2}:\d{2}" title="Format waktu harus 00:00:00" placeholder="00:00:00">
                                                                    <input class="form-absen" type="hidden" name="tanggal" value="<?= $date ?>">
                                                                    <input class="form-absen" type="hidden" name="nip" value="<?= $row['nip'] ?>">
                                                                    <input class="form-absen" type="hidden" name="id1" value="<?= $datang_id ?>">
                                                                    <input class="form-absen" type="hidden" name="id2" value="<?= $pulang_id ?>">
                                                                    <button type="submit" name="ubahdatang" class="btn btn-primary btn-sm mt-2">ubah</button>
                                                                </form>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td align="center" style="font-size: 20px; font-weight: bold;">
                                                            <?php
                                                            if ($waktu_datang == '') {
                                                            ?>
                                                                <?= $keterangan_deskripsi_check ?>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <?= $hadir_datang_check ?>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php
                                                            if ($waktu_pulang == '') {
                                                            ?>
                                                                <form action="update_absensi_mingguan.php" method="post">
                                                                    <input class="form-absen" type="text" name="keterangantakabsen" value="<?= htmlspecialchars($keterangan_tak_absen_value) ?>" pattern="Sakit|Izin|Alpa|Dinas Luar" title="Masukkan salah satu dari: Sakit, Izin, Alpa, Dinas Luar" placeholder="Sakit, Izin, Alpa, Dinas Luar">
                                                                    <input class="form-absen" type="hidden" name="tanggal" value="<?= $date ?>">
                                                                    <input class="form-absen" type="hidden" name="nip" value="<?= $row['nip'] ?>">
                                                                    <input class="form-absen" type="hidden" name="id1" value="<?= $datang_id ?>">
                                                                    <input class="form-absen" type="hidden" name="id2" value="<?= $pulang_id ?>">
                                                                    <button type="submit" name="ketakabsen" class="btn btn-primary btn-sm mt-2">ubah</button>
                                                                </form>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <form action="update_absensi_mingguan.php" method="post">
                                                                    <input class="form-absen timeInput" type="text" name="waktupulang" value="<?= $waktu_pulang ?>" pattern="\d{2}:\d{2}:\d{2}" title="Format waktu harus 00:00:00" placeholder="00:00:00">
                                                                    <input class="form-absen" type="hidden" name="tanggal" value="<?= $date ?>">
                                                                    <input class="form-absen" type="hidden" name="nip" value="<?= $row['nip'] ?>">
                                                                    <input class="form-absen" type="hidden" name="id1" value="<?= $datang_id ?>">
                                                                    <input class="form-absen" type="hidden" name="id2" value="<?= $pulang_id ?>">
                                                                    <button type="submit" name="ubahpulang" class="btn btn-primary btn-sm mt-2">ubah</button>
                                                                </form>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td align="center" style="font-size: 20px; font-weight: bold;">
                                                            <?php
                                                            if ($waktu_pulang == '') {
                                                            ?>
                                                                <?= $keterangan_deskripsi_check ?>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <?= $hadir_pulang_check ?>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    // Menampilkan keterangankhusus untuk tanggal hari Sabtu
                                                    $saturday_date = date('Y-m-d', strtotime('Saturday', $end_date));
                                                    $keterangankhusus = '-';
                                                    // Ambil keterangankhusus dari absensi berdasarkan tanggal Sabtu
                                                    $sql_khusus = "SELECT id, nip, keterangankhusus FROM absen WHERE tanggal = '$saturday_date' AND nip = '{$row['nip']}'";
                                                    $result_khusus = mysqli_query($conn, $sql_khusus);
                                                    if ($result_khusus && $row_khusus = mysqli_fetch_assoc($result_khusus)) {
                                                        $keterangankhusus = $row_khusus['keterangankhusus'];
                                                    ?>
                                                        <form action="update_absensi_mingguan.php" method="post">
                                                            <td align="center">
                                                                <textarea class="form-absen" style="height: max-content; width: 400px;" name="keterangankhusus" placeholder="Isi Keterangan Minggu Ini Jika Ada"><?= $keterangankhusus ?></textarea>
                                                                <input type="hidden" name="idket" value="<?= $row_khusus['id'] ?>">
                                                                <input type="hidden" name="nip" value="<?= $row_khusus['nip'] ?>">
                                                                <input type="hidden" name="tanggal" value="<?= $saturday_date ?>">
                                                                <button type="submit" name="ubahket" class="btn btn-primary btn-sm">ubah</button>
                                                            </td>
                                                        </form>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <form action="update_absensi_mingguan.php" method="post">
                                                            <td align="center">
                                                                <textarea class="form-absen" style="height: max-content; width: 400px;" name="keterangankhusus" placeholder="Isi Keterangan Minggu Ini Jika Ada"></textarea>
                                                                <input type="hidden" name="nip" value="<?= $row['nip'] ?>">
                                                                <input type="hidden" name="tanggal" value="<?= $saturday_date ?>">
                                                                <button type="submit" name="tambahket" class="btn btn-primary btn-sm">ubah</button>
                                                            </td>
                                                        </form>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="text-danger">
                                        <b>PERINGATAN <i class="bi bi-exclamation-triangle-fill"></i> <i class="bi bi-cone-striped"></i></b><br>
                                        Harap Berhati-Hati Dalam Mengapus Waktu Absen Pekerja. Apabila Sudah terhapus Maka Tidak Bisa Mengiput Waktu Lagi dan Hanya Bisa Menginput Keterangan Tidak Absen Saja. Jadi Berhati-hatilah!<br><br>
                                        Apabila terlanjut sudah maka bisa edit di <a href="table_absensi_harian.php">Sini</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Logout -->
                    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form class="modal-content" action="../logout.php" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabelLogout">Ohh No!</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;">
                                    <img src="default/Tak berjudul28_20240708190427.png" alt="" style="width: 90%;">
                                </div>
                                <div class="modal-footer" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <div class="h5 mb-3">Apa Jawaban Anda?</div>
                                    <div style="display: flex; align-items: center;">
                                        <button type="submit" class="btn btn-primary mr-2" id="logoutButton">I'm Sure</button>
                                        <div class="h5 mx-2">Atau</div>
                                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Not Sure</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#logoutModal').on('shown.bs.modal', function() {
                                // $('#focusInput').trigger('focus');
                                document.addEventListener('keydown', function(event) {
                                    if (event.key === 'Enter') {
                                        document.getElementById('logoutButton').click();
                                    }
                                }, {
                                    once: true
                                });
                            });

                            document.addEventListener('keydown', function(event) {
                                if (event.key === 'Alt') {
                                    document.addEventListener('keydown', function(event) {
                                        if (event.key === '/') {
                                            document.getElementById('buttonModalLogout').click();
                                        }
                                    }, {
                                        once: true
                                    });
                                }
                            });
                        });
                    </script>
                    <!-- Akhir Modal Logout -->

                </div>
                <!---Container Fluid-->
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-navbar">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy;
                            <?= date("Y") ?> i RPL UPTD SMK Negeri Labuang
                        </span>
                    </div>
                </div>
            </footer>
            <!-- Footer -->
        </div>
    </div>

    <?php
    if ($_SESSION['hakakses'] == "Dilarang") {
        echo "<script type='text/javascript'>
        setTimeout(function () { 
          swal({
                  title: 'Anda Dilarang Untuk Mengakses Halaman Ini',
                  text:  'Silahkan Hubungi Admin Utama Untuk Meminta Izin Akses!',
                  type: 'error',
                  timer: 5000,
                  showConfirmButton: false
              });   
        });  
        window.setTimeout(function(){ 
      window.location.replace('../index.php');
    } ,5000); 
        </script>";
    }
    ?>

    <!-- Modal Scrollable -->
    <div class="modal fade" id="cetakHarian" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form class="modal-content" id="printForm" method="post" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle"><i class="bi bi-printer-fill"></i> Cetak (Masukan Data)</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control mb-2" id="tanggal" name="tanggal" placeholder="klik dan ketik disini..." required value="<?= date("Y-m-d") ?>">

                    <label for="outprintid">Cetak Keluar Sebagai:</label>
                    <select name="outprintid" id="outprintid" class="form-control mb-2" required>
                        <option value="">Pilihan</option>
                        <?php
                        $sql_outprint = mysqli_query($conn, "SELECT * FROM outprint");
                        while ($data_outprint = mysqli_fetch_array($sql_outprint)) {
                        ?>
                            <option value="<?= $data_outprint['outprintid'] ?>"><?= $data_outprint['outprintas'] ?></option>
                        <?php
                        }
                        ?>
                    </select>

                    <label for="printOption">Pilih Hasil Print</label>
                    <select name="printOption" id="printOption" class="form-control mb-2" required>
                        <option value="hasil_print_mingguan.php">Hasil Print Mingguan</option>
                        <option value="hasil_print_harian.php">Hasil Print Harian</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Tampikan" class="btn btn-success">
                    <button type="reset" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Scrollable -->

    <script>
        document.getElementById('printForm').addEventListener('submit', function(event) {
            var printOption = document.getElementById('printOption').value;
            this.action = printOption;
        });
    </script>

    <!-- Modal Scrollable -->
    <div class="modal fade" id="tambahKelas" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <form class="modal-content" action="proses_jabatan.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Tambah Jabatan</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="jabatan">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="form-control mb-2" required>
                        <option value="">Pilihan</option>
                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                        <option value="Guru">Guru</option>
                        <option value="Kepala Tata Usaha">Kepala Tata Usaha</option>
                        <option value="STAF">STAF</option>
                        <option value="GTT">GTT</option>
                        <option value="PTT">PTT</option>
                        <option value="other">Lainnya</option> <!-- Opsi untuk menampilkan input teks -->
                    </select>

                    <input type="text" id="jabatan_other" class="form-control mb-2" placeholder="Masukkan kelas lainnya" style="display: none;" required>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const selectElement = document.getElementById('jabatan');
                            const inputOther = document.getElementById('jabatan_other');

                            selectElement.addEventListener('change', () => {
                                if (selectElement.value === 'other') {
                                    inputOther.style.display = 'block';
                                    inputOther.required = true;
                                } else {
                                    inputOther.style.display = 'none';
                                    inputOther.required = false;
                                }
                            });

                            inputOther.addEventListener('input', () => {

                                const inputValue = inputOther.value.trim();

                                // Create a new option element
                                const newOption = document.createElement('option');
                                newOption.value = inputValue;
                                newOption.textContent = inputValue;

                                // Replace the existing second option (index 1) with the new option
                                selectElement.options[8] = new Option(newOption.textContent, newOption.value);

                                const SelectedCostumOption = selectElement.options[8];

                                // Set the new option as selected
                                SelectedCostumOption.selected = true;
                            });
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Tambah" class="btn btn-success">
                    <button type="reset" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Scrollable -->

    <?php
    $kelasArray = [
        "Kepala Sekolah",
        "Guru",
        "Kepala Tata Usaha",
        "STAF",
        "GTT",
        "PTT",
    ];
    // Menghitung jumlah elemen dalam array
    $jumlahKelas = count($kelasArray);
    // foreach ($sql as $index => $data) {
    ?>
    <!-- Modal Scrollable -->
    <div class="modal fade" id="editJabatan<?= $data['jabatanid'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <form class="modal-content" action="update_jabatan.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Jabatan</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" name="jabatanid" value="<?= $data['jabatanid'] ?>" hidden required>
                    <label for="jabatanEdit<?= $index ?>">Jabatan</label>
                    <select name="jabatan" id="jabatanEdit<?= $index ?>" class="form-control mb-2" required>
                        <?php
                        // Jika $data['jabatan'] tidak ada di $kelasArray, tambahkan sebagai opsi
                        if (!in_array($data['jabatan'], $kelasArray)) : ?>
                            <option value="<?= $data['jabatan'] ?>" selected><?= $data['jabatan'] ?></option>
                        <?php endif; ?>
                        <option value="Kepala Sekolah" <?= $data['jabatan'] == "Kepala Sekolah" ? "selected" : "" ?>>Kepala Sekolah</option>
                        <option value="Guru" <?= $data['jabatan'] == "Guru" ? "selected" : "" ?>>Guru</option>
                        <option value="Kepala Tata Usaha" <?= $data['jabatan'] == "Kepala Tata Usaha" ? "selected" : "" ?>>Kepala Tata Usaha</option>
                        <option value="STAF" <?= $data['jabatan'] == "STAF" ? "selected" : "" ?>>STAF</option>
                        <option value="GTT" <?= $data['jabatan'] == "GTT" ? "selected" : "" ?>>GTT</option>
                        <option value="PTT" <?= $data['jabatan'] == "PTT" ? "selected" : "" ?>>PTT</option>
                        <option value="other">Lainnya</option> <!-- Opsi untuk menampilkan input teks -->
                    </select>
                    <input type="text" id="jabatanEdit_other<?= $index ?>" class="form-control mb-2" placeholder="Masukkan kelas lainnya" value="<?= $data['jabatan'] ?>" style="display: none;" required>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const selectElementEdit = document.getElementById('jabatanEdit<?= $index ?>');
                            const inputOtherEdit = document.getElementById('jabatanEdit_other<?= $index ?>');

                            selectElementEdit.addEventListener('change', () => {
                                if (selectElementEdit.value === 'other') {
                                    inputOtherEdit.style.display = 'block';
                                    inputOtherEdit.required = true;
                                } else {
                                    inputOtherEdit.style.display = 'none';
                                    inputOtherEdit.required = false;
                                }
                            });

                            inputOtherEdit.addEventListener('input', () => {

                                const inputValue = inputOtherEdit.value.trim();

                                // Create a new option element
                                const newOption = document.createElement('option');
                                newOption.value = inputValue;
                                newOption.textContent = inputValue;

                                // Replace the existing second option (index 1) with the new option
                                selectElementEdit.options[<?= $jumlahKelas  + 2 ?>] = new Option(newOption.textContent, newOption.value);

                                const SelectedCostumOption = selectElementEdit.options[<?= $jumlahKelas  + 2 ?>];

                                // Set the new option as selected
                                SelectedCostumOption.selected = true;
                            });
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Ubah" class="btn btn-success">
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Scrollable -->
    <?php
    // }
    ?>

    <!-- Modal Scrollable -->
    <div class="modal fade" id="buatQRCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form id="qrcode-form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Buat QR-Code</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <style>
                    #qrcode {
                        margin-top: 20px;
                    }

                    #qrcode canvas {
                        width: 100%;
                        /* Set the width you want here */
                        max-width: 300px;
                        /* Optional: set a max width */
                        height: auto;
                        /* Maintain the aspect ratio */
                        /* margin: 0 auto; */
                    }

                    #qrcode-container {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        height: 100%;
                    }
                </style>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="text">Kode Akses</label>
                        <input class="form-control" type="text" id="text" placeholder="Enter text" required>
                    </div>
                    <div class="form-group">
                        <label for="logo">Pilih Gambar Anda</label>
                        <input class="form-control" type="file" id="logo" accept="image/*">
                    </div>
                    <div id="error-message" style="color: red; display: none;">Logo must be a square image (1:1 aspect ratio).</div>
                    <div id="qrcode-container" style="display: none; text-align: center;">
                        <div id="qrcode"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="download-btn" class="btn btn-warning" style="display:none;">Download QR Code</button>
                    <button type="submit" class="btn btn-success">Generate QR Code</button>
                    <button type="reset" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Scrollable -->

    <!-- Modal Scrollable -->
    <div class="modal fade" id="myProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form class="modal-content" action="update_profile.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Profile Saya</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <!-- <form action="update_user.php" method="post" enctype="multipart/form-data"> -->
                    <?php
                    $userid = $_SESSION['userid'];
                    $sql = mysqli_query($conn, "SELECT * FROM user WHERE userid='$userid'");
                    while ($data = mysqli_fetch_array($sql)) {
                        $fotoPath = 'fotouser/' . $data['fotouser'];
                        // Jika file tidak ada atau nama file tidak valid, gunakan gambar default
                        if (!file_exists($fotoPath) || empty($data['fotouser'])) {
                            $fotoPath = 'default/user.png'; // Ganti dengan path gambar default
                        }
                    ?>
                        <input type="text" class="form-control mb-2" id="userid" name="userid" placeholder="klik dan ketik disini..." value="<?= $data['userid'] ?>" hidden required>
                        <label for="inputFoto" style="display: block;" class="mb-3">
                            <img src="<?= $fotoPath ?>" alt="" class="berita-user-profile" id="imgFoto">
                        </label>
                        <input type="file" name="fotouser" id="inputFoto" class="form-control" hidden>
                        <label for="username">Username</label>
                        <input type="text" class="form-control mb-2" id="username" name="username" placeholder="klik dan ketik disini..." value="<?= $data['username'] ?>" required>
                        <script>
                            // Add event listener to the Username input field
                            $('#username').on('input', function() {
                                var username = $(this).val();
                                // Convert to lowercase and remove spaces
                                username = username.toLowerCase().replace(/\s+/g, '');
                                $(this).val(username);
                            });
                        </script>
                        <label for="password">Password</label>
                        <input type="password" class="form-control mb-2" id="password" name="password" placeholder="klik dan ketik disini..." value="<?= $data['password'] ?>" minlength="8" required>
                        <input type="checkbox" id="show-password"> <label for="show-password">Tampilkan Sandi</label>
                        <label for="email" style="display: block;">Email</label>
                        <input type="email" class="form-control mb-2" id="email" name="email" placeholder="klik dan ketik disini..." value="<?= $data['email'] ?>" required>
                        <label for="namalengkap">Nama Lengkap</label>
                        <input type="text" class="form-control mb-2" id="namalengkap" name="namalengkap" placeholder="klik dan ketik disini..." value="<?= $data['namalengkap'] ?>" required>
                        <input type="hidden" class="form-control mb-2" id="nip" name="nip" placeholder="klik dan ketik disini..." value="<?= $data['nip'] ?>" required>
                        <input type="hidden" class="form-control mb-2" id="jabatanid" name="jabatanid" placeholder="klik dan ketik disini..." value="<?= $data['jabatanid'] ?>" required>
                        <input type="hidden" class="form-control mb-2" id="pangkatid" name="pangkatid" placeholder="klik dan ketik disini..." value="<?= $data['pangkatid'] ?>" required>
                        <input type="hidden" class="form-control mb-2" id="golonganid" name="golonganid" placeholder="klik dan ketik disini..." value="<?= $data['golonganid'] ?>" required>
                        <input type="hidden" class="form-control mb-2" id="hakakses" name="hakakses" placeholder="klik dan ketik disini..." value="<?= $data['hakakses'] ?>" required>
                        <input type="hidden" class="form-control mb-2" id="idpekerja" name="idpekerja" placeholder="klik dan ketik disini..." value="<?= $data['idpekerja'] ?>" required>
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" class="form-control mb-2" id="alamat" cols="30" rows="5" placeholder="klik dan ketik disini..." required><?= $data['alamat'] ?></textarea>
                        <input type="text" class="form-control mb-2" id="role" name="role" placeholder="klik dan ketik disini..." value="<?= $data['role'] ?>" hidden required>
                    <?php
                    }
                    ?>
                    <!-- </form> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Ubah" class="btn btn-success">
                    <button type="reset" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Scrollable -->

    <!-- Modal Scrollable -->
    <div class="modal fade" id="setting" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form class="modal-content" action="change_theme.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle"><i class="bi bi-gear-wide-connected"></i> Pengaturan</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="theme">Pilih Tema:</label>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="theme" id="themeDefault" value="default" <?php if ($theme == 'default') echo 'checked'; ?>>
                        <label class="form-check-label" for="themeDefault">
                            <img src="theme/images/Tak berjudul20_20240608002134.png" alt="Default Tema" class="img-thumbnail" style="width: 50%;"> Default Tema
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="theme" id="theme1" value="theme1" <?php if ($theme == 'theme1') echo 'checked'; ?>>
                        <label class="form-check-label" for="theme1">
                            <img src="theme/images/Tak berjudul20_20240608002241.png" alt="Biru & Biru Langit" class="img-thumbnail" style="width: 50%;"> Biru & Biru Langit
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="theme" id="theme2" value="theme2" <?php if ($theme == 'theme2') echo 'checked'; ?>>
                        <label class="form-check-label" for="theme2">
                            <img src="theme/images/Tak berjudul20_20240608002352.png" alt="Orange & Kuning" class="img-thumbnail" style="width: 50%;"> Orange & Kuning
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="theme" id="theme3" value="theme3" <?php if ($theme == 'theme3') echo 'checked'; ?>>
                        <label class="form-check-label" for="theme3">
                            <img src="theme/images/Tak berjudul20_20240608002447.png" alt="Hijau & Hijau Muda" class="img-thumbnail" style="width: 50%;"> Hijau & Hijau Muda
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="theme" id="theme4" value="theme4" <?php if ($theme == 'theme4') echo 'checked'; ?>>
                        <label class="form-check-label" for="theme4">
                            <img src="theme/images/Tak berjudul20_20240608002529.png" alt="Merah & Merah Muda" class="img-thumbnail" style="width: 50%;"> Merah & Merah Muda
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="theme" id="theme5" value="theme5" <?php if ($theme == 'theme5') echo 'checked'; ?>>
                        <label class="form-check-label" for="theme5">
                            <img src="theme/images/Tak berjudul20_20240608002628.png" alt="Ungu & Lavender" class="img-thumbnail" style="width: 50%;"> Ungu & Lavender
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-success" data-bs-dismiss="modal">Ubah Tema</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Scrollable -->

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->
    <!-- <script src="js/demo/chart-area-demo.js"></script> -->
    <script src="js/ruang-admin.min.js"></script>

    <script src="js/image.js"></script>
    <script src="js/bootstrap.js"></script>

    <script>
        document.getElementById('show-password').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>

    <script>
        // Add event listener to the Username input field
        $('#username').on('input', function() {
            var username = $(this).val();
            // Convert to lowercase and remove spaces
            username = username.toLowerCase().replace(/\s+/g, '');
            $(this).val(username);
        });
    </script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable(); // ID From dataTable 
            $('#dataTableHover').DataTable(); // ID From dataTable with Hover
        });
    </script>

    <script src="js/qrcode.js"></script>
    <script src="js/qrcode.min.js"></script>

    <script>
        document.querySelectorAll('.timeInput').forEach(function(input) {
            input.addEventListener('input', function(e) {
                // Allow only numbers and colon
                let value = e.target.value;
                let validChars = '0123456789:';
                for (let i = 0; i < value.length; i++) {
                    if (validChars.indexOf(value[i]) === -1) {
                        e.target.value = value.slice(0, i) + value.slice(i + 1);
                    }
                }

                // Ensure the format is HH:MM:SS
                let parts = e.target.value.split(':');
                if (parts.length > 3) {
                    e.target.value = parts.slice(0, 3).join(':');
                }
                if (parts.length > 0 && parts[0].length > 2) {
                    e.target.value = parts[0].slice(0, 2) + (parts[1] ? ':' + parts[1] : '') + (parts[2] ? ':' + parts[2] : '');
                }
                if (parts.length > 1 && parts[1].length > 2) {
                    e.target.value = parts[0] + ':' + parts[1].slice(0, 2) + (parts[2] ? ':' + parts[2] : '');
                }
                if (parts.length > 2 && parts[2].length > 2) {
                    e.target.value = parts[0] + ':' + parts[1] + ':' + parts[2].slice(0, 2);
                }
            });
        });
    </script>

</body>

</html>