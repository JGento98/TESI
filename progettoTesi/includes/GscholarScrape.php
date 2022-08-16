<?php 

function pretty_print($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

//**********************************************************************API***************************************
    //SCRAPESTACK API KEY 7387b2bf6eccddc112606fb96e37cb38
    //--------------------------------------------------
    /*

    4Q2X7TM2MJ2WGRHGN3IDIJU5PGEU6KB8X9G4WIME1CDTMEW3IPN1U1N0IC6EC05G4SH95G1FH7GI10TG  reparto.sviluppo
*/

//URL
$googleScholarURL = "https://scholar.google.com/scholar?hl=it&as_sdt=0,5&q=digital+scholarly+editing";

function scrapeGoogleScholar($url){   // Con ScrapingBee 
    $url_encoded = urlencode($url);
    $ch = curl_init();                                                                                                                                                                 
    curl_setopt($ch, CURLOPT_URL, "https://app.scrapingbee.com/api/v1/?api_key=4Q2X7TM2MJ2WGRHGN3IDIJU5PGEU6KB8X9G4WIME1CDTMEW3IPN1U1N0IC6EC05G4SH95G1FH7GI10TG&url=$url_encoded&render_js=false&custom_google=True");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    
    $dom = new DOMDocument();
    @ $dom->loadHTML($response);
    $xpath = new DomXPath($dom);
    
    $results = [];
    $arr = [];
    
    $results['title'] = $xpath->query('//h3//a');
    $results['link'] = $xpath->query('//h3//a//@href');
    $results['date'] = $xpath->query('//div[@class="gs_a"]');
   //$results['numCites'] = $xpath->query('//div[@class="gs_fl"]//a[3]');

    for($x=0; $x < $results['link']->length;$x++){
        $txt = $results['title']->item($x)->textContent;
        $link = $results['link']->item($x)->textContent;
        $date = $results['date']->item($x)->textContent;
        //$numCites = $results['numCites']->item($x)->textContent;

        $authors = explode('-',$date);
        $year = preg_replace('/[^0-9]/', '', $date); 
        
        if(strlen($year)> 4 ){
            $splitstring1 = substr($year, 0, floor(strlen($year) / 2));
            $splitstring2 = substr($year, floor(strlen($year) / 2));  
            if (substr($splitstring1, 0, -1) != ' ' AND substr($splitstring2, 0, 1) != ' '){
                $middle = strlen($splitstring1) + strpos($splitstring2, ' ');
            } else {
                $middle = strrpos(substr($year, 0, floor(strlen($year) / 2)), ' ');    
            }
            $string1 = substr($year, 0, $middle);  
            $string2 = substr($year, $middle); 
            $year = $string2;  
        }
        //$numCites = preg_replace('/[^0-9]/', '', $numCites);  
        
        $arr[$x]['title'] = $txt;
        $arr[$x]['link']  = $link;
        $arr[$x]['year']  = trim($year);
        $arr[$x]['authors'] = trim($authors[0]);
    }
    return $arr;
}
//$googleScholar_SinglePage_results  = scrapeGoogleScholar($googleScholarURL);
//pretty_print($googleScholar_SinglePage_results);

function scrapeGooglePage($url){
    $gScholar_completamento = 'https://scholar.google.com';

    $url_encoded = urlencode($url);
    $ch = curl_init();                                                                                                                                                                 
    curl_setopt($ch, CURLOPT_URL, "https://app.scrapingbee.com/api/v1/?api_key=YCRJKBIJTP1620BNMOUIA5WIYDEWV89SF5C8930XM2KPCC69VUUD1X2JZM5S6ASZDIRZX2AXS4H9EJRA&url=$url_encoded&render_js=false&custom_google=True");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    $dom = new DOMDocument();
    @ $dom->loadHTML($response);
    $xpath = new DomXPath($dom);

    $results = [];
    $arr = [];
    $arr[0] = $url;

    $results['pageLinktxt'] = $xpath->query('//td//a');
    $results['pageLink'] = $xpath->query('//td//a//@href');

    for($x=0; $x < $results['pageLink']->length;$x++){
        $txt = $results['pageLinktxt']->item($x)->textContent;
        $link = $results['pageLink']->item($x)->textContent;
        if(is_numeric($txt)){
            $arr[] = $gScholar_completamento.$link;
        }             
    }   
    return $arr;
}
//$pages = scrapeGooglePage($googleScholarURL);
/*$pages= [
    'https://scholar.google.com/scholar?hl=it&as_sdt=0,5&q=digital+scholarly+editing',
    'https://scholar.google.com/scholar?start=10&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=20&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=30&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=40&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=50&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=60&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=70&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=80&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    'https://scholar.google.com/scholar?start=90&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
    ];
*/
       
function DOSCRAPEOFALLARTICLE($arrpag){
    //$pages_arr = scrapeGooglePage($url);
    $articles  = [];
    foreach ($arrpag as $k => $linkURL){
        $articles[] = scrapeGoogleScholar($linkURL);
    }
    return $articles;
}   
//$articles = DOSCRAPEOFALLARTICLE($pages);




?>