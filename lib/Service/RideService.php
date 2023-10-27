<?php 

namespace OCA\Rides\Service;

use OCP\Files\IRootFolder;
use OCP\Files\IAppData;


class RideService {

    /** @var IAppData */
    private $appData;
    private $storage;


    public function __construct(IRootFolder $rootFolder, 
                                $userId) {
        
        $this->storage = $rootFolder->getUserFolder($userId);

    }

    public function setRidesDetails() {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        
        return $data;
    }
    


    public function serializeRide($data) {
        try {
            try {
                file_put_contents('/id.json', $data);
                $file = $this->storage->get('/id.json');
            } catch(\OCP\Files\NotFoundException $e) {
                $file = $this->storage->get('/id.json');

            } 

            $id = $file->getContent();

        } catch(\OCP\Files\NotPermittedException $e) {

            throw new StorageException('Cant write to file');
        }

        $idArray = array("id" => $id);
        $mergedArray = array_merge($idArray, $data);

        return $mergedArray;
    }





    public function setRideFile($data) {
        
        try {
            try {
                $file = $this->storage->get('/rides1.json');
            } catch(\OCP\Files\NotFoundException $e) {
                $this->storage->touch('/rides1.json');
                $file = $this->storage->get('/rides1.json');

            } 

            $file->putContent($data);

        } catch(\OCP\Files\NotPermittedException $e) {

            throw new StorageException('Cant write to file');
        }
    }
   



}
    
















?>