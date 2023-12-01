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

      
            $this->fileService->editFiles($data);
            return "File edited succesfully";

    }


    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function deleteRide() {
        

        $content = $this->fileService->getRideDetails();
    
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



    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function saveBmfSettings() {
        $data = $this->fileService->getBmfSettings();
        $this->fileService->saveBmfSettings($data);
    }


    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function saveR2GSettings() {
        $data = $this->fileService->getR2GSettings();
        $this->fileService->saveR2GSettings($data);
    }

}




