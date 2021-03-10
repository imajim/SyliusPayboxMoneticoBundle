<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Imajim\SyliusMoneticoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class MoneticoGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', TextType::class, [
                'label' => 'imajim.form.gateway_configuration.monetico.site',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('tpe', TextType::class, [
                'label' => 'imajim.form.gateway_configuration.monetico.tpe',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('identifiant', TextType::class, [
                'label' => 'imajim.form.gateway_configuration.monetico.identifier',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('hmac', TextType::class, [
                'label' => 'imajim.form.gateway_configuration.monetico.hmac',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('sandbox', CheckboxType::class, [
                'label' => 'imajim.form.gateway_configuration.monetico.sandbox',
                'required' => false,
            ])
            ->add('langue', ChoiceType::class, [
                'label' => 'imajim.form.gateway_configuration.monetico.langues',
                'choices' => array_flip([
                    'DE' => 'Allemande',
                    'EN' => 'Anglaise',
                    'ES' => 'Espagnole',
                    'FR' => 'Francaise',
                    'IT' => 'Italienne',
                    'JA' => 'Japonnaise',

                ]),
                'required' => false,
            ]);
    }
}
