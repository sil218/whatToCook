<?php

namespace Sil\WhatToCookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/whattocook');

        $this->assertTrue($crawler->filter('html:contains("What to cook?")')->count() > 0);
    }

    public function testUploadFridgeFail()
    {
    	$client = static::createClient();

        $crawler = $client->request('POST', '/uploadFridge');
        $this->assertEquals(
		    Response::HTTP_BAD_REQUEST,
		    $client->getResponse()->getStatusCode()
		);
    }

    public function testUploadFridgeSuccess()
    {
    	$client = static::createClient();

		$file = new UploadedFile(
		    './web/fridge.csv',
		    'fridge.csv',
		    'application/vnd.ms-excel',
		    123
		);

		$client->request(
		    'POST',
		    '/uploadFridge',
		    array(),
		    array('file' => $file),
		    array('CONTENT_TYPE' => 'Content-Type:multipart/form-data')
		);

        $this->assertEquals(
		    Response::HTTP_OK,
		    $client->getResponse()->getStatusCode()
		);

    }

    public function testUploadRecipes()
    {
    	$client = static::createClient();

        $crawler = $client->request('POST', '/uploadRecipes');

        $this->assertEquals(
		    Response::HTTP_BAD_REQUEST,
		    $client->getResponse()->getStatusCode()
		);
    }

    public function testUploadRecipesSuccess()
    {
    	$client = static::createClient();

		$file = new UploadedFile(
		    './web/recipes.json',
		    'recipes.json',
		    'text/json',
		    123
		);

		$client->request(
		    'POST',
		    '/uploadRecipes',
		    array(),
		    array('file' => $file),
		    array('CONTENT_TYPE' => 'Content-Type:multipart/form-data')
		);

        //$crawler = $client->request('POST', '/uploadFridge');
        $this->assertEquals(
		    Response::HTTP_OK,
		    $client->getResponse()->getStatusCode()
		);

    }

    public function testLetCook()
    {
    	$client = static::createClient();

        $crawler = $client->request('POST', '/letCook');

        $this->assertTrue($crawler->filter('html:contains("So...You can cook")')->count() > 0);
    }
}
