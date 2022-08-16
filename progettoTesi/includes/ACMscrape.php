<?php

//I LINK STANNO IN DEGLI ACCORDION CHE SI DEVONO APRIRE, con quello funziona perchè non sono da aprire 
$acmURL = "https://dl.acm.org/doi/proceedings/10.1145/3453187"; //ok

function scrapeACM($url){
    $queryString = http_build_query([ 
        'access_key' => '7387b2bf6eccddc112606fb96e37cb38', 
        'url' => $url, 
    ]);
    
    // API URL with query string 
    $apiURL = sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString); 
    $acmCompletamento = 'https://dl.acm.org';
    // Create a new cURL resource 
    $ch = curl_init(); 
     
    // Set URL and other appropriate options 
    curl_setopt($ch, CURLOPT_URL, $apiURL); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
     
    // Execute and get response from API 
    $website_content = curl_exec($ch); 
     
    // Close cURL resource 
    curl_close($ch);
    
    $dom = new DOMDocument();
    
    @ $dom->loadHTML($website_content);
    
    $xpath = new DomXPath($dom);
    
    $results = [];
    $arr = [];
    
    //Advance articles
    $results['title'] = $xpath->query('//h5[@class="issue-item__title"]');
    $results['link'] = $xpath->query('//h5[@class="issue-item__title"]//a//@href');
    $results['date'] = $xpath->query('//div[@class="issue-item__detail"]//span[1]');
    $results['numCites'] = $xpath->query('//span[@class="citation"]//span');
    $results['numDownload'] = $xpath->query('//span[@class="metric"]//span');
    
    for($x=0; $x < $results['link']->length;$x++){
        $txt = $results['title']->item($x)->textContent;
        $link = $results['link']->item($x)->textContent;
        $date = $results['date']->item($x)->textContent;
        $numCites = $results['numCites']->item($x)->textContent;
        $numDownload = $results['numDownload']->item($x)->textContent;

        $year = explode(' ', $date);

        $arr[$x]['title'] = $txt;
        $arr[$x]['link']  = $acmCompletamento.$link;
        $arr[$x]['year']  = str_replace (',', '', $year[1] );
        $arr[$x]['numCites'] = $numCites;
        $arr[$x]['numDownload'] = $numDownload;
    }
    return $arr;
}

//$ACM_results            = scrapeACM($acmURL);

//-------------------------------------------------------

//pretty_print($ACM_results);

?>