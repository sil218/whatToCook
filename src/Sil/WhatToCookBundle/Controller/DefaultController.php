<?php

namespace Sil\WhatToCookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('SilWhatToCookBundle:Default:index.html.twig');
    }

    public function uploadFridgeAction(Request $request)
    {
    	$path = $this->get('kernel')->getRootDir() . '/../web';
    	$name = 'fridge.csv';
    	$message = array();
    	if($request->files->has('file'))
    	{
    		$message = array('status'=> true,'message'=>'All good!');
    		$file = $request->files->get('file')->move($path, $name);
    	} else {
    		$message = array('status'=> false,'message'=>'Something is wrong!');
    	}
    	$response = new Response(json_encode($message));
    	$response->setStatusCode(($message['status'])?Response::HTTP_OK:Response::HTTP_BAD_REQUEST);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
    }

    public function uploadRecipesAction(Request $request)
    {
    	$path = $this->get('kernel')->getRootDir() . '/../web';
    	$name = 'recipes.json';
    	$message = array();
    	if($request->files->has('file'))
    	{
    		var_dump($request->files->get('file'));
    		$message = array('status'=> true,'message'=>'All good!');
    		$file = $request->files->get('file')->move($path, $name);
    	} else {
    		$message = array('status'=> false,'message'=>'Something is wrong!');
    	}
    	$response = new Response(json_encode($message));
    	$response->setStatusCode(($message['status'])?Response::HTTP_OK:Response::HTTP_BAD_REQUEST);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
    }

    public function letCookAction(Request $request)
    {
    	$path = $this->get('kernel')->getRootDir() . '/../web';
    	$fridgeFile = $path.'/fridge.csv';
    	$recipesFile = $path.'/recipes.json';

    	$fs = new Filesystem();
    	if($fs->exists( array($fridgeFile, $recipesFile) ) )
    	{
    		//read Fridge File
    		$fridgeLines = array();
    		$fridgeFile = @fopen($fridgeFile, "r");
			while (($buffer = fgets($fridgeFile, 4096)) !== false)
			{
			    $fridgeLines[] = $buffer;
			}

    		$fridgeArray = array();
    		$validUnitArray = array('of','grams','ml','slices');
    		foreach ($fridgeLines as $line) {
    			$item = explode(',', $line);
    			$fridgeArray[ucfirst($item[0])] = array(
    					'quantity'	=> (int)$item[1],
    					'unit'		=> (!empty($item[2]) AND in_array($item[2],$validUnitArray))?$item[2]:NULL,
    					'expire'	=> date('d-m-Y',strtotime(str_replace("/","-",$item[3])))
    				);  
    		}
    		//read Recipes File
    		$recipesArray = json_decode(file_get_contents($recipesFile, true),true);

    		//Process Fridge items list into only usable item list
    		$usableIngredients = array();
    		foreach ($fridgeArray as $name => $value) {
    			if($value['quantity'] > 0 AND $value['unit'] != NULL AND strtotime($value['expire'])>=strtotime('today'))
    			{
    				$usableIngredients[$name] = $fridgeArray[$name];
    			}
    		}

    		//Check usable item list against recipes 
			$cookAble = array();
    		foreach($recipesArray as $recipe)
    		{
    			$tempIngredients = array();
    			for($i=0;$i<count($recipe['ingredients']);$i++) {
    				$tempIngredients[ucfirst($recipe['ingredients'][$i]['item'])] = array(
    					'quantity'	=> (int)$recipe['ingredients'][$i]['amount'],
    					'unit'		=> (!empty($recipe['ingredients'][$i]['unit']) AND in_array($recipe['ingredients'][$i]['unit'],$validUnitArray))?$recipe['ingredients'][$i]['unit']:NULL,
    					);
    			}

    			//Empty array mean there are ingredients for the recipe;
    			$hasIngredients = array_diff(array_keys($tempIngredients), array_keys($usableIngredients));

    			if(empty($hasIngredients)){
    				$hasAll = true;
	    			foreach ($tempIngredients as $key => $value) {

	    				if($value['quantity'] > $usableIngredients[$key]['quantity'] OR $value['unit'] != $usableIngredients[$key]['unit'])
	    				{
	    					$hasAll=false;
	    				}
	    			}
	    			if($hasAll)
	    			$cookAble[] = $recipe;
    			}
    		}
    		return $this->render('SilWhatToCookBundle:Default:meals.html.twig',array('cookAble'=>$cookAble));
    	}
    }
}
