<?php
namespace App\Form;

use App\Entity\Pelicula;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PeliculaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formulario = $builder
            ->add('titulo', TextType::class)
            ->add('duracion', NumberType::class, [
                'empty_data' => 0,
                'html5' => true,
            ])
            ->add('director', TextType::class)
            ->add('genero', TextType::class)
            ->add('Actualizar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success my-3'],
            ])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pelicula::class,
        ]);
    }
}
