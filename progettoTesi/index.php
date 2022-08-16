<?php require_once('config.php') ?>
<?php require_once( ROOT_PATH . '/includes/functions.php') ?>
<?php require_once( ROOT_PATH . '/includes/head_section.php') ?>
<?php 
$all = getAll();
$tab = getTable();

$selected_year = [];
$selected_tab= [];

foreach($all as $k => $val){
  if(strlen($val['year']) == 4 ){
    $year_arr_raw[] = $val['year'];
  }
}
$year_arr = array_unique($year_arr_raw);
rsort($year_arr);

if(isset($_POST['filtroAvanzato'])){
  $all = filtroAvanzatoAll($_POST);
  if(!empty($_POST['sito'])){
    $sito = $_POST['sito'];
  }else{
    $sito = '';
  }
  if(!empty($_POST['autore'])){
    $autore = $_POST['autore'];
  }else{
    $autore = '';
  }
  if(!empty($_POST['titolo'])){
    $titolo = $_POST['titolo'];
  }else{
    $titolo = '';
  }

  foreach($all as $k => $val){
    if(!empty($val['year'])){
      $selected_year_raw[] = $val['year'];
      $selected_year = array_unique($selected_year_raw);
    }
    if(!empty($val['table'])){
      $index = 0; 
      if($val['table'] == 'Balisage'){
        $index = 1;
      }
      else if($val['table'] == 'DSH'){
        $index = 2;
      }
      else if($val['table'] == 'JTEI'){
        $index = 3;
      }
      else if($val['table'] == 'Google scholar'){
        $index = 4;
      }
      
      $selected_tab_raw[$index] = $val['table'];
      $selected_tab = array_unique($selected_tab_raw);
    }
   
  }
    
}
if(isset($_POST['reset'])){
  $_POST = [];
  $all = getAll();
}


?>
<script> 
  $(document).ready(function() {
      <?php  require_once( ROOT_PATH . '/includes/datatable_config.php');?> 

      $('.selectpicker').selectpicker();
      $('#DA_A').hide();

      $('#annoDA').prop("disabled", true);
      $(".selectpicker[data-id='annoDA']").addClass("disabled"); 

      $('#annoA').prop("disabled", true);
      $(".selectpicker[data-id='annoDA']").addClass("disabled"); 

      $('#checkRange').click(function() {
        if($(this).prop("checked") == true) {      
          $('#DA_A').show();
          $('#annoDIV').hide();
          $('#anno').prop("disabled", true);
          $(".selectpicker[data-id='anno']").addClass("disabled");    
          
          $('#annoDA').prop("disabled", false);
          $(".selectpicker[data-id='annoDA']").removeClass("disabled"); 

          $('#annoA').prop("disabled", false);
          $(".selectpicker[data-id='annoA']").removeClass("disabled"); 
        }
        else if($(this).prop("checked") == false) {
          $('#DA_A').hide();
          $('#annoDIV').show()
          $('#anno').prop("disabled", false);
          $(".selectpicker[data-id='anno']").removeClass("disabled");

          $('#annoDA').prop("disabled", true);
          $(".selectpicker[data-id='annoDA']").addClass("disabled"); 

          $('#annoA').prop("disabled", true);
          $(".selectpicker[data-id='annoDA']").addClass("disabled");

        }
      });



    });
</script>
<style>
.logolink:hover { 
  text-decoration: none;
  color:orange;
  
   }
</style>
  <body>
   <!--navbar-->
   <div class="container-fluid">
    <?php require_once( ROOT_PATH . '/includes/navbar.php') ?>
   </div>
    <main role="main">
    <div class="hero-image" style="border-bottom:5px solid #007bff">
        <div class="hero-text">
            <img src="logowhite.png" style="width:300px;">
            <p style="text-align:justify">
            EasyScrape è una piattoforma web che aiuta la ricerca e la condivisione di articoli scientifici, inerenti alle Digital Humanities e riguarda, più nello specifico, il mondo del Digital Scholarly Editing.<br>
            La presenza di repository di articoli scientifici rappresenta un netto vantaggio per il ricercatore: diminusce il tempo di ricerca degli articoli nel web, dato che questi sono centralizzati un unico serbatorio di informazioni; inoltre si evita anche di replicare studi già compiuti da altri studiosi, ottimizzando quindi i processi di ricerca.  
            <br>Grazie alla tecnica dello scrape, questo sito web raccoglie più di 1000 link ad articoli scientifici, estratti da 4 serbatori: Balisage, DSH, JTEI e Google Scholar.
          </p>
        </div>
        </div>
     </div>
    </div> 

    <div class="container-fluid" style="margin-bottom:2%;border-bottom:5px solid #007bff;background-image: linear-gradient(to bottom, #d6eafa, white);">
        <div class="row">
            <div class="col-sm-6 text-center logo" style="padding:20px;">
              <a class="logolink" href="balisage.php?tiporicerca=T">
                  <h2 style="text-align:center;font-size:70px;padding:50px;">BALISAGE</h2>
              </a>
            </div>
          
            <div class="col-sm-6 text-center logo" style="padding:10px">
              <a class="logolink" href="jtei.php">
                <h2 style="text-align:center;font-size:70px;padding:50px;">JTEI</h2>
              </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 text-center logo" style="padding:20px">
              <a class="logolink" href="dsh.php?tiporicerca=IS">
                <h2 style="text-align:center;font-size:70px;padding:50px;">DSH</h2>
              </a>
            </div>

            <div class="col-sm-6 text-center logo" style="padding:20px">
              <a class="logolink" href="googleScholar.php">
                <h2 style="text-align:center;font-size:70px;padding:50px;">Google Scholar</h2>
              </a>
            </div>
        </div>
        </div>
    </div>

      <div class="col-md-12" >
            <div class="row">
              <form action="index.php" method="POST" style="display:block;margin-left:auto;margin-right:auto;padding:20px; border:2px solid #007bff">
                <div class="form-row"  style="margin-top:1%;">
                    <div class="col">
                      <label>Sito</label>
                      <select  name="sito[]" id="sito" class="form-control selectpicker" required multiple>                                   
                          <?php foreach($tab as $tab_id => $val){ ?>
                            <option value="<?= $tab_id ?>"
                            <?php 
                            if(isset($_POST['filtroAvanzato'])){
                              if(array_key_exists($tab_id, $selected_tab)){
                                print 'selected';
                              }
                            }
                          
                          ?>
                            ><?= $val ?></option>
                          <?php } ?>                              
                        </select>  
                    </div>
                    <div class="col">
                      <label>Autore</label>
                      <input type="text" name="autore" id="autore" class="form-control" placeholder="Inserisci Keyword" value="<?php if(isset($_POST['filtroAvanzato'])){print $autore;} ?>" autocomplete="off">
                    </div>
                    <div class="col">
                      <label>Titolo</label>
                      <input type="text" name="titolo" id="titolo" class="form-control"  autocomplete="off" placeholder="Inserisci Keyword" value="<?php if(isset($_POST['filtroAvanzato'])){print $titolo;}?>">
                    </div> 
                </div>
                <div class="form-row" style="margin-top:3%;margin-bottom:3%;">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkRange" >
                    <label class="form-check-label" for="checkRange">Abilita ricerca per range </label>
                </div>
              </div>
                <div class="form-row"  style="margin-top:1%;">
                  <div class="col" id="annoDIV">
                      <label>Anno</label>              
                        <select  name="anno[]" id="anno" class="form-control selectpicker" multiple>                                   
                          <?php foreach($year_arr as $k=>$val){ ?>
                            <option value="<?= $val ?>"
                            <?php 
                                if(isset($_POST['filtroAvanzato'])){
                                  if( in_array($year_arr[$k],$selected_year)){
                                    print 'selected';
                                  }
                                }
                            ?>
                            ><?= $val ?></option>
                          <?php } ?>                              
                        </select>     
                  </div>

                  <div class="col" id="DA_A">
                            <label>Da:</label>
                            <select  name="annoDA" id="annoDA" class="form-control selectpicker"> 
                                <option value=""></option>                            
                                    <?php $raw = array_reverse($year_arr);
                                    foreach($raw as $k=>$val){ ?>
                                      <option value="<?= $val ?>"
                                      <?php 
                                          if(isset($_POST['filtroAvanzato'])){
                                            if( in_array($year_arr[$k],$selected_year)){
                                              print 'selected';
                                            }
                                          }
                                      ?>
                                      ><?= $val ?></option>
                                    <?php } ?>                               
                            </select>  
                          
                            <label>A:</label>
                            <select  name="annoA" id="annoA" class="form-control selectpicker">  
                              <option value=""></option>                           
                              <?php foreach($year_arr as $k=>$val){ ?>
                                      <option value="<?= $val ?>"
                                      <?php 
                                          if(isset($_POST['filtroAvanzato'])){
                                            if( in_array($year_arr[$k],$selected_year)){
                                              print 'selected';
                                            }
                                          }
                                      ?>
                                      ><?= $val ?></option>
                                    <?php } ?>                           
                              </select>                             
                  </div>
                </div>
                
        
                <button type="submit" name="filtroAvanzato" id="filtroAvanzato" class="btn btn-primary" style="margin-left:2%;margin-top:3%;float:right;width:30%;">Filtra</button>
                <button type="submit"  name="reset" id="reset" class="btn btn-secondary" style="margin-left:2%;margin-top:3%;float:right;">Resetta</button>
  
              </form>
            </div>                 
            </div>
          </div>
      <div class="col-md-11" style="display:block !important;margin-right:auto !important; margin-left:auto !important; margin-top:3%;">
        <!-- PROCEEDINGS TABLE --> 
        <div class="table-responsive-nomarginpadding">           
        <table id="myDatatable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>From</th> 
                <th>Author</th>  
                <th>Title</th>
                <th>Year</th>                  
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>From</th> 
                <th>Author</th>  
                <th>Title</th>
                <th>Year</th>                                                   
              </tr>
            </tfoot>
            <tbody>
            <?php foreach ( $all as $art_id=>$value ) {?>
            <tr>
                <td>
                  <?=  $value['table']  ?>
                </td>
                <td>
                  <?=  $value['author']  ?>
                </td>
                <td class="focus">
                  <a href="<?= $value['link'] ?>"> 
                    <?= $value['title'] ?>
                  </a>
                </td>
                <td>
                  <?=  $value['year']  ?>
                </td>
            </tr>
            <?php }?>
            </tbody>
          </table>                                      
      </div> 
      
    </main>
      <!-- FOOTER -->
      <?php require_once( ROOT_PATH . '/includes/footer.php') ?>
  </body>
</html>

    
  