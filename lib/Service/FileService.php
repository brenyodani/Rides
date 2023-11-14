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
       $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/rides/";
       $jsonDirectory = glob($baseDir . $currentUser . '*.json');
 
       $jsonData = [];
    
        foreach ($jsonDirectory as $file) {
            $fileContent = file_get_contents($file);
            $decodedData = json_decode($fileContent, true); 
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
        $fileName = $currentUser . "_" . $id . ".json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/rides/";
    
        try {
            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0777, true);
            }
    
            $filePath = $baseDir . $fileName;
            
            $content = json_encode($data);
    
            if (file_exists($filePath)) {
                file_put_contents($filePath, $content);
            } else {
                file_put_contents($filePath, $content);
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    



    public function deleteRideFile($data){

        $currentUser = $this->currentUser->getUID();
        $id = $data;

        $fileName = $currentUser . "_" .  $id . ".json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/rides/" . $fileName;
        
        try{
            if (!unlink($baseDir)) { 
                echo ("$baseDir cannot be deleted due to an error"); 
            } 
            else { 
                echo ("$baseDir has been deleted"); 
            } 
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }



}



?>


