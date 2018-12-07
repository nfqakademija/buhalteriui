<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('bill', FileType::class, [
        'attr' => ['submit' => 'form-control']
        ])
        ->add('submit', SubmitType::class, [
            'attr'=> ['class' => 'btn btn-success btn-lg btn-block']
        ]);
    }

}