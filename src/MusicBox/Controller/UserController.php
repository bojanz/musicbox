<?php

namespace MusicBox\Controller;

use MusicBox\Entity\User;
use MusicBox\Form\Type\UserType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function meAction(Request $request, Application $app)
    {
        $token = $app['security']->getToken();
        $user = $token->getUser();
        $now = new \DateTime();
        $interval = $now->diff($user->getCreatedAt());
        $memberSince = $interval->format('%d days %H hours %I minutes ago');
        $limit = 60;
        $likes = $app['repository.like']->findAllByUser($user->getId(), $limit);
        // Divide artists into groups of 6.
        $groupSize = 6;
        $groupedLikes = array();
        $progress = 0;
        while ($progress < $limit) {
            $groupedLikes[] = array_slice($likes, $progress, $groupSize);
            $progress += $groupSize;
        }

        $data = array(
            'user' => $user,
            'memberSince' => $memberSince,
            'groupedLikes' => $groupedLikes,
        );
        return $app['twig']->render('profile.html.twig', $data);
    }

    public function loginAction(Request $request, Application $app)
    {
        $form = $app['form.factory']->createBuilder('form')
            ->add('username', 'text', array('label' => 'Username', 'data' => $app['session']->get('_security.last_username')))
            ->add('password', 'password', array('label' => 'Password'))
            ->add('login', 'submit')
            ->getForm();

        $data = array(
            'form'  => $form->createView(),
            'error' => $app['security.last_error']($request),
        );
        return $app['twig']->render('login.html.twig', $data);
    }

    public function logoutAction(Request $request, Application $app)
    {
        $app['session']->clear();
        return $app->redirect($app['url_generator']->generate('homepage'));
    }
}
