<?php 
/*
function pretty_print($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
*/
//URL
$balisageUrlBibliography =  'https://www.balisage.net/Proceedings/bibliography.html';
$balisageTopicsURL       = 'https://www.balisage.net/Proceedings/topics.html';
$urlProceedings          = 'https://www.balisage.net/Proceedings/index.html';

    /* ************************RECUPERA PROCEEDINGS ********************************************************/
        // recupera la lista con i link alle pagine dei proceedings
        function scrapeProceedingsList($url){  
            $ch = curl_init();
            $balisageURLcomplemento = 'https://www.balisage.net/Proceedings/';

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $html = curl_exec($ch);
                $dom = new DOMDocument();
                @ $dom->loadHTML($html);
                $xpath = new DomXPath($dom);

                
                $results = [];
                $arr = [];
                $y=0;

                //Advance articles
                $results['link'] = $xpath->query('//p[@class="toc-entry"]//a//@href');

                for($x=0; $x < $results['link']->length;$x++){
                    $link = $results['link']->item($x)->textContent;
                    $concat_link = $balisageURLcomplemento.$link;  
                    if( strpos( $concat_link,"cover" ) ){
                        $changedLink = str_replace("cover","contents",$concat_link);
                        $arr[] = $changedLink;
                    }                                   
                }
            return $arr; 
        }
        function scrapeBalisageProceedings($url){  
            $ch = curl_init();
            $balisageURLcomplemento = 'https://www.balisage.net/Proceedings/';

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $html = curl_exec($ch);
                $dom = new DOMDocument();
                @ $dom->loadHTML($html);
                $xpath = new DomXPath($dom);
                $elimina = ['[Paper]','[EPUB]','[Slides and materials]','[PDF]','[Abstract]'];

                $results = [];
                $arr = [];
                $y=0;

                //proceedings
                $results['author'] = $xpath->query('//p[@class="bibliomixed"]');
                $results['title'] = $xpath->query('//p[@class="bibliomixed"]//a');
                $results['link'] = $xpath->query('//p[@class="bibliomixed"]//a//@href');
             
                for($x=0; $x < $results['author']->length;$x++){
                    $date = '';
                    $txt = $results['title']->item($x)->textContent;
                    $txtClean =  str_replace(array("“","”"),"",$txt);

                    $link = $results['link']->item($x)->textContent;
                    $good_link = str_replace("../","","$link");
                    $concat_link = $balisageURLcomplemento.$good_link;

                    $p_bibliomixed =  $results['author']->item($x)->textContent;
                    $authors = explode('“',$p_bibliomixed);
                
                    $segments = explode('/', parse_url($concat_link, PHP_URL_PATH));
                    
                    if($segments[2] == 'vol27' ){
                        $date ='2022';
                    } 
                    if($segments[2] == 'vol26' ){
                        $date ='2021';
                    } 
                    else if($segments[2] == 'vol25' ){
                        $date='2020';
                    } 
                    else if($segments[2] == 'vol24' || $segments[2] == 'vol23' ){
                        $date='2019';
                    } 
                    else if($segments[2] == 'vol22' || $segments[2] == 'vol21' ){
                        $date='2018';
                    } 
                    else if($segments[2] == 'vol20' || $segments[2] == 'vol19' ){
                        $date='2017';
                    } 
                    else if($segments[2] == 'vol18' || $segments[2] == 'vol17' ){
                        $date='2016';
                    } 
                    else if($segments[2] == 'vol16' || $segments[2] == 'vol15' ){
                        $date='2015';
                    } 
                    else if($segments[2] == 'vol14' || $segments[2] == 'vol13' ){
                        $date='2014';
                    } 
                    else if($segments[2] == 'vol12' || $segments[2] == 'vol11' || $segments[2] == 'vol10'){
                        $date='2013';
                    }
                    else if( $segments[2] == 'vol9' || $segments[2] == 'vol8'  ){
                        $date='2012';
                    }
                    else if($segments[2] == 'vol7' ){
                        $date='2011';
                    }
                    else if($segments[2] == 'vol6' || $segments[2] == 'vol5'){
                        $date='2010';
                    }
                    else if($segments[2] == 'vol4' || $segments[2] == 'vol3'){
                        $date='2009';
                    }
                    else if($segments[2] == 'vol2' || $segments[2] == 'vol1'){
                        $date='2008';
                    }
                   
                    
                    $pattern = '/,/i';
                    $newAuth = preg_replace($pattern, '', $authors[0],1);
                    $finalAuth = str_replace('and',',',$newAuth); 

                    if(!in_array($txtClean,$elimina) && !strpos($link,"/author-pkg/")){  
                        $arr[$y]['title'] = rtrim($txtClean,'.');
                        $arr[$y]['link']  = $concat_link;
                        $arr[$y]['volume']  = $segments[2];
                        $arr[$y]['year']  = $date;
                        $arr[$y]['author']  = str_replace(',,',',',$finalAuth); 
                        $y++;
                    }                     
            }
            return $arr; 
        }
        function scrapeAllBalisageProceedings($arrayLinks){
            foreach($arrayLinks as $key => $URL){
                $final_results[$key] = scrapeBalisageProceedings($URL);   
            }
            return $final_results;
        }

        //crea una lista di pagine da visitare e per ciascuna pagina si savano i risultati 
        function SCRAPEALLPROCEEDINGS($urlProceedings){
            $proceedings_arrLink = scrapeProceedingsList($urlProceedings); 
            $articles            = scrapeAllBalisageProceedings($proceedings_arrLink);
            return $articles;
        }

    //*********************    RECUPERO LINK TOPIC   ********************************** */
        function scrapeTopicsLink($url){
            
            $ch = curl_init();
            $balisageURLcomplemento = 'https://www.balisage.net/Proceedings/';

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $html = curl_exec($ch);
                $dom = new DOMDocument();
                @ $dom->loadHTML($html);
                $xpath = new DomXPath($dom);

                $results = [];
                $arr = [];
                $y=0;

                //$results['topic'] = $xpath->query('//div[@class="mast-box"][1]//li//a');
                $results['link'] = $xpath->query('//div[@class="mast-box"][1]//li//a//@href');

                for($x=0; $x < $results['link']->length;$x++){
                   
                    $link = $results['link']->item($x)->textContent;
                    $concat_link = $balisageURLcomplemento.$link;
                    $good_link = str_replace("../","","$concat_link");
                        $arr[$y]  = $good_link;
                        $y++;
                    } 
                    $cleanArr = array_unique($arr);  
            return $cleanArr;
        }
       // $arr = scrapeTopicsLink($balisageTopicsURL);
       // pretty_print($arr);
   
        function scrapeAllBalisageTopics($arrayLinks){
            foreach($arrayLinks as $key => $URL){
                $final_results[$key] = scrapeBalisageTopic($URL);   
            }
            return $final_results;
        }
        //$results = scrapeAllBalisageTopics($arr);
        //pretty_print($results);

        //uguale alla generale ma cambia il COMPLETAMENTO
        function scrapeBalisageTopic($url){  
            $ch = curl_init();
            $balisageURLcomplemento = 'https://www.balisage.net/Proceedings';

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $html = curl_exec($ch);
                $dom = new DOMDocument();
                @ $dom->loadHTML($html);
                $xpath = new DomXPath($dom);

                $elimina = ['[Paper]','[EPUB]','[Slides and materials]','[PDF]','[Abstract]'];

                $results = [];
                $arr = [];
                $y=0;

                //Advance articles
                $results['author'] = $xpath->query('//p[@class="bibliomixed"]');
                $results['title'] = $xpath->query('//p[@class="bibliomixed"]//a');
                $results['link'] = $xpath->query('//p[@class="bibliomixed"]//a//@href');

                
                for($x=0; $x < $results['author']->length;$x++){
                    $txt = $results['title']->item($x)->textContent;
                    $txtClean =  str_replace(array("“","”"),"",$txt);

                    $link = $results['link']->item($x)->textContent;
                    $good_link = str_replace("../","","$link");
                    $concat_link = $balisageURLcomplemento.$good_link;

                    $segments = explode('/', parse_url($url, PHP_URL_PATH));
                    $topic = explode('.', parse_url($segments[3], PHP_URL_PATH));

                    $p_bibliomixed =  $results['author']->item($x)->textContent;
                    $authors = explode('“',$p_bibliomixed);
                    $pattern = '/,/i';
                    $newAuth = preg_replace($pattern, '', $authors[0],1);
                    $finalAuth = str_replace(' and',',',$newAuth); 
                    $finalAuth2 = str_replace(', ,',',',$newAuth); 

                    $volume_year_raw  = explode('(',$p_bibliomixed);
                    $vol_raw =  explode('/Balisage',$volume_year_raw[1]);
                    if( count($vol_raw) < 2){
                        $vol_raw2 =  explode('. Balisage Series on Markup Technologies,',$vol_raw[0]);
                        
                        $vol = $vol_raw2[1];
                        $vol_final_raw = str_replace(". ","",$vol);
                        $vol_final = trim($vol_final_raw);
                         
                    }else{
                        $vol =  explode('.',$vol_raw[1]);
                        $vol_final = lcfirst($vol[0]);
                        
                    }
                
                    if($vol_final == 'vol29'){
                        $date ='2023';
                    }
                    else if($vol_final == 'vol27' || $vol_final == 'vol28' ){
                        $date ='2022';
                    } 
                    else if($vol_final  == 'vol26' ){
                        $date ='2021';
                    } 
                    else if($vol_final  == 'vol25' ){
                        $date='2020';
                    } 
                    else if($vol_final == 'vol24' || $vol_final == 'vol23' ){
                        $date='2019';
                    } 
                    else if($vol_final == 'vol22' || $vol_final == 'vol21' ){
                        $date='2018';
                    } 
                    else if($vol_final == 'vol20' || $vol_final == 'vol19' ){
                        $date='2017';
                    } 
                    else if($vol_final == 'vol18' || $vol_final == 'vol17' ){
                        $date='2016';
                    } 
                    else if($vol_final == 'vol16' || $vol_final == 'vol15' ){
                        $date='2015';
                    } 
                    else if($vol_final == 'vol14' ||  $vol_final == 'vol13' ){
                        $date='2014';
                    } 
                    else if($vol_final == 'vol12' || $vol_final == 'vol11' || $vol_final == 'vol10'){
                        $date='2013';
                    }
                    else if( $vol_final == 'vol9' || $vol_final == 'vol8'  ){
                        $date='2012';
                    }
                    else if($vol_final == 'vol7' ){
                        $date='2011';
                    }
                    else if($vol_final == 'vol6' || $vol_final == 'vol5'){
                        $date='2010';
                    }
                    else if($vol_final == 'vol4' || $vol_final == 'vol3'){
                        $date='2009';
                    }
                    else if($vol_final == 'vol2' || $vol_final == 'vol1'){
                        $date='2008';
                    }              
                    if(!in_array($txtClean,$elimina) && !strpos($link,"/author-pkg/")){  
                        $arr[$y]['title'] = rtrim($txtClean,'.');
                        $arr[$y]['link']  = $concat_link;
                        $arr[$y]['topic']  = $topic[0];
                        $arr[$y]['author']  = str_replace(',,',',',$finalAuth); 
                        $arr[$y]['year'] = $date;
                        $arr[$y]['volume'] = trim($vol_final);
                        $y++;
                    }    
                   
            }
           
            return $arr; 
        }
      
        function SCRAPEOFTOPICSBALISAGE($BALISAGETOPICURLs){
            $topics          = scrapeTopicsLink($BALISAGETOPICURLs);  //PASSO ARRAY ALLA FUNZIONE SOTTOSTANTE PER CREARE UN ARRAY[x] => link
            $BalisageTopics  = scrapeAllBalisageTopics($topics);
            return $BalisageTopics;
        }
            
//***************************************************************************** */

//-----------------------------SALVA RISULTATI---------------------------------------
//$BalisageProceedings    = SCRAPEALLPROCEEDINGS($urlProceedings);
//$BalisageTopics         = SCRAPEOFTOPICSBALISAGE($balisageTopicsURL);
//---------------------------STAMPA RISULTATI ---------------------------------------
//pretty_print('--------------------PROCEEDINGS-----------------------------------');
//pretty_print($BalisageProceedings);
//pretty_print('--------------------TOPICS----------------------------------OK');
//pretty_print($BalisageTopics); 



?>