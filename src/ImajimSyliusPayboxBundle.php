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

namespace Imajim\SyliusPayboxBundle;

use Imajim\SyliusPayboxBundle\DependencyInjection\ImajimSyliusPayboxExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ImajimSyliusPayboxBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new ImajimSyliusPayboxExtension();
        }

        return $this->extension;
    }
}
