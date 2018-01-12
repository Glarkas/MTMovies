<?php
  session_start();

  if (isset($_GET['id'])) {
    include_once 'php/dbh.php';
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "SELECT * FROM novinky WHERE id_novinky='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
  }
  else {
    header("Location: index.php?problemseclankem");
  }
?>

<!doctype html>
<html>
  <head>
    <title><?= $row['titulek'] ?></title>
    <?php include_once 'hlavicka.php'?>
  </head>
  <body>
    <div class="container">
      <?php include_once 'navigace.php' ?>
      <?php include_once 'vyhledavani.php'?>
      <!--OBSAH-->
      <div class="card">
        <h4 class="card-header bg-primary text-light"><?php echo $row['titulek']; ?></h4>
        <div class="card-body">
          <p class="card-text"><?php echo nl2br($row['novinka']); ?></p>
          <div class="row">
            <div class="col">
              <small class="text-muted float-left"><?php echo $row['autor']; ?></small>
            </div>
            <div class="col">
              <small class="text-muted float-right"><?php echo $row['datum']; ?></small>
            </div>
          </div>

          <hr>
          <h5>Komentáře</h5>
          <form action="php/pridat_komentar.php" onsubmit="return valid_komentar()" method="post">
            <fieldset <?php if (!isset($_SESSION['id'])) { echo "disabled";} ?>>
              <input type="hidden" name="id_autora_komentare" value="<?php echo $_SESSION['id'] ?>">
              <input type="hidden" name="prezdivka_autora_komentare" value="<?php echo $_SESSION['prezdivka'] ?>">
              <input type="hidden" name="id_clanku" value="<?php echo $id ?>">
              <textarea class="form-control mb-2" id="text_komentare" name="text_komentare" placeholder="<?php if (!isset($_SESSION['id'])) {echo "Pro psaní musíte být přihlášen...";} else {echo "Napište komentář...";} ?>" rows="3"></textarea>
              <button type="submit" name="pridat_komentar" class="btn btn-primary mb-5 ps">
                Přidat
                <i class="fas fa-spinner fa-pulse ml-1 loading" id="pridat_komentar_loading"></i>
              </button>


          </fieldset>
          </form>

          <ul class="list-unstyled" id="clanek_komentare">
            <?php include_once 'php/zobrazit_komentare.php' ?>

          </ul>
          <?php
          $sql = "SELECT * FROM komentare WHERE id_novinky='$id'";
          $result = mysqli_query($conn, $sql);
          $pocet_komentaru = mysqli_num_rows($result);
          if ($pocet_komentaru > 5){
            echo '
            <div id="tlac_zob_star_kom">
            <button class="btn btn-primary rounded-circle d-block mx-auto" type="button" id="zobraz_starsi_komentare">
              <i class="fas fa-angle-down"></i>
            </button>
            </div>';
          }
          ?>

        </div>
      </div>

      <!-- Optional JavaScript -->
    </div>
    <?php include_once 'paticka.php'?>
    <script src="js/potvrzeni_odstraneni.js"></script>
    <script src="js/valid_komentar.js"></script>
    <!-- Zobrazení starších komentářů -->
    <script>
      $(document).ready(function(){
        <?php
        if (isset($_SESSION['id'])) {
          echo "var id_uzivatele = ".$_SESSION['id'].";";
        }
        ?>
        var id_clanku = <?= $id ?>;
        var pocet_komentaru = 5;

        $("#zobraz_starsi_komentare").click(function(){
          pocet_komentaru = pocet_komentaru + 5;
          $("#clanek_komentare").load("php/zobrazit_starsi_komentare.php", {
            novy_pocet_komentaru: pocet_komentaru,
            id_clanku: id_clanku,
            <?php
              if (isset($_SESSION['id'])) {
                echo "id_uzivatele: id_uzivatele";
              } else {
                echo "id_uzivatele: null";
              }
            ?>
          });
        });
      });
    </script>

  </body>
</html>
