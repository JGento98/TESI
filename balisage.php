<?php
require_once('./config.php');
 require_once( ROOT_PATH . '/includes/functions.php');
 require_once( ROOT_PATH . '/includes/head_section.php');

$tipo_ricerca = $_GET['tiporicerca'];

$topic_arr = getTopic();
$year_arr = getYear();
$volume_arr = getVolume();

if(isset($_POST['tiporicerca'])){
  $tipo_ricerca = $_POST['tiporicerca'];
  if( $tipo_ricerca == 'P'){
    $result = getBalisageProceedings();
   
  }else if($tipo_ricerca == 'T'){
    $result = getBalisageTopics();
  }
}

if( $tipo_ricerca == 'P'){
  $result = getBalisageProceedings();
  
}else if($tipo_ricerca == 'T'){
  $result = getBalisageTopics();
}

if(isset($_POST['hidden_topic'])){
  $result = searchTopic($_POST['hidden_topic']);
}

if(isset($_POST['filtroAvanzato'])){
  $result = filtroAvanzato($_POST);
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

  foreach($result as $k => $val){
    if(!empty($val['art_topic'])){
      $selected_topic_raw[] = $val['art_topic'];
      $selected_topic = array_unique($selected_topic_raw);
    }
    if(!empty($val['art_year'])){
      $selected_year_raw[] = $val['art_year'];
      $selected_year = array_unique($selected_year_raw);
    }
    if(!empty($val['art_volume'])){
      $selected_volume_raw[] = $val['art_volume'];
      $selected_volume = array_unique($selected_volume_raw);
    }
  }
  
}
  if(isset($_POST['reset'])){
    $_POST = [];
    if( $tipo_ricerca == 'P'){
      $result = getBalisageProceedings();
     
    }else if($tipo_ricerca == 'T'){
      $result = getBalisageTopics();
    }
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
    /* 
      $('.label').click(function() {
        topic = $(this).val();
        //alert(topic);
        $.ajax({
              url: './rpc/gestione_art.php',
              type: 'post',
              data: {
                topic:topic,
                search:'true'
              },
              success: function(response){
               
              },
            });  
          
      });
      */
      
    });
</script>
<style>
  .focus:hover{
    background-color:lightyellow;
  }
  .badge{
    padding:10px;
    background-color:orange;
  }
  .focus a:hover{
   text-decoration: none !important;
  }
  main{
    background-image: url('./bg.jpg');
  }
  
</style>
<body>

<!--navbar-->
<?php require_once( ROOT_PATH . '/includes/navbar.php') ?>

<main role="main" id="main">
  <div class="container-fluid" id="bg" style="margin-top:10%;padding-left:0px">
      <div class="row">
         
          <div class="col-md-4 offset-1 " style="text-align:justify;margin-right:1%;" >
            <h3><a href="https://balisage.net/index.html">Balisage: Markup conference</a></h3>
                <p>Balisage è una conferenza annuale dedicata al markup e a tutte le tecnologie correlate, utili per strutturare e gestire le informazioni.
                </p>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="GET" style="margin-top:5%;border-top:1px solid lightgrey;border-bottom:1px solid lightgrey;">
                  <h5>Seleziona un criterio di ricerca</h5>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <select name="tiporicerca" id="tiporicerca" class="form-control">
                              <option value="T" <?php if( $tipo_ricerca == 'T'){print 'selected';}?>>Topic</option>
                              <option value="P" <?php if( $tipo_ricerca == 'P'){print 'selected';}?>>Anno</option>                           
                            </select>
                        </div>
                        <button style ="height:38px;"type="submit" class="btn btn-primary">Search</button>
                      </div>
                </form>
          </div> 
          <!-- FORM RICERCA --> 
          <div class="col-md-6">
            <div class="row">
                  <div class="col-md-10 offset-2" style="padding:20px;border:2px solid #007bff;border-radius:30px; background-color:white;">
                    <h4>Filtro avanzato</h4>
                        <form action="balisage.php?tiporicerca=<?= $_GET['tiporicerca']?>" method="POST">
                        <div class="form-row"  style="margin-top:1%;">
                            <div class="col">
                              <label>Autore</label>
                              <input type="text" name="autore" id="autore" class="form-control" autocomplete="off" placeholder="Inserisci nome o cognome" value="<?php if(isset($_POST['filtroAvanzato'])){print $autore;} ?>">
                            </div>
                          </div>
                          <div class="form-row"  style="margin-top:1%;">
                            <div class="col">
                              <label>Titolo</label>
                              <input type="text" name="titolo" id="titolo" class="form-control"  autocomplete="off" placeholder="Inserisci Keyword" value="<?php if(isset($_POST['filtroAvanzato'])){print $titolo;}?>">
                            </div>
                          </div>
                          <?php if( $_GET['tiporicerca'] == 'T'){?>
                            <div class="form-row"  style="margin-top:1%;">
                            <div class="col">
                              <label>Topic</label>
                              <select name="topic[]" id="topic" class="form-control selectpicker" multiple>
                                <?php foreach($topic_arr as $k => $val){?>
                                  <option value="<?= $val['art_topic'] ?>" 
                                  <?php 
                                    if(isset($_POST['filtroAvanzato'])){
                                      if(!empty($selected_topic)){
                                        if( in_array($val['art_topic'],$selected_topic)){
                                          print 'selected';
                                        }
                                      }
                                    }          
                                  ?>
                                  ><?= $val['art_topic']?></option>
                                <?php }?>
                              </select>   
                            </div>
                          </div>
                          <?php } ?>

                  
                      <div class="form-row"  style="margin-top:1%;">
                              <div class="col">
                                <label>Volume</label>                        
                                <select  name="volume[]" id="volume" class="form-control selectpicker" multiple>
                                  <?php foreach($volume_arr as $k => $val){?>
                                    <option value="<?= $val['art_volume'] ?>"
                                    <?php 
                                    if(isset($_POST['filtroAvanzato'])){
                                      if(!empty($selected_volume)){
                                      if( in_array($val['art_volume'],$selected_volume)){
                                        print 'selected';
                                      }
                                    }
                                  }
                                  
                                  ?>
                                    ><?= $val['art_volume']?></option>
                                  <?php }?> 
                                </select>  
                                <small style="color:red">NOTA: <i>il volume è strettamente legato con l'anno (Es. Vol3 corrisponde all'anno 2009)</i></small>                           
                              </div>
                            </div>
                         
                          <div class="form-row"  style="margin-top:1%;" id="annoDIV">
                            <div class="col-md-4">
                              <label>Anno</label>              
                                <select  name="anno[]" id="anno" class="form-control selectpicker" multiple>                                   
                                  <?php foreach($year_arr as $k => $val){?>
                                    <option value="<?= $val['art_year'] ?>"
                                    <?php 
                                    if(isset($_POST['filtroAvanzato'])){
                                      if(!empty($selected_volume)){
                                      if( in_array($val['art_year'],$selected_year)){
                                        print 'selected';
                                      }
                                    }
                                  }
                                  
                                  ?>
                                    ><?= $val['art_year'] ?></option>
                                  <?php }?>                              
                                </select>     
                             
                            </div>
                          </div>

                          <div class="form-row"  style="margin-top:1%;" id="DA_A">
                            <div class="col">
                              <label>Da:</label>
                              <select  name="annoDA" id="annoDA" class="form-control selectpicker">                             
                                  <?php foreach(array_reverse($year_arr) as $k => $val){?>
                                    <option value="<?= $val['art_year'] ?>"
                                    <?php 
                                    if(isset($_POST['filtroAvanzato'])){
                                      if(!empty($_POST['annoDA'])){
                                      if($val['art_year'] == $_POST['annoDA']){
                                        print 'selected';
                                      }
                                    }}
                                    ?>
                                    ><?= $val['art_year'] ?></option>
                                  <?php }?>                              
                                </select>  
                            </div>
                            <div class="col">
                              <label>A:</label>
                              <select  name="annoA" id="annoA" class="form-control selectpicker">  
                                <option value=""></option>                           
                                  <?php foreach($year_arr as $k => $val){?>
                                    <option value="<?= $val['art_year'] ?>"
                                    <?php 
                                    if(isset($_POST['filtroAvanzato'])){
                                      if(!empty($_POST['annoA'])){
                                      if($val['art_year'] == $_POST['annoA']){
                                        print 'selected';
                                      }
                                    }
                                  }
                                    ?>><?= $val['art_year'] ?></option>
                                  <?php }?>                              
                                </select>                             
                            </div>
                          </div>
                            
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="checkRange">
                              <label class="form-check-label" for="checkRange">Abilita ricerca per intervallo temporale </label>
                            </div>
                    
                            <button type="submit" name="filtroAvanzato" id="filtroAvanzato" class="btn btn-primary" style="margin-left:2%;margin-top:3%;float:right;width:30%;">Filtra</button>
                            <button type="submit"  name="reset" id="reset" class="btn btn-secondary" style="margin-left:2%;margin-top:3%;float:right;">Resetta</button>
  
                        </form>
                  </div>                 
            </div>
          </div>
           <!-- ----------- -->       
      </div>
          <div class="col-md-11" style="display:block !important;margin-right:auto !important; margin-left:auto !important; margin-top:3%;background-color:white;border-radius:10px;">
            <!-- PROCEEDINGS TABLE --> 
            <div class="table-responsive-nomarginpadding">           
              <?php if( $tipo_ricerca == 'T' || $tipo_ricerca == 'Filtrati' ){ ?>
                        <table id="myDatatable" class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th style="display:none;">#</th>
                                <th style="display:none;">Topic</th> 
                                <th>Author</th>  
                                <th>Title</th>
                                <th>Year</th>
                                <th>Volume</th>                    
                              </tr>
                            </thead>
                            <tfoot>
                              <tr>
                                <th style="display:none;">#</th>
                                <th style="display:none;">Topic</th> 
                                <th>Author</th>   
                                <th>Title</th>  
                                <th>Year</th>
                                <th>Volume</th>                                             
                              </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ( $result as $art_id=>$value ) {?>
                            <tr>
                                <td style="display:none;" class="hidden-xs">
                                  <?= $value['art_id'] ?>
                                </td>
                                <td style="display:none;">
                                  <?=  $value['art_topic']  ?>
                                </td>
                                <td>
                                  <?=  $value['art_author']  ?>
                                </td>
                                <td class="focus">
                                  <a href="<?= $value['art_link'] ?>"> 
                                    <?= $value['art_title'] ?>
                                  </a>
                                  <form action="balisage.php?tiporicerca=<?= $_GET['tiporicerca']?>" method="POST" >
                                    <input type="hidden" name="hidden_topic" value="<?=  $value['art_topic'] ?>">
                                    <button type="submit" name="<?=  $value['art_topic'].'_'.$art_id  ?>" id="<?=  $value['art_topic'].'_'.$art_id  ?>" class="btn badge "><?=  $value['art_topic']  ?></button>
                                  </form>
                                </td>
                                <td>
                                  <?=  $value['art_year']  ?>
                                </td>
                                <td>
                                  <?=  $value['art_volume']  ?>
                                </td>
                            </tr>
                            <?php }?>
                            </tbody>
                          </table>                     
                      <?php }else if( $tipo_ricerca == 'P' || $tipo_ricerca == 'Filtrati') {?>
              
                      <table id="myDatatable" class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th style="display:none;">#</th>
                                <th>Author</th>
                                <th>Title</th>
                                <th>Year</th>
                                <th>Volume</th>                         
                              </tr>
                            </thead>
                            <tfoot>
                              <tr>
                                <th style="display:none;">#</th>
                                <th>Author</th>
                                <th>Title</th>
                                <th>Year</th>
                                <th>Volume</th>                                                
                              </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ( $result as $art_id=>$value ) {?>
                            <tr>
                                <td style="display:none;" class="hidden-xs">
                                  <?= $value['art_id'] ?>
                                </td>
                                <td>
                                  <?= $value['art_author']  ?>
                                </td>
                                <td  class="focus">
                                  <a href="<?= $value['art_link'] ?>"> 
                                      <?= $value['art_title'] ?>
                                  </a>
                                </td>
                                <td>
                                  <?= $value['art_year']  ?>
                                </td>
                                <td>
                                  <?= $value['art_volume'] ?>
                                </td>
                            </tr>
                            <?php }?>
                            </tbody>
                          </table>   
                        <?php }?> 
            </div> 
            <div class="row" style="height:40px;"></div> 
          </div>
  </div>
  
<!-- FOOTER -->

</main>
<?php require_once( ROOT_PATH . '/includes/footer.php') ?>
</body>

</html>   