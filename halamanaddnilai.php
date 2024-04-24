<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Nilai Mahasiswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 30px;
        }

        form {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Insert Nilai Mahasiswa</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="nim">NIM:</label>
        <input type="text" name="nim" id="nim" required>

        <label for="kode_mk">Kode Mata Kuliah:</label>
        <input type="text" name="kode_mk" id="kode_mk" required>

        <label for="nilai">Nilai:</label>
        <input type="number" name="nilai" id="nilai" min="0" max="100" required>

        <input type="submit" name="submit" value="Submit">
    </form>
    
    <?php

    if(isset($_POST['submit'])) {
        
        $nim = $_POST['nim'];
        $kode_mk = $_POST['kode_mk'];
        $nilai = $_POST['nilai'];

        $url = 'http://localhost/uts_psit/api.php';
        $ch = curl_init($url);
        
        $jsonData = array(
            'nim' =>  $nim,
            'kode_mk' =>  $kode_mk,
            'nilai' =>  $nilai,
        );

        $jsonDataEncoded = json_encode($jsonData);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 


        $result = curl_exec($ch);
        
        // Tangani hasil respons
        $result = json_decode($result, true);

        curl_close($ch);

        // Tampilkan return statusnya, apakah sukses atau tidak
        echo "<center><br>status :  {$result["status"]} "; 
        echo "<br>";
        echo "message :  {$result["message"]} "; 
        echo "<br><a href=index.php> OK </a></center>";
    }
    ?>
</body>
</html>
