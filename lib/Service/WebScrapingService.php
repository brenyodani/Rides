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


    public function loginBesserMitFahren($data){
        
        
        $dataArray = json_decode($data, true);

        $email = $dataArray[0]['email'];
        $password = $dataArray[0]["password"];
        $this->loginClient->request('POST', 'https://www.bessermitfahren.de?cls=page_ajax&action=get&ajax=login&args[]={"gender":"F4wojdSS","email":"' . $email . '","password":"' . $password .'"}');

        $this->loginClient->request('GET', 'https://www.bessermitfahren.de/login',[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);
        $content = $this->loginClient->getResponse()->getContent();

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
                          "people" => $people ];
           
           
            $jsonData = json_encode($crawledData);
            echo $jsonData;
       });
    }
   

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


