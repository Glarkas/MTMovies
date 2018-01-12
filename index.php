<?php
  session_start();
  include_once 'php/novinky.php';
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
      <?php include_once 'vyhledavani.php' ?>
      <div class="row">
        <div class="col col-md-9">
          <!--Novinky-->
          <div class="card">
            <h4 class="card-header bg-primary text-light">Novinky ze světa filmu</h4>
            <div class="card-body">
              <!--Kartička s novinkou-->
              <?php
                zobraz_novinku();
              ?>
            </div>
          </div>
        </div>
        <div class="col col-md-3">
          <!--Právě v kině-->
          <div class="card">
            <h4 class="card-header bg-primary text-light">Právě v kině</h4>
            <div class="card-body">
              <p>John Wick</p>
            </div>
          </div>

        </div>
      </div>


      <!-- Optional JavaScript -->
      <?php include_once 'paticka.php'?>
      
    </div>
  </body>
</html>
