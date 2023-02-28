<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

	public function __construct($defaultLocale = 'es'){
        $this->defaultLocale = $defaultLocale;
    } 

    public function onKernelRequest(RequestEvent $event): void
    {
        //Sacamos el objeto "request" asociado al evento que ha provocado la ejecucion de esta function
        $request = $event->getRequest();
        //Validamos que no exista ninguna session previa (si existe se termina la ejecucion de la funcion
        //if (!$request->hasPreviousSession()) {return;} 

        // Verificamos si hay un atributo "_locale" asociado a este objeto "request"
        if ($locale = $request->attributes->get('_locale')) 
        {
            //Si el atributo existe, guardamos su valor en la session asociada a ese "request"
            $request->getSession()->set('_locale', $locale);
        }else{
            //Si el objeto "request" no tiene un atributo "_locale", usamos el valor almacenado en la session para
            //y lo ponemos como valor de dicho atributo "_locale". 
            //El defaultLocale es el valor que se retorna si no existe un "_locale" en session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
