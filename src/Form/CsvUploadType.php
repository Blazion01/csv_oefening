<?php

namespace App\Form;

use App\Entity\CsvFile;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('csvFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Remove File',
                'download_uri' => true,
                'download_label' => true,
                'asset_helper' => true,
            ])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => ['class' => 'form-control btn-primary pull-right'],
                    'label' => 'Upload File'
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CsvFile::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'author_form';
    }
}
