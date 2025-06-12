<?php

namespace App\EventListener;

use App\Entity\Rencontre;
use App\Service\UpdateClassementService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class RencontreListener
{
    private $updateClassementService;

    public function __construct(UpdateClassementService $updateClassementService)
    {
        $this->updateClassementService = $updateClassementService;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Rencontre) {
            return;
        }

        $this->updateClassementService->updateClassements();
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Rencontre) {
            return;
        }

        $this->updateClassementService->updateClassements();
    }
}
