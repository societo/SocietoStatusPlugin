<?php

namespace SocietoPlugin\Societo\StatusPlugin;

use Societo\ActivityBundle\ActivityGenerator\DoctrineEntityGenerator;
use Societo\ActivityBundle\ActivityObject;

class ActivityGenerator extends DoctrineEntityGenerator
{
    const ACTIVITY_TYPE_STATUS_POST = 'status_post';

    public function getAvailableType()
    {
        return array(
            self::ACTIVITY_TYPE_STATUS_POST => 'Member posts his status',
        );
    }

    protected function getPersistActivity($entity, $em)
    {
        $this->registerActivity($em, $entity->getAuthor(), 'post', new ActivityObject('status'), new ActivityObject(), $entity->getBody(), self::ACTIVITY_TYPE_STATUS_POST);
    }

    protected function getUpdateActivity($entity, $em)
    {
        // do nothing
    }
}
