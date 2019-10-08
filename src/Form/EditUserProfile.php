<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserProfile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'required'=> false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Lastname'
                ],
            ])
            ->add('firstname', TextType::class, [
                'required'=> false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Firstname'
                ],

            ])
            ->add('username', TextType::class, [
                'required'=> false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Pseudo'
                ],

            ])
            ->add('email', EmailType::class, [
                'required'=> false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],

            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}