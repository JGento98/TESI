<?php
 require_once('../config.php');
 require_once( ROOT_PATH . '/includes/functions.php');
 require_once( ROOT_PATH . '/includes/head_section.php');

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
//BALISAGE
    if(isset($_POST['deleteProceedings'])){
        $delP = deleteAllProceedings();
    }
    if (isset($_POST['addProceedings'])){
        $add_proceedings  = addProceedings();
    }
    if(isset($_POST['deleteTopics'])){
        $delT = deleteAlltopics();
    }
    if (isset($_POST['addTopics'])){
        $add_topics   = addTopics();
    }
//DSH  
    if(isset($_POST['deleteAdvArt'])){
        $delAD = delete_advance_articles();
    }
    if (isset($_POST['addAdvArt'])){
       $addAdvanceArticles = add_advance_articles();
    }
    if(isset($_POST['deleteIssues'])){
        $delI = delete_all_Issues();
    }
    if (isset($_POST['addIssues'])){
        $addIssues = add_all_Issues();
    }

//JTEI
  if( isset($_POST['addJTEIArt'])){
    $add = add_all_JTEI_Issues();;
  }
  if( isset($_POST['deleteJTEIArt'])){
    $del = delete_JTEI_Issues();
  }

//GOOGLE SCHOLAR
  if( isset($_POST['addGSart'])){
    $link = $_POST['insertGSLink'];
    $add10Articles = add_10articles($link);
  }
  if( isset($_POST['addDefaultArt_GS'])){
      $addDefaultArticles = add_10Pages_of_articles_GoogleScholar();
  }
  if( isset($_POST['deleteDefaultArt_GS'])){
      $deleteDefaultACM = delete_all_GS();
  }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>EasyScrape</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>

    <!-- MY CSS -->
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
    
  </style>
  <body>
   <script>
         
   </script> 

    <div class="container-fluid">   

        <div class="row">
            <div class="col-md-2" style="background-color: black;color:white;height:auto;border-top-right-radius:40px;border-right:6px solid #007bff">
                <img src="../logowhite.png" style="width:200px;">
                <ul class="leftNav" style ="list-style-type:none;position: fixed;">               
                    <h4 style="margin-bottom:20%;text-decoration:underline">ADMIN AREA</h4>
                    <li class="leftNav-item"><a href="<?php print BASE_URL ?>index.php">Home</a></li>
                    <li class="leftNav-item"><a href="../balisage.php?tiporicerca=T">Balisage</a></li>
                    <li class="leftNav-item"><a href="<?php print BASE_URL ?>dsh.php?tiporicerca=IS">DSH</a></li>
                    <li class="leftNav-item"><a href="<?php print BASE_URL ?>jtei.php">JTEI</a></li>
                    <li class="leftNav-item"><a href="<?php print BASE_URL ?>googleScholar.php">Google Scholar</a></li>
                    <li class="leftNav-item"><a id="logout" href="<?php print BASE_URL ?>logout.php">Logout</a></li>
                </ul>
            </div>

            <div class="col-md-10">                         
                        <!--RISORSE E API-->
                        <div class="row" style="margin-top:5%;border-bottom:4px solid #007bff">
                                <div class="col-md-5" style="width:50%;margin-left:12%;" >
                                    <h3 style="text-align:center;">LINK RISORSE (sito ufficiale)</h3>
                                    <ul style="margin-left:20%;">
                                        <li><a href="https://www.balisage.net/index.html">BALISAGE</a></li>
                                        <li><a href="https://academic.oup.com/dsh">DSH</a></li>
                                        <li><a href="https://journals.openedition.org/jtei/">JTEI</a></li>
                                        <li><a href="https://scholar.google.com/scholar?hl=it&as_sdt=0%2C5&q=&btnG=">GOOGLE SCHOLAR</a></li>
                                    </ul>
                                </div>
                                
                                <div class="col-md-5" style="width:50%;">
                                    <h3 style="text-align:center;">Gestione API</h3>
                                    <ul style="margin-left:35%;">
                                        <li><a href="https://scrapestack.com/login">SCRAPESTACK</a></li>
                                        <li><a href="https://app.scrapingbee.com/account/login">SCRAPEBEE</a></li>
                                    </ul>
                                </div>
                        </div>
                         <!--balisage-->
                         <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff">
                              <div class="col-md-12 text-center"><h3>BALISAGE</h3></div>
                              <div class="col-md-6 text-center" style="width:50%;" >
                                    <h4>Gestione Proceedings</h4>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="addProceedings" type="submit" class="btn btn-primary">ADD PROCEEDINGS</button>
                                  </form>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="deleteProceedings" type="submit"class="btn btn-danger">DELETE PROCEEDINGS</button>
                                  </form>
                              </div>
                               
                              <div class="col-md-6 text-center" style="width:40%;">
                                    <h4>Gestione topics articoli </h4>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="addTopics" type="submit" class="btn btn-primary">ADD TOPICS</button>
                                  </form>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="deleteTopics" type="submit"class="btn btn-danger">DELETE TOPICS</button>
                                  </form>
                              </div>
                        </div>
                        <!--DSH -->
                        <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff">
                              <div class="col-md-12 text-center"><h3>DSH</h3></div>
                              <div class="col-md-6 text-center" style="width:50%;" >
                                    <h4>Gestione Advaced Article</h4>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="addAdvArt" type="submit" class="btn btn-primary">ADD ADVANCED ARTICLES</button>
                                  </form>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="deleteAdvArt" type="submit"class="btn btn-danger">DELETE ADVANCED ARTICLES</button>
                                  </form>
                              </div>
                               
                              <div class="col-md-6 text-center" style="width:40%;">
                                    <h4>Gestione Issues </h4>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="addIssues" type="submit" class="btn btn-primary">ADD ALL ISSUES</button>
                                  </form>
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="deleteIssues" type="submit"class="btn btn-danger">DELETE ALL ISSUES</button>
                                  </form>
                              </div>
                        </div>
                         <!--JTEI -->
                         <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff">
                              <div class="col-md-12 text-center"><h3>JTEI</h3></div>
                              <div class="col-md-6 text-center" style="width:50%;" >
                                
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="addJTEIArt" type="submit" class="btn btn-primary">ADD ARTICLES</button>
                                  </form>
                              </div>
                               
                              <div class="col-md-6 text-center" style="width:40%;">
                                  <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                    <button name="deleteJTEIArt" type="submit"class="btn btn-danger">DELETE ARTICLES</button>
                                  </form>
                              </div>
                        </div>
                       
                        <!--GSCHOLAR -->
                        <div class="row" style="margin-top:2%;border-bottom: 4px solid #007bff">
                            <div class="col-md-12 text-center"><h3>Google Scholar</h3></div>
                              <div class="col-md-5" style="width:50%;margin-top:2%;" >
                                    <h5>Scape personalizzato</h5>
                                        <ol>
                                            <li><p>Apri <a href="https://scholar.google.com/">Google Scholar</a></p></li>
                                            <li><p>Seleziona la pagina di interessa (in basso)</p></li>
                                            <li>Assicurati di avere un url simile: <br>(<i style ="font-size:10px;">https://scholar.google.com/scholar?hl=it&as_sdt=0%2C5&q=digital+scholarly+editing+&oq=</i>)<br> e inseriscilo nel campo a destra.</li> 
                                            <li>Clicca sul bottone sotto la casella di testo per salvare i 10 articoli presenti in quella pagina</li>
                                        </ol>
                                        <div class="row">
                                          <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                            <button name="addDefaultArt_GS" type="submit" class="btn btn-primary">ADD DEFAULT ARTICLES</button>
                                          </form>

                                          <form action="admin.php" method="POST" style="margin:2%;padding:10px;">
                                            <button name="deleteDefaultArt_GS" type="submit"class="btn btn-danger">DELETE ALL ARTICLES</button>
                                          </form>
                                        </div>
                                        

                              </div>
                                <div class="col-md-1"></div>
                              <div class="col-md-6 text-center" style="width:40%;margin-top:2%;">
                              
                                <form method="POST" action="admin.php">
                                        <div class="form-group">
                                            <label for="insertGSLink">Proceedings list link</label>
                                            <input type="text" class="form-control" id="insertGSLink"  name="insertGSLink" aria-describedby="insertAcmLink" autocomplete="off" placeholder="Inserisci link">
                                            <small id="linkhelp" class="form-text text-muted">Es. <i>https://scholar.google.com/scholar?hl=it&as_sdt=0%2C5&q=digital+scholarly+editing+&oq=</i></small>
                                        </div>
                                        <button type="submit" name="addGSart" class="btn btn-primary">SCRAPE PAGE</button>
                                </form>
                            </div>  
                        </div>  
            </div>
      
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
    </div>
  </body>
</html>