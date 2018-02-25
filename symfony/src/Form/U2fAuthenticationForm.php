<?php

namespace App\Form;

use App\Submission\U2fAuthenticationSubmission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @todo Rename suffix to Type?
 */
class U2fAuthenticationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('u2fTokenResponse', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'data_class' => U2fAuthenticationSubmission::class,
        ]);
    }
}
