<!DOCTYPE html>
<html lang="hr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Antonijo Modrusan">
    <meta name="keywords"  content="sport, politics, news, german, Frankfurt, article">
    <title>Ogledni primjer</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

  <header class="container">

    <nav class="row">
      <div class="col-sm-3 startText">
          <a href="index.html">HOME</a>
      </div>
      <div class="col-sm-3 centerText">
          <a href="">POLITIK</a>
      </div>
      <div class="col-sm-3 centerText">
          <a href="">SPORT</a>
      </div>
      <div class="col-sm-3 endText">
          <a href="unos.html">ADMINISTRACIJA</a>
      </div>
    </nav>

    <div class="row">
      <div class="col-sm-12">
        <hr class="headerHr">
      </div>
    </div>

    <div class="row">
      <h1 class="col-sm-12 text-center kings-header">Frankfurter Allgemeine</h1>
    </div>
    
  </header>

    <main class="container">

        <?php
        $fileName = $_FILES['datoteka']['name'];
        $fileTmpName = $_FILES['datoteka']['tmp_name'];

        if (isset($_POST['naslov']) && isset($_POST['podnaslov']) && isset($_FILES['datoteka']) && isset($_POST['uvod']) && isset($_POST['tekst']) && isset($_POST['zanr'])) 
        {
            $naslov = $_POST['naslov'];
            $podnaslov = $_POST['podnaslov'];
            $uvod = $_POST['uvod'];
            $tekst = $_POST['tekst'];
            $zanr = $_POST['zanr'];

            $putanjaSlike = "images/" . $_FILES['datoteka']['name'];

            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'frankfurter';

            $conn = new mysqli($host, $username, $password, $database);

            if ($conn->connect_error) {
                die("Neuspješna konekcija: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("SELECT id FROM zanrovi WHERE naziv = ?");
            $stmt->bind_param("s", $zanr);
            $stmt->execute();
            $result = $stmt->get_result();
            $zanrId = $result->fetch_assoc();

            $stmt = $conn->prepare("INSERT INTO clanci ( naslov, podnaslov, image, uvod, tekst, promjene, zanr) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
            $stmt->bind_param("sssssi", $naslov, $podnaslov, $putanjaSlike, $uvod, $tekst, $zanrId['id']);
            if ($stmt->execute()) {
                error_log("Uspjesan unos clanka: $naslov"); 
                if (move_uploaded_file($fileTmpName, $putanjaSlike)) {
                    error_log("Slika uspjesno premjestena: $putanjaSlike");
                }
            } else {
                echo "Greška prilikom unosa članka: " . $stmt->error;
            }
            $stmt->close();
            $conn->close();

            echo'
            <div class="row">

      <div class="col-sm-2"></div>
      <h3 class="col-sm-8">'.$naslov.'</h3>
      <div class="col-sm-2"></div>

      <div class="col-sm-2"></div>
      <h4 class="col-sm-8">"'.date("d-m-Y").'"</h4>
      <div class="col-sm-2"></div>

    </div>

    <div class="row">

      <div class="col-sm-1"></div>
      <img src="'.$putanjaSlike.'" alt="" class="col-sm-10 img-fluid">
      <div class="col-sm-1"></div>

    </div>

    <div class="row">

      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <h5>'.$uvod.'</h5>
      </div>
      <div class="col-sm-2"></div>

    </div>

    <div class="row">

      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <p class="vijestText"><span style="font-size: 40px;">'.strtoupper(substr($tekst, 0, 1)).'</span>'.substr($tekst, 1).'</p>
      </div>
      <div class="col-sm-2"></div>

    </div>';
        } else {
            echo "Nisu poslani svi potrebni podaci.";
        }


    ?>

    </main>

    <footer class="container-fluid">

    <div class="row">
      <h2 class="col-sm-12 text-center kings-footer">Frankfurter Allgemeine</h2>
    </div>

  </footer>

</body>

</html>

<!--
<form action="upload.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>


if (isset($_FILES['fileToUpload'])) {
    // Get the file details
    $fileName = $_FILES['fileToUpload']['name'];
    $fileTmpName = $_FILES['fileToUpload']['tmp_name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileError = $_FILES['fileToUpload']['error'];
    $fileType = $_FILES['fileToUpload']['type'];

    // Define the upload directory
    $uploadDir = 'uploads/'; // Make sure the 'uploads' folder is writable

    // Set the destination file path
    $fileDestination = $uploadDir . basename($fileName);

    // Check if there are any upload errors
    if ($fileError === 0) {
        // Check the file size (optional)
        if ($fileSize <= 5000000) { // 5MB max
            // Move the uploaded file to the desired location on the server
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                echo "File uploaded successfully!";
            } else {
                echo "There was an error moving the file!";
            }
        } else {
            echo "The file is too large!";
        }
    } else {
        echo "There was an error uploading the file!";
    }
} else {
    echo "No file was uploaded!";
}
-->