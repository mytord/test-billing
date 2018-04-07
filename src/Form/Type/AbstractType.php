<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbstractType.
 */
abstract class AbstractType extends BaseAbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param string $fieldName
     */
    protected function addWallet(FormBuilderInterface $builder, string $fieldName = 'walletId'): void
    {
        $builder
            ->add($fieldName, IntegerType::class);
    }

    /**
     * @param FormBuilderInterface $builder
     */
    protected function addMoneyAmount(FormBuilderInterface $builder): void
    {
        $builder
            ->add('amount');
    }
}