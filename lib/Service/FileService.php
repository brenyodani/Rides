<?php

namespace OCA\Rides\Service;

use OCP\IUserSession;



class FileService {

    /** @var IUser */
	private $currentUser;

    public function __construct(IUserSession $currentUser) {
        $this->currentUser = $currentUser->getUser();
    }

    
    public function readFiles() {
        $currentUser = $this->currentUser->getUID();
        $jsonDirectory = "/var/www/html/apps/rides/rides/" . $currentUser . '*.json';
        $jsonData = [];
    
        foreach ($jsonDirectory as $file) {
            $fileContent = file_get_contents($file);
            $decodedData = json_decode($fileContent, false);

            if ($decodedData !== null && !empty($decodedData)) {
                $jsonData[] = $decodedData;
            }
        }
    
        return json_encode($jsonData);
    }
    

    public function getRideDetails() {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        
        if ($data !== null) {
            
            $response = [
                'data' => $data,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(400); 
            echo json_encode(['error' => 'Invalid JSON data']);
        }

        return $data;
    }

    public function editFiles($data) {
        
        $currentUser = $this->currentUser->getUID();

        $id = $data["id"];

        $fileName = $currentUser . "_" .  $id . ".json";
        $filePath =   '/var/www/html/apps/rides/rides/' . $fileName;
        
        try{  
        
        $content = json_encode($data);
        file_put_contents($filePath,$content);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }



    public function deleteRideFile($data){

        $currentUser = $this->currentUser->getUID();
        $id = $data;

        $fileName = $currentUser . "_" .  $id . ".json";
        $filePath =   '/var/www/html/apps/rides/rides/' . $fileName;
        
        try{
            if (!unlink($filePath)) { 
                echo ("$filePath cannot be deleted due to an error"); 
            } 
            else { 
                echo ("$filePath has been deleted"); 
            } 
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }



}



?>


