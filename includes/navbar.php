<header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color:black;border-bottom:3px solid #007bff">
        <a class="navbar-brand" href="index.php">
          <img src="logowhite.png" style="width:100px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">

            <li class="nav-item mr-sm-2">
              <a class="nav-link" href="balisage.php?tiporicerca=T" >Balisage</a>
            </li>
            <li class="nav-item mr-sm-2">
              <a class="nav-link" href="dsh.php?tiporicerca=IS">DSH</a>
            </li>
            <li class="nav-item mr-sm-2">
              <a class="nav-link" href="jtei.php">JTEI</a>
            </li>
            <li class="nav-item mr-sm-2">
              <a class="nav-link" href="googleScholar.php">Google Scholar</a>
            </li>
           
          <?php if(isset($_SESSION['user'])) {?>
            <li class="nav-item mr-sm-2">
              <a class="nav-link" href="<?= BASE_URL ?>admin/admin.php">Admin Area</a>
            </li>
            <li  class="nav-item  ml-auto">
            <a id="logout" class="nav-link" href="logout.php">Logout</a>
            </li>
          <?php }else{?> 
            <li  class="nav-item  ml-auto">
              <a id="logout" class="nav-link" href="login.php">Login</a>
            </li>
          <?php }?> 
          </ul>
          
        </div>
      </nav>
    </header>