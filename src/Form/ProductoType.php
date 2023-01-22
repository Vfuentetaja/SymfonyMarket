<?php

namespace App\Form;

use App\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', null, array(
                'attr' => array('style' => 'width: 70%')
               ))
            ->add('precio')
            ->add('descripcion', null, array(
                'attr' => array('style' => 'width: 70%')
               ))
            ->add('cantidad')
            ->add('categoria', ChoiceType::class, array(
                'choices' => array(
                    'Ropa' => 'Ropa',
                    'Regalos' => 'Regalos',
                    'Figuras' => 'Figuras',
                    'Decoracion' => 'Decoracion'
                )))
            ->add('imagen1', FileType::class, ['label' => 'Foto del producto','mapped' => false, 'required' => false,
                'constraints' => [new File([
                      'maxSize' => '1024k',
                      'mimeTypes' => ['image/jpeg','image/png',],
                      'mimeTypesMessage' => 'Por favor sube una imágen',
                  ])],
               ])
            ->add('destacado')
            ->add('novedad')
            ->add('oferta')
            ->add('descuento', ChoiceType::class, array(
                'choices' => array(
                    '5%' => 5,
                    '10%' => 10,
                    '15%' => 15,
                    '20%' => 20,
                    'sin descuento' => null
                )))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Producto::class,
        ]);
    }
}
