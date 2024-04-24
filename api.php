<?php
require_once "config.php";
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["nim"]) && !empty($_GET["kode_mk"])) {
            $nim = $_GET["nim"];
            $kode_mk = $_GET["kode_mk"];
            get_nim_kode_mk($nim, $kode_mk);
        } elseif (!empty($_GET["nim"]) && empty($_GET["kode_mk"])) {
            $nim = $_GET["nim"];
            get_nilai_mhs_by_nim($nim);
        } else {
            get_all_nilai_mhs();
        }
        break;
    
    case 'POST':
        if (!empty($_POST["nim"]) && !empty($_POST["kode_mk"]) && !empty($_POST["nilai"])) {
            insert_nilai_mhs($_POST["nim"], $_POST["kode_mk"], $_POST["nilai"]);
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!empty($data["nim"]) && !empty($data["kode_mk"]) && !empty($data["nilai"])) {
                insert_nilai_mhs($data["nim"], $data["kode_mk"], $data["nilai"]);
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Missing parameters'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        break;
    case 'PUT':
        if (!empty($_GET["nim"]) && !empty($_GET["kode_mk"])) {
            update_nilai_mhs($_GET["nim"], $_GET["kode_mk"]);
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Missing parameters'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        break;
    case 'DELETE':
        if (!empty($_GET["nim"]) && !empty($_GET["kode_mk"])) {
            delete_nilai_mhs($_GET["nim"], $_GET["kode_mk"]);
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Missing parameters'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_all_nilai_mhs()
{
    global $mysqli;
    $query = "SELECT mahasiswa.nim, mahasiswa.nama, mahasiswa.alamat, mahasiswa.tanggal_lahir, matakuliah.kode_mk,matakuliah.nama_mk, matakuliah.sks, perkuliahan.nilai 
            FROM mahasiswa mahasiswa 
            JOIN perkuliahan perkuliahan ON mahasiswa.nim = perkuliahan.nim 
            JOIN matakuliah matakuliah ON perkuliahan.kode_mk = matakuliah.kode_mk";
    $data = array();
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
        $data[] = $row;
    }
    $response = array(
        'status' => 1,
        'message' => 'Get List Nilai Mahasiswa Successfully.',
        'data' => $data
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}

function get_nilai_mhs_by_nim($nim)
{
    global $mysqli;
    $query = "SELECT mahasiswa.nim, mahasiswa.nama, matakuliah.kode_mk, matakuliah.nama_mk, perkuliahan.nilai
            FROM mahasiswa
            JOIN perkuliahan ON mahasiswa.nim = perkuliahan.nim
            JOIN matakuliah ON perkuliahan.kode_mk = matakuliah.kode_mk
            WHERE mahasiswa.nim='$nim'";
    $data = array();
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
        $data[] = $row;
    }
    $response = array(
        'status' => 1,
        'message' => 'Get Nilai Mahasiswa Successfully.',
        'data' => $data
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}

function insert_nilai_mhs($nim, $kode_mk, $nilai)
{
    global $mysqli;
    $result = mysqli_query($mysqli, "INSERT INTO perkuliahan SET nim='$nim', kode_mk='$kode_mk', nilai='$nilai'");
    if ($result) {
        $response = array(
            'status' => 1,
            'message' => 'Nilai Mahasiswa Added Successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Nilai Mahasiswa Addition Failed.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function get_nim_kode_mk($nim, $kode_mk)
{
    global $mysqli;
    $query = "SELECT mahasiswa.nim, mahasiswa.nama, matakuliah.kode_mk, matakuliah.nama_mk, perkuliahan.nilai
            FROM mahasiswa
            JOIN perkuliahan ON mahasiswa.nim = perkuliahan.nim
            JOIN matakuliah ON perkuliahan.kode_mk = matakuliah.kode_mk
            WHERE mahasiswa.nim='$nim' AND matakuliah.kode_mk='$kode_mk'";
    $data = array();
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
        $data[] = $row;
    }
    $response = array(
        'status' => 1,
        'message' => 'Get Nilai Mahasiswa Successfully.',
        'data' => $data
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}


function update_nilai_mhs($nim, $kode_mk)
{
    global $mysqli;

    // Mengambil data dari payload JSON
    $input_data = file_get_contents("php://input");
    $put_data = json_decode($input_data, true);

    // Memeriksa apakah 'nilai' ada dalam data yang diterima
    if (isset($put_data["nilai"])) {
        $nilai = $put_data["nilai"];
        $result = mysqli_query($mysqli, "UPDATE perkuliahan SET nilai='$nilai' WHERE nim='$nim' AND kode_mk='$kode_mk'");
        
        // Menangani respons
        if ($result) {
            $response = array(
                'status' => 1,
                'message' => 'Nilai Mahasiswa Updated Successfully.'
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Nilai Mahasiswa Updation Failed.'
            );
        }
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Data nilai tidak ditemukan dalam permintaan.'
        );
    }

    // Mengirim respons ke klien
    header('Content-Type: application/json');
    echo json_encode($response);
}



function delete_nilai_mhs($nim, $kode_mk)
{
    global $mysqli;
    $query = "DELETE FROM perkuliahan WHERE nim='$nim' AND kode_mk='$kode_mk'";
    if (mysqli_query($mysqli, $query)) {
        $response = array(
            'status' => 1,
            'message' => 'Nilai Mahasiswa Deleted Successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Nilai Mahasiswa Deletion Failed.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
