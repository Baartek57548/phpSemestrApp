<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, [
                'label' => 'Imie',
                'constraints' => [
                    new NotBlank(message: 'Podaj imie.'),
                    new Length(
                        min: 2,
                        max: 40,
                        minMessage: 'Imie powinno miec przynajmniej {{ limit }} znaki.',
                        maxMessage: 'Imie moze miec maksymalnie {{ limit }} znakow.',
                    ),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank(message: 'Podaj adres e-mail.'),
                    new Email(message: 'Podaj poprawny adres e-mail.'),
                ],
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Ocena wpisu',
                'placeholder' => 'Wybierz ocene',
                'choices' => [
                    '1/5' => 1,
                    '2/5' => 2,
                    '3/5' => 3,
                    '4/5' => 4,
                    '5/5' => 5,
                ],
                'constraints' => [
                    new NotBlank(message: 'Wybierz ocene.'),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Twoja opinia',
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Napisz, co myslisz o tym wpisie.',
                ],
                'constraints' => [
                    new NotBlank(message: 'Wpisz tresc opinii.'),
                    new Length(
                        min: 10,
                        max: 500,
                        minMessage: 'Opinia powinna miec przynajmniej {{ limit }} znakow.',
                        maxMessage: 'Opinia moze miec maksymalnie {{ limit }} znakow.',
                    ),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Dodaj opinie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
