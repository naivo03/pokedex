<?php

namespace App\EventListenner;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class AllRequestAnalizer
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        dump($event); die;
    }
}
