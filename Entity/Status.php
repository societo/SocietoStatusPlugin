<?php

namespace SocietoPlugin\Societo\StatusPlugin\Entity;

use Societo\BaseBundle\Entity\AbstractContent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="status")
 */
class Status extends AbstractContent
{
    public function __construct($body = null, $author = null)
    {
        $this->setBody($body);
        $this->setAuthor($author);
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }
}
