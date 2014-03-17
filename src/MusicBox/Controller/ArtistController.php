<?php

namespace MusicBox\Controller;

use MusicBox\Entity\Comment;
use MusicBox\Entity\Like;
use MusicBox\Form\Type\CommentType;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class ArtistController
{
    public function indexAction(Request $request, Application $app)
    {
        // Perform pagination logic.
        $limit = 20;
        $total = $app['repository.artist']->getCount();
        $numPages = ceil($total / $limit);
        $currentPage = $request->query->get('page', 1);
        $offset = ($currentPage - 1) * $limit;
        $artists = $app['repository.artist']->findAll($limit, $offset);
        // Divide artists into groups of 4.
        $groupSize = 4;
        $groupedLikedArtists = array();
        $progress = 0;
        while ($progress < $limit) {
            $groupedArtists[] = array_slice($artists, $progress, $groupSize);
            $progress += $groupSize;
        }

        $data = array(
            'groupedArtists' => $groupedArtists,
            'currentPage' => $currentPage,
            'numPages' => $numPages,
            'here' => $app['url_generator']->generate('artists'),
        );
        return $app['twig']->render('artists.html.twig', $data);
    }

    public function viewAction(Request $request, Application $app)
    {
        $artist = $request->attributes->get('artist');
        if (!$artist) {
            $app->abort(404, 'The requested artist was not found.');
        }

        // Replace with the current user.
        $user = $app['repository.user']->find(2);
        $token = $app['security']->getToken();
        $user = $token->getUser();
        $commentFormView = NULL;
        if ($user != 'anon.') {
            // Provide and handle the add comment form.
            $comment = new Comment();
            $comment->setArtist($artist);
            $comment->setUser($user);
            // @todo Provide the option for comments to be initially unpublished.
            $comment->setPublished(TRUE);
            $commentForm = $app['form.factory']->create(new CommentType(), $comment);
            if ($request->isMethod('POST')) {
                $commentForm->bind($request);
                if ($commentForm->isValid()) {
                    // Save the comment.
                    $app['repository.comment']->save($comment);
                    // Send an email notification.
                    $this->sendNotification($comment, $app);
                    $app['session']->getFlashBag()->add('success', 'Your comment has been added.');
                }
            }
            $commentFormView = $commentForm->createView();
        }
        // @todo Might be a good idea to have pagination on comments.
        $comments = $app['repository.comment']->findAllByArtist($artist->getId(), 50);

        $data = array(
            'artist' => $artist,
            'soundcloudWidget' => $app['soundcloud']->getWidget($artist->getSoundCloudUrl()),
            'comments' => $comments,
            'newCommentForm' => $commentFormView,
        );
        return $app['twig']->render('artist.html.twig', $data);
    }

    public function likeAction(Request $request, Application $app)
    {
        $artist = $request->attributes->get('artist');
        $token = $app['security']->getToken();
        $user = $token->getUser();
        if (!$artist) {
            $app->abort(404, 'The requested artist was not found.');
        }
        if ($user == 'anon.') {
            // Only logged-in users can comment.
            return;
        }

        // Don't allow the user to like the artist twice.
        $existingLike = $app['repository.like']->findByArtistAndUser($artist->getId(), $user->getId());
        if (!$existingLike) {
            // Save the individual like record.
            $like = new Like();
            $like->setArtist($artist);
            $like->setUser($user);
            $app['repository.like']->save($like);

            // Increase the counter on the artist.
            $numLikes = $artist->getLikes();
            $numLikes++;
            $artist->setLikes($numLikes);
            $app['repository.artist']->save($artist);
        }
        return '';
    }

    protected function sendNotification($comment, Application $app)
    {
        $artist = $comment->getArtist();
        $user = $comment->getUser();
        $messageBody = 'The following comment was posted by ' . $user->getUsername() . ":\n";
        $messageBody .= $comment->getComment();
        $message = \Swift_Message::newInstance()
            ->setSubject('New comment posted for artist ' . $artist->getName())
            ->setFrom(array($app['site_email']))
            ->setTo(array($app['admin_email']))
            ->setBody('The following comment was posted by :');
        $app['mailer']->send($message);
    }
}
