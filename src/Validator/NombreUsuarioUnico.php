<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class NombreUsuarioUnico extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Ya existe un usuario con el nombre "{{ value }}", escoge otro.';

    //le digo que esta restriccion es para una propiedad especifica
    public function getTargets(){return array(self::PROPERTY_CONSTRAINT);} 
}
