<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoiceSeries', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 10,
                    ]),
                ],
                'label' => 'Serijos numeris',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceNumber', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 20,
                    ]),
                ],
                'label' => 'Sąskaitos numeris',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 255,
                    ]),
                ],
                'label' => 'Pirkėjo vardas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerAddress', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 255,
                    ]),
                ],
                'label' => 'Pirkėjo adresas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerCode', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 20,
                    ]),
                ],
                'label' => 'Pirkėjo kodas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceBuyerVatCode', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 20,
                    ]),
                ],
                'label' => 'Pirkėjo PVM kodas',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceDate', DateType::class, [
                'required' => true,
                'years' => range(1990, date('Y')+1),
                'constraints' => [
                    new Date(),
                ],
                'label' => 'Data',
                'attr' => ['class' => 'input-group']
            ])
            ->add('invoiceTotal', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Type([
                        'type' => 'float',
                    ]),
                ],
                'label' => 'Galutinė suma',
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-space btn-success']
            ]);
    }
}
