<?php
  session_start();
?>
<!doctype html>
<html>
  <head>
    <title>Žebříčky</title>
    <?php include_once 'hlavicka.php'?>
  </head>
  <body>
    <div class="container">
      <?php include_once 'navigace.php' ?>
      <?php include_once 'vyhledavani.php' ?>

      <!-- OBSAH -->
      <div class="card">
        <h4 class="card-header bg-primary text-light">Žebříčky</h4>
        <div class="card-body">
          <!-- Seřezování -->
          <form class="order" id="order_form" action="zebricky.php" method="get">
            <select class="custom-select d-block ml-auto" id="order" name="order">
              <option selected>-- Seřadit podle --</option>
              <option value="rok_asc">Nejstarší</option>
              <option value="rok_desc">Nejnovější</option>
              <option value="cesky_nazev_asc">Název (A-Z)</option>
              <option value="cesky_nazev_desc">Název (Z-A)</option>
              <option value="prum_znamka_asc">Nejhůře hodnocené</option>
              <option value="prum_znamka_desc">Nejlépe hodnocené</option>
            </select>
          </form>
          <!-- Žebříčky -->
          <table class="table table-striped mt-2">
            <thead class="bg-primary text-light">
              <tr>
                <th id="zebricky_rok" scope="col">Rok vydání</th>
                <th id="zebricky_nazev" scope="col">Název filmu</th>
                <th id="zebricky_hodnoceni" scope="col">Hodnocení</th>
              </tr>
            </thead>
            <tbody>
              <?php include_once 'php/zebricky_vypis.php' ?>
            </tbody>
          </table>

        </div>
      </div>

      <?php include_once 'paticka.php'?>
      <!-- Optional JavaScript -->
      <script>
      // Seřazování tabulky
        $(document).ready(function() {
          $("#order").on("change", function() {
            $("#order_form").submit();
          });
        });

      </script>
    </div>
  </body>
</html>
