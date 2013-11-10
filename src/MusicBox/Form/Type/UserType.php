<?php

namespace MusicBox\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'constraints' => new Assert\NotBlank(),
            ))
            ->add('password', 'repeated', array(
                'type'            => 'password',
                'invalid_message' => 'The password fields must match.',
                'options'         => array('required' => false),
                'first_options'   => array('label' => 'Password'),
                'second_options'  => array('label' => 'Repeat Password'),
                'required' => FALSE,
            ))
            ->add('mail', 'email', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Email()),
            ))
            ->add('file', 'file', array(
                'required' => FALSE,
                'label' => 'Image',
            ))
            ->add('role', 'choice', array(
                'choices' => array('ROLE_USER' => 'User', 'ROLE_ADMIN' => 'Admin')
            ))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'user';
    }
}
