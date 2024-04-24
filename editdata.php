<?php
if(isset($_POST['submit']))
{    
    $nim = $_POST['nim']; 
    $kode_mk = $_POST['kode_mk']; 
    $nilai = $_POST['nilai']; 


    $url = 'http://localhost/uts_psit/api.php?nim=' . $nim . '&kode_mk=' . $kode_mk;
    $ch = curl_init($url);


    $jsonData = array(
        'nilai' => $nilai 
    );

    $jsonDataEncoded = json_encode($jsonData);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

    $result = curl_exec($ch);
    $result = json_decode($result, true);
    curl_close($ch);

    if ($result !== false) {
        if(isset($result["status"]) && $result["status"] == 1) {
            echo "<center><br>status :  {$result["status"]} "; 
            echo "<br>";
            echo "message :  {$result["message"]} "; 
            echo "<br><a href=index.php> OK </a>";
        } else {
            echo "Gagal mengupdate data: " . $result["message"];
        }
    } else {
        echo "Gagal melakukan permintaan ke server.";
    }
}
?>
