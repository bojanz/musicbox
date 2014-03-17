<?php

// Register route converters.
// Each converter needs to check if the $id it received is actually a value,
// as a workaround for https://github.com/silexphp/Silex/pull/768.
$app['controllers']->convert('artist', function ($id) use ($app) {
    if ($id) {
        return $app['repository.artist']->find($id);
    }
});
$app['controllers']->convert('comment', function ($id) use ($app) {
    if ($id) {
        return $app['repository.comment']->find($id);
    }
});
$app['controllers']->convert('user', function ($id) use ($app) {
    if ($id) {
        return $app['repository.user']->find($id);
    }
});

// Register routes.
$app->get('/', 'MusicBox\Controller\IndexController::indexAction')
    ->bind('homepage');

$app->get('/me', 'MusicBox\Controller\UserController::meAction')
    ->bind('me');
$app->match('/login', 'MusicBox\Controller\UserController::loginAction')
    ->bind('login');
$app->get('/logout', 'MusicBox\Controller\UserController::logoutAction')
    ->bind('logout');

$app->get('/artists', 'MusicBox\Controller\ArtistController::indexAction')
    ->bind('artists');
$app->match('/artist/{artist}', 'MusicBox\Controller\ArtistController::viewAction')
    ->bind('artist');
$app->match('/artist/{artist}/like', 'MusicBox\Controller\ArtistController::likeAction')
    ->bind('artist_like');
$app->get('/api/artists', 'MusicBox\Controller\ApiArtistController::indexAction');
$app->get('/api/artist/{artist}', 'MusicBox\Controller\ApiArtistController::viewAction');
$app->post('/api/artist', 'MusicBox\Controller\ApiArtistController::addAction');
$app->put('/api/artist/{artist}', 'MusicBox\Controller\ApiArtistController::editAction');
$app->delete('/api/artist/{artist}', 'MusicBox\Controller\ApiArtistController::deleteAction');

$app->get('/admin', 'MusicBox\Controller\AdminController::indexAction')
    ->bind('admin');

$app->get('/admin/artists', 'MusicBox\Controller\AdminArtistController::indexAction')
    ->bind('admin_artists');
$app->match('/admin/artists/add', 'MusicBox\Controller\AdminArtistController::addAction')
    ->bind('admin_artist_add');
$app->match('/admin/artists/{artist}/edit', 'MusicBox\Controller\AdminArtistController::editAction')
    ->bind('admin_artist_edit');
$app->match('/admin/artists/{artist}/delete', 'MusicBox\Controller\AdminArtistController::deleteAction')
    ->bind('admin_artist_delete');

$app->get('/admin/users', 'MusicBox\Controller\AdminUserController::indexAction')
    ->bind('admin_users');
$app->match('/admin/users/add', 'MusicBox\Controller\AdminUserController::addAction')
    ->bind('admin_user_add');
$app->match('/admin/users/{user}/edit', 'MusicBox\Controller\AdminUserController::editAction')
    ->bind('admin_user_edit');
$app->match('/admin/users/{user}/delete', 'MusicBox\Controller\AdminUserController::deleteAction')
    ->bind('admin_user_delete');

$app->get('/admin/comments', 'MusicBox\Controller\AdminCommentController::indexAction')
    ->bind('admin_comments');
$app->match('/admin/comments/{comment}/edit', 'MusicBox\Controller\AdminCommentController::editAction')
    ->bind('admin_comment_edit');
$app->match('/admin/comments/{comment}/delete', 'MusicBox\Controller\AdminCommentController::deleteAction')
    ->bind('admin_comment_delete');
$app->match('/admin/comments/{comment}/approve', 'MusicBox\Controller\AdminCommentController::approveAction')
    ->bind('admin_comment_approve');
$app->match('/admin/comments/{comment}/unapprove', 'MusicBox\Controller\AdminCommentController::unapproveAction')
    ->bind('admin_comment_unapprove');
