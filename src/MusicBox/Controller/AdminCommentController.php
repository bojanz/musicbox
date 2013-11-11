<?php

namespace MusicBox\Controller;

use MusicBox\Entity\Comment;
use MusicBox\Form\Type\CommentType;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class AdminCommentController
{
    public function indexAction(Request $request, Application $app)
    {
        // Perform pagination logic.
        $limit = 10;
        $total = $app['repository.comment']->getCount();
        $numPages = ceil($total / $limit);
        $currentPage = $request->query->get('page', 1);
        $offset = ($currentPage - 1) * $limit;
        $comments = $app['repository.comment']->findAll($limit, $offset);

        $data = array(
            'comments' => $comments,
            'currentPage' => $currentPage,
            'numPages' => $numPages,
            'here' => $app['url_generator']->generate('admin_comments'),
        );
        return $app['twig']->render('admin_comments.html.twig', $data);
    }

    public function editAction(Request $request, Application $app)
    {
        $comment = $request->attributes->get('comment');
        if (!$comment) {
            $app->abort(404, 'The requested comment was not found.');
        }
        $form = $app['form.factory']->create(new CommentType(), $comment);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $app['repository.comment']->save($comment);
                $message = 'The comment has been saved.';
                $app['session']->getFlashBag()->add('success', $message);
            }
        }

        $data = array(
            'form' => $form->createView(),
            'title' => 'Edit comment',
        );
        return $app['twig']->render('form.html.twig', $data);
    }

    public function deleteAction(Request $request, Application $app)
    {
        $comment = $request->attributes->get('comment');
        if (!$comment) {
            $app->abort(404, 'The requested comment was not found.');
        }

        $app['repository.comment']->delete($comment->getId());
        return $app->redirect($app['url_generator']->generate('admin_comments'));
    }

    public function approveAction(Request $request, Application $app)
    {
        $comment = $request->attributes->get('comment');
        if (!$comment) {
            $app->abort(404, 'The requested comment was not found.');
        }

        $app['repository.comment']->delete($comment->getId());
        return $app->redirect($app['url_generator']->generate('admin_comments'));
    }

    public function unapproveAction(Request $request, Application $app)
    {
        $comment = $request->attributes->get('comment');
        if (!$comment) {
            $app->abort(404, 'The requested comment was not found.');
        }

        $app['repository.comment']->delete($comment->getId());
        return $app->redirect($app['url_generator']->generate('admin_comments'));
    }
}
