<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IdiomaController extends AbstractController
{
	//@Route("/idioma/{_locale}/{ruta}", name="app_idioma",defaults={"ruta": ""})
    #[Route('/idioma/{_locale}', name: 'app_idioma')]
	public function index(Request $request)
	{
		//$idiomaActual = $request->getSession()->get('_locale');
		//dd($request->get('ruta'));
		$ruta=$request->get('ruta');
        $idiomaActual = $request->getLocale();
		$metodo = $request->getMethod();			
		if ("POST" === $metodo) {
		    //$ruta = $request->get('ruta');
           	    return $this->redirectToRoute($ruta);
        	}
		return $this->render('comunes/_idiomas.html.twig', ['ruta' => $ruta,'idioma_actual' => $idiomaActual]); 
	}	 						
}