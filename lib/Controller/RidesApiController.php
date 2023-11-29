<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Daniel Brenyo <brenyodani@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Rides\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\ApiController;
use OCA\Rides\Service\RideService;
use OCA\Rides\Service\FileService;
use OCA\Rides\Service\ApiService;



class RidesApiController extends ApiController
{

    /** @var RideService */
    private $rideService;
    /** @var FileService */
    private $fileService;
    /** @var ApiService */
    private $apiService;

    public function __construct(string $AppName,
                                IRequest $request,
                                RideService $rideService,
                                FileService $fileService,
                                ApiService $apiService) {
        parent::__construct($AppName, $request);
        $this->rideService = $rideService;
        $this->fileService = $fileService;
        $this->apiService = $apiService;
    }

    
    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
     * @return JSONResponse
	 */
    public function setRideDetails() {
        
        $content = $this->rideService->setRidesDetails();
        $id = $this->rideService->createId();
        $this->rideService->checkID($id);

        $enabledServices = $this->apiService->getEnabledServices();
        $apiServiceDetails = $this->apiService->getApiServiceDetails($enabledServices);

        $apiUrl = "https://amarillo-dev.mfdz.de/carpool/";

        $header = $this->apiService->getHeader($apiServiceDetails["apiKey"]);
        $body = $this->apiService->getBody(
            strval($id),
            $apiServiceDetails["userName"],
            $apiServiceDetails["password"],
            $content["original"],
            $content["final"],
            $content["date"],
            $content["time"] 
    );


    $curl = $this->apiService->callAmarilloRides($apiUrl, $body, $header);

    if(http_response_code() === 200) {
        $this->rideService->createRideFile($content, $id);
        return $curl;
    } else {
        throw new Exception("Something went wrong");
    }
}

    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
     * @return JSONResponse
	 */
    public function getRides() {
        $result = $this->fileService->readFiles();
        header('Content-Type: application/json');
        echo $result;
    }
  
    
  	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function editRide() {

        $data = $this->fileService->getRideDetails();

        $enabledServices = $this->apiService->getEnabledServices();
        $apiServiceDetails = $this->apiService->getApiServiceDetails($enabledServices);

        $apiUrl = "https://amarillo-dev.mfdz.de/carpool/";

        $header = $this->apiService->getHeader($apiServiceDetails["apiKey"]);
        $body = $this->apiService->getBody(
            strval($data["id"]),
            $apiServiceDetails["userName"],
            $apiServiceDetails["password"],
            $data["original"],
            $data["final"],
            $data["date"],
            $data["time"] 
            );

        $curl = $this->apiService->callAmarilloRides($apiUrl, $body, $header);



        if(http_response_code() === 200) {
            $this->fileService->editFiles($data);
            return "File edited succesfully";
        }

    }


    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function deleteRide() {
        

        $enabledServices = $this->apiService->getEnabledServices();
        $apiServiceDetails = $this->apiService->getApiServiceDetails($enabledServices);
        $apiKey = $apiServiceDetails["apiKey"];
        $header = $this->apiService->getDeleteHeader($apiKey);



        $content = $this->fileService->getRideDetails();
        $apiUrl = "https://amarillo-dev.mfdz.de/carpool/" . $apiServiceDetails["userName"] . "/" . $content;


        $this->apiService->deleteAmarilloRide($apiUrl, $apiKey, $header);

        if(http_response_code() === 200) {
            return "Ride deleted succesfully";
            $this->fileService->deleteRideFile($content);
            $this->rideService->deleteID($content);
        } else {
            return "something went wrong";
        }

    
    }

    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function createUserSettings() {
        $data = $this->fileService->getUserSettings();
        $this->fileService->createUserSettings($data);
    }


   /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
     * @return JSONResponse
	 */
    public function getUserSettings() {
        $result = $this->fileService->readUserSettings();
        header('Content-Type: application/json');
        echo $result;
    }

   	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function saveUserApiSettings() {
        $data = $this->fileService->getUserApiSettings();
        $this->fileService->saveUserApiSettings($data);
    }



    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function saveBmfSettings() {
        $data = $this->fileService->getBmfSettings();
        $this->fileService->saveBmfSettings($data);
    }

}



