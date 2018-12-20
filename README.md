# Documentation bundle

Tool for developpers to share documentation with users − embeds inline documentation
parsed from files into the current site.

# Installation

First install it:

```sh
composer require makinacorpus/documentation-bundle
```
**Note** that you have to add `https://github.com/makinacorpus/documentation-bundle` as a repository
in your composer.json first.

Create a foo documentation file:

```sh
mkdir -p docs/test
echo '
# This is a test page

Hello, World!
' > docs/test/hello-world.md

```

Then register the bundle in your `bundles.php` file:

```php
<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            ...

            new MakinaCorpus\DocumentationBundle\DocumentationBundle(),

            ...
        ];

        ...

        return $bundles;
    }
```

Add this minimum configuration in your config.yml:

```yaml
documentation:
    files:

        docs/test:                  # Non existing file path
            path: test              # Logical documentation path (tree is built upon it)
            title: Test section     # Displayed title in table of contents
            virtual: true           # Virtual page will be a table of content page

        docs/test/hello-world.md:   # Real file path
            path: test/hello        # Logical documentation path (tree is built upon it)
            title: Hello, World!    # Displayed title in table of contents
```

# Usage

## Write documentation

Your documentation must live somewhere in your project root, anywhere.

@todo as of now, it must live in the `%kernel.project_root%/docs/` folder under.

## Register it

@todo see bare example on top

## FAQ

### What do I do if my original page contains the page title ?

You may disable the `title` option being displayed in page easily:

```yaml
    files:
        # ... your other pages and sections

        docs/test/hello-world.md
            path: test/hello
            skip_title              # Do not display in page contents
            title: Hello, World!    # Will only be displayed in table of contents
```

### Any other questions ?

Yeah, not for now, I'm good thank you.

## Tweak display

@todo implement template base name override

## Access control

As of now, access is denied except for user tokens that have the
`MakinaCorpus\DocumentationBundle\DocumentationBundle::ROLE_VIEW_DOCUMENTATION` role.

This will change.

@todo make it configurable (disable access check, allow using security.yaml)

## Allow formats

As of now, you may only place:

 - plain html using the `.html` file name suffix,
 - markdown files, with either `.md` or `.markdown` file name suffix.

@todo make this

