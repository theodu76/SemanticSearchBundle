<?php

namespace iDCity\SemanticSearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

			/*
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
			*/

			$reshape = $this->container->get('i_d_city_semantic_search.reshape_response');
			$arrData = ['relevantWords' => $reshape->flatten($words)];

			return new JsonResponse($arrData);
		}

	}

}