<?php

namespace iDCity\SemanticSearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use iDCity\SemanticBundle\Form\AdvertType;

class SemanticSearchController extends Controller
{
	public function researchAction(){

		//$content = $this->render('iDCitySemanticSearchBundle:SemanticSearch:view.html.twig');

		return new Response ($content = $this->render('iDCitySemanticSearchBundle:SemanticSearch:view.html.twig'));
	}

	public function responseAction(Request $request)
	{
		if($request->isXMLHttpRequest()){
			$words = $request->get('relevantWords');
			$flatWords = array();
			foreach ($words as $word) {
				array_push($flatWords, $word['word']);
				if (array_key_exists('categories', $word)) {
					foreach ($word['categories'] as $category) {
						array_push($flatWords, implode('|', $category));
					}
				}
			}
			$arrData = ['relevantWords' => implode('|', $flatWords)];
			return new JsonResponse($arrData);
		}

	}

}