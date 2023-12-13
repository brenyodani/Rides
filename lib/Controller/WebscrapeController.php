<?php



namespace OCA\Rides\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCA\Rides\Service\WebScrapingService;
use OCP\IRequest;
use OCA\Rides\Service\FileService;




class WebscrapeController extends Controller {
    

    /** @var WebScrapingService */
    private $webScraper;
    /** @var FileService */
    private $fileService;


    public function __construct($AppName, 
                                WebScrapingService $webScraper,
                                IRequest $request,
                                FileService $fileService) {
        parent::__construct($AppName, $request);
        $this->webScraper = $webScraper;
        $this->fileService = $fileService;

    }



    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function showScrapedContent() {
        $scrapedData = $this->webScraper->scrapeBesserMitFahren();

        return $scrapedData;
    }

    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function loginBesserMitFahren() {
       $data = $this->fileService->readBmfSettings(); 
       $scrapedData = $this->webScraper->loginBesserMitFahren($data);
       $this->webScraper->scrapeBesserMitFahren($scrapedData);

    }

    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function loginR2G() {

       $data = $this->fileService->readR2GSettings();
       $scrapedData = $this->webScraper->loginRide2Go($data);
       $this->webScraper->scrapeRide2Go($scrapedData);


    }

    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function registerRideBMF() {
        $data = $this->fileService->readBmfSettings();
        $scrapedData = $this->webScraper->loginBesserMitFahren($data);
        
        $response = $this->webScraper->registerRideBMF($rideData);
        $this->fileService->saveBmfRide($response);
    }


     /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
    public function registerR2G() {
        //login to ride2go
        $data = $this->fileService->readR2GSettings();
        $scrapedData = $this->webScraper->loginRide2Go($data);

        $details = $this->fileService->getRideDetails();
        
        //registering ride 
        $content = $this->webScraper->registerRideR2G($details);
        $id_json = $this->webScraper->getIDRide2G($content);


        $this->fileService->saveRideR2G($details, $id_json);
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

     /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function searchFromCity() {
        $this->webScraper->searchCity();
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function searchToCity() {
        $this->webScraper->searchToCity();
    }
}



?>