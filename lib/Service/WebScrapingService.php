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

    public function scrapeBesserMitFahren() {
        $response = $this->client->request('GET', 'https://www.bessermitfahren.de');
    
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            
            $crawler = new Crawler($content);
            $nodeValues = $crawler->filter('body > #content > #main > .tabcontent > #tab1-next > .resultlist > li > a');
            $result = $nodeValues->each(function (Crawler $node, $i) {

                $origin = $node->filter('.from')->first()->text();
                $destinaiton = $node->filter('.to')->first()->text();
                $date = $node->filter('.date')->first()->text();
                $time = $node->filter('.time')->first()->text();
                $price = $node->filter('.price')->first()->text();
                $people = $node->filter('.people')->first()->text();

                $crawledData = [ "origin" => $origin,
                              "destination" => $destinaiton,
                              "date" => $date,
                              "time" => $time,
                              "price" => $price,
                              "people" => $people];
                echo json_encode($crawledData);
           });

         } else {
            return 'Failed to fetch the page';
        }
    }


    public function loginBesserMitFahren($data){
        
        
        $dataArray = json_decode($data, true);

        $email = $dataArray[0]['email'];
        $password = $dataArray[0]["password"];
        $this->loginClient->request('POST', 'https://www.bessermitfahren.de?cls=page_ajax&action=get&ajax=login&args[]={"gender":"F4wojdSS","email":"' . $email . '","password":"' . $password .'""}');

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
   

}



?>