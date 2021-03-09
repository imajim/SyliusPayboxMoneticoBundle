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

namespace Imajim\SyliusMoneticoBundle\Action;

use Imajim\SyliusMoneticoBundle\MoneticoParams;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;
use Sylius\Component\Core\Model\PaymentInterface;

class ConvertPaymentAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();
        $order = $payment->getOrder();


         // ATTENTION à l'ordre des champs
        $details = ArrayObject::ensureArrayObject($payment->getDetails());
        $details[MoneticoParams::PBX_TOTAL] = $order->getTotal();
        $details[MoneticoParams::PBX_DEVISE] = MoneticoParams::PBX_DEVISE_EURO;
        $details[MoneticoParams::PBX_CMD] = $order->getNumber();
        $details[MoneticoParams::PBX_PORTEUR] = $order->getCustomer()->getEmail();
        $token = $request->getToken();
        $details[MoneticoParams::PBX_RETOUR] = MoneticoParams::PBX_RETOUR_VALUE;
        $details[MoneticoParams::PBX_EFFECTUE] = $token->getTargetUrl();
        $details[MoneticoParams::PBX_ANNULE] = $token->getTargetUrl();
        $details[MoneticoParams::PBX_REFUSE] = $token->getTargetUrl();
        //$details[MoneticoParams::PBX_TYPECARTE] = 'CB';
        //$details[MoneticoParams::PBX_TYPEPAIEMENT] = 'CARTE';



        // Prevent duplicated payment error
        if (strpos($token->getGatewayName(), 'sandbox') !== false) {
            $details[MoneticoParams::PBX_CMD] = sprintf('%s-%d', $details[MoneticoParams::PBX_CMD], time());
        }else{
            //$details[MoneticoParams::PBX_CMD] = sprintf('%s-%d', $details[MoneticoParams::PBX_CMD], time());
            //$details[MoneticoParams::PBX_CMD] =  time();
        }

        if (false == isset($details[MoneticoParams::PBX_REPONDRE_A]) && $this->tokenFactory) {
            $notifyToken = $this->tokenFactory->createNotifyToken($token->getGatewayName(), $payment);
            $details[MoneticoParams::PBX_REPONDRE_A] = $notifyToken->getTargetUrl();
        }


        $request->setResult((array) $details);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array'
        ;
    }
}
