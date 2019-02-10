<?php
// src/Controller/ApiController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Config\Business;
use App\Exception\RessourceException;
use App\Utils\ResponseContent;

class ApiController extends Controller
{
    public function index(Request $request)
    {
		//$params = $request->query;
		//$request->getPathInfo()
		//var_dump($request->getMethod());
		//print_r("\r\n");
		$queryParameters = $request->query->all();
		// $request->query->get('test')
		//print_r($parametersToValidate);
		//print_r("\r\n");
		
		// Vérifie les cookies lors d'une utilisation web
		$this->getAndSetCookies($queryParameters);
		
		
		// Méthode GET ou POST
		if ($request->getMethod() === 'GET') {
			
			// Vérification des paramètres et règles métiers
			$contentResponse = $this->parametersValidation($queryParameters);
			
			
			return $this->setResponse($contentResponse);
			
			
		} elseif ($request->getMethod() === 'POST') {
			// Gestion d'un tableau de paramètre
			
			return $this->setResponse();
		} else {
			return $this->setResponseError();
		}
    }

	protected function getAndSetCookies(&$queryParameters)
	{
		// Get cookies and put them into corresponding parameters
	}
	
	protected function parametersValidation($queryParameters)
	{
		try {
			// Pas plus d'un appel par seconde pour un même évènement
			
			// Vérifie que tous les paramètre requis sont présents
			$result = $this->checkMandatoryFields($queryParameters);
			
			// Vérifie que l'utilisateur existe
			$result = $this->checkUser($queryParameters);
			
			// Vérifie qt
			$result = $this->checkQt($queryParameters);
			
			// Vérifie v
			$result = $this->checkVersion($queryParameters);
			
		} catch (Exception $e) {
			return new ResponseContent(array(
				'result' => 'ERROR',
				'code' => '400',
				'type' => get_class($e),
				'message' => $e->message
			));
		} catch (RessourceException $e) {
			
			return new ResponseContent(array(
				'result' => 'ERROR',
				'code' => '404',
				'type' => $e->type,
				'message' => $e->getMessage()
			));
		}
		
		return new ResponseContent(array(
			'result' => 'SUCCESS',
			'code' => '200',
			'type' => 'Process successfull',
			'message' => null
		));;
	}
	
	private function checkMandatoryFields($queryParameters) {
		
		$fieldsConfig = business::getFieldsConfig();
		
		foreach ($fieldsConfig as $fieldConfig) {
			if ($fieldConfig['mandatory'] === true && isset($queryParameters[$fieldConfig['name']]) === false) {
				throw new RessourceException($fieldConfig['name']);
			}
		}
		
	}
	
	private function checkUser($queryParameters) 
	{
		if (isset($queryParameters['wui']) && !empty($queryParameters['wui'])) {
			if (in_array($queryParameters['wui'], $this->getUsersWizbii())) {
				return array('result' => true, 'message' => 'Utilisateur Wizbii.');
			} else {
				throw new Exception('Utilisateur ' . $queryParameters['wui'] . ' inconnu.');
			}
		}
		throw new RessourceException('wui');
	}
	
	private function checkQt($queryParameters) 
	{
		if (isset($queryParameters['qt']) && !empty($queryParameters['qt'])) {
			if ($queryParameters['qt'] <= Business::QUEUE_TIME_MAX_DELAY) {
				return array('result' => true, 'message' => 'Qt ok.');
			} else {
				throw new Exception('Qt hors délai (délai max : ' . Business::QUEUE_TIME_MAX_DELAY . ').');
			}
		}
		throw new RessourceException('qt');
	}
	
	private function checkVersion($queryParameters) 
	{
		if (isset($queryParameters['v']) && !empty($queryParameters['v'])) {
			if (intval($queryParameters['v']) === Business::SUPPORTED_VERSION) {
				return array('result' => true, 'message' => 'Version ok.');
			} else {
				throw new Exception('Version non supportée (version courante : ' . Business::SUPPORTED_VERSION . ').');
			}
		}
		throw new RessourceException('version');
	}
	
	protected function setResponse($contentResponse)
	{
		$response = new Response();
		//$responseContent = new ResponseContent();
		//$responseContent->setProperty('message', 'monmessage');
		$response->setContent(json_encode($contentResponse));
		$response->setStatusCode(Response::HTTP_OK);
		$response->headers->set('Content-Type', 'text/json');
		return $response;
	}
	
	protected function setResponseOk($message)
	{
		$response = new Response();
		$response->setContent(
			'<html><body>'
			. 'OK' . $message
			.'</body></html>'
		);
		$response->setStatusCode(Response::HTTP_OK);
		$response->headers->set('Content-Type', 'text/html');
		return $response;
	}
	
	protected function setResponseError($message)
	{
		$response = new Response();
		$response->setContent(
			'<html><body>'
			. 'ERROR' . $message
			.'</body></html>'
		);
		$response->setStatusCode(Response::HTTP_NOT_FOUND);
		$response->headers->set('Content-Type', 'text/html');
		return $response;
	}
	
	private function getUsersWizbii() {
		return array('david', 'eric', 'bruno');
	}
}
