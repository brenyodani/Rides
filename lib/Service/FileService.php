<?php

namespace OCA\Rides\Service;

use OCP\IUserSession;



class FileService {

    /** @var IUser */
	private $currentUser;

    public function __construct(IUserSession $currentUser) {
        $this->currentUser = $currentUser->getUser();
    }

    
    // getting rides of the logged in user
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
    
    
    // getting the input values from frontend
    public function getRideDetails() {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        
        if ($data !== null) {
            
            $response = [
                'data' => $data,
            ];
            echo json_encode($response);
        } else {
            http_response_code(400); 
            echo json_encode(['error' => 'Invalid JSON data']);
        }

        return $data;
    }

    // edit existing ride json
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
    


    // delete already existing ride
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

    // getting user settings from input fields
    public function getUserSettings() {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        
        if ($data !== null) {
            
            $response = [
                'data' => $data,
            ];
            echo json_encode($response);
        } else {
            http_response_code(400); 
            echo json_encode(['error' => 'Invalid JSON data']);
        }

        return $data;    
    }

    // creating user settiongs json
    public function createUserSettings($data) {
        $currentUser = $this->currentUser->getUID();
        $content = json_encode($data);
        $decodedContent = json_decode($content, true); 
        
        if (!$decodedContent || !isset($decodedContent["serviceName"])) {
            echo "Error: Invalid data or missing serviceName";
            return;
        }
        
        $serviceName = $decodedContent["serviceName"];
        $fileName = $currentUser . "_" . $serviceName . "_settings.json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/settings/";
        $filePath = $baseDir . $fileName;
        
        try {
            if (!file_exists($baseDir)) {
                mkdir($baseDir, 0777, true); 
            }
            if (file_exists($filePath)) {
               
                throw new Exception("File already created.");

            }
            
            if (!file_exists($filePath)) {
                file_put_contents($filePath, $content);
                echo "File created and written successfully";
            } else {
                $fileHandle = fopen($filePath, "a+");
                
                if ($fileHandle === false) {
                    throw new Exception("Failed to open the file for writing.");
                }
                
                if (fwrite($fileHandle, $content) === false) {
                    throw new Exception("Failed to write data to the file.");
                }
                
                fclose($fileHandle);
                echo "Data appended to the existing file successfully";
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    

    
    // getting rides of the logged in user
    public function readUserSettings() {

        $currentUser = $this->currentUser->getUID();
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/settings/";
        $jsonDirectory = glob($baseDir . $currentUser . '*_settings.json');
    
        $jsonData = [];
    
        foreach ($jsonDirectory as $file) {
            $fileContent = file_get_contents($file);
            $decodedData = json_decode($fileContent); 
            if ($decodedData !== null && !empty($decodedData)) {
                $jsonData[] = $decodedData;
            }
        }
    
        return json_encode($jsonData);
    }


    public function getUserApiSettings() {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
    
        if ($data !== null) {
          
            $response = [
                'message' => 'Data received successfully',
                'receivedData' => $data,
            ];
            echo json_encode($response);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON data']);
        }
        return $data;
    }


    // saving API endpoints for API calls 
    public function saveUserApiSettings($data) {

        $currentUser = $this->currentUser->getUID();
        $content = json_encode($data);
        $decodedContent = json_decode($content, true); 
        
        $fileName = $currentUser . "_apisettings.json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/settings/";
        $filePath = $baseDir . $fileName;
        
        try {
            if (!file_exists($baseDir)) {
                mkdir($baseDir, 0777, true); 
            }
            
            if (!file_exists($filePath)) {
                file_put_contents($filePath, $content);
                return "File created and written successfully";
            } else {
                $fileHandle = fopen($filePath, "w+");
                
                if ($fileHandle === false) {
                    throw new Exception("Failed to open the file for writing.");
                }
                
                if (fwrite($fileHandle, $content) === false) {
                    throw new Exception("Failed to write data to the file.");
                }
                
                fclose($fileHandle);
                return "Data appended to the existing file successfully";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }

    }


    public function getBmfSettings() {

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
    
        if ($data !== null) {
          
            $response = [
                'message' => 'Data received successfully',
                'receivedData' => $data,
            ];
            echo json_encode($response);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON data']);
        }
        return $data;
    }



    public function saveBmfSettings($data) {

        $currentUser = $this->currentUser->getUID();
        $content = json_encode($data);
        $decodedContent = json_decode($content, true); 

        $fileName = $currentUser . "_bmfsettings.json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/settings/";
        $filePath = $baseDir . $fileName;
        
                
        try {
            if (!file_exists($baseDir)) {
                mkdir($baseDir, 0777, true); 
            }
            
            if (!file_exists($filePath)) {
                file_put_contents($filePath, $content);
                return "File created and written successfully";
            } else {
                $fileHandle = fopen($filePath, "w+");
                
                if ($fileHandle === false) {
                    throw new Exception("Failed to open the file for writing.");
                }
                
                if (fwrite($fileHandle, $content) === false) {
                    throw new Exception("Failed to write data to the file.");
                }
                
                fclose($fileHandle);
                return "Data appended to the existing file successfully";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function readBmfSettings(){
        
        $currentUser = $this->currentUser->getUID();
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/settings/";
        $jsonDirectory = glob($baseDir . $currentUser . '*_bmfsettings.json');
    
        $jsonData = [];
    
        foreach ($jsonDirectory as $file) {
            $fileContent = file_get_contents($file);
            $decodedData = json_decode($fileContent); 
            if ($decodedData !== null && !empty($decodedData)) {
                $jsonData[] = $decodedData;
            }
        }
    
        return json_encode($jsonData);
    }



    // handling r2g login details get details->save details->use details


    public function getR2GSettings() {

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
    
        if ($data !== null) {
          
            $response = [
                'message' => 'Data received successfully',
                'receivedData' => $data,
            ];
            echo json_encode($response);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON data']);
        }
        return $data;
    }



    public function saveR2GSettings($data) {

        $currentUser = $this->currentUser->getUID();
        $content = json_encode($data);
        $decodedContent = json_decode($content, true); 

        $fileName = $currentUser . "_r2gsettings.json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/settings/";
        $filePath = $baseDir . $fileName;
        
                
        try {
            if (!file_exists($baseDir)) {
                mkdir($baseDir, 0777, true); 
            }
            
            if (!file_exists($filePath)) {
                file_put_contents($filePath, $content);
                return "File created and written successfully";
            } else {
                $fileHandle = fopen($filePath, "w+");
                
                if ($fileHandle === false) {
                    throw new Exception("Failed to open the file for writing.");
                }
                
                if (fwrite($fileHandle, $content) === false) {
                    throw new Exception("Failed to write data to the file.");
                }
                
                fclose($fileHandle);
                return "Data appended to the existing file successfully";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function readR2GSettings(){
        
        $currentUser = $this->currentUser->getUID();
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/settings/";
        $jsonDirectory = glob($baseDir . $currentUser . '*_r2gsettings.json');
    
        $jsonData = [];
    
        foreach ($jsonDirectory as $file) {
            $fileContent = file_get_contents($file);
            $decodedData = json_decode($fileContent); 
            if ($decodedData !== null && !empty($decodedData)) {
                $jsonData[] = $decodedData;
            }
        }
    
        return json_encode($jsonData);
    }


    public function saveBmfRide($data_array) {
        
        $data = json_decode($data_array, true);

        if($data_array === null) {
            return;
        }

        $id = $data["id"];        
        $currentUser = $this->currentUser -> getUID();

        $fileName = $currentUser . "_" . $id . ".json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/rides/";
        $filePath =   $baseDir . $fileName;

        try {
            if (!file_exists($filePath)) {
                file_put_contents($filePath, $data_array);
                echo "File created and written successfully";
            } else {

                $fileHandle = fopen($filePath, "a");
    
                if ($fileHandle === false) {
                    throw new Exception("Failed to open the file for writing.");
                }
    
                if (fwrite($fileHandle, $content) === false) {
                    throw new Exception("Failed to write data to the file.");
                }
    
                fclose($fileHandle);
    
                echo "Data appended to the existing file successfully";
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }


    public function saveRideR2G($details, $id) {

        $ride_ID = $id["url"];
        $details["id"] = $ride_ID;
        $details["agency"] = "R2G";

        $content = json_encode($details);

        if($content === null) {
            return;
        }

        $currentUser = $this->currentUser -> getUID();

        $fileName = $currentUser . "_" . $ride_ID . ".json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps/rides/rides/";
        $filePath =   $baseDir . $fileName;

        try {
            if (!file_exists($filePath)) {
                file_put_contents($filePath, $content);
                echo "File created and written successfully";
            } else {

                $fileHandle = fopen($filePath, "a");
    
                if ($fileHandle === false) {
                    throw new Exception("Failed to open the file for writing.");
                }
    
                if (fwrite($fileHandle, $content) === false) {
                    throw new Exception("Failed to write data to the file.");
                }
    
                fclose($fileHandle);
    
                echo "Data appended to the existing file successfully";
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }


    public function deleteRideR2G($id) {
        

        
    }


}







?>


