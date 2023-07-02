<?php

namespace App\Form;

use App\Entity\UserSelectionLoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridLotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ballsSelectionLoto', ChoiceType::class, [
                'choices' => array_combine(range(1, 49), range(1, 49)),
                'expanded' => true,
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add('luckyNumberSelection', ChoiceType::class, [
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'expanded' => true,
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add('validate', SubmitType::class, [
                'label' => 'valider',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSelectionLoto::class,
        ]);
    }
}
