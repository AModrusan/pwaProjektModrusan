<!DOCTYPE html>
<html lang="hr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Antonijo Modrusan">
    <meta name="keywords"  content="sport, politics, news, german, Frankfurt, article">
    <title>Frankfurter allegmeine</title>
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
    if ($row = $result->fetch_assoc()){
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

    <section class="row">
      <div class="col-sm-3">
        <hr class="mainHr">
        <h3>POLITIK</h3>
      </div>

      <?php
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'frankfurter';

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Neuspješna konekcija: " . $conn->connect_error);
        }

        $sql = "SELECT naslov, podnaslov, uvod, image FROM clanci WHERE zanr LIKE 2 ORDER BY promjene DESC LIMIT 3";
        $result = $conn->query($sql);
        for ($i = 0; $i < 3; $i++) {
            if ($row = $result->fetch_assoc()) {
                echo ' <div class="col-sm-3">';
                echo ' <div class="imgCropper">';
                echo ' <img src="'.$row['image'].'" alt="">';
                echo ' </div>';
                echo ' <h4>'.$row['podnaslov'].'</h4>';
                echo ' <a href="vijest.php?naslov='.htmlspecialchars($row['naslov']).'">'.$row['naslov'].'</a>';
                echo ' <p class="noto-regular">'.$row['uvod'].'</p>';
                echo '</div>';
            }
        }

        $conn->close();
      ?>
    </section>

    <section class="row">

      <div class="col-sm-3">
        <hr class="mainHr">
        <h3>SPORT</h3>
      </div>

        <?php
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'frankfurter';
        
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Neuspješna konekcija: " . $conn->connect_error);
        }

        $sql = "SELECT naslov, podnaslov, uvod, image FROM clanci WHERE zanr LIKE 1 ORDER BY promjene DESC LIMIT 3";
        $result = $conn->query($sql);
        for ($i = 0; $i < 3; $i++) {
            if ($row = $result->fetch_assoc()) {
                echo '<div class="col-sm-3">';
                echo ' <div class="imgCropper">';
                echo '   <img src="'.$row['image'].'" alt="">';
                echo ' </div>';
                echo ' <h4>'.$row['podnaslov'].'</h4>';
                echo ' <a href="vijest.php?naslov='.htmlspecialchars($row['naslov']).'">'.$row['naslov'].'</a>';
                echo ' <p class="noto-regular">'.$row['uvod'].'</p>';
                echo '</div>';
            }
        }

        $conn->close();
      ?>

    </section>

  </main>

  <footer class="container-fluid">

    <div class="row">
      <h2 class="col-sm-12 text-center kings-footer">Frankfurter Allgemeine</h2>
    </div>

  </footer>

</body>

</html>