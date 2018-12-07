<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoiceSeries', TextType::class, [
                'label' => 'Serijos numeris',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceNumber', TextType::class, [
                'label' => 'Saskaitos numeris',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerName', TextType::class, [
                'label' => 'Pirkejo vardas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerAddress', TextType::class, [
                'label' => 'Pirkejo adresas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerCode', TextType::class, [
                'label' => 'Pirkejo kodas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerVatCode', TextType::class, [
                'label' => 'Pirkejo VAT kodas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceDate', DateType::class, [
                'label' => 'Data',
                'attr' => ['class' => 'input-group']
            ])
            ->add('invoiceTotal', NumberType::class, [
                'label' => 'Galutine suma',
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'attr'=> ['class' => 'btn btn-space btn-success']
            ]);
    }

}