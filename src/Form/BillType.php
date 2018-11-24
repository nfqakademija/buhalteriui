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
        'attr' => ['class' => 'form-control']
        ])
        ->add('Įkelti', SubmitType::class, [
            'attr'=> ['class' => 'btn btn-outline-success mt-1']
        ]);
    }

}