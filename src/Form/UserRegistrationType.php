<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('_user', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Please enter your name']), 
                new Type(['type' => 'string']),
                new Length(['min' => 3]),
            ],
           
        ])
        ->add('email', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Please enter your email']), 
                new Email(['message' => 'email is not a valid.'])
            ],
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'password and confirm password is not same',
            'first_options' => array(
                'constraints' => [
                    new NotBlank(['message' => 'Please Enter Password']), 
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],

            ),
            'second_options' => array(
                'constraints' => [
                    new NotBlank(['message' => 'Please Enter confirm Password']), 
                ],

            ),
            'first_name'  => 'password',
            'second_name' => 'confirm_password',
        ])
        ->add('agreement', CheckboxType::class, [
            'label_html' => true,
            'constraints' => [
                new NotBlank(['message' => 'Please Check Agreement Box']), 
            ],
        ])
        ->add('register', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ],
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'user_registration',
        ]);
    }
}
