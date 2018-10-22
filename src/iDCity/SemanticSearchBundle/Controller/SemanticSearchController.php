<?php

namespace iDCity\SemanticSearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

}