<!DOCTYPE html>
<html lang="hr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Antonijo Modrusan">
    <meta name="keywords"  content="sport, politics, news, german, Frankfurt, article">
    <title>Vijest</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
      <?php
      if (isset($_GET['naslov'])) {
          $naslov = $_GET['naslov'];
          $host = 'localhost';
          $username = 'root';
          $password = '';
          $database = 'frankfurter';
          
          $conn = new mysqli($host, $username, $password, $database);
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }
          $stmt = $conn->prepare("SELECT promjene, uvod, image, tekst, zanr FROM clanci WHERE naslov = ?");
          $stmt->bind_param("s", $naslov);
          $stmt->execute();
          $stmt->bind_result($promjene, $uvod, $image, $tekst, $zanr);
          if ($stmt->fetch()) {
              $aktualizirano = date("d.m.Y", strtotime($promjene));
          }
      }
    ?>

    <?php
      $host = 'localhost';
      $username = 'root';
      $password = '';
      $database = 'frankfurter';
      $conn = new mysqli($host, $username, $password, $database);
      if ($conn->connect_error) {
        die("Neuspješna konekcija: " . $conn->connect_error);
      }
      $sql = "SELECT naslov FROM clanci WHERE zanr LIKE 2 ORDER BY promjene DESC LIMIT 1";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $naslovPolitika = $row['naslov'];
      $sql = "SELECT naslov FROM clanci WHERE zanr LIKE 1 ORDER BY promjene DESC LIMIT 1";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $naslovSport = $row['naslov'];
      $conn->close();
    ?>

    <header class="container">

    <nav class="row">
      <div class="col-sm-3 startText">
        <a href="index.php">HOME</a>
      </div>
      <div class="col-sm-3 centerText">
        <a href="vijest.php?naslov=<?= htmlspecialchars($naslovPolitika) ?>" style="<?php if ($zanr == '2') echo 'text-decoration: underline;'; ?>">POLITIK</a>
      </div>
      <div class="col-sm-3 centerText">
        <a href="vijest.php?naslov=<?= htmlspecialchars($naslovSport) ?>" style="<?php if ($zanr == '1') echo 'text-decoration: underline;'; ?>">SPORT</a>
      </div>
      <div class="col-sm-3 endText">
        <a href="unos.php">ADMINISTRACIJA</a>
      </div>
    </nav>

    <div class="row">
      <div class="col-sm-12">
        <hr>
      </div>
    </div>

    <div class="row">
      <h1 class="col-sm-12 text-center kings-header">Frankfurter Allgemeine</h1>
    </div>
    
  </header>

  <main class="container">

    <div class="row">

      <div class="col-sm-2"></div>
      <h3 class="col-sm-8"><?= $naslov ?></h3>
      <div class="col-sm-2"></div>

      <div class="col-sm-2"></div>
      <h4 class="col-sm-8">"<?= "Aktualizirano " . $aktualizirano ?>"</h4>
      <div class="col-sm-2"></div>

    </div>

    <div class="row">

      <div class="col-sm-1"></div>
      <img src="<?= htmlspecialchars($image) ?>" alt="" class="col-sm-10 img-fluid">
      <div class="col-sm-1"></div>

    </div>

    <div class="row">

      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <h5><?= htmlspecialchars($uvod) ?></h5>
      </div>
      <div class="col-sm-2"></div>

    </div>

    <div class="row">

      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <p class="vijestText"><span style="font-size: 40px;"><?= strtoupper(substr($tekst, 0, 1)) ?></span><?= nl2br(substr($tekst, 1)) ?></p>
      </div>
      <div class="col-sm-2"></div>

    </div>

  </main>

  <footer class="container-fluid">

    <div class="row">
      <h2 class="col-sm-12 text-center kings-footer">Frankfurter Allgemeine</h2>
    </div>

  </footer>

</body>

</html>

<!--

-->