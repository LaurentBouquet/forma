<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * @ORM\Table(name="tbl_upload")
 * @ORM\Entity(repositoryClass="App\Repository\UploadRepository")
 */
class Upload
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file_name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Session", mappedBy="upload", cascade={"persist", "remove"})
     */
    private $session;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function setFileName($file_name)
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(Session $session): self
    {
        $this->session = $session;

        // set the owning side of the relation if necessary
        if ($this !== $session->getUpload()) {
            $session->setUpload($this);
        }

        return $this;
    }
}