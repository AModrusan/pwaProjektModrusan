<?php
session_start();
$poruka = "";
 $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'frankfurter';
    $conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Greška pri povezivanju s bazom: " . $conn->connect_error);
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: registracija.php");
    exit();
}

if (isset($_POST['nastavi']) && isset($_SESSION['korisnik'])) {
    if ($_SESSION['korisnik']['razina'] == 1) {
        header("Location: unos.php");
        exit();
    } else {
        $poruka = "Prijavljeni ste, ali nemate administratorska prava.";
    }
}

if (isset($_POST['register'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = password_hash($_POST['lozinka'], PASSWORD_DEFAULT);
    $razina = $_POST['razina'];

    $sql = "SELECT * FROM korisnici WHERE korisnicko_ime = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $korisnicko_ime);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $poruka = "Korisničko ime već postoji.";
        } else {
            $stmt->close();
    $stmt = $conn->prepare("INSERT INTO korisnici (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $ime, $prezime, $korisnicko_ime, $lozinka, $razina);

            if ($stmt->execute()) {
                $poruka = "Registracija uspješna!";
            } else {
                $poruka = "Greška: " . $stmt->error;
            }
        }
    }
}

if (isset($_POST['login'])) {
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];

    $stmt = $conn->prepare("SELECT * FROM korisnici WHERE korisnicko_ime = ?");
    $stmt->bind_param("s", $korisnicko_ime);
    $stmt->execute();
    $rezultat = $stmt->get_result();

    if ($rezultat->num_rows === 1) {
        $korisnik = $rezultat->fetch_assoc();
        if (password_verify($lozinka, $korisnik['lozinka'])) {
            $_SESSION['korisnik'] = $korisnik;
            if ($korisnik['razina'] == 1) {
                header("Location: unos.php");
                exit();
            } else {
                $poruka = "Prijava uspješna, ali nemate administratorska prava.";
            }
        } else {
            $poruka = "Pogrešna lozinka.";
        }
    } else {
        $poruka = "Korisnik ne postoji.";
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Antonijo Modrusan">
    <meta name="keywords"  content="sport, politics, news, german, Frankfurt, article">
    <title>Security</title>
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
  <main class="container adminEnd">

    <section>

            <?php if (isset($_SESSION['korisnik'])): ?>
        <div class="row">
            <h2 class="col-sm-12 text-center">Dobrodošao, <?= htmlspecialchars($_SESSION['korisnik']['korisnicko_ime']) ?>!</h2>
        </div>

        <div class="row">
            <p class="col-sm-12 text-center"><?= $poruka ?></p>
        </div>

            <form method="post">

                <div class="row adminRow">
                    <div class="col-sm-5"></div>
                    <input type="submit" name="nastavi" value="Nastavi kao <?= htmlspecialchars($_SESSION['korisnik']['korisnicko_ime']) ?>" class="btn-admin col-sm-2 text-center">
                    <div class="col-sm-5"></div>
                </div>
                
            </form>
            <div class="row adminRow">
                <div class="col-sm-5"></div>
                <a href="?logout=1" class="btn-admin col-sm-2 text-center">Odjavi se</a>
                <div class="col-sm-5"></div>
            </div>

            <?php else: ?>
                
        <div class="row">
            <h1 class="col-sm-12 text-center">Administracija</h1>
        </div>

        <div class="row">
            <h2 class="col-sm-12 text-center">Registracija i prijava</
        </div>

        <form method="post">

        <div class="row adminRow">
                <label for="ime" class="col-sm-4 endText">Ime: </label>
                <input type="text" name="ime" class="col-sm-4" required>

        </div>

        <div class="row adminRow">
                <label for="prezime" class="col-sm-4 endText">Prezime: </label>
                <input type="text" name="prezime" class="col-sm-4" required>
                <div class="col-sm-4"></div>
        </div>

        <div class="row adminRow">
                <label for="korisnicko_ime" class="col-sm-4 endText">Korisničko ime: </label>
                <input type="text" name="korisnicko_ime" class="col-sm-4" required>
                <div class="col-sm-4"></div>

        </div>

        <div class="row adminRow">
            <label for="lozinka" class="col-sm-4 endText">Lozinka: </label>
            <input type="password" name="lozinka" class="col-sm-4" required>
            <div class="col-sm-4"></div>
        </div>

        <div class="row adminRow">
            <label for="razina" class="col-sm-4 endText">Razina: </label>
            <input type="number" name="razina" class="col-sm-1" min="0" max="1" required>
            <label for="razina" class="col-sm-4 startText">(1=admin, 0=korisnik)</label>
            <div class="col-sm-3"></div>
        </div>

        <div class="row adminRow">
            <div class="col-sm-5"></div>
            <input type="submit" name="register" value="Registriraj se" class="btn-admin col-sm-2 text-center">
            <div class="col-sm-5"></div>
        </div>
        </form>

        <div class="row">
            <p class="col-sm-12 text-center"><?= $poruka ?></p>
        </div>

    </section>

    <section>
        <div class="row adminRow">
            <h2 class="col-sm-12 text-center">Prijava</h2>
        </div>

        <form method="post">

        <div class="row adminRow">
            <label for="korisnicko_ime" class ="col-sm-4 endText">Korisničko ime: </label>
            <input type="text" name="korisnicko_ime" class="col-sm-4" required>
            <div class="col-sm-4"></div>
        </div>

        <div class="row adminRow">
            <label for="lozinka" class ="col-sm-4 endText">Lozinka: </label>
            <input type="password" name="lozinka" class="col-sm-4" required>
            <div class="col-sm-4"></div>
        </div>

        <div class="row adminRow">
            <div class="col-sm-5"></div>
            <input type="submit" name="login" value="Prijavi se" class="btn-admin col-sm-2 text-center">
            <div class="col-sm-5"></div>
        </div>
            </form>
    </section>

    <?php endif; ?>


</main>

  <footer class="container-fluid">

    <div class="row">
      <h2 class="col-sm-12 text-center kings-footer">Frankfurter Allgemeine</h2>
    </div>

  </footer>

</body>

</html>