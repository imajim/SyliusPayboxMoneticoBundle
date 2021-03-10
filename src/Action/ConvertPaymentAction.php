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

use App\Entity\Order\Order;
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
        $details[MoneticoParams::PBX_TPE] = '';
        $details[MoneticoParams::PBX_VERSION] = MoneticoParams::MONETICO_VERSION;
        $details[MoneticoParams::PBX_DATE] = $order->getCreatedAt()->format('d/m/Y:H:i:s');
        $details[MoneticoParams::PBX_MONTANT] = $order->getTotal();
        $details[MoneticoParams::PBX_REFERENCE] = $order->getNumber();
        $langue = MoneticoParams::MONETICO_LANGUE;
        if (isset($payment->getMethod()->getGatewayConfig()->getConfig()['langue'])) {
            $langue = $payment->getMethod()->getGatewayConfig()->getConfig()['langue'];
        }
        $details[MoneticoParams::PBX_LANGUE] = $langue;
        $details[MoneticoParams::PBX_MAC] = '';
        $details[MoneticoParams::PBX_CONTEXTE] = $this->createContext($order);
        $details[MoneticoParams::PBX_SOCIETE] = '';

        $details[MoneticoParams::PBX_EMAIL] = $order->getCustomer()->getEmail();
        $token = $request->getToken();
        //$details[MoneticoParams::PBX_URL_RETOUR_OK] = MoneticoParams::PBX_RETOUR_VALUE;

        $details[MoneticoParams::PBX_URL_RETOUR_OK] = $token->getTargetUrl();
        $details[MoneticoParams::PBX_URL_RETOUR_ERROR] = $token->getTargetUrl();




        //$details[MoneticoParams::PBX_TYPECARTE] = 'CB';
        //$details[MoneticoParams::PBX_TYPEPAIEMENT] = 'CARTE';


        // Prevent duplicated payment error
        if (strpos($token->getGatewayName(), 'sandbox') !== false) {
            //$details[MoneticoParams::PBX_CMD] = sprintf('%s-%d', $details[MoneticoParams::PBX_CMD], time());
        } else {
            //$details[MoneticoParams::PBX_CMD] = sprintf('%s-%d', $details[MoneticoParams::PBX_CMD], time());
            //$details[MoneticoParams::PBX_CMD] =  time();
        }

        /*if (false == isset($details[MoneticoParams::PBX_REPONDRE_A]) && $this->tokenFactory) {
            $notifyToken = $this->tokenFactory->createNotifyToken($token->getGatewayName(), $payment);
            $details[MoneticoParams::PBX_REPONDRE_A] = $notifyToken->getTargetUrl();
        }*/

        $request->setResult((array)$details);
    }

    private function createContext(Order $order)
    {
        $billing = $order->getShippingAddress();

        $datas = [];
        $datas['billing'] = [
            'name' => $billing->getLastName() . ' ' .$billing->getFirstName(),
            'lastname' => $billing->getLastName(),
            'firstName' => $billing->getFirstName(),
            'address' => $billing->getStreet(),
            'city' => $billing->getCity(),
            'postalCode' => $billing->getPostcode(),
            'country' => $billing->getCountryCode(),
            'email' => $order->getCustomer()->getEmail(),
            'phone' => $order->getCustomer()->getPhoneNumber(),
        ];
        $datas['client'] = $datas['billing'];

        return json_encode($datas);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array';
    }
}
