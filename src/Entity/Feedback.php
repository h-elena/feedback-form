<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedbackRepository")
 * @ORM\Table(name="feedback")
 */
class Feedback
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Ваше имя должно быть как минимум {{ limit }} символов",
     *      maxMessage = "Ваше имя не должно быть длиннее {{ limit }} символов"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zа-яё\s\-]+$/ui",
     *     match=true,
     *     message="Не корректно задано имя"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="text")
     */
    private $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="feedback")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(Users $user)
    {
        $this->user = $user;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }
}
