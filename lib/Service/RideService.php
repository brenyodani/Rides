<?php 

namespace OCA\Rides\Service;

use OCP\Files\IRootFolder;
use OCP\Files\IAppData;
use OCP\Files\Storage\IStorage;
use OCP\Files\Node\Folder;
use OCP\IUserSession;

class RideService {

    /** @var IAppData */
    private $appData;
    
    /** @var Folder */
    private $folder;
    
    /** @var IUser */
	private $currentUser;




    public function __construct(IUserSession $userSession, 
                                $userId
                                ) 
    {
        $this->currentUser = $userSession->getUser();
    }


    public function setRidesDetails() {
        
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
    



    public function createRideFile($data, $id) {
        
        $currentUser = $this->currentUser -> getUID();

        $fileName = $currentUser . "_" .  $id . ".json";
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/apps-writable/rides/rides/";

        $filePath =   $baseDir . $fileName;
        $data["id"] = $id;
        $content = json_encode($data);


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
    


    public function createId() {
       return $id = rand(1,9999);
    }


    public function checkID($id) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/apps-writable/rides/rides/id.txt";
        try {

            $ids = file_get_contents($filePath);
    
            $idArray = explode("/", trim($ids));
    
            while (in_array($id, $idArray)) {
                $id++;
            }
    
            $idArray[] = $id;
    
            file_put_contents($filePath, implode('/', $idArray));
    
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    
    public function deleteID($content) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/apps-writable/rides/rides/id.txt";
    
        ;
    
        try {
            $ids = file_get_contents($filePath);
    
            $idArray = explode('/', trim($ids));
    
            foreach($idArray as $key => $value) {
               
                $trimmedValue = trim($value);
                
                if(strval($content) === $trimmedValue) {
                    unset($idArray[$key]);
                }

            }
    
            file_put_contents($filePath, implode('/', $idArray));
            error_log('ID deleted successfully.');
    
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            error_log('Error: ' . $e->getMessage());
        }
    }
    

  


    }
    
    
    

        


















?>