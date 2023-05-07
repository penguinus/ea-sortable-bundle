<?php

namespace Orkestra\EaSortable;

use Orkestra\EaSortable\DependencyInjection\OrkestraEaSortableExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OrkestraEaSortableBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new OrkestraEaSortableExtension();
    }
}