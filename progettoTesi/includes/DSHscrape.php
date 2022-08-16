<?php
//**********************************************************************API***************************************
    //SCRAPESTACK API KEY 7387b2bf6eccddc112606fb96e37cb38
    //--------------------------------------------------
    /*SCRAPERAPI

        API Key               -> 054e08f626c589cdf0f84d8e57dd35bd
        
        Sample Async API Code -> curl -X POST -H "Content-Type: application/json" -d '{"apiKey": "054e08f626c589cdf0f84d8e57dd35bd", "url": "http://httpbin.org/ip"}' "https://async.scraperapi.com/jobs"
        
        Sample API Code       -> curl "http://api.scraperapi.com?api_key=054e08f626c589cdf0f84d8e57dd35bd&url=http://httpbin.org/ip"
        
        Sample Proxy Code     -> 
    ---------------------------------------------------------------------
    SCRAPING BEE 
    NSDVP4O4NTYJ746RUK3FYPYWRYFOR5J5FOD4A82SMY23D037E8T9HUEZBZH7A4W366MVUYWJTBJS8VS4

*/
/*
function pretty_print($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
*/
//URL
$dshURL             = 'https://academic.oup.com/dsh/advance-articles';
$all_issues_archive = 'https://academic.oup.com/dsh/issue-archive';


/* *******************************SINGLE PAGE****************************
            /* ------------------------------advanced article  --------recupera il contenuto della pagina advanced articles----------------------*/ 
                function scrapeDSH_advance_articles($url){
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $dshCompletamento = "https://academic.oup.com";
                    $html = curl_exec($ch);
                    
                    $dom = new DOMDocument();
                    @ $dom->loadHTML($html);
                    $xpath = new DomXPath($dom);

                    $results = [];
                    $arr = [];
                    $auth_arr = [];

                    //Advance articles
                    $results['title'] = $xpath->query('//h5[@class="al-title"]//a');
                    $results['link'] = $xpath->query('//h5[@class="al-title"]//a//@href');
                    $results['date'] = $xpath->query('//span[@class="citation-date"]');
                    $results['authors'] = $xpath->query('//div[@class="al-authors-list"]');
                    //ERRATUM é VUOTO E SFANCULA TUTTO 
                    
                    for($x=0; $x < $results['authors']->length;$x++){   
                        $auth_arr[] = $results['authors']->item($x)->textContent;
                    } 

                    for($x=0; $x < $results['title'] ->length;$x++){ 
                        $txt = $results['title']->item($x)->textContent;
                        if( strpos($txt,'Erratum') !== false || strpos($txt,'Corrigendum') !== false || (strpos($txt,'Introduction') !== false && strlen($txt) == 12 ) ){
                            $pos = $x;
                            $val = 'Nessun risutato';
                            $auth_arr = array_merge(array_slice($auth_arr, 0, $pos), array($val), array_slice($auth_arr, $pos));
                        }
                    }
                
                    for($x=0; $x < $results['title']->length;$x++){    
                        $txt = $results['title']->item($x)->textContent;    
                        $link = $results['link']->item($x)->textContent;
                        $date = $results['date']->item($x)->textContent;
                        $year = explode(' ', $date);
                        
                        $arr[$x]['title'] = $txt;
                        $arr[$x]['link']  = $dshCompletamento.$link;
                        $arr[$x]['year'] = $year[2];
                        $arr[$x]['authors'] = trim($auth_arr[$x]);
        
                    }                                              
                    return $arr;
                }
            /* ------------------------------one page of issues  ------recupera il contenuto di una pagina di issues (es: https://academic.oup.com/dsh/issue/37/1)-------------------*/ 
                function ScrapeOneIssue($issueURL){
                    
                    $dshCompletamento = "https://academic.oup.com";
                    $queryString = http_build_query([ 
                        'access_key' => '7387b2bf6eccddc112606fb96e37cb38', 
                        'url' => $issueURL, 
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

                    $resultsVolume = [];
                    $final_arr = [];
                    $auth_arr= [];
                    
                    $resultsVolume['title'] = $xpath->query('//h5[@class="customLink item-title"]//a');
                    $resultsVolume['link'] = $xpath->query('//h5[@class="customLink item-title"]//a//@href');
                    $results['citation'] = $xpath->query('//div[@class="ww-citation-primary"]');
                    $results['authors'] = $xpath->query('//div[@class="al-authors-list"]');

                    for($x=0; $x < $results['authors'] ->length;$x++){ 
                        $auth_arr[$x] = $results['authors']->item($x)->textContent; 
                    }
                   
                    for($x=0; $x < $resultsVolume['title'] ->length;$x++){ 
                        $txt = $resultsVolume['title']->item($x)->textContent;
                        if( strpos($txt,'Erratum') !== false || strpos($txt,'Corrigendum') !== false || (strpos($txt,'Introduction') !== false && strlen($txt) == 12 ) ){
                            $pos = $x;
                            $val = 'Nessun risutato';
                            $auth_arr = array_merge(array_slice($auth_arr, 0, $pos), array($val), array_slice($auth_arr, $pos));
                        }
                    }

                    for($x=0; $x < $resultsVolume['link']->length;$x++){
                        
                        $cit = $results['citation']->item($x)->textContent;
                        $segments = explode(',', $cit);
                        $vol = trim($segments[1]);
                        $year = explode(' ', $segments[3]);
                
                        $txt = $resultsVolume['title']->item($x)->textContent;
                        $link = $resultsVolume['link']->item($x)->textContent;
                    
                        $final_arr[$x]['title']   = $txt;
                        $final_arr[$x]['link']    = $dshCompletamento.$link;
                        $final_arr[$x]['volume']  = $vol;
                        $final_arr[$x]['year']    = $year[2];
                        $final_arr[$x]['authors'] = trim($auth_arr[$x]);
                                           
                    }
                    return $final_arr;
                }
                function scrapeVolume($url_volume_anno){
                    $queryString = http_build_query([ 
                        'access_key' => '7387b2bf6eccddc112606fb96e37cb38', 
                        'url' => $url_volume_anno, 
                    ]);
                    
                    // API URL with query string 
                    $apiURL = sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString); 
                    $dshCompletamento = "https://academic.oup.com";
                    
                    $ch = curl_init(); 
                    curl_setopt($ch, CURLOPT_URL, $apiURL); 
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                    $html = curl_exec($ch); 
                    curl_close($ch);
                    
                    $dom = new DOMDocument();
                    @ $dom->loadHTML($html);
                    $xpath = new DomXPath($dom); 
                    
                    $results = [];
                    $results['volume'] = $xpath->query('//ul//div[@class="customLink"]//a');
                    $results['link'] = $xpath->query('//ul//div[@class="customLink"]//a//@href');
                    
                    $arrVolume = [];
                    for($x=0; $x < $results['link']->length;$x++){
                        //$year = $results['volume']->item($x)->textContent;
                        $link = $results['link']->item($x)->textContent;
                    
                        $arrVolume[] = $dshCompletamento.$link; 
                    }
                    return $arrVolume;
                }
                //NOTA: sono richiamate nelle funzioni sottostanti

/* ***************************************MULTI PAGE ISSUES**************************************************/

            // recupera i link dei vari volumi in base agli anni, restituisce un array [0] -> https://academic.oup.com/dsh/issue-archive/2022 */
                function scrapeIssuesYear($archiveURL){
                    $queryString = http_build_query([ 
                    'access_key' => '7387b2bf6eccddc112606fb96e37cb38', 
                    'url' => $archiveURL, 
                    ]);

                    // API URL with query string 
                    $apiURL = sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString); 
                    $dshCompletamento = "https://academic.oup.com";

                    $ch = curl_init(); 
                    curl_setopt($ch, CURLOPT_URL, $apiURL); 
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                    $html = curl_exec($ch); 
                    curl_close($ch);

                    $dom = new DOMDocument();
                    @ $dom->loadHTML($html);
                    $xpath = new DomXPath($dom);


                    $resultsVolume = [];
                    $arrAllIssuesLink = [];
                    $arrVolume = [];

                    //YEAR ISSUE
                    $results = [];
                    $results['year'] = $xpath->query('//div[@class="widget widget-IssueYears widget-instance-OUP_Issues_Year_List"]//div//a');
                    $results['link'] = $xpath->query('//div[@class="widget widget-IssueYears widget-instance-OUP_Issues_Year_List"]//div//a//@href');
                    
                    
                    $arrYear = [];
                    for($x=0; $x < $results['link']->length;$x++){
            
                        $year = $results['year']->item($x)->textContent;
                        $link = $results['link']->item($x)->textContent;
                        if($year > '2015'){
                            $arrYear[] = $dshCompletamento.$link;
                        }   
                    }
                    return $arrYear;
                }
            // riceve prima l'array precedente per estrapolare i volumi([0]=>[0]=>https://academic.oup.com/dsh/issue/37/1) e per ognuno di questi link recupera i singoli articoli
                function scrapeAllIssues_fromVolume($issuesYear_arr){
                    $volumes = [];
                
                    foreach($issuesYear_arr as $k => $linksVolume){
                        $volumes[] = scrapeVolume($linksVolume);  
                    }

                    foreach($volumes as $key => $volumeLinkArr){
                        foreach($volumeLinkArr as $index => $volumeIssue){
                            $total_issues[] = ScrapeOneIssue($volumeIssue);
                        }
                    }
                    return $total_issues;
                }
    
    // recupera tutti gli articoli di tutti i volumi dei vari anni richiamando le funzioni soprastanti
    function DOSCRAPEOFALLISSUE($all_issues_archive){
            $issuesYear_arr = scrapeIssuesYear($all_issues_archive);
            $total_Issues = scrapeAllIssues_fromVolume($issuesYear_arr);
        return $total_Issues;
    }
/************************************************************************************************************** */    

//risultati 
//$advance_articles = scrapeDSH_advance_articles($dshURL);
//$All_issues2015to2020 = DOSCRAPEOFALLISSUE($all_issues_archive);
//stampa 
//pretty_print('---------------------ADVANCED ARTICLES-------------------');
//pretty_print($advance_articles);
//pretty_print('---------------------ISSUES------------------------------');
//pretty_print($All_issues2015to2020);
//pretty_print($oneIss);

?>