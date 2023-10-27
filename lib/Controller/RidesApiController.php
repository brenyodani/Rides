<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Daniel Brenyo <brenyodani@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Rides\Controller;

use OCP\IRequest;
use OCP\AppFramework\ApiController;
use OCA\Rides\Service\RideService;

class RidesApiController extends ApiController
{

    /** @var RideService */
    private $rideService;

    public function __construct(string $AppName,
                                IRequest $request,
                                RideService $rideService) {
        parent::__construct($AppName, $request);
        $this->rideService = $rideService;
    }




    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
     * @return JSONResponse
	 */
    public function setRideDetails() {

        $data = $this->rideService->setRidesDetails();
        $this->rideService->serializeRide($data);
        $this->rideService->setRideFile($data);


        return $data;
    }


  
    
    


}
