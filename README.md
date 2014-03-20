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

* Dump assets
    php app/console assetic:dump --env=prod --no-debug

4) Unit Test
-------------------------------------

Using phpunit, to run test, enter command:

    phpunit -c app src/Sil/WhatToCookBundle/
    
5) Demo Fridge (csv file) and recipes (json file)
-------------------------------------------------

csv file
---------

    Demo file:
    http://wtcdemo.phpfenix.biz/fridge.csv

    Format: item, amount, unit, use-by
    
    Where:
        Item (string) = the name of the ingredient – e.g. egg)
        Amount (int) = the amount
        Unit (enum) = the unit of measure, values of (for individual items; eggs, bananas etc), grams, ml (milliliters)  and slices
        Use-By (date) = the use by date of the ingredient (dd/mm/yy)

    e.g.
    bread,10,slices,25/12/2014
    cheese,10,slices,25/12/2014
    butter,250,grams,25/12/2014
    peanut butter,250,grams,2/12/2014
    mixed salad,150,grams,26/12/2013:
    
json file
---------

    Demo file:
    http://wtcdemo.phpfenix.biz/recipes.json

    Array of recipes with format specified as below
    name : String
    ingredients[] 
    item : String
    amount : int
    unit : enum

    e.g.
    [
        {
            "name": "grilled cheese on toast",
            "ingredients": [
                { "item":"bread", "amount":"2", "unit":"slices"},
                { "item":"cheese", "amount":"2", "unit":"slices"}
            ]
        }
        ,
        {
            "name": "salad sandwich",
            "ingredients": [
                { "item":"bread", "amount":"2", "unit":"slices"},
                { "item":"mixed salad", "amount":"100", "unit":"grams"}
            ]
        }
    ]


    phpunit -c app src/Sil/WhatToCookBundle/
