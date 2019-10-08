<?php

namespace App\Form;

use App\Entity\AssoEvent;
use App\Entity\BelongTo;
use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssoEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                ],
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description'
                ],
                'required' => true
            ])
            ->add('beginAt', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => false
            ])
            ->add('endAt', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => false
            ])
            ->add('imageFileOne', FileType::class, [
                'label' => 'Fichier image 1 :',
                'attr' => [
                    'id' => 'inputGroupFile01',
                    'aria-describeby' => 'inputGroupFileAddon01',
                    'type' => 'file',
                    'class' => 'custom-file-input'
                ],
                'required' => false,
            ])
            ->add('imageFileTwo', FileType::class, [
                'label' => 'Fichier image 2 :',
                'attr' => [
                    'id' => 'inputGroupFile01',
                    'aria-describeby' => 'inputGroupFileAddon01',
                    'type' => 'file',
                    'class' => 'custom-file-input'
                ],
                'required' => false,
            ])
            ->add('imageFileThree', FileType::class, [
                'label' => 'Fichier image 3 :',
                'attr' => [
                    'id' => 'inputGroupFile01',
                    'aria-describeby' => 'inputGroupFileAddon01',
                    'type' => 'file',
                    'class' => 'custom-file-input'
                ],
                'required' => false,
            ])
            ->add('organizationsPartner', EntityType::class, [
                'label'=> false,
                'class'=>Organization::class,
                'required'=>false,
                'choice_label' => 'name',
                'expanded'=>true,
                'multiple'=>true,
                'mapped'=>false
            ])
          ;
    }
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => AssoEvent::class
            ]);
        }
    }
