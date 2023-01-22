<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'autocomplete' => 'nombre',                     
                    'class' => 'input_formulario_registro',
                    'placeholder' => 'Pon tu nombre aqui'
                ],
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'attr' => [
                    'autocomplete' => 'apellidos',                     
                    'class' => 'input_formulario_registro',
                    'placeholder' => 'Pon tus apellidos aqui'
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'autocomplete' => 'email',                     
                    'class' => 'input_formulario_registro',
                    'placeholder' => 'Pon tu correo electronico aqui'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password', 'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Repita su password'],
                'invalid_message'=>'Las passwords deben ser iguales.',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'email',
                    'placeholder' => 'Password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor introduce una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Direccion',
                'attr' => [
                    'autocomplete' => 'direccion',                     
                    'class' => 'input_formulario_registro',
                    'placeholder' => 'Pon tu direccion aqui'
                ],
            ])
            ->add('fecha_nacimiento',DateType::Class, [
                'format' => 'dd-MM-yyyy',
                'years' => range(date('1980'), date('Y'))
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Terminos de contrato',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Debes aceptar nuestras condiciones.',
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
