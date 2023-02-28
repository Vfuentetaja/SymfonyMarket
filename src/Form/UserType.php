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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'autocomplete' => 'nombre',                     
                    'class' => 'input_formulario_registro'
                ],
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'attr' => [
                    'autocomplete' => 'apellidos',                     
                    'class' => 'input_formulario_registro'
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'autocomplete' => 'email',                     
                    'class' => 'input_formulario_registro'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password', 'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Repita su password'],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'input_formulario_registro',
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
/*             ->add('verifyPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Repita la password',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'input_formulario_registro',
                    'placeholder' => 'Verifique la password'
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
            ]) */
            ->add('direccion', TextType::class, [
                'label' => 'Direccion',
                'attr' => [
                    'autocomplete' => 'direccion',                     
                    'class' => 'input_formulario_registro'
                ],
            ])
            ->add('fecha_nacimiento',DateType::Class, [
                'format' => 'dd-MM-yyyy',
                'years' => range(date('1950'), date('Y'))
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
