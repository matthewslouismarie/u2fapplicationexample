<?php

namespace App\Service;

use Firehed\U2F\Server;

class U2fServerService
{
    public function getServer(): Server
    {
        return (new Server())
            ->disableCAVerification()
            ->setAppId('https://172.16.240.10')
        ;
    }
}
