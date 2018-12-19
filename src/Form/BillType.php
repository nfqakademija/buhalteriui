<?php

namespace App\Form;

use App\Entity\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('template_id', EntityType::class, [
                'class' => Template::class,
                'label' => 'Sąskaitos šablonas',
                'choice_label' => 'title',
                'choice_value' => 'template_id',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control input-group']
            ])
            ->add('bill', FileType::class, [
                'attr' => ['hidden' => 'hidden'],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
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
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Įkelti',
                'attr' => ['class' => 'btn btn-success btn-lg btn-block']
            ]);
    }
}
