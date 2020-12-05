<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\TicketPriority;
use App\Entity\TicketType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\File;

class TicketFormType extends AbstractType
{
    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre*', 'required' => true])

            ->add('description', TextareaType::class, [
                'label' => 'Description*',
                'required' => true,
                'attr' => [
                    'class' => 'tinymce',
                    //'data-theme' => 'bbcode', // Skip it if you want to use default theme
                ],
            ])

            ->add('ticketPriority', EntityType::class, [
                'label' => 'Priorité*',
                'class' => TicketPriority::class,
                'choice_label' => 'name',
                'multiple' => false,
                //'expanded' => true,
            ])

            ->add('ticketType', EntityType::class, [
                'label' => 'Type*',
                'class' => TicketType::class,
                'choice_label' => 'name',
                'multiple' => false,
                //'expanded' => true,
            ])

            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.',
                    ]),
                ],
            ])

            ->add('submit', SubmitType::class, ['label' => 'Sauvegarder'])
        ;

        /*if ($this->security->isGranted('ROLE_ADMIN')) {
            echo 'ok';
            die;
        }*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
