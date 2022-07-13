<?php

namespace App\Form;

use App\Entity\Kilometrage;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class KilometrageType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compteur')
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'query_builder' => function (VehiculeRepository $vehiculeRepository) {
                    return $vehiculeRepository->createQueryBuilder('v')->innerJoin(User::class, 'u')->where('u.id = :id')->setParameter('id', $this->token->getToken()->getUser()->getId());
                },
                'choice_label' => 'immatriculation',
                'multiple' => false,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kilometrage::class,
        ]);
    }
}
