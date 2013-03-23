MvBlogBundle Upgrade from 1.0.x
============

###1)  Remove from composer.json root `require` key  

    "Trsteel/ckeditor-bundle": "master@dev"

###2)  Replace in your AppKernel.php

    new Trsteel\CkeditorBundle\TrsteelCkeditorBundle(),

by

    new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

that's all (I think)