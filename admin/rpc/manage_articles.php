<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/progettoTesi/config.php');
require_once(ROOT_PATH . '/includes/functions.php');

if($_POST['tab'] == 1){
    if($_POST['type'] == 'P'){
        $result = addProceedings();
    }
    if( $_POST['type'] == 'T'){
        $result = addTopics();
    }
}
if($_POST['tab'] == 2){
    
    if($_POST['type'] == 'AD'){
        $result = add_advance_articles();
    }
    if($_POST['type'] == 'IS'){
        $result = add_all_Issues();
    }
}
if($_POST['tab'] == 3 ){
    $result = add_all_JTEI_Issues();
}
if($_POST['tab'] == 4 && $_POST['type'] = 'DEFAULT'){
    $result = add_10Pages_of_articles_GoogleScholar();
}
if($_POST['tab'] == 4 && isset($_POST['link']) && $_POST['type'] = 'CUSTOM'){ 
    $link = $_POST['link'];
    $result = add_10articles($link);
}









?>