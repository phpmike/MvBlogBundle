MvBlogBundle Upgrade from <= 1.2.1
============

###1)  Change in app routing.yml

Change

    mv_blog:
        resource: "@MvBlogBundle/Resources/config/routing.yml"

By

    mv_blog:
        resource: "@MvBlogBundle/Resources/config/routing.yml"
        prefix: /blog

Add

    mv_blog_admin:
        resource: "@MvBlogBundle/Resources/config/routing_admin.yml"
        prefix: /badp

###2)  For dev only, add in app routing_dev.yml

    mv_blog_secure:
        resource: "@MvBlogBundle/Resources/config/routing_dev.yml"
        prefix: /badp

that's all (I think)