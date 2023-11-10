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



class RidesApiController extends ApiController
{

    /** @var RideService */
    private $rideService;
    /** @var FileService */
    private $fileService;

    public function __construct(string $AppName,
                                IRequest $request,
                                RideService $rideService,
                                FileService $fileService) {
        parent::__construct($AppName, $request);
        $this->rideService = $rideService;
        $this->fileService = $fileService;
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
    }


    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function deleteRide() {
        $content = $this->fileService->getRideDetails();
        $this->fileService->deleteRideFile($content);
    }




}
