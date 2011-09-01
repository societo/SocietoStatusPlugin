<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace SocietoPlugin\Societo\StatusPlugin\Tests\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Societo\PageBundle\Entity\PageGadget;
use Societo\PageBundle\Entity\Page;

class LoadStatusFormGadgetData implements FixtureInterface
{
    public function load($manager)
    {
        $page = new Page('example');
        $manager->persist($page);

        $gadget = new PageGadget($page, 'head', 'SocietoStatusPlugin:StatusForm');
        $gadget->setParameter('show_max_length', false);
        $gadget->setParameter('max_length', 10);
        $manager->persist($gadget);

        $gadget = new PageGadget($page, 'head', 'SocietoStatusPlugin:Invalid');
        $manager->persist($gadget);

        $manager->flush();
    }
}
