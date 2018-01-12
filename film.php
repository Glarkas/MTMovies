<?php
  session_start();

  if (isset($_GET['id'])) {
    include 'php/dbh.php';

    $id_filmu = $_GET['id'];
    $id_uzivatele = (isset($_SESSION['id'])) ? $_SESSION['id'] : 0;

    //INFORMACE O FILMU
    $sql = "SELECT filmy.*, typ_filmu.typ
            FROM filmy
              INNER JOIN typ_filmu
                ON filmy.id_typu = typ_filmu.id_typu
            WHERE filmy.id_filmu='$id_filmu'";
    $result_info = mysqli_query($conn, $sql);
    $info = mysqli_fetch_assoc($result_info);

    //ŽÁNRY
    $sql = "SELECT zanry.zanr
            FROM zanry_filmu
              INNER JOIN zanry
                ON zanry_filmu.id_zanru = zanry.id_zanru
            WHERE id_filmu='$id_filmu'";
    $result_zanry = mysqli_query($conn, $sql);
    $pocet_zanru = mysqli_num_rows($result_zanry);

    //ZEMĚ PŮVODU
    $sql = "SELECT zeme.nazev_zeme
            FROM zeme_puvodu_filmu
              INNER JOIN zeme
                ON zeme_puvodu_filmu.id_zeme = zeme.id_zeme
            WHERE id_filmu='$id_filmu'";
    $result_zeme = mysqli_query($conn, $sql);
    $pocet_zemi = mysqli_num_rows($result_zeme);

    //HODNOCENÍ
    $sql = "SELECT COUNT(znamka) AS pocet_hodnoceni, FORMAT (AVG(hodnoceni.znamka), 1) AS prum_znamka
            FROM hodnoceni
            WHERE id_filmu='$id_filmu'";
    $result_hodnocení = mysqli_query($conn, $sql);
    $hodnoceni = mysqli_fetch_assoc($result_hodnocení);


    //MOJE HODNOCENÍ
    $sql = "SELECT *
            FROM hodnoceni
            WHERE id_uzivatele='$id_uzivatele' AND id_filmu='$id_filmu'";
    $result_moje_hodnoceni = mysqli_query($conn, $sql);
    $moje_hodnoceni = mysqli_fetch_assoc($result_moje_hodnoceni);

    //POSTAVY
    $sql = "SELECT postavy_ve_filmu.id_cloveka, postavy_ve_filmu.id_postavy,
                   lidi.krestni, lidi.prostredni, lidi.prijmeni,
                   postavy.postava
            FROM ((postavy_ve_filmu
            	INNER JOIN lidi ON postavy_ve_filmu.id_cloveka = lidi.id_cloveka)
                  INNER JOIN postavy ON postavy_ve_filmu.id_postavy = postavy.id_postavy)
            WHERE id_filmu='$id_filmu'";
    $result_postavy = mysqli_query($conn, $sql);

    //print_r($info);
  } else {
    header("Location: index.php");
  }
?>
<!doctype html>
<html>
  <head>
    <title><?= $info['cesky_nazev'] ?></title>
    <?php include_once 'hlavicka.php'?>
  </head>
  <body>
    <div class="container">
      <?php include_once 'navigace.php' ?>
      <?php include_once 'vyhledavani.php' ?>

      <!--OBSAH-->
      <div class="card">
        <h4 class="card-header bg-primary text-light">
          <?= $info['cesky_nazev'] ?><span class="text-muted"> (<?= $info['rok'] ?>)</span>
        </h4>
        <div class="card-body">
          <div class="grid">
            <div class="row">
              <!-- LEVÝ SLOUPEC -->
              <div class="col-auto">
                <img class="mb-4" src="<?= $info['img_source'] ?>">
                <ul id="film_info">
                  <li><span class="font-weight-bold">Český název: </span><?= $info['cesky_nazev'] ?></li>
                  <li><span class="font-weight-bold">Původní název: </span><?= $info['puvodni_nazev'] ?></li>
                  <hr>
                  <li>
                    <span class="font-weight-bold">Žánry: </span>
                    <?php
                      for ($i=1; $i <= $pocet_zanru; $i++) {
                        $zanry = mysqli_fetch_assoc($result_zanry);
                        echo $zanry['zanr'];
                        if ($i < $pocet_zanru) {
                          echo ", ";
                        }
                      }
                    ?>
                  </li>
                  <li><span class="font-weight-bold">Premiéra: </span><?= $info['rok'] ?></li>
                  <li><span class="font-weight-bold">Délka: </span><?= $info['delka'] ?> min.</li>
                  <li><span class="font-weight-bold">Původ: </span>
                    <?php
                      for ($i=1; $i <= $pocet_zemi; $i++) {
                        $zeme = mysqli_fetch_assoc($result_zeme);
                        echo $zeme['nazev_zeme'];
                        if ($i < $pocet_zemi) {
                          echo ", ";
                        }
                      }
                    ?>
                  </li>
                  <li><span class="font-weight-bold">Typ: </span><?= $info['typ'] ?></li>
                </ul>
              </div>
              <!-- PRAVÝ SLOUPEC -->
              <div class="col" id="film_pravy_sloupec">

                <!-- HODNOCENÍ -->
                  <div class="row mx-0 mb-3 align-items-center bg-primary">
                    <div class="col">
                      <div class="row">
                        <div class="col pt-2 text-center">
                          <h2 class="text-white">
                            <?= $hodnoceni['prum_znamka'] ?> <i class="fas fa-star text-warning"></i>
                          </h2>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col pb-2 text-center text-white">
                          <small>Hodnoceno: <?= $hodnoceni['pocet_hodnoceni'] ?>x</small>
                        </div>
                      </div>
                    </div>
                    <div class="col text-center">
                      <form onsubmit="return valid_hodnoceni_filmu()" action="php/hodnotit_film.php" method="post">
                        <select class="custom-select custom-select-sm m-1 rounded-0" id="hodnoceni" name="hodnoceni" <?php echo (isset($_SESSION['id'])) ? "" : "disabled" ?>>
                          <option class="text-muted" value="0">-- Hodnocení --</option>
                          <option class="text-dark" value="10" <?php if ($moje_hodnoceni['znamka'] == 10) echo "selected" ?> >(10) 11 z 10</option>
                          <option class="text-dark" value="9" <?php if ($moje_hodnoceni['znamka'] == 9) echo "selected" ?> >(9) Výborné</option>
                          <option class="text-dark" value="8" <?php if ($moje_hodnoceni['znamka'] == 8) echo "selected" ?> >(8) Velmi dobré</option>
                          <option class="text-dark" value="7" <?php if ($moje_hodnoceni['znamka'] == 7) echo "selected" ?> >(7) Dobré</option>
                          <option class="text-dark" value="6" <?php if ($moje_hodnoceni['znamka'] == 6) echo "selected" ?> >(6) Nadprůměrné</option>
                          <option class="text-dark" value="5" <?php if ($moje_hodnoceni['znamka'] == 5) echo "selected" ?> >(5) Průměrné</option>
                          <option class="text-dark" value="4" <?php if ($moje_hodnoceni['znamka'] == 4) echo "selected" ?> >(4) Podprůměrné</option>
                          <option class="text-dark" value="3" <?php if ($moje_hodnoceni['znamka'] == 3) echo "selected" ?> >(3) Špatné</option>
                          <option class="text-dark" value="2" <?php if ($moje_hodnoceni['znamka'] == 2) echo "selected" ?> >(2) Velmi špatné</option>
                          <option class="text-dark" value="1" <?php if ($moje_hodnoceni['znamka'] == 1) echo "selected" ?> >(1) Nejhorší</option>
                        </select>
                        <select class="custom-select custom-select-sm m-1 rounded-0" id="stav" name="stav" <?php echo (isset($_SESSION['id'])) ? "" : "disabled" ?>>
                          <option class="text-muted" value="0">-- Stav --</option>
                          <option class="text-success" value="1" <?php if ($moje_hodnoceni['id_stavu'] == 1) echo "selected" ?> >Dokončeno</option>
                          <option class="text-muted" value="2" <?php if ($moje_hodnoceni['id_stavu'] == 2) echo "selected" ?> >Plánuji</option>
                          <option class="text-primary" value="3" <?php if ($moje_hodnoceni['id_stavu'] == 3) echo "selected" ?> >Sleduji</option>
                          <option class="text-warning" value="4" <?php if ($moje_hodnoceni['id_stavu'] == 4) echo "selected" ?> >Odloženo</option>
                          <option class="text-danger" value="5" <?php if ($moje_hodnoceni['id_stavu'] == 5) echo "selected" ?> >Přerušeno</option>
                        </select>
                        <input type="hidden" name="id_filmu" value="<?= $id_filmu ?>">
                        <input type="hidden" name="id_uzivatele" value="<?= $id_uzivatele ?>">
                        <button class="btn btn-light btn-sm m-1" type="submit" name="pridat_do_filmlistu" <?php echo (isset($_SESSION['id'])) ? "" : "disabled" ?>>
                          <span>Odeslat</span><i class="fas fa-spinner fa-pulse ml-1 loading" id="pridat_do_filmlistu_loading"></i>
                        </button>
                      </form>
                    </div>
                  </div>

                <!-- DĚJ -->
                <div class="row mb-3">
                  <div class="col">
                    <h5>Děj</h5>
                    <p><?= $info['popis'] ?></p>
                  </div>
                </div>

                <!-- POSTAVY -->
                <div class="row mb-3">
                  <div class="col">
                    <h5>Postavy</h5>
                    <table class="table table-striped">
                      <tbody>
                        <?php
                          while ($postavy = mysqli_fetch_assoc($result_postavy)) {
                            ?>
                            <tr>
                              <td>
                                <a href="lidi.php?id=<?= $postavy['id_cloveka'] ?>">
                                  <?php echo $postavy['krestni']." ".$postavy['prostredni']." ".$postavy['prijmeni'] ?>
                                </a>
                              </td>
                              <td class="text-right">
                                <a href="postavy.php?id=<?= $postavy['id_postavy'] ?>">
                                  <?= $postavy['postava'] ?>
                                </a>
                              </td>
                            </tr>
                            <?php
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- RECENZE -->
                <div class="row mb-3">
                  <div class="col">
                    <h5>Recenze</h5>
                  </div>
                </div>

                <!-- PODOBNÉ -->
                <div class="row mb-3">
                  <div class="col">
                    <h5>Podobné</h5>
                  </div>
                </div>


              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include_once 'paticka.php'?>
      <!-- Optional JavaScript -->
      <script src="js/select_barva.js"></script>
      <script src="js/valid_hodnoceni_filmu.js"></script>

    </div>
  </body>
</html>
