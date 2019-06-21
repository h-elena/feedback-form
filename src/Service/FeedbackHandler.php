<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use \Twig\Environment;
use App\Entity\Feedback;
use App\Entity\Users;
use App\Entity\Form;

class FeedbackHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var UserRepository */
    private $users;

    /** @var FeedbackRepository */
    private $feedback;

    /** @var Swift_Mailer */
    private $mailer;

    /** @var \Twig\Environment */
    private $templating;

    /** @var Symfony\Component\Routing\Router */
    private $router;

    /** @var Symfony\Component\HttpFoundation\Session\Session */
    private $session;

    public function __construct(
        EntityManagerInterface $em,
        \Swift_Mailer $mailer,
        Environment $templating,
        Session $session,
        Router $router
    )
    {
        $this->em = $em;
        $this->users = $em->getRepository(Users::class);
        $this->feedback = $em->getRepository(Feedback::class);

        $this->mailer = $mailer;
        $this->templating = $templating;

        $this->session = $session;
        $this->router = $router;
    }

    private function sendMail($setTo, $params)
    {
        $message = (new \Swift_Message('Сообщение из формы обратной связи'))
            ->setFrom('***@yandex.ru')
            ->setTo($setTo)
            ->setBody(
                $this->templating->render(
                    'emails/feedback.html.twig',
                    $params
                ),
                'text/html'
            );

        return $this->mailer->send($message);
    }

    public function saveMessage(Form $data)
    {
        $user = $this->users->findByEmail($data->getEmail());
        if (!$user) {
            $user = new Users();
            $user->setEmail($data->getEmail());
            $user->setDateCreate(new \DateTime());
            $user->setDateUpdate(new \DateTime());

            $feedback = new Feedback();
            $feedback->setName($data->getName());
            $feedback->setMessage($data->getText());
            $feedback->setDateCreate(new \DateTime());
            $feedback->setIp($_SERVER['REMOTE_ADDR']);
            $feedback->setUser($user);
        } else {
            $feedbackCountsIP = $this->feedback->findByIpOrEmailOnTime();
            if ($feedbackCountsIP > 2) {
                $this->session->getFlashBag()->add('error', 'Слишком много запросов с вашего адреса, пожалуйста, подождите минуту.');

                return false;
            }

            $feedbackCountsLastEmail = $this->feedback->findByInsertOnTime();
            if ($feedbackCountsLastEmail > 2) {
                $this->session->getFlashBag()->add('error', 'Слишком много запросов с вашего адреса, пожалуйста, подождите минуту.');

                return false;
            }

            $feedback = new Feedback();
            $feedback->setName($data->getName());
            $feedback->setMessage($data->getText());
            $feedback->setDateCreate(new \DateTime());
            $feedback->setIp($_SERVER['REMOTE_ADDR']);
            $feedback->setUser($user);
        }

        $this->em->persist($user);
        $this->em->persist($feedback);
        $this->em->flush();

        $params = [
            'name' => $data->getName(),
            'email' => $data->getEmail(),
            'text' => $data->getText(),
        ];

        if ($this->sendMail($data->getEmail(), $params)) {
            $params['link'] = 'http://domain' . $this->router->generate('user_feedback', ['id' => $user->getId()]);
            if ($this->sendMail('rabota@awardwallet.com', $params)) {
                $this->session->getFlashBag()->add('success', 'Ваше сообщение было отправлено.');

                return true;
            }
        }
    }
}