<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IdiomaController extends AbstractController
{
    #[Route('/idioma/{_locale}', name: 'app_idioma')]
	public function index(Request $request)
	{
		$idiomaActual = $request->getSession()->get('_locale'); //Aunque se llame la Controller
		//por primera vez, habra una sesion creara por que se habra creado antes a causa del LocaleSubscriber
		$ruta=$request->get('ruta');
		$metodo = $request->getMethod();			
		if ("POST" === $metodo) {
		    //Si la peticion viene por POST, simplemente hacemos un redireccionamiento para
			//que el EventSuscriber se ocupe de cambiar el valor del _locale
           	    return $this->redirectToRoute($ruta);
        	}
		//Si es la primera vez que se accede al Controller, pintamos el formulario directamente
		return $this->render('comunes/_idiomas.html.twig', ['ruta' => $ruta,'idioma_actual' => $idiomaActual]); 
	}	 						
}