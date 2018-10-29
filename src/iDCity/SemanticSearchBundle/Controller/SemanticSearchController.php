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

			$reshape = $this->container->get('i_d_city_semantic_search.reshape_response');

			// $repository = $this
			// 	->getDoctrine()
			// 	->getManager()
			// 	->getRepository('AppBundle:Proposal');

			// $proposals = $repository->findArticles($reshape->flatten($words));

			$arrData = ['relevantWords' => $reshape->flatten($words)];

			return new JsonResponse($arrData);
			return $this->render('iDCitySemanticSearchBundle:SemanticSearch:articles.html.twig', array(
				'proposals' => $proposals
			));
		}

	}

}