What To Cook (Symfony2 Bundle)
==============================

The program is written to take two inputs: fridge CSV list, and the JSON recipe data to produce a recommendation for what to cook tonight.

1) Demo
-------

    http://wtcdemo.phpfenix.biz/app_dev.php/whattocook

2) Install Symfony 2
----------------------------------

Follow

    http://symfony.com/doc/current/book/installation.html

3) Install What To Cook Bundle
-------------------------------------

Download the zip file from Github:

    https://github.com/sil218/whatToCook/archive/master.zip

Upload and extract to Symfony 2 folder

* Update app/AppKernel.php;
    $bundles = array(
            ...
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        );

* Update Assetic Configuration in app/config/config.yml;    
    bundles:        [ SilWhatToCookBundle ]

* Update Routing in app/config/routing.yml;
    sil_what_to_cook:
      resource: "@SilWhatToCookBundle/Resources/config/routing.yml"
      prefix:   /;    
      bundles:        [ SilWhatToCookBundle ]
