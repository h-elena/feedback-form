<?php

namespace App\Controller;

use App\Service\FeedbackHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormType;
use App\Entity\Form;
use App\Entity\Users;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, FeedbackHandler $feedbackHandler)
    {
        $session = $request->getSession();
        $session->start();

        $entity = new Form();
        $form = $this->createForm(FormType::class, $entity);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                if (!$feedbackHandler->saveMessage($data)) {
                    return $this->render('pages/index.html.twig', [
                        'form' => $form->createView(),
                        'capchaText' => ($_SESSION['capcha']['text'] ?? '')
                    ]);
                }

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('pages/index.html.twig', [
            'form' => $form->createView(),
            'capchaText' => ($_SESSION['capcha']['text'] ?? '')
        ]);
    }

    /**
     * @Route("/user-feedback/{id}", name="user_feedback")
     */
    public function userFeedback(Users $user_id)
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['id' => $user_id]);

        $feedbackList = [];
        $lists = $user->getFeedback();
        if (!empty($lists)) {
            foreach ($lists as $list) {
                $feedbackList[] = [
                    'name' => $list->getName(),
                    'message' => $list->getMessage(),
                    'date' => $list->getDateCreate()->format('d.m.Y H:i'),
                ];
            }
        }

        return $this->render('pages/messages.html.twig', [
            'feedbackList' => $feedbackList,
            'email' => $user->getEmail()
        ]);
    }
}