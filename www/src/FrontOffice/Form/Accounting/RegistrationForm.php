<?php

namespace FrontOffice\Form\Accounting;

use Core\Entity\User;
use Core\Validator\Constraints\EntityNotExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Nom', 'class' => 'form-control-lg'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Prenom', 'class' => 'form-control-lg'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Téléphone', 'class' => 'form-control-lg'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Adresse', 'class' => 'form-control-lg'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Entreprise', 'class' => 'form-control-lg'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Your email address', 'class' => 'form-control-lg'],
                'constraints' => [
                    new NotBlank(),
                    new Email(['message' => "The '{{ value }}' is not a valid email!"]),
                    new EntityNotExists([
                        'entityClass' => User::class,
                        'field' => 'email',
                        'message' => 'Email {{ value }} has already been taken!'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_name' => 'first',
                'first_options' => [
                    'attr' => ['placeholder' => 'Password', 'class' => 'form-control-lg'],
                    'constraints' => [new NotBlank(), new Length(['min' => 8])]
                ],
                'second_name' => 'second',
                'second_options' => [
                    'attr' => ['placeholder' => 'Repeat password', 'class' => 'form-control-lg'],
                    'constraints' => [new NotBlank(), new Length(['min' => 8])]
                ]
            ])
           ->add('news_letter', RadioType::class, [
              'required' => true,
              'attr' => ['placeholder' => 'Nom', 'class' => 'form-control-lg'],
              'constraints' => [
                 new NotBlank(),
              ]
           ])
            ;
    }
}