<?php 

namespace OCA\Rides\Service;

use OCP\IUserSession;

require_once __DIR__ . '/../../config.php';

class ApiService {

     /** @var IUser */
	private $currentUser;

    public function __construct(IUserSession $currentUser) {
        $this->currentUser = $currentUser->getUser();

    }


    // getting API header
    public function getHeader($apiKey) {

        return array(
            'accept: application/json',
            'X-API-Key: ' . $apiKey,
            'Content-Type: application/json'
        );

    }


    // getting API body
    public function getBody($rideID, $agencyID, $hostUrl, $startDest, $finalDest, $tripDate, $tripTime) {

        return array(
			'id' => $rideID,
			'agency' => $agencyID,
			'deeplink' => $hostUrl,
			'stops' => array(
				array(
					'id' => 'de:08115:4802:0:3',
					'name' => $startDest,
					'lat' => 48.5948979,
					'lon' => 8.8684534
				),
				array(
					'id' => 'de:08111:6221:3:6',
					'name' => $finalDest,
					'lat' => 48.7733275,
					'lon' => 9.167159
				)
			),
			'departureTime' => $tripTime,
			'departureDate' => $tripDate
		);
		
    }


    // Post amarillo rides
    public function callAmarilloRides($apiUrl, $data, $header) {

        $ch = curl_init($apiUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		
		$response = curl_exec($ch);
		curl_close($ch);

        return $response;

    }


    public function getEnabledServices() {

        $currentUser = $this->currentUser->getUID();
        $baseDir = ROOT_DIR . "/settings/";
        $jsonDirectory = glob($baseDir . $currentUser . '_apisettings.json');
    
        $jsonData = [] ;
    
        foreach ($jsonDirectory as $file) {
            $fileContent = file_get_contents($file);
            $decodedData = json_decode($fileContent); 
            if ($decodedData !== null && !empty($decodedData)) {
                $jsonData = $decodedData;
            }
        }
    
        return json_encode($jsonData);


    }


    public function getApiServiceDetails($data) {
        $currentUser = $this->currentUser->getUID();
        $baseDir = ROOT_DIR . "/settings/";
        $jsonFile = glob($baseDir . $currentUser . '*_settings.json');
    
        $returnData = "";

        foreach($jsonFile as $file) {
            $fileContent = file_get_contents($file);
            $decodedData = json_decode($fileContent); 
            if ($decodedData !== null && !empty($decodedData)) {
                $returnData = $decodedData;
            } 
        }

        $decodedArray = json_encode(array($returnData));

        return $decodedArray;
    

    }
   

    public function getDeleteHeader($apiKey) {
        return array (
            'accept: application/json',
            'X-API-Key:' . $apiKey
        );
    }


    public function deleteAmarilloRide($url, $apiKey, $header) {

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Error: ' . curl_error($ch);
        } else {
            echo 'Response: ' . $response;
        }

        curl_close($ch);

    }




}

?>


