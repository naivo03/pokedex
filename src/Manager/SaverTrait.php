<?php

namespace App\Manager;

trait SaverTrait
{
    private function save($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
