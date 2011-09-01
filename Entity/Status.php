<?php

namespace SocietoPlugin\Societo\StatusPlugin\Entity;

use Societo\BaseBundle\Entity\AbstractContent;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="status")
 */
class Status extends AbstractContent
{
    private $maxLength = null;

    /**
     * @ORM\Column(name="body", type="text")
     * @Assert\NotBlank()
     */
    protected $body;

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

    /**
     * Set max length restriction in the current context.
     *
     * @param int $length
     */
    public function setMaxLength($length = null)
    {
        if ($length) {
            $this->maxLength = (int)$length;
        }
    }

    /**
     * @Assert\True(message = "Too long value")
     */
    public function isValidLength()
    {
        if (!$this->maxLength) {
            return true;
        }

        $constraint = new \Symfony\Component\Validator\Constraints\MaxLength(array(
            'limit' => $this->maxLength,
        ));
        $validator = new \Symfony\Component\Validator\Constraints\MaxLengthValidator();

        return $validator->isValid($this->getBody(), $constraint);
    }
}
