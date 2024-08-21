<?php

require 'admin/koneksi.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['kodeakses'])) {
    header("Location:login_akses.php");
}

// Menentukan waktu absensi berdasarkan hari
$absen_schedule = [
    'Monday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Tuesday'   => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Wednesday' => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Thursday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Friday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:15:00', 'menunggu_end' => '11:44:59', 'pulang_start' => '11:45:00', 'pulang_tepat_waktu_end' => '13:00:00', 'pulang_end' => '16:00:00'],
    'Saturday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:30:00', 'menunggu_end' => '11:59:59', 'pulang_start' => '12:00:00', 'pulang_tepat_waktu_end' => '14:00:00', 'pulang_end' => '16:00:00'],
    'Sunday'    => ['datang_start' => '00:00:00', 'datang_end' => '00:00:00', 'pulang_start' => '00:00:00', 'pulang_end' => '00:00:00'] // Tidak ada absensi di hari Minggu
];

// $result = mysqli_query($conn, "SELECT * FROM 
// siswa INNER JOiN absen on siswa.nis=absen.nis");
date_default_timezone_set('Asia/Makassar');
$tanggal = date("Y-m-d");
$waktu = date("H:i:s");
$hari_ini = date("l");

// Mendapatkan jadwal absensi berdasarkan hari ini
$schedule = $absen_schedule[$hari_ini];

// $result = mysqli_query($conn, "SELECT * FROM siswa 
//                                 INNER JOIN absen ON siswa.nis = absen.nis 
//                                 WHERE absen.tanggal = '$tanggal' 
//                                 ORDER BY absen.waktu DESC");

// $result = mysqli_query($conn, "SELECT siswa.*, jurusan.*
//                                 FROM siswa
//                                 INNER JOIN absen ON siswa.nis = absen.nis 
//                                 INNER JOIN jurusan ON siswa.jurusanid = jurusan.jurusanid
//                                 WHERE absen.tanggal = '$tanggal' 
//                                 ORDER BY absen.waktu DESC");

// $absen_hari_ini = mysqli_query($conn, "SELECT siswa.*, jurusan.*, absen.* FROM siswa INNER JOIN absen ON siswa.nis = absen.nis INNER JOIN jurusan ON siswa.jurusanid = jurusan.jurusanid WHERE absen.tanggal = '$tanggal' ORDER BY absen.waktu DESC");

// $result = mysqli_query($conn, "SELECT siswa.*, jurusan.*, absen.*
//                                 FROM siswa
//                                 INNER JOIN absen ON siswa.nis = absen.nis 
//                                 INNER JOIN jurusan ON siswa.jurusanid = jurusan.jurusanid
//                                 WHERE absen.tanggal = '$tanggal' 
//                                 ORDER BY absen.waktu DESC
//                                 LIMIT 1");

// $result = mysqli_query($conn, "SELECT siswa.*, jurusan.*, absen.*, kelas.*
// FROM siswa 
// INNER JOIN absen ON siswa.nis = absen.nis 
// INNER JOIN jurusan ON siswa.jurusanid = jurusan.jurusanid 
// INNER JOIN kelas ON siswa.kelasid = kelas.kelasid
// WHERE absen.tanggal = '$tanggal' 
// ORDER BY absen.id DESC LIMIT 1");

$filter_jabatan = isset($_GET['filter_jabatan']) ? $_GET['filter_jabatan'] : '';
$result = "SELECT pekerja.*, pangkat.*, absen.*, jabatan.*, golongan.*
        FROM pekerja 
        INNER JOIN absen ON pekerja.nip = absen.nip 
        INNER JOIN pangkat ON pekerja.pangkatid = pangkat.pangkatid 
        INNER JOIN jabatan ON pekerja.jabatanid = jabatan.jabatanid
        INNER JOIN golongan ON pekerja.golonganid = golongan.golonganid
        WHERE absen.tanggal = '$tanggal'";

if (!empty($filter_jabatan)) {
    $result .= " AND jabatan.jabatan = '$filter_jabatan'";
}

if ($hari_ini == "Sunday") {
    $result .= " ORDER BY absen.id DESC LIMIT 1";
} else if ($waktu >= $schedule['datang_start'] && $waktu <= $schedule['pulang_cepat_start']) {
    $result .= " ORDER BY absen.id DESC LIMIT 1";
} else {
    $result .= " ORDER BY absen.waktupulang DESC LIMIT 1";
}

$result_sql = mysqli_query($conn, $result);

?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="admin/js/color-modes.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QRSCANN</title>
    <style>
        #video,
        #canvas {
            /* position: absolute;
            top: 0;
            left: 0; */
            width: 100%;
            height: 100%;
        }

        #countdown {
            font-size: 2em;
            color: red;
            position: absolute;
            top: 20px;
            left: 10px;
        }

        .navbar-fixed {
            position: fixed;
            width: 100%;
            z-index: 3;
            top: 0;
        }

        .container-margin-top {
            margin-top: 50px;
        }

        .space-between-page {
            display: flex;
            justify-content: space-between;
            flex-direction: column;
            min-height: 100vh;
        }

        .space-between-card-2 {
            display: flex;
            justify-content: space-between;
            flex-direction: column;
            height: 100%;
        }

        .foto-user-profile-nav {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
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
    <link rel="stylesheet" href="admin/css/bootstrap.css">
    <link rel="icon" href="admin/logo/smkn_labuang.png">
    <script src="face-api.min.js"></script>
    <link rel="stylesheet" href="admin/css/sweetalert.css">
    <script src="admin/js/jquery-2.1.4.min.js"></script>
    <script src="admin/js/sweetalert.min.js"></script>
</head>

<body class="space-between-page">
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

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
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

    <nav class="navbar navbar-expand-lg bg-primary navbar-dark navbar-fixed">
        <div class="container">
            <a class="navbar-brand" href="#">E-Absensi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pilihan
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            if (isset($_SESSION['userid'])) {
                            ?>
                                <li><a class="dropdown-item" href="admin/index.php">Dashboard</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                                <li><a class="dropdown-item" href="keluar_akses.php">Kunci</a></li>
                            <?php
                            } else {
                            ?>
                                <li><a class="dropdown-item" href="login.php">Login</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="keluar_akses.php">Kunci</a></li>
                            <?php
                            }
                            ?>
                            <!-- <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="admin/register.php">Register</a></li> -->
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <!-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-success" type="submit">Search</button> -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="admin/logo/smkn_labuang.png" class="nav-link" style="margin: 0; padding: 0;"><b>UPTD SMK Negeri Labuang</b> <img src="admin/logo/smkn_labuang.png" alt="" class="foto-user-profile-nav"></a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </nav>

    <?php
    $nip = $_POST['nip'];
    $check_nip_query = mysqli_query($conn, "SELECT * FROM pekerja WHERE nip='$nip'");

    if ($hari_ini == "Sunday") {
        $message = "Maaf Absensi Tidak Dilakukan Pada Hari Minggu! Silahkan Lakukan Absensi Pada Hari Senin, Jangan Sampai Lewat Yah!";
        echo "<script type='text/javascript'>
            setTimeout(function () { 
                var utterance = new SpeechSynthesisUtterance('$message');
                var voices = window.speechSynthesis.getVoices();
    
                // Mengatur kecepatan bicara
                utterance.rate = 1.75; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)
    
                // Mengatur volume (0.1 sampai 1)
                utterance.volume = 1;
    
                // Mengatur pitch (0 sampai 2)
                utterance.pitch = 2;
    
                window.speechSynthesis.speak(utterance);
                
                swal({
                    title: 'Maaf Absensi Tidak Dilakukan Pada Hari Minggu!',
                    text:  'Silahkan Lakukan Absensi Pada Hari Senin, Jangan Sampai Lewat Yah!',
                    type: 'error',
                    timer: 5000,
                    showConfirmButton: true
                });   
            }); // Menunda sedikit sebelum memulai text-to-speech
                
            window.setTimeout(function(){ 
                window.location.replace('index.php');
            }, 3000); 
          </script>";
        return false;
    } else if (mysqli_num_rows($check_nip_query) == 0) {
        $message = "NIP tidak terdaftar! Silakan hubungi admin";
        echo "<script type='text/javascript'>
            setTimeout(function () { 
                var utterance = new SpeechSynthesisUtterance('$message');
                var voices = window.speechSynthesis.getVoices();
    
                // Mengatur kecepatan bicara
                utterance.rate = 1.75; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)
    
                // Mengatur volume (0.1 sampai 1)
                utterance.volume = 1;
    
                // Mengatur pitch (0 sampai 2)
                utterance.pitch = 2;
    
                window.speechSynthesis.speak(utterance);
                
                swal({
                    title: 'NIP Tidak Dikenal',
                    text:  'Pastikan NIP yang anda gunakan sudah diinput admin',
                    type: 'error',
                    timer: 5000,
                    showConfirmButton: true
                });   
            }); // Menunda sedikit sebelum memulai text-to-speech
                
            window.setTimeout(function(){ 
                window.location.replace('index.php');
            }, 3000); 
          </script>";
        return false;
    }
    ?>

    <div class="container-fluid bg-body-tertiary py-3" style="height: 100%;">
        <?php
        $nip =  $_POST['nip'];
        $tanggal =  $_POST['tanggal'];
        $sql_pekerja = mysqli_query($conn, "SELECT pekerja.*, pangkat.*, jabatan.*, golongan.*
        FROM pekerja 
        INNER JOIN pangkat ON pekerja.pangkatid = pangkat.pangkatid 
        INNER JOIN jabatan ON pekerja.jabatanid = jabatan.jabatanid
        INNER JOIN golongan ON pekerja.golonganid = golongan.golonganid
        WHERE pekerja.nip = '$nip'");
        ?>
        <div class="row row-grid">
            <div class="col-xl-5 col-lg-6 col-md-6 col-sm-12 col-sm-12">
                <div style="position: relative;">
                    <video id="video" width="640" height="480" autoplay></video>
                    <div id="countdown">3</div>
                    <canvas id="canvas" style="position: absolute; top: 0; left: 0;"></canvas>
                    <form id="uploadForm" action="prosesabsen.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="image" id="image">
                        <?php
                        foreach ($sql_pekerja as $pekerja) {
                        ?>
                            <input type="hidden" name="nip" id="nip" value="<?= $nip ?>">

                            <input type="hidden" name="tanggal" value="<?php date_default_timezone_set('Asia/Makassar');
                                                                        echo date("Y-m-d"); ?>">
                        <?php
                        }
                        ?>
                    </form>
                </div>

                <script>
                    // Mengakses kamera
                    var video = document.getElementById('video');
                    var canvas = document.getElementById('canvas');
                    var context = canvas.getContext('2d');

                    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        navigator.mediaDevices.getUserMedia({
                                video: true
                            })
                            .then(function(stream) {
                                video.srcObject = stream;
                                video.play();
                            })
                            .catch(err => console.error("Error accessing camera: ", err));
                    }

                    // Memuat model face-api.js
                    Promise.all([
                        faceapi.nets.ageGenderNet.loadFromUri('models'),
                        faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
                        faceapi.nets.tinyFaceDetector.loadFromUri('models'),
                        faceapi.nets.faceLandmark68Net.loadFromUri('models'),
                        faceapi.nets.faceRecognitionNet.loadFromUri('models'),
                        faceapi.nets.faceExpressionNet.loadFromUri('models')
                    ]).then(startVideo);

                    function startVideo() {
                        video.addEventListener('play', () => {
                            const displaySize = {
                                width: video.width,
                                height: video.height
                            };
                            faceapi.matchDimensions(canvas, displaySize);

                            setInterval(async () => {
                                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
                                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                                context.clearRect(0, 0, canvas.width, canvas.height);
                                faceapi.draw.drawDetections(canvas, resizedDetections);
                                faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);

                                // Jika wajah terdeteksi, mulai hitung mundur
                                if (resizedDetections.length > 0) {
                                    startCountdown();
                                } else {
                                    stopCountdown();
                                }
                            }, 100);
                        });
                    }

                    let countdown = 2;
                    let countdownInterval;

                    function startCountdown() {
                        if (!countdownInterval) {
                            countdown = 2;
                            countdownInterval = setInterval(() => {
                                document.getElementById('countdown').innerText = countdown;
                                countdown--;
                                if (countdown < 0) {
                                    clearInterval(countdownInterval);
                                    countdownInterval = null;
                                    takeSelfie();
                                }
                            }, 1000);
                        }
                    }

                    function stopCountdown() {
                        if (countdownInterval) {
                            clearInterval(countdownInterval);
                            countdownInterval = null;
                            document.getElementById('countdown').innerText = '';
                        }
                    }

                    function takeSelfie() {
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const dataURL = canvas.toDataURL('image/png');
                        document.getElementById('image').value = dataURL;
                        document.getElementById('uploadForm').submit();
                    }
                </script>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-sm-12">
                <?php
                foreach ($sql_pekerja as $pekerja) {
                ?>
                    <div class="form-holder mb-2">
                        <label for="nama">Nama Pekerja:</label>
                        <p class="form-control"><?= $pekerja['nama'] ?></p>
                    </div>
                    <div class="form-holder mb-2">
                        <label for="nip">NIP:</label>
                        <p class="form-control"><?= $pekerja['nip'] ?></p>
                    </div>
                    <div class="form-holder mb-2">
                        <label for="jabatan">Jabatan:</label>
                        <p class="form-control"><?= $pekerja['jabatan'] ?></p>
                    </div>
                    <div class="form-holder mb-2">
                        <label for="pangkat">Pangkat/Golongan:</label>
                        <p class="form-control"><?= $pekerja['pangkat'] ?>/<?= $pekerja['golongan'] ?></p>
                    </div>
                    <div class="form-holder mb-2">
                        <label for="jk">Jenis Kelamin:</label>
                        <p class="form-control"><?= $pekerja['jk'] ?></p>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-sm-12">
                <?php
                foreach ($sql_pekerja as $pekerja) {
                ?>
                    <div class="form-holder">
                        <label for="foto">Foto Pekerja:</label>
                        <img src="admin/fotopekerja/<?= $pekerja['fotopekerja'] ?>" alt="" style="width: 100%;">
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-primary text-center">
        <b class="text-light">Copyright © <?= date("Y") ?> i Team RPL SMKN Labuang</b>
    </div>

    <script src="admin/js/bootstrap.js"></script>

</body>

</html>