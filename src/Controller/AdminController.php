<?php
// src/Controller/AppAdminController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
//use Doctrine\MongoDB\Connection;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

class AdminController
{
    public function displayPhpInfo()
    {
		
		AnnotationDriver::registerAnnotationClasses();
		
		$config = new Configuration();
$config->setProxyDir('/path/to/generate/proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir('/path/to/generate/hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setMetadataDriverImpl(AnnotationDriver::create('/path/to/document/classes'));

$dm = DocumentManager::create(new Connection(), $config);
		/*
		$connection = new Connection('mongodb://localhost');
		$database = $connection->selectDatabase('my_project_database');
		$users = $database->selectCollection('users');
		$user = [
			'username' => 'jwage',
		];
		//$users->insert($user);
		//$user = $users->findOne(['username' => 'jwage']);
		//print_r($user);
		*/
        return new Response(
			'<html><body>' . phpinfo() . '</body></html>'
        );
    }

}