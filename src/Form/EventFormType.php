<?php

namespace App\Form;

use App\Entity\Event;
use App\Form\Type\CKEditor5Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('startDate', DateTimeType::class, [
                'required' => false,
                'empty_data' => '',
            ])
            ->add('endDate', DateTimeType::class, [
                'required' => false,
                'empty_data' => '',
            ])
            ->add('location')
            // ->add('lists')
            ->add('remark')
            // ->add('gallery')
            // ->add('creator', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('isPrivate')
            // ->add('participants', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            // ])
            // ->add('comments', EntityType::class, [
            //     'class' => Comment::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
