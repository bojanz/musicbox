<?php

namespace MusicBox\Controller;

use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class IndexController
{
    public function indexAction(Request $request, Application $app)
    {
        $limit = 4;
        $offset = 0;
        $likedOrderBy = array('likes' => 'DESC');
        $newestOrderBy = array('created_at' => 'DESC');
        $likedArtists = $app['repository.artist']->findAll($limit, $offset, $likedOrderBy);
        $newestArtists = $app['repository.artist']->findAll($limit, $offset, $newestOrderBy);
        // Divide artists into groups of 2.
        $groupSize = 2;
        $groupedLikedArtists = array();
        $groupedNewestArtists = array();
        $progress = 0;
        while ($progress < $limit) {
            $groupedLikedArtists[] = array_slice($likedArtists, $progress, $groupSize);
            $groupedNewestArtists[] = array_slice($newestArtists, $progress, $groupSize);
            $progress += $groupSize;
        }

        $data = array(
            'groupedLikedArtists' => $groupedLikedArtists,
            'groupedNewestArtists' => $groupedNewestArtists,
        );
        return $app['twig']->render('index.html.twig', $data);
    }
}
