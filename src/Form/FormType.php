<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Form;

class FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $operationList = ['-', '+'];
        $number2 = rand(1, 100);
        $number1 = rand($number2 + 1, 100);
        $operation = $operationList[rand(0, 1)];

        $_SESSION['capcha']['text'] = $number1 . ' ' . $operation . ' ' . $number2;

        $request = Request::createFromGlobals();
        if (!empty($request->getContent())) {
            $_SESSION['capcha']['OldResult'] = $_SESSION['capcha']['result'];
        }

        eval('$_SESSION[\'capcha\'][\'result\'] = ' . $number1 . $operation . $number2 . ';');

        $builder
            ->add('name', TextType::class, ['label' => 'Имя', 'required' => true])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => true])
            ->add('text', TextareaType::class, ['label' => 'Tекст обращения', 'required' => true])
            ->add('capcha', TextType::class, ['label' => 'Вычислите ', 'required' => true])
            ->add('save', SubmitType::class, ['label' => 'Create Post']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Form::class,
        ]);
    }
}