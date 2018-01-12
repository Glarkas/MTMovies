<nav class="navbar navbar-expand-md bg-primary navbar-dark">
  <!--Logo-->
  <a class="navbar-brand" href="index.php">
    <!--
    <i class="fas fa-film mr-1"></i>
    -->
    <i class="fab fa-d-and-d"></i>
    MTMovies
  </a>
  <!--Collapse button-->
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#main_nav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <!--Links-->
  <div class="collapse navbar-collapse" id="main_nav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Novinky</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Filmy</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="zebricky.php">Žebříčky</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Uživatelé</a>
      </li>
    </ul>
    <!--Prihlaseni tlacitko-->
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbar_prihlaseni" data-toggle="dropdown">
          <i class="fas fa-user mr-1"></i>
          <?php
            if (isset($_SESSION['id'])) {
              echo $_SESSION['prezdivka'];
            } else {
              echo "PŘIHLÁSIT";
            }
          ?>
        </a>
        <!--Prihlasovaci menu-->
          <div class="dropdown-menu dropdown-menu-right p-4">

            <?php
              if (isset($_SESSION['id'])) {
                //prihlaseny
                echo '<div class="text-center">
                        <a class="dropdown-item" href="profil.php">Zobrazit profil</a>
                        <a class="dropdown-item" href="muj_seznam_filmu.php">Můj seznam filmů</a>
                        <div class="dropdown-divider"></div>
                        <form onsubmit="return valid_odhlasit();" action="php/odhlasit.php" method="POST">
                          <input type="hidden" name="redirect" value="'.$_SERVER['REQUEST_URI'].'">
                          <button type="submit" name="odhlasit" class="btn btn-primary btn-danger">
                            <span>Odhlásit</span><i class="fas fa-spinner fa-pulse ml-1 loading" id="odhlasit_loading"></i>
                          </button>
                        </form>
                      </div>';
              } else {
                //neprihlaseny
                echo '<form onsubmit="return valid_prihlasit();" action="php/prihlasit.php" method="POST">
                        <div class="form-group">
                          <label for="dropdown_uzivatel" id="dropdown_label_uzivatel">Uživatelské jméno:</label>
                          <input class="form-control" id="dropdown_uzivatel" type="text" name="prezdivka" placeholder="">
                        </div>
                        <div class="form-group">
                          <label for="dropdown_heslo">Heslo:</label>
                          <input class="form-control" id="dropdown_heslo" type="password" name="heslo" placeholder="">
                        </div>
                        <div class="text-center">
                          <input type="hidden" name="redirect" value="'.$_SERVER['REQUEST_URI'].'">
                          <button type="submit" name="prihlasit" class="btn btn-primary">
                          <span>Přihlásit</span><i class="fas fa-spinner fa-pulse ml-1 loading" id="prihlasit_loading"></i>
                          </button>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="registrace.php">Vytvořit účet</a>
                        </div>
                      </form>';
              }
            ?>
          </div>

      </li>
    </ul>
  </div>
</nav>
