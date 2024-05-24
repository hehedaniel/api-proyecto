<?php

namespace App\Form;

use App\Entity\Receta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('instrucciones')
            ->add('cantidadFinal')
            ->add('proteinas')
            ->add('grasas')
            ->add('carbohidratos')
            ->add('azucares')
            ->add('vitaminas')
            ->add('minerales')
            ->add('imagen')
            ->add('usuariosTomadores')
            ->add('usuarioCreador')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Receta::class,
        ]);
    }
}
