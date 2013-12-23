Getting Started With CodeRefactorBundle
==================================

## Installation



### Step 1: Download CodeRefactorBundle using composer

Add CodeRefactorBundle in your composer.json:

```js
{
    "require": {
        "abenbachir/refactor-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update abenbachir/refactor-bundle
```

Composer will install the bundle to your project's `vendor/abenbachir` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Code\RefactorBundle\CodeRefactorBundle(),
    );
}
```

### Step 5: Configure the CodeRefactorBundle


Add the following configuration to your `config.yml`

``` yaml
# app/config/config.yml
code_refactor:
   scan:
       extensions: ['php','twig','yml','xml','js','css']
       ignore: ['/vendor','/web','/app/cache','/app/logs','/bin']
```

### Step 6: Run command

``` shell
php app/console refactor:project:rename
```
