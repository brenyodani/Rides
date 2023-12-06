<?php
declare(strict_types=1);


namespace Acme;

namespace OCA\Rides\Service;
require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\BrowserKit\HttpBrowser;




class WebScrapingService{
    private $client;
    private $crawler;
    private $loginClient;


    public function __construct() {
        $this->client = HttpClient::create();
        $this->loginClient = new HTTPBrowser(HTTPClient::create());

     
    }

    // Login to Besermitfahren
    public function loginBesserMitFahren($data){
        
        
        $dataArray = json_decode($data, true);

        $email = $dataArray[0]['email'];
        $password = $dataArray[0]["password"];
        $this->loginClient->request('POST', 'https://www.bessermitfahren.de?cls=page_ajax&action=get&ajax=login&args[]={"gender":"F4wojdSS","email":"' . $email . '","password":"' . $password .'"}');

        $this->loginClient->request('GET', 'https://www.bessermitfahren.de/login',[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);
        $content = $this->loginClient->getResponse()->getContent();

        return $content;
    }


    // scraping of Bessermitfahren

    public function scrapeBesserMitFahren($content) {
        $crawler = new Crawler($content);
        $nodeValues = $crawler->filter('body > #content > #main > .tabcontent > #tab1-offers > .resultlist > li > a');
     
        $result = $nodeValues->each(function (Crawler $node, $i) {

            $origin = $node->filter('.from')->first()->text();
            $destinaiton = $node->filter('.to')->first()->text();
            $date = $node->filter('.date')->first()->text();
            $time = $node->filter('.time')->first()->text();
            $price = $node->filter('.price')->first()->text();
            $people = $node->filter('.people')->first()->text();

            $crawledData = ["origin" => $origin,
                          "destination" => $destinaiton,
                          "date" => $date,
                          "time" => $time,
                          "price" => $price,
                          "people" => $people];
           
           
            $jsonData = json_encode($crawledData);
            echo $jsonData;
       });
    }


    // registering ride to BesserMitFahren
    public function registerRideBMF() {
        
           
        $request_body = file_get_contents('php://input');
        $content = json_decode($request_body, true);
        
        $date = $content["date"];
        $newDate = str_replace('-','.',date("d-m-Y", strtotime($date)));  
        $time = $content["time"];
        $origin = $content["original"];
        $destination = $content["final"];
        $price = $content["price"];
        $matches_from = [];
        $matches_to = [];


        // getting origin request city + code

        $this->loginClient->request('GET' , 'https://www.bessermitfahren.de/search.php?q='. $origin .'&_=',[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);
        $tmp_from_search_response = $this->loginClient->getResponse()->getContent();

        $tmp_from_array = explode("new Array", $tmp_from_search_response);
        $tmp_from = explode("()" , $tmp_from_array[1]);
        $from = explode("()" , $tmp_from_array[2]);

        $tmp_from_cities = explode("'", $tmp_from[0]);
        $tmp_from_city = $tmp_from_cities[1];
        $from_codes = explode("'", $from[0]);
        $from_final = $from_codes[1];



        // getting destination request and city + code

        $this->loginClient->request('GET' , 'https://www.bessermitfahren.de/search.php?q='. $destination .'&_=',[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);

        $tmp_to_search_response = $this->loginClient->getResponse()->getContent();
        $tmp_to_array = explode("new Array", $tmp_to_search_response);
        $tmp_to = explode("()" , $tmp_to_array[1]);
        $to = explode("()" , $tmp_to_array[2]);

        $tmp_to_cities = explode("'", $tmp_to[0]);
        $tmp_to_city = $tmp_to_cities[1];
        $to_codes = explode("'", $to[0]);
        $to_final = $to_codes[1];

        $this->loginClient->request('POST','https://www.bessermitfahren.de?cls=page_ajax&action=get&ajax=offer_save&args[]={"gender":"d4as8dSS","autostopover_distance":"50","tpl":"882259","frequency":"0","vehicle":"car","date":"' . $newDate  .'","from":"' . $from_final .'","tmp_from":"' . $tmp_from_city . '","time":"' . $time . '","ontime":"0","stopmode":["0"],"step":[""],"tmp_step":"","step_time":[""],"to":"'. $to_final .'","tmp_to":"'. $tmp_to_city .'","time2":"14:56","price":"2,00","people":"2","desc":"","car":"","speed":"0","mark":"","baggage":"1","name":"Daniel","mobile":"321321312321","phone":"31321321321","template":""}',[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);

        $content = $this->loginClient->getResponse()->getContent();

        return $content;
    }



   
    // login to Ride2go

    public function loginRide2Go($data) {

        $dataArray = json_decode($data, true);

        $email = $dataArray[0]['email'];
        $password = $dataArray[0]["password"];

        $this->loginClient->request('GET', 'https://ride2go.com/login?tenant=ride2go');

        
        $this->loginClient->submitForm('Anmelden', [
            'username' => $email,
            'password' => $password
        ]);
        

        $this->loginClient->request('GET', 'https://ride2go.com/my_trips?tenant=ride2go');
        
        
        $content = $this->loginClient->getResponse()->getContent();
        
        return $content;
        
       
    }


    // scrape ride2go

    public function scrapeRide2Go($content) {
        $crawler = new Crawler($content);
        $nodeValues = $crawler->filter(' body > #tabs > .tab-content > .result-container > .result-container-card-extended > .trip-url > #result-container-card');
     
        $result = $nodeValues->each(function (Crawler $node, $i) {

            $origin = $node->filter('.trip-card-origin')->first()->text();
            $destinaiton = $node->filter('.trip-card-destination')->first()->text();
            $date = $node->filter('.trip-card-date')->first()->text();
            $time = $node->filter('.trip-local-time')->first()->text();

            $crawledData = ["origin" => $origin,
                          "destination" => $destinaiton,
                          "date" => $date,
                          "time" => $time
                          ];
           
           
            $jsonData = json_encode($crawledData);
            echo $jsonData;
       });
    }


 
}
?>