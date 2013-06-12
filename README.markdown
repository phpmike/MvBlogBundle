MvBlogBundle
============

Blog Bundle for symfony2 v2.3 (v2.2 are tagged 1.2.x and v2.1 are tagged 1.0.x)

If you're upgrading from 1.0.x, see "[UPGRADE-1.2](https://github.com/phpmike/MvBlogBundle/blob/master/Resources/Doc/UPGRADE-1.2.markdown)"

If you're upgrading from <= 1.2.1, see "[UPGRADE-1.2.2](https://github.com/phpmike/MvBlogBundle/blob/master/Resources/Doc/UPGRADE-1.2.2.markdown)"

If you're upgrading from <= 1.2.2, see "[UPGRADE-1.2.3](https://github.com/phpmike/MvBlogBundle/blob/master/Resources/Doc/UPGRADE-1.2.3.markdown)"

[![Build Status](https://secure.travis-ci.org/phpmike/MvBlogBundle.png)](http://travis-ci.org/phpmike/MvBlogBundle)

[![Total Downloads](https://poser.pugx.org/mv/mv-blog-bundle/d/total.png)](https://packagist.org/packages/mv/mv-blog-bundle)

[![Latest Stable Version](https://poser.pugx.org/mv/mv-blog-bundle/version.png)](https://packagist.org/packages/mv/mv-blog-bundle)

Features include:

- URLs SEO friendly
- Categories with 1 level sub category
- Article writing with ckEditor (ckEditor provided by Trsteel/ckeditor-bundle)
- Share your article on Facebook, Tweeter & Google+ (an api key needed for Facebook)
- Picture upload and management with resizing, croping and rotate (integrated in ckEditor and provided by helios-ag/fm-elfinder-bundle)
- Comments management with email confirmation to publish
- Time between 2 comments from same IP is parametrable
- Validator to exclude comments from email who's have host in parametrable black list (ex: temporary mails)

INSTALLATION with COMPOSER
--------------------------

You need have installed Symfony2 2.3 with Composer or have a composer.json file

###1)  Add to composer.json in the root `require` key  

    "mv/mv-blog-bundle": "~1.3.0@stable",
    "helios-ag/fm-elfinder-bundle": "dev-master"

The second requirement here because only dev version available for Symfony 2.3 and composer won't install otherwise

**I deeply recommend to use symlinks for assets to non expose your images to be deleted**

You can do it by adding this option to your "extra" key in composer.json

    "symfony-assets-install": "symlink"

*If you are using an exotic OS like Windows who don't support this option, you are wrong, framework made for you is .NET*

###2)  Add to your AppKernel.php

    new Mv\BlogBundle\MvBlogBundle(),
    new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
    new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
    new FM\ElfinderBundle\FMElfinderBundle(),

###3)  Add to routing.yml

For front pages

    mv_blog:
        resource: "@MvBlogBundle/Resources/config/routing.yml"
        prefix: /blog

For admin pages

    mv_blog_admin:
        resource: "@MvBlogBundle/Resources/config/routing_admin.yml"
        prefix: /badp

###4)  See `Resources/Example/security.yml.example` to configure access to the admin panel

**This is for dev tests only.**

Ensure you have in your AppKernel.php

    new JMS\AopBundle\JMSAopBundle(),
    new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

###5)  See `Resources/Example/config.yml.example`

This bundle send mails, a parameter is required for the email from.

Ensure you have in your AppKernel.php

    new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

###6)  Import config bundle in your config.yml

    imports:
        - { resource: "@MvBlogBundle/Resources/config/config.yml" }

**Be carrefull, may be you have already the "imports" key**

Run update with composer

    php path/to/composer.phar update

Dump assetic (for production)
    
    php app/console assetic:dump

**In future it will be this script in root composer.json**

    "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::dumpAssetic"

**VERY IMPORTANT**

Read this "[YUI Compressor](http://symfony.com/doc/current/cookbook/assetic/yuicompressor.html)" unless picture management won't work in production mode

###7)  You have to implement (if you don't have yet) an user management

You have access to admin panel in dev mode to see it, but you can't keep it in production mode.
To have routes for dev mode, you have to add in your app routing_dev.yml:

    mv_blog_secure:
        resource: "@MvBlogBundle/Resources/config/routing_dev.yml"
        prefix: /badp

ex: FOSUserBundle can help you.

Surcharge app/Resources/MvBlogBundle/views/_logout-link.html.twig to have your logout link.  
See original in `Mv/BlogBundle/Resources/views/_logout-link.html.twig`

You can also surcharge `base.admin-layout.html.twig` & `base.layout.html.twig` to change CSS, JS or this templates inherit from (ex: to integrate your header, footer or what you need)

###8)  Build Database

ex:

    app/console doctrine:schema:update

or to have dump sql:

    app/console doctrine:schema:update --dump-sql

###9)  Blog is accessible on /blog and Admin panel on /badp

**If you have an error when you try to access to image management, ensure your web/bundles/mvblog/images is writable by server**

Enjoy...

To be continued