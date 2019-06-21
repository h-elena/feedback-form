<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Form
{
    /**
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
     * @Assert\NotBlank
     * @Assert\Email(
     *     message="Не корректно задан Email",
     *     checkMX = true
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Ваш Email должен быть как минимум {{ limit }} символов",
     *      maxMessage = "Ваш Email не должен быть длиннее {{ limit }} символов"
     * )
     */
    private $email;

    /**
     * @Assert\NotBlank
     */
    private $text;

    /**
     * @Assert\NotBlank
     */
    private $capcha;

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getCapcha(): ?string
    {
        return $this->capcha;
    }

    /**
     * @param mixed $text
     */
    public function setCapcha($capcha): void
    {
        $this->capcha = $capcha;
    }

    /**
     * @Assert\IsTrue(message = "Введенное выражение не верно.")
     */
    public function isCapchaChecked()
    {
        return (!empty($_SESSION['capcha']['OldResult']) && $this->capcha == $_SESSION['capcha']['OldResult'] ? true : false);
    }
}
