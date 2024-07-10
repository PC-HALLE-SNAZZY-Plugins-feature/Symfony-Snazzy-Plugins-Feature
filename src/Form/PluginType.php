<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Plugin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class PluginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class'       => 'form-control relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm',
                    'placeholder' => 'Enter The Plugin Name',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Plugin Name cannot be blank']),
                ],
                'label'      => 'Name',
                'label_attr' => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
            ])
            ->add('description', TextareaType::class, [
                'required'    => false,
                'attr'        => [
                    'class'       => 'form-control relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm',
                    'placeholder' => 'Description',
                    'rows'        => 5,
                ],
                'label'      => 'Description',
                'label_attr' => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
            ])
            ->add('setup', TextareaType::class, [
                'required'    => false,
                'attr'        => [
                    'class'       => 'form-control relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm',
                    'placeholder' => 'Steps to retrieve user credentials',
                    'rows'        => 5,
                ],
                'label'      => 'Setup',
                'label_attr' => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
            ])
            ->add('imageFile', VichImageType::class, [
                'required'       => false,
                'allow_delete'   => true,
                'download_uri'   => true,
                'download_label' => true,
                'image_uri'      => true,
                'asset_helper'   => true,
                'attr'           => [
                    'class' => 'form-control w-full bg-gray-600 block text-sm px-2 font-medium text-gray-100 mb-2',
                ],
                'label'      => 'Logo',
                'label_attr' => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
            ])
            ->add('dashboard_path', TextType::class, [
                'attr' => [
                    'class'       => 'form-control relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm',
                    'placeholder' => 'Enter The Dashboard Path',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Dashboard Path cannot be blank']),
                ],
                'label'      => 'Dashboard Path',
                'label_attr' => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
            ])
            ->add('credentials_form_fields', CollectionType::class, [
                'entry_type'    => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class'       => 'form-control relative block w-full appearance-none rounded-md border border-gray-300 mb-2 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm',
                        'placeholder' => 'Enter a credential field',
                    ],
                    'label' => false,
                ],
                'allow_add'     => true,
                'allow_delete'  => true,
                'delete_empty'  => true,
                'by_reference'  => false,
                'prototype'     => true,
                'label'         => 'Credentials Form Fields',
                'label_attr'    => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
                'attr'          => ['class' => 'credentials-form-fields-collection block text-sm font-medium text-gray-900 mb-3'],
            ])

            ->add('category', EntityType::class, [
                'class'         => Category::class,
                'choice_label'  => 'name',
                'label_attr'    => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
                'attr'          => [
                    'class'       => 'form-control relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm',
                    'placeholder' => 'Enter The Category Name',
                ],
            ])
            ->add('screenShots', CollectionType::class, [
                'entry_type'    => PluginScreenShotsType::class,
                'entry_options' => ['label' => false],
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'prototype'     => true,
                'label'         => 'Screen Shots',
                'label_attr'    => ['class' => 'block text-lg font-medium text-gray-900 mb-2'],
                'attr'          => ['class' => 'screenShots-collection'],
            ])
            ->add('submit', SubmitType::class, [
                'attr'  => ['class' => 'w-full md:w-1/2 xl:w-1/4 px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 focus:outline-none transition-colors mb-3'],
                'label' => 'Save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plugin::class,
        ]);
    }
}
