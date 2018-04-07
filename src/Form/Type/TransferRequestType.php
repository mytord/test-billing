<?php

namespace App\Form\Type;

use App\Request\TransferRequest;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TransferRequestType.
 */
class TransferRequestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->addWallet($builder, 'sourceWalletId');
        $this->addWallet($builder, 'recipientWalletId');
        $this->addMoneyAmount($builder);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => TransferRequest::class,
        ]);
    }
}