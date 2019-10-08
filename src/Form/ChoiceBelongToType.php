<?php


namespace App\Form;


use App\Entity\BelongTo;
use App\Entity\Organization;
use App\Repository\BelongToRepository;
use App\Repository\OrganizationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceBelongToType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('organizations', EntityType::class, [
                'class' => Organization::class,
                'query_builder' =>
                    function (OrganizationRepository $er) use ($user) {
                    return $er->createQueryBuilder('org')
                       ->where('org.user != :user')
                        ->setParameter('user', $user)
                       ->leftJoin('org.belongTos', 'bto')
                        ->from(BelongTo::class, 'b')
                        ->andWhere('b.isAdmin = 0')
                        ->andWhere('b.user = :user_id')
                       ->setParameter('user_id', $user);

                       /*
                        ->where('org.user != :user')
                        ->setParameter('user', $user)
                        ->leftJoin('org.belongTos', 'bto')
                        ->from(BelongTo::class, 'b')
                        ->andWhere('b.isAdmin = 0')
                        ->setParameter('user', $user);*/
                },
                'required' => false,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'mapped' => false,
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BelongTo::class,
            'user' => null,
        ]);
    }
}