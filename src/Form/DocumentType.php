<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('finalSum', NumberType::class, [
                'label' => 'Galutine suma',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Išsaugoti', SubmitType::class, [
                'attr'=> ['class' => 'btn btn-outline-info mt-1']
            ]);
    }

}