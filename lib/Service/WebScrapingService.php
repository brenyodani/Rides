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
        $origin = $content["origin"];
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
        if(str_contains($content, "alert")) {

            return null;

         }else{
        $response = explode("mitfahrgelegenheiten\/", $content);
        $id_to_explode = implode("",$response);
        $response_explode = explode("/",$id_to_explode);
        $id = explode("'",$response_explode[1])[0];



        $bmf_ride = array (
            "origin" => $origin,
            "final" => $destination,
            "date" => $date,
            "price" => $price,
            "time" => $time,
            "id" => $id,
            "agency" => "Bmf"
        );


        return json_encode($bmf_ride);

      } 
    }


    public function deleteBmfRide($data) {

        $id = $data;
        $this->loginClient->request('GET' , 'https://www.bessermitfahren.de?cls=page_ajax&action=get&ajax=offer_delete&args[]=' . $id ,[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);

        
    }
   
    public function editBmfRide($data, $origin, $final) {

        $id = $data["id"];
        $date = $data["date"];
        $time = $data["time"];
  
        $this->loginClient->request('GET' , 'https://www.bessermitfahren.de?cls=page_ajax&action=get&ajax=offer_save&args[]={"id":"' . $id .'","gender":"d4as8dSS","autostopover_distance":"50","frequency":"0","vehicle":"car","date":"'. $date .'","from":"'. $origin[1] .'","tmp_from":"'. $origin[0] .'","time":"'. $time .'","ontime":"0","stopmode":["0"],"step":[""],"tmp_step":"","step_time":[""],"to":"'. $final[1] .'","tmp_to":"'. $final[0] .'","time2":"11:12","price":"2,00","people_max":"2","people":"2","online_1701903600":"1","people_1701903600":"2","online_1701990000":"1","people_1701990000":"2","online_1702076400":"1","people_1702076400":"2","online_1702162800":"1","people_1702162800":"2","online_1702249200":"1","people_1702249200":"2","online_1702335600":"1","people_1702335600":"2","online_1702422000":"1","people_1702422000":"2","online_1702508400":"1","people_1702508400":"2","online_1702594800":"1","people_1702594800":"2","online_1702681200":"1","people_1702681200":"2","online_1702767600":"1","people_1702767600":"2","online_1702854000":"1","people_1702854000":"2","online_1702940400":"1","people_1702940400":"2","online_1703026800":"1","people_1703026800":"2","online_1703113200":"1","people_1703113200":"2","online_1703199600":"1","people_1703199600":"2","online_1703286000":"1","people_1703286000":"2","online_1703372400":"1","people_1703372400":"2","online_1703458800":"1","people_1703458800":"2","online_1703545200":"1","people_1703545200":"2","online_1703631600":"1","people_1703631600":"2","online_1703718000":"1","people_1703718000":"2","online_1703804400":"1","people_1703804400":"2","online_1703890800":"1","people_1703890800":"2","online_1703977200":"1","people_1703977200":"2","online_1704063600":"1","people_1704063600":"2","online_1704150000":"1","people_1704150000":"2","online_1704236400":"1","people_1704236400":"2","online_1704322800":"1","people_1704322800":"2","online_1704409200":"1","people_1704409200":"2","online_1704495600":"1","people_1704495600":"2","online_1704582000":"1","people_1704582000":"2","online_1704668400":"1","people_1704668400":"2","online_1704754800":"1","people_1704754800":"2","online_1704841200":"1","people_1704841200":"2","online_1704927600":"1","people_1704927600":"2","online_1705014000":"1","people_1705014000":"2","online_1705100400":"1","people_1705100400":"2","online_1705186800":"1","people_1705186800":"2","online_1705273200":"1","people_1705273200":"2","online_1705359600":"1","people_1705359600":"2","online_1705446000":"1","people_1705446000":"2","desc":"","car":"","speed":"0","mark":"","baggage":"1","name":"Daniel","mobile":"321321312321","phone":"31321321321","template":""}'  ,[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);

    }


    public function getCityDetailsBmf($city) {

        
        $this->loginClient->request('GET' , 'https://www.bessermitfahren.de/search.php?q='. $city .'&_=',[],[],['HTTP_COOKIE' => ["sid"=>$this->loginClient->getCookieJar()->get('sid')->getValue()]]);

        $search_response = $this->loginClient->getResponse()->getContent();
        $search_to_array = explode("new Array", $search_response);
        $city_array = explode("()" , $search_to_array[1]);
        $city_code_array = explode("()" , $search_to_array[2]);

        $city = explode("'", $city_array[0])[1];
        $city_code = explode("'", $city_code_array[0])[1];

        $finals = [$city, $city_code];

        return $finals;
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

    // registering rides to r2g
    public function registerRideR2G($content) {

       
        $date = $content["date"];
        $time = $content["time"];
        $origin = $content["origin"];
        $destination = $content["final"];
        $price = $content["price"];


       $this->loginClient->request("POST", 'https://ride2go.com/trip/createOffer/?typeOfTrip=offer&carTrainSelector=car&repeating=single&departDate='. $date .'&departTime='. $time .'&origin='. $origin .'&stopoverPrice1=&stopoverAddress1=&stopoverDepartTime1='. $time .'&stopoverPrice2=&stopoverAddress2=&stopoverDepartTime2=16%3A00&stopoverPrice3=&stopoverAddress3=&stopoverDepartTime3=16%3A00&destinationPrice=&destination='. $destination .'&price=&currency=EUR&availablePlaces=3&luggageSize=medium&pets=ask&gender=unknown&smoking=no&description=&namePrivacy=visible&email=brenyodani%40gmail.com&emailPrivacy=visible&mobilePhoneNumber=&mobilePhonePrivacy=visible&landlineNumber=&landlinePrivacy=visible&carName=&carPrivacy=visible&numberPlate=&numberPlatePrivacy=visible');
       
       $content = $this->loginClient->getResponse()->getContent();

       return $content;
    }


    // somehow retreive registered ride id from url from content
    public function getIDRide2G($content) {


        $crawler = new Crawler($content);
        $nodeValues = $crawler->filter(' body > .result-container > .result-container-content > #cards-container > .result-container-card-extended ');
        $result = $nodeValues->each(function (Crawler $node, $i) {

            $url = $node->filter('.trip-url');
            $uri = $url->attr('href');

            $uri_explode = explode("/", $uri);
            $id = explode("?", $uri_explode[2]);
            return $id[0];

        });
        $jsonData = [];
        foreach($result as $id) {
            // IF MÁR LE VAN MENTVE CONTINUE
            // ELSE jsonData =  ['url'=>$id]; break;
            $jsonData = ['url'=>$id];
        }
        return $jsonData;

}


    public function deleteRideR2G($id) {
            
        
            
}


}
?>