<?php

namespace MusicBox\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'constraints' => new Assert\NotBlank(),
            ))
            ->add('shortBiography', 'textarea', array(
                'attr' => array(
                    'rows' => '7',
                )
            ))
            ->add('biography', 'textarea', array(
                'attr' => array(
                    'rows' => '15',
                )
            ))
            ->add('soundCloudUrl', 'url')
            ->add('file', 'file', array(
                'required' => FALSE,
                'label' => 'Image',
            ))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'artist';
    }
}
