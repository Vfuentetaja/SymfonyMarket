<?php

namespace App\Validator;

use App\Repository\UserRepository;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

//Lo usamos para validar que no exista ya un usuario con el mismo nombre
class NombreUsuarioUnicoValidator extends ConstraintValidator
{
    private $userRepository;

    //inyectamos el repositorio en le constructor del validator para poder trabajar con el
    public function __construct(UserRepository $userRepository){ 
        $this->userRepository=$userRepository;
    }

    public function validate($nombre, Constraint $constraint) //como es una constraint de propiedad, lo que recibo
                    //como parametro al hacer el validate es el string nombre, ya que la validacion la he aplicado
                    //sobre una propiedad tipo String
    {
        if (null === $nombre || '' === $nombre) { //verificamos que "nombre" no sea null ni este vacio
            return;
        }

        //Buscamos en la base de datos un usuario ya existente con ese mismo nombre
        $UsuarioYaExistenteConEseNombre= $this->userRepository->findOneByNombre($nombre);

        //Si el usuario que recuperamos es distinto de null
        //hacemos que salte la violacion de la restriccion
        if(null!== $UsuarioYaExistenteConEseNombre){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $nombre)
            ->addViolation();
        }


    }
}
