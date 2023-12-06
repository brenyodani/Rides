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
        return $response;
    }

}



?>