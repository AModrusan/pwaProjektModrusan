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

    
    <?php
      $host = 'localhost';
      $username = 'root';
      $password = '';
      $database = 'frankfurter';
      $naslovPolitika = '';
      $naslovSport = '';
      $conn = new mysqli($host, $username, $password, $database);
      if ($conn->connect_error) {
        die("Neuspješna konekcija: " . $conn->connect_error);
      }
      $sql = "SELECT naslov FROM clanci WHERE zanr LIKE 2 ORDER BY promjene DESC LIMIT 1";
      $result = $conn->query($sql);
      if($row = $result->fetch_assoc()){
        $naslovPolitika = $row['naslov'];
      }
      $sql = "SELECT naslov FROM clanci WHERE zanr LIKE 1 ORDER BY promjene DESC LIMIT 1";
      $result = $conn->query($sql);
      if($row = $result->fetch_assoc()){
        $naslovSport = $row['naslov'];
      }
      $conn->close();
    ?>
    <nav class="row">
      <div class="col-sm-3 startText">
        <a href="index.php">HOME</a>
      </div>
      <div class="col-sm-3 centerText">
        <a href="vijest.php?naslov=<?= htmlspecialchars($naslovPolitika) ?>">POLITIK</a>
      </div>
      <div class="col-sm-3 centerText">
        <a href="vijest.php?naslov=<?= htmlspecialchars($naslovSport) ?>">SPORT</a>
      </div>
      <div class="col-sm-3 endText">
        <a href="registracija.php">ADMINISTRACIJA</a>
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
        if (!empty($_POST['naslov']) && !empty($_POST['podnaslov']) && isset($_FILES['datoteka']) && !empty($_POST['uvod']) && !empty($_POST['tekst']) && !empty($_POST['zanr']))
        {
            $fileName = $_FILES['datoteka']['name'];
            $fileTmpName = $_FILES['datoteka']['tmp_name'];
            $naslov = $_POST['naslov'];
            $podnaslov = $_POST['podnaslov'];
            $uvod = $_POST['uvod'];
            $tekst = $_POST['tekst'];
            $zanr = $_POST['zanr'];

            $putanjaSlike = "images/" . $fileName;

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
            try{
              $stmt->execute();
                if (move_uploaded_file($fileTmpName, $putanjaSlike)) {
                    error_log("Slika uspjesno premjestena: $putanjaSlike");
                }
            }
            catch (Exception $e) {
                error_log("Greška prilikom unosa članka: " . $e->getMessage());
                echo '<p class="col-sm-12 text-center">Došlo je do greške prilikom unosa članka.</p>';
            }
            $stmt->close();
            $conn->close();

            echo'
                <div class="row">

          <div class="col-sm-2"></div>
          <h3 class="col-sm-8">'.$naslov.'</h3>
          <div class="col-sm-2"></div>

          <div class="col-sm-2"></div>
          <h4 class="col-sm-8">Aktualizirano '.date("d.m.Y.").'</h4>
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
            <p class="vijestText"><span style="font-size: 40px;">'.strtoupper(substr($tekst, 0, 1)).'</span>'.nl2br(substr($tekst, 1)).'</p>
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