<?php 
//**********************************************************************API***************************************
    //SCRAPESTACK API KEY 7387b2bf6eccddc112606fb96e37cb38

    //https://journals.openedition.org/jtei/3331

/*
function pretty_print($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
*/
//URL
$jteiURL     = 'https://journals.openedition.org/jtei/124';

$single_url = 'https://journals.openedition.org/jtei/3331';

// *******************************SINGLE PAGE****************************
             
function scrapeAllIssuesPages($url){
    $queryString = http_build_query([ 
        'access_key' => '7387b2bf6eccddc112606fb96e37cb38', 
        'url' => $url, 
    ]);
    $apiURL = sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString); 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $apiURL); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $html = curl_exec($ch); 
    curl_close($ch);
   
    $dom = new DOMDocument();
    @ $dom->loadHTML($html);
    $xpath = new DomXPath($dom);

    $results = [];
    $arr = [];
    
    $jteiComple = 'https://journals.openedition.org/jtei/';
    $results['pages'] = $xpath->query('//ul[@class="collection summary"]//li//a');
    $results['link'] = $xpath->query('//ul[@class="collection summary"]//li//a//@href');

    for($x=0; $x < $results['pages']->length;$x++){
        //$year = $results['volume']->item($x)->textContent;
        $page = $results['pages']->item($x)->textContent;
        $link = $results['link']->item($x)->textContent; 
        $arr[] =  $jteiComple.$link ; 
    }
    return $arr;
}
function scrapeArticles($url){
    $queryString = http_build_query([ 
        'access_key' => '7387b2bf6eccddc112606fb96e37cb38', 
        'url' => $url, 
    ]);
    
    // API URL with query string 
    $apiURL = sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString); 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $apiURL); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $html = curl_exec($ch); 
    curl_close($ch);
    
    $dom = new DOMDocument();
    @ $dom->loadHTML($html);
    $xpath = new DomXPath($dom);
    $jTeiCompl = 'https://journals.openedition.org/jtei/';
    $results = [];

    $results['year'] = $xpath->query('//h1[@id="publiTitle"]');
    $results['author'] = $xpath->query('//ul[@class="summary"]//li//div[@class="author"]');
    $results['title'] = $xpath->query('//ul[@class="summary"]//li//div[@class="title"]//a');
    $results['link'] = $xpath->query('//ul[@class="summary"]//li//div[@class="title"]//a//@href');
   
    $arr = [];
    $year = $results['year']->item(0)->textContent;
   

    for($x=0; $x < $results['title']->length;$x++){
        $author = $results['author']->item($x)->textContent;
        $title = $results['title']->item($x)->textContent;
        $link = $results['link']->item($x)->textContent;

        $year = $results['year']->item(0)->textContent;
        $final_year = '';

        if( strpos($year,'Selected Papers')){
            $year = explode("|",$year);
            $newstr = explode("Selected Papers",$year[1]);
            $final_year = preg_replace('/[^0-9]/', '', $newstr[1]);
            if(strlen($final_year)> 4 ){
                $splitstring1 = substr($final_year, 0, floor(strlen($final_year) / 2));
                $splitstring2 = substr($final_year, floor(strlen($final_year) / 2));  
                if (substr($splitstring1, 0, -1) != ' ' AND substr($splitstring2, 0, 1) != ' '){
                    $middle = strlen($splitstring1) + strpos($splitstring2, ' ');
                } else {
                    $middle = strrpos(substr($final_year, 0, floor(strlen($final_year) / 2)), ' ');    
                }
        
                $string1 = substr($final_year, 0, $middle);  
                $string2 = substr($final_year, $middle); 
                $final_year = $string2;  
            }
        }
        else if(strpos($year,'Reaching') ){
            $year = explode("|",$year);
            $newstr = explode("Reaching",$year[1]);
            $final_year = preg_replace('/[^0-9]/', '', $newstr[0]);
        }
        else{
            $year = explode("|",$year);
            $final_year = preg_replace('/[^0-9]/', '', $year[1]);
            
        }
        if(strpos($title,"          ")){
            $title = str_replace("      ","",$title);
            if(strpos($title,"   ")){
                $title = str_replace("   "," ",$title);
            }
        }
         
        $arr[$x]['title']   =  trim($title);
        $arr[$x]['author']  = trim($author);
        $arr[$x]['link']    =  $jTeiCompl.trim($link);
        $arr[$x]['year']    =  $final_year; 
    }
    return $arr;
}            
function scrapeAll($url){
        $issuesPages_arr = scrapeAllIssuesPages($url);
        foreach($issuesPages_arr as $k => $url_jtei){
            $total_Issues[] = scrapeArticles($url_jtei);
        }
       
    return $total_Issues;
}                


/************************************************************************************************************** */    

//risultati 
//$articles  = scrapeAll($jteiURL);
//stampa 
//pretty_print($articles);



 
?>