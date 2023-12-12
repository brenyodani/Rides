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
use OCA\Rides\Service\WebScrapingService;



class RidesApiController extends ApiController
{

    /** @var RideService */
    private $rideService;
    /** @var FileService */
    private $fileService;
    /** @var ApiService */
    private $apiService;
    /** @var WebScrapingService */
    private $webScraper;


    public function __construct(string $AppName,
                                WebScrapingService $webScraper,
                                IRequest $request,
                                RideService $rideService,
                                FileService $fileService,
                                ApiService $apiService) {
        parent::__construct($AppName, $request);
        $this->rideService = $rideService;
        $this->fileService = $fileService;
        $this->apiService = $apiService;
        $this->webScraper = $webScraper;

    }

    
    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
     * @return JSONResponse
	 */
    public function setRideDetails() {
        
        // gets the ride details
        $content = $this->rideService->setRidesDetails();

        //creates and checks if NC_ID exists
        $id = $this->rideService->createId();
        $this->rideService->checkID($id);

        foreach($content["agency"] as $agency) {
            if($agency["name"] === "bmf" && $agency["value"] === true) {
               
                $loginData = $this->fileService->readBmfSettings(); 
                $this->webScraper->loginBesserMitFahren($loginData);
                $bmf_id = $this->webScraper->registerRideBMF($content);

            }
            if($agency["name"] === "r2g" && $agency["value"] === true) {
                
                $data = $this->fileService->readR2GSettings();
                $scrapedData = $this->webScraper->loginRide2Go($data);

               $details = $this->webScraper->registerRideR2G($content);
                $id_json = $this->webScraper->getIDRide2G($details);
            }
        }

        $content["bmf_id"] = $bmf_id;
        $content["r2g_id"] = $id_json["url"];
        

        //creates file if all the responses came back 200
        $this->rideService->createRideFile($content, $id);



        



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
       

        // editing the bmf part
       if($data["bmf_id"] !== null) {
            $loginData = $this->fileService->readBmfSettings(); 
            $bmf_id = $data["bmf_id"];
            $this->webScraper->loginBesserMitFahren($loginData);

            $origin = $this->webScraper->getCityDetailsBmf($data["origin"]);
            $final = $this->webScraper->getCityDetailsBmf($data["final"]);

            $this->webScraper->editBmfRide($data, $origin, $final);
    }


        if($data["r2g_id"] !== null) {
            $loginData = $this->fileService->readR2GSettings();
            $scrapedData = $this->webScraper->loginRide2Go($loginData);

            $this->webScraper->editRideR2G($data);

        }


        $this->fileService->editFiles($data);

        return "File edited succesfully";

    }


    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function deleteRide() {
        

        $content = $this->fileService->getRideDetails();
        

        if($content["bmf_id"] !== null) {
        //BMF part
        $data = $this->fileService->readBmfSettings(); 
        $this->webScraper->loginBesserMitFahren($data);
        $this->webScraper->deleteBmfRide($content["bmf_id"]);
        
    }

        if($content["r2g_id"] !== null) {
            $data = $this->fileService->readR2GSettings();
            $scrapedData = $this->webScraper->loginRide2Go($data);

            $this->webScraper->deleteRideR2G($content["r2g_id"]);
        }


        $this->fileService->deleteRideFile($content);
        $this->rideService->deleteID($content);
        return "Ride deleted succesfully";


    
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


}




