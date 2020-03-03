<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Res;
use Symfony\Component\HttpFoundation\Request;

class ShowController extends AbstractController
{

    /** @Route(
		"/tv_show",
		name="tv_show_schedule",
		methods={"GET","HEAD"}
	)*/
    public function showSchedule(){

        $_todayDate = date('Y-m-d');
        $client = HttpClient::create();
		$response = $client->request('GET', 'http://api.tvmaze.com/schedule?country=US&date='.$_todayDate);

		$statusCode = $response->getStatusCode();
		if (200 !== $response->getStatusCode()) {
    		$return_default =  new JsonResponse([
	            'error_no' => '-200',
	            'error_msg' => 'API Failure',
	            'stories' => [],
	         ]);
		    return $return_default;exit;
		}

		/*if ('application/json' !==  $response->getHeaders()['content-type'][0]){
			$return_default =  new JsonResponse([
	            'error_no' => '-200',
	            'error_msg' => 'API Failure',
	            'stories' => [],
	         ]);
		     return $return_default;exit;
		}*/

		//$content = $response->getContent();
		$content = $response->toArray();
        dump($content);exit;
		$newArray = array();
		foreach ($content['hits'] as $key => $value) {
			//dump($value['created_at_i']);exit;
			$createdTime = $value['created_at_i'];
			$value['created_at_i'] = date("d-M-Y", $createdTime);
			$newArray[] = $value;
		}

        return $this->render('tvshow/show.html.twig', [
            'title' => 'TV Show',
            'schedule' => $newArray,
        ]);

    }
}

?>