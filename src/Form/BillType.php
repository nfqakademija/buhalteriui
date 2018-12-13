<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bill', FileType::class, [
//                'required' => true,
                'attr' => ['submit' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nurodykite dokumentą.',
                    ]),
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            //'image/png',
                        ],
                        'allowSquare' => false,
                        'detectCorrupted' => true,
                        'minWidth' => 1654,
                        'maxWidth' => 1654,
                        'minHeight' => 2339,
                        'maxHeight' => 2339,
                        'maxSizeMessage' => 'Įkeliamas dokumentas yra per didelis ({{ size }} {{ suffix }}). ' .
                            'Maksimalus dydis yra {{ limit }} {{ suffix }}.',
                        'mimeTypesMessage' => 'Prašome nurodyti JPG bylą',
                        'disallowEmptyMessage' => 'Prašome nurodyti dokumentą.',
                        'maxWidthMessage' => 'Dokumentas per platus ({{ width }}px). ' .
                            'Leidžiamas plotis {{ max_width }}px.',
                        'minWidthMessage' => 'Dokumentas per siauras ({{ width }}px). ' .
                            'Leidžiamas plotis {{ min_width }}px.',
                        'maxHeightMessage' => 'Dokumentas per aukštas ({{ height }}px). ' .
                            'Leidžiamas aukštis {{ max_height }}px.',
                        'minHeightMessage' => 'Dokumentas per mažas ({{ height }}px). ' .
                            'Leidžiamas aukštis {{ min_height }}px.',
                        'sizeNotDetectedMessage' => 'Nepavyko nustatyti dokumento matmenų',
                        'allowSquareMessage' => 'Dokumento kraštinės yra lygios ({{ width }}x{{ height }}px). ' .
                            'Paveiksliukas turi būti stačiakampis.',
                        'corruptedMessage' => 'Nepavyko nuskaityti dokumento, greičiausiai byla sugadinta.',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success btn-lg btn-block']
            ]);
    }
}
