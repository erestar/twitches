<?php

namespace Erestar\TwitchesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ErestarTwitchesBundle extends Bundle
{
    public function getParent()  
    {  
        return 'FOSOAuthServerBundle';  
    }  
}
