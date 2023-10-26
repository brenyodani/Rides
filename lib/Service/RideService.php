<?php 

namespace OCA\Rides\Service;

use OCP\Files\IRootFolder;
use OCP\Files\IAppData;


class RideService {

         /** @var IAppData */
    private $appData;

    public function __construct(IAppData $appData) {
        $this->appData = $appData;
    }


    public function setRidesDetails() {
        
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        return $data;
    }


    public function setRideFile($data) {

        $this->appData->newFolder('ride_history');
        $this->appData->getFolder('ride_history');
        $this->appData->newFile('ride_history');
        $this->appData->putContent($data);
        

    }


}













?>