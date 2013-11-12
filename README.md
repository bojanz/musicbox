Musicbox
========
MusicBox is a portal based on the Silex framework, built for PHP 5.3 and MySQL 5.
It tries to demonstrate best practices in today's world of development, and has
been written as a university project by Bojan Zivanovic and Lana Petkovic.

Uses Doctrine DBAL for accessing the database, and Twig for its templates.
Relies on SwiftMailer to send emails.
The UI is based on Bootstrap 2.

Getting started
---------------
1. Move the files
Extract the contents of the "web" folder into your public html directory.
Copy all other folders one level above. Your structure should look like this:

    - app
    - src
    - vendor
    - public_html (Your public html directory, and inside the contents of the "web" folder)

Make sure that the img/artists and img/users directories in your public_html directory
are writable.

2. Create a new empty database, and import the sql dump provided in app/db_dump.sql
Open app/config/prod.php and under $app['db.options'] set your database credentials

3. MusicBox should now be functional. An admin user with the admin/admin username/password has been created for you.

Application structure
---------------------

    - app
      -- cache - Used by Twig to save compiled templates
      -- config - Contains the dev.php and prod.php files with configuration data (db credentials, etc).
      -- views - Contains the Twig templates called by the controllers, including the main layout file.
    - src - Contains the main application code
      -- app.php - Called by index.php, initializes the app by registering libraries into the dependency injection container.
      -- routes.php - Called by index.php, routes map urls to controller classes.
                       Each route has a name (such as "admin_artist_edit"), used to generate urls from the views and controllers.
      -- MusicBox - this folder contains the root of the namespace. All classes are nested below.
        --- Controller - Contains the controller classes.
        --- Entity - Contains the entities, POPO (plan old PHP objects) that represent the data that is manipulated by the repositories.
                 An entity class has properties that mostly correspond to database columns, as well as related getters and setters.
        --- Form - Contains the Form classes used by the Symfony Form library to render each form.
        --- Repository - Contains classes that manipulate entity data by doing queries against the database. This includes all CRUD operations.
    - vendor - Contains the dependencies managed by composer.
    - web - Contains static files (CSS, JS, etc) as well as the main entrypoint (index.php)

Application flow
----------------
index.php is called for all requests (directed by htaccess file).
index.php includes the config file, and then app.php and routes.php.
At this point, $app knows about all parts of the application, and the routes.
index.php then calls $app->run(), which instantiates the correct controller for the current url.
The controller manipulates data (creating, reading, updating, deleting Entities from their Repository), and passes it to a Twig template.
Twig has template inheritance, so the template file inherits the main layout file in order to provide all of the page elements.

The API
-------
The application provides a REST API for managing artists.
- GET api/artists - Provides a listing of artists. Possible parameters: limit (default: 20), offset.
- GET api/artist - Retrieves a single artist.
- POST api/artist - Creates a new artist.
- PUT  api/artist/{artist} - Updates a single artist.
- DELETE api/artist/{artist} - Deletes a single artist.

The output format is JSON.

Emails
------
The application notifies the admin by email of every new posted comment.
The sending is done in the ArtistController.
The recepient email address is configured in the dev.php config file.

Blog post: http://bojanz.wordpress.com/2013/11/11/learning-php-development-with-silex
