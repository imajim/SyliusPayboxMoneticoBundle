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

namespace Imajim\SyliusMoneticoBundle;

/**
 * Interface PayBoxRequestParams.
 */
interface MoneticoParams
{
    // Default servers urls
    const SERVERS_PREPROD = array('p.monetico-services.com/test/');
    const SERVERS_PROD = array('p.monetico-services.com');

    const URL_CLASSIC = 'paiement.cgi';

    // Requests params values
    // TODO : use ConfigTreeBuilder to configure it
    /*const PBX_RETOUR_VALUE = 'Mt:M;Ref:R;Auto:A;Trans:S;error_code:E';
    const PBX_DEVISE_EURO = '978';
    const PBX_SOURCE_MOBILE = 'XHTML';
    const PBX_SOURCE_DESKTOP = 'HTML';*/
    const MONETICO_VERSION = '3.0';

    // Requests params keys
    const PBX_SOCIETE= 'societe';
    const PBX_TPE = 'TPE';
    const PBX_HMAC = 'MAC';
    const PBX_MONTANT = 'montant';
    const PBX_DATE = 'date';
    const PBX_REFERENCE = 'reference';
    const PBX_URL_RETOUR_OK = 'url_retour_ok';
    const PBX_URL_RETOUR_ERROR = 'url_retour_err';
    const PBX_LANGUE = 'lgue';
    const PBX_CONTEXTE = 'contexte_commande';
    const PBX_VERSION = 'version';
    const PBX_CMD = 'texte-libre';
    const PBX_EMAIL = 'mail';


    /*const PBX_IDENTIFIANT = 'IDENTIFIANT';
    const PBX_HASH = 'PBX_HASH';
    const PBX_RETOUR = 'PBX_RETOUR';
    const PBX_TYPEPAIEMENT = 'PBX_TYPEPAIEMENT';
    const PBX_DEVISE = 'PBX_DEVISE';
    const PBX_CMD = 'PBX_CMD';
    const PBX_PORTEUR = 'PBX_PORTEUR';
    const PBX_EFFECTUE = 'PBX_EFFECTUE';
    const PBX_ANNULE = 'PBX_ANNULE';
    const PBX_REFUSE = 'PBX_REFUSE';
    const PBX_REPONDRE_A = 'PBX_REPONDRE_A';
    const PBX_TIME = 'PBX_TIME';
    const PBX_SOURCE = 'PBX_SOURCE';
    */
}
