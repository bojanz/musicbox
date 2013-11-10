<?php

namespace MusicBox\Controller;

use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class AdminController
{
    public function indexAction(Request $request, Application $app)
    {
      // Just redirect to admin/artists for now.
      return $app->redirect($app['url_generator']->generate('admin_artists'));
    }
}
