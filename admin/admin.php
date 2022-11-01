<?php
  require_once('../config.php');
  require_once( ROOT_PATH . '/includes/functions.php');


  if(!isset($_SESSION['user']['us_is']) && !isset($_SESSION['user']['username']) && !isset($_SESSION['user']['password']) ){
      header('location:'. BASE_URL .'index.php');
      exit(0);
  }
  // log user out if logout button clicked
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
  }
  $last_update_baliP =  getLogs(1,'P');
  $last_update_baliT =  getLogs(1,'T');
  $last_update_DSHis =  getLogs(2,'IS');
  $last_update_DSHad =  getLogs(2,'AD');
  $last_update_jtei  =  getLogs(3,'');
  $last_update_GS    =  getLogs(4,'');

  //BALISAGE
      if(isset($_POST['deleteProceedings'])){
          $delP = deleteAllProceedings();
      }
      if(isset($_POST['deleteTopics'])){
          $delT = deleteAlltopics();
      }
  //DSH  
      if(isset($_POST['deleteAdvArt'])){
          $delI = delete_advance_articles();
      }
      if(isset($_POST['deleteIssues'])){
        $delI = delete_all_Issues();
    }
    //JTEI
      if( isset($_POST['deleteJTEIArt'])){
        $del = delete_JTEI_Issues();
      }
     //GOOGLE SCHOLAR
    if( isset($_POST['deleteDefaultArt_GS'])){
        $deleteDefaultGS = delete_all_GS();
    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
  

    <title>EasyScrape</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.js" integrity="sha512-cEgdeh0IWe1pUYypx4mYPjDxGB/tyIORwjxzKrnoxcif2ZxI7fw81pZWV0lGnPWLrfIHGA7qc964MnRjyCYmEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </head>
  <style>
    .leftNav{
        margin-top:2%;  
    }
    #logout{
      color:red !important;
      font-size:larger;
    }
    .leftNav-item{
        padding:10px;
        font-size: 23px;
        margin-bottom:1px solid lightgrey;
        
    }
    .leftNav-item a{
        color:white;
    }
    .leftNav-item a:hover{
     color:#007bff !important;
     text-decoration: none;
    }
    .author {color: #007bff;float:right;margin-top:4%;} 
    .btn{
      opacity: 0.9;
      font-family:Verdana, Geneva, Tahoma, sans-serif;
    }
    .btn:hover {
      box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
      opacity: 1
    }
    a:hover{
      text-decoration: none;
      color:#dc3545;   
    }
    #container-spinner {
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1060;
      width: 100vw;
      height: 100vh;
    }
    #spinner img{
      opacity: 1;
    }
    .spinner-center {
      position: fixed;
      top: 20%;
      left: 39%;
      z-index: 9999;
    }
  </style>
  <body>
   <script>
    function openModal(){
      $("#myModal").modal('show');
    }
    function ErrorModal(){
      $("#ErrorModal").modal('show');
      window.location.reload();
    }
    
      $(document).ready(function(){
        $("#loaderBalisage").hide();
        $("#loaderdsh").hide();
        $("#loaderjtei").hide();
        $("#loaderGS").hide();
      //ADD
    //BALISAGE ADD
          $("#addProceedings").click(function(){
            var hiddenBali = $('#tabBali').val();
            $.ajax({
              url: './rpc/manage_articles.php',
              type: 'post',
              data: {
                tab:hiddenBali,
                type:'P'
              },
              beforeSend: function(){
                $('#spinner').show(); 
                $('#container-spinner').show(); 
                $('#mainDIV').css('opacity', 0.1);
              },
              success: function(response){
                openModal();
                
              },
              complete:function(data){
                $('#spinner').hide(); 
                $('#container-spinner').hide(); 
                $('#mainDIV').css('opacity', 1);
              }
            });        
          });

          $("#addTopic").click(function(){
            var hiddenBali = $('#tabBali').val();
            $.ajax({
              url: './rpc/manage_articles.php',
              type: 'post',
              data: {
                tab:hiddenBali,
                type:'T'
              },
              beforeSend: function(){
                $('#spinner').show(); 
                $('#container-spinner').show(); 
                $('#mainDIV').css('opacity', 0.1);
              },
              success: function(response){
                openModal();
              },
              complete:function(data){
                $('#spinner').hide(); 
                $('#container-spinner').hide(); 
                $('#mainDIV').css('opacity', 1);
              }
            });        
          });
    //DSH ADD
          $("#addAdvArt").click(function(){
            var hiddenDSH = $('#tabdsh').val();
            $.ajax({
              url: './rpc/manage_articles.php',
              type: 'post',
              data: {
                tab:hiddenDSH,
                type:'AD'
              },
              beforeSend: function(){
                $('#spinner').show(); 
                $('#container-spinner').show(); 
                $('#mainDIV').css('opacity', 0.1);
              },
              success: function(response){
                openModal();
              },
              complete:function(data){
                $('#spinner').hide(); 
                $('#container-spinner').hide(); 
                $('#mainDIV').css('opacity', 1);
              }
            });        
          });
          $("#addIssues").click(function(){
           var hiddenDSH = $('#tabdsh').val();
            $.ajax({
              url: './rpc/manage_articles.php',
              type: 'post',
              data: {
                tab:hiddenDSH,
                type:'IS'
              },
              beforeSend: function(){
                $('#spinner').show(); 
                $('#container-spinner').show(); 
                $('#mainDIV').css('opacity', 0.1);
              },
              success: function(response){
                openModal();
              },
              complete:function(data){
                $('#spinner').hide(); 
                $('#container-spinner').hide(); 
                $('#mainDIV').css('opacity', 1);
              }
            });        
          });
    //JTEI ADD
          $("#addJTEIArt").click(function(){
                var hiddenDSH = $('#tabjtei').val();
      
                  $.ajax({
                    url: './rpc/manage_articles.php',
                    type: 'post',
                    data: {
                      tab:hiddenDSH,
                    },
                    beforeSend: function(){
                      $('#spinner').show(); 
                      $('#container-spinner').show(); 
                      $('#mainDIV').css('opacity', 0.1);
                    },
                    success: function(response){
                      openModal();
                    },
                    complete:function(data){
                      $('#spinner').hide(); 
                      $('#container-spinner').hide(); 
                      $('#mainDIV').css('opacity', 1);
                    }
                  });        
                });
    //GS ADD
          $("#addDefaultArtGS").click(function(){
                var hiddenDSH = $('#tabGS').val();
                  $.ajax({
                    url: './rpc/manage_articles.php',
                    type: 'post',
                    data: {
                      tab:hiddenDSH,
                      type:'DEFAULT'
                    },
                    beforeSend: function(){
                      $('#spinner').show(); 
                      $('#container-spinner').show(); 
                      $('#mainDIV').css('opacity', 0.1);
                    },
                    success: function(response){
                      openModal();
                    },
                    complete:function(data){
                      $('#spinner').hide(); 
                      $('#container-spinner').hide(); 
                      $('#mainDIV').css('opacity', 1);
                    }
                  });        
          });
          $("#addGSart").click(function(){
            var hiddenDSH = $('#tabGS').val();
            var link = $('#insertGSLink').val();
            $.ajax({
              url: './rpc/manage_articles.php',
              type: 'post',
              data: {
                tab:hiddenDSH,
                type:'CUSTOM',
                link: link
              },
              beforeSend: function(){
                $('#spinner').show(); 
                $('#container-spinner').show(); 
                $('#mainDIV').css('opacity', 0.1);
              },
              success: function(response){
                openModal();
              },
              complete:function(data){
                $('#spinner').hide(); 
                $('#container-spinner').hide();
                $('#mainDIV').css('opacity', 1); 
              }
            });        
          });

        });

    
   </script> 
  <!-- SPINNER --> 
   <div id="container-spinner" style="display:none">
        <div id="spinner" class="spinner-center" style="display:none" >
            <h4 class="text-center"  style="font-weight:900;">ACQUISIZIONE DATI IN CORSO</h4>
            <img src="../admin/VAyR.gif" style="width:35%;margin-left:27%;margin-top:15%;margin-bottom:15%;" alt="loading">
            <h4 class="text-center" style="font-weight:900;" >ATTENDI QUALCHE SECONDO....</h4>
        </div>
    </div>
  <!-- SPINNER --> 

  <!-- MAIN --> 
      <div class="container-fluid div" id="mainDIV">  
              <!-- MODAL --> 
                  <?php  include('modal.php'); ?>  
            <!-- /MODAL --> 
          <div class="row">
              <div class="col-md-2" style="background-color: black;color:white;height:auto;border-top-right-radius:40px;border-right:6px solid #007bff">
                  <img src="../logowhite.png" style="width:200px;">    
                  <ul class="leftNav" style ="list-style-type:none;position:fixed;display:block">           
                      <h4 style="margin-bottom:14%;text-decoration:underline">ADMIN AREA <br> <img src="../admin/VAyR.gif" class="navLoader text-center" style="width:30%;margin-left:15%;margin-top:25%;display:none;"></h4>
                      <li class="leftNav-item"><a href="<?php print BASE_URL ?>index.php">Home</a></li>
                      <li class="leftNav-item"><a href="../balisage.php?tiporicerca=T">Balisage</a></li>
                      <li class="leftNav-item"><a href="<?php print BASE_URL ?>dsh.php?tiporicerca=IS">DSH</a></li>
                      <li class="leftNav-item"><a href="<?php print BASE_URL ?>jtei.php">JTEI</a></li>
                      <li class="leftNav-item"><a href="<?php print BASE_URL ?>googleScholar.php">Google Scholar</a></li>
                      <li class="leftNav-item"><a id="logout" href="<?php print BASE_URL ?>logout.php">Logout</a></li>
                      
                  </ul>
              </div>
              <div class="col-md-10" id="centralDIV">                         
                <!--RISORSE E API-->
                <div class="row" style="margin-top:5%;border-bottom:4px solid #007bff;    background-image: url('../bg.jpg');">
                    <div class="col-md-6" style="width:50%;" >
                        <h3 style="text-align:center;">LINK RISORSE (sito ufficiale)</h3>
                        <ul style="margin-left:20%;">
                            <li><a href="https://www.balisage.net/index.html">BALISAGE</a></li>
                            <li><a href="https://academic.oup.com/dsh">DSH</a></li>
                            <li><a href="https://journals.openedition.org/jtei/">JTEI</a></li>
                            <li><a href="https://scholar.google.com/scholar?hl=it&as_sdt=0%2C5&q=&btnG=">GOOGLE SCHOLAR</a></li>
                        </ul>
                    </div>

                    <div class="col-md-6" style="width:50%;">
                        <h3 style="text-align:center;">Gestione API</h3>
                        <ul style="margin-left:35%;">
                            <li><a href="https://scrapestack.com/login">SCRAPESTACK</a></li>
                            <li><a href="https://app.scrapingbee.com/account/login">SCRAPEBEE</a></li>
                        </ul>
                    </div>
                </div>
                <!--balisage-->
                <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff;">
                      <div class="col-md-12 text-center"><h3>BALISAGE</h3></div>
                      <div class="col-md-6 text-center" style="width:50%;" >
                            <h4>Gestione Proceedings</h4>
                            <?php  
                            if(!empty($last_update_baliP[0]['ultima_mod'])){
                            foreach($last_update_baliP as $k => $last){
                              $DateTime = DateTime::createFromFormat('Y-m-d', $last['ultima_mod']);
                              $newDate = $DateTime->format('d/m/Y');?>
                                <small style="margin-bottom:3%;">Ultimo aggiornamento: <?= $newDate ?></small><br>
                            <?php } }?>
                          <div id="addProceedingsForm"style="margin:2%;padding:10px;">
                            <input type="hidden" id="tabBali"value="1">
                            <button name="addProceedings" id="addProceedings" type="button" class="btn btn-primary" title="Recupera e inserisce articoli nel db" >GET ALL PROCEEDINGS</button>
                          </div>

                          <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                            <button name="deleteProceedings" type="submit"class="btn btn-danger" title="Elimina tutti i proceedings">DROP CURRENT PROCEEDINGS</button>
                          </form>
                      </div>
                    
                    
                      <div class="col-md-6 text-center" style="width:40%;">
                            <h4>Gestione topics articoli </h4>
                            <?php  
                            if(!empty($last_update_baliT[0]['ultima_mod'])){
                            foreach($last_update_baliT as $k => $last){
                              $DateTime = DateTime::createFromFormat('Y-m-d', $last['ultima_mod']);
                              $newDate = $DateTime->format('d/m/Y');?>
                                <small style="margin-bottom:3%;">Ultimo aggiornamento: <?= $newDate ?></small><br>
                            <?php } }?>
                          <div id="addTopicForm" style="margin:2%;padding:10px;">
                            <button name="addTopics" id="addTopic"type="button" class="btn btn-primary" title="Recupera e inserisce articoli nel db">GET ALL TOPICS</button>
                          </div>
                          <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                            <button name="deleteTopics" type="submit"class="btn btn-danger" title="Elimina gli articoli associati ai topic di Balisage">DROP CURRENT TOPICS</button>
                          </form>
                        
                      </div>
                </div>
                <!--DSH -->
                <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff">
                      <div class="col-md-12 text-center"><h3>DSH</h3></div>

                      <div class="col-md-6 text-center" style="width:50%;" >
                            <h4>Gestione Advaced Articles</h4>
                            <?php  
                            if(!empty($last_update_DSHad[0]['ultima_mod'])){
                            foreach($last_update_DSHad as $k => $last){
                              $DateTime = DateTime::createFromFormat('Y-m-d', $last['ultima_mod']);
                              $newDate = $DateTime->format('d/m/Y');?>
                                <small style="margin-bottom:3%;">Ultimo aggiornamento: <?= $newDate ?></small><br>
                            <?php } }?>
                          <div id="addAdvancedArticlesForm" style="margin:2%;padding:10px;">
                            <input type="hidden" id="tabdsh"value="2">
                            <button name="addAdvArt" id="addAdvArt" type="submit" class="btn btn-primary" title="Recupera e inserisce nel db gli articoli denominati Advanced Articles">GET ALL ADVANCED ARTICLES</button>
                          </div>
                          <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                            <button name="deleteAdvArt" type="submit"class="btn btn-danger" title="Elimina Advanced Articles dal db">DROP CURRENT ADVANCED ARTICLES</button>
                          </form>
                      </div>

                      <div class="col-md-6 text-center" style="width:40%;">
                            <h4>Gestione Issues </h4>
                            <?php  
                            if(!empty($last_update_DSHis[0]['ultima_mod'])){
                            foreach($last_update_DSHis as $k => $last){
                              $DateTime = DateTime::createFromFormat('Y-m-d', $last['ultima_mod']);
                              $newDate = $DateTime->format('d/m/Y');?>
                                <small style="margin-bottom:3%;" >Ultimo aggiornamento: <?= $newDate ?></small><br>
                            <?php } }?>
                          <div id="addIssuesForm" style="margin:2%;padding:10px;">
                        
                          <input type="hidden" id="tabdsh"value="2">
                            <button name="addIssues" id="addIssues" type="submit" class="btn btn-primary" title="Recupera e inserisce nel db gli articoli denominati Issues">ADD ALL ISSUES</button>
                          </form>
                          <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                            <button name="deleteIssues" type="submit"class="btn btn-danger" title="Elimina articoli 'Issues' dal db">DELETE ALL ISSUES</button>
                          </form>
                        </div>
                    </div>
                </div>
                <!--JTEI -->
                <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff">
                      <div class="col-md-12 text-center"><h3>JTEI</h3>
                        <?php  
                            if(!empty($last_update_jtei[0]['ultima_mod'])){
                            foreach($last_update_jtei as $k => $last){
                              $DateTime = DateTime::createFromFormat('Y-m-d', $last['ultima_mod']);
                              $newDate = $DateTime->format('d/m/Y');?>
                                <small style="margin-bottom:3%;">Ultimo aggiornamento: <?= $newDate ?></small><br>
                            <?php } }?>
                      </div>

                      <div class="col-md-6 text-center" style="width:50%;" >
                          <div id="addartJTEIForm" style="margin:2%;padding:10px;">
                            <input type="hidden" id="tabjtei"value="3">
                            <button name="addJTEIArt" id="addJTEIArt" type="submit" class="btn btn-primary" title="Recupera e inserisce articoli nel db articoli da JTEI">GET ALL ARTICLES</button>
                          </div>
                      </div>

                      <div class="col-md-6 text-center" style="width:40%;">
                          <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                            <button name="deleteJTEIArt" type="submit"class="btn btn-danger" title="Elimina gli articoli JTEI">DROP CURRENT ARTICLES</button>
                          </form>
                      </div>
                </div>
                <!--GSCHOLAR -->
                <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff">
                    <div class="col-md-12 text-center"><h3>Google Scholar</h3>
                      <?php  
                            if(!empty($last_update_GS[0]['ultima_mod'])){
                            foreach($last_update_GS as $k => $last){
                              $DateTime = DateTime::createFromFormat('Y-m-d', $last['ultima_mod']);
                              $newDate = $DateTime->format('d/m/Y');?>
                                <small style="margin-bottom:3%;">Ultimo aggiornamento: <?= $newDate ?></small><br>
                            <?php } }?>
                    </div>

                      <div class="col-md-6 offset-md-1" style="width:50%;margin-top:2%;" >
                            <h5>Scape personalizzato</h5>
                                <ol>
                                    <li><p>Apri <a href="https://scholar.google.com/">Google Scholar</a></p></li>
                                    <li><p>Seleziona la pagina di interessa (in basso)</p></li>
                                    <li>Assicurati di avere un url simile: <br>(<i style ="font-size:10px;">https://scholar.google.com/scholar?hl=it&as_sdt=0%2C5&q=digital+scholarly+editing+&oq=</i>)<br> e inseriscilo nel campo a destra.</li> 
                                    <li>Clicca sul bottone sotto la casella di testo per salvare i 10 articoli presenti in quella pagina</li>
                                </ol>
                                <div class="row">
                                  <div id="addDefaultGSForm" style="margin:2%;padding:10px;">
                                  <input type="hidden" id="tabGS"value="4">
                                    <button name="addDefaultArtGS" id="addDefaultArtGS" type="submit" class="btn btn-primary" title="Recupera e inserisce nel db gli articoli delle prime 10 pagine della ricerca 'Digital scholarly editing' ">GET ALL DEFAULT ARTICLES</button>
                                </div>

                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="deleteDefaultArt_GS" type="submit"class="btn btn-danger" title="Elimina TUTTI gli articoli di GS">DROP CURRENT ARTICLES</button>
                                  </form>
                                </div>                                     
                      </div>

                      <div class="col-md-4 text-center" style="width:40%;margin-top:2%;">
                        <div>
                                <div class="form-group">
                                    <label for="insertGSLink">Proceedings list link</label>
                                    <input type="text" class="form-control" id="insertGSLink"  name="insertGSLink" aria-describedby="insertAcmLink" autocomplete="off" placeholder="Inserisci link">
                                    <small id="linkhelp" class="form-text text-muted">Es. <i>https://scholar.google.com/scholar?hl=it&as_sdt=0%2C5&q=digital+scholarly+editing+&oq=</i></small>
                                </div>
                                <button type="submit" name="addGSart" id="addGSart" class="btn btn-primary" title="Recupera e inserisce nel db gli articoli della pagina indicata nell casella di testo">SCRAPE PAGE</button>
                        </div>
                    </div>  
                </div>  
              </div>
          </div>
      </div>
  <!--/MAIN --> 
  <footer class="container text-center" style="width:100% !important;height:100px;margin-top:20px;">
    <div class="container">
      <div class="row">
        <div class="col-sm-2">
          <img src="../logofooter.jpg" style="width:100%;opacity:50%;">
        </div>
        <div class="col-sm-8">
            <p><a href="index.php">Back to the Homepage</a></p>
            <p style="margin-top:2%;"> Tesi di laurea triennale - Informatitica Umanistica - Università di Pisa</p>
            <p> Jacopo Gentili - 561836 <br>© 2021-2022 </p>
            
        </div>
        <div class="col-sm-2">
        <img src="../logoblack.png" style="width:100%;opacity:50%;">
        </div>
      </div>
    </div>
  </footer>

  </body>
</html>