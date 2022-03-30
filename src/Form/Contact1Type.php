<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Contact1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',null,['label' => 'Nom et Prénom(s)'])
        ->add('email',null,['label' => 'E-mail'])
        ->add('subject',null,['label' => 'Objet'])
        ->add('phone',null,['label' => 'Téléphone'])
        ->add('content',null,['label' => 'Message'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
