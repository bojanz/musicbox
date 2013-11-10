<?php

namespace MusicBox\Controller;

use MusicBox\Entity\Artist;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class ApiArtistController
{
    public function indexAction(Request $request, Application $app)
    {
        $limit = $request->query->get('limit', 20);
        $offset = $request->query->get('offset', 0);
        $artists = $app['repository.artist']->findAll($limit, $offset);
        $data = array();
        foreach ($artists as $artist) {
            $data[] = array(
                'id' => $artist->getId(),
                'name' => $artist->getName(),
                'short_biography' => $artist->getShortBiography(),
                'biography' => $artist->getBiography(),
                'soundcloud_url' => $artist->getSoundCloudUrl(),
                'likes' => $artist->getLikes(),
            );
        }

        return $app->json($data);
    }

    public function viewAction(Request $request, Application $app)
    {
        $artist = $request->attributes->get('artist');
        if (!$artist) {
            return $app->json('Not Found', 404);
        }
        $data = array(
            'id' => $artist->getId(),
            'name' => $artist->getName(),
            'short_biography' => $artist->getShortBiography(),
            'biography' => $artist->getBiography(),
            'soundcloud_url' => $artist->getSoundCloudUrl(),
            'likes' => $artist->getLikes(),
        );

        return $app->json($data);
    }

    public function addAction(Request $request, Application $app)
    {
        if (!$request->request->has('name')) {
            return $app->json('Missing required parameter: name', 400);
        }
        if (!$request->request->has('short_biography')) {
            return $app->json('Missing required parameter: short_biography', 400);
        }

        $artist = new Artist();
        $artist->setName($request->request->get('name'));
        $artist->setShortBiography($request->request->get('short_biography'));
        $artist->setBiography($request->request->get('biography'));
        $artist->setSoundCloudUrl($request->request->get('soundcloud_url'));
        $app['repository.artist']->save($artist);

        $headers = array('Location' => '/api/artist/' . $artist->getId());
        return $app->json('Created', 201, $headers);
    }

    public function editAction(Request $request, Application $app)
    {
        $artist = $request->attributes->get('artist');
        if (!$artist) {
            return $app->json('Not Found', 404);
        }
        if (!$request->request->has('name')) {
            return $app->json('Missing required parameter: name', 400);
        }
        if (!$request->request->has('short_biography')) {
            return $app->json('Missing required parameter: short_biography', 400);
        }
        $artist->setName($request->request->get('name'));
        $artist->setShortBiography($request->request->get('short_biography'));
        $artist->setBiography($request->request->get('biography'));
        $artist->setSoundCloudUrl($request->request->get('soundcloud_url'));
        $app['repository.artist']->save($artist);

        return $app->json('OK', 200);
    }

    public function deleteAction(Request $request, Application $app)
    {
        $artist = $request->attributes->get('artist');
        if (!$artist) {
            return $app->json('Not Found', 404);
        }
        $app['repository.artist']->delete($artist);

        return $app->json('No Content', 204);
    }
}
