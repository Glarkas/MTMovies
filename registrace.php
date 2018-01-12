<?php
  session_start();
?>
<!doctype html>
<html>
  <head>
    <title>MTMovies</title>
    <?php include_once 'hlavicka.php'?>
  </head>
  <body>
    <div class="container">
      <?php include_once 'navigace.php' ?>
      <!--OBSAH-->
        <?php
          if (isset($_GET['r'])) {
            if ($_GET['r'] == "vytvoren") {
            echo '
              <div class="alert alert-success mt-4 text-center" id="vytvoren" role="alert">
                Váš účet byl úspěšně vytvořen!
              </div>';
            }
          }
        ?>
        <form class="card text-center mt-4 mx-auto" id="registrace" onsubmit="return valid_registrace()" action="php/registrovat.php" method="post">
        <h4 class="card-header bg-primary text-light">Vytvořit účet</h4>
        <div class="card-body">
          <div class="form-group">
            <label for="Uživatelské jméno">Uživatelské jméno</label>
            <input type="text" name="prezdivka" class="form-control text-center" id="prezdivka">
          </div>
          <div class="form-group">
            <label for="Heslo">Heslo</label>
            <input type="password" name="heslo" class="form-control text-center" id="heslo">
          </div>
          <div class="form-group">
            <label for="Heslo2">Heslo znovu</label>
            <input type="password" name="heslo2" class="form-control text-center" id="heslo2">
          </div>
          <button class="btn btn-primary" type="submit" name="registrovat">
            Vytvořit
            <i class="fas fa-spinner fa-pulse ml-1 loading" id="registrace_loading"></i>
          </button>

        </div>
      </form>
      <!-- Optional JavaScript -->
      <?php include_once 'paticka.php'?>
      <script src="js/valid_registrace.js"></script>
      <script>
        window.setTimeout(function(){
          $("#vytvoren").hide()
        }, 3000);
      </script>
    </div>
  </body>
</html>
