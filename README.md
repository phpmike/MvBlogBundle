MvBlogBundle
============

Blog Bundle for symfony2 v2.1 &amp; v2.2

## INSTALLATION with COMPOSER 

1)  Add to composer.json to the `require` key  

``` 
"mv/mv-blog-bundle": "0.1.*",
``` 

and add the repositories:

```
    "repositories": {
        "MvBlog": {
            "type": "git",
            "url": "git://github.com/phpmike/MvBlogBundle.git"
        }
    },   
```

2)  Add to your appKernel.php

```
new Mv\BlogBundle\MvBlogBundle(),
new Trsteel\CkeditorBundle\TrsteelCkeditorBundle(),
```

3)  Add to routing.yml

```
mv_blog:
    resource: "@MvBlogBundle/Controller/"
    type:     annotation
    prefix:   /
```

Run update with composer

```
php path/to/composer.phar update
```

4)  Blog is accessible on /blog and Admin panel on /badp/post

Enjoy...

To be continued