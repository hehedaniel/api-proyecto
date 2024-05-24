<?php

namespace App\Form;

use App\Entity\Alimento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlimentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('marca')
            ->add('cantidad')
            ->add('proteinas')
            ->add('grasas')
            ->add('carbohidratos')
            ->add('azucares')
            ->add('vitaminas')
            ->add('minerales')
            ->add('imagen')
            ->add('usuarios')
            ->add('usuarioProponedor')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alimento::class,
        ]);
    }
}
