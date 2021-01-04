# Migrating from FOF 3 to FOF 4

FOF 4 is a major version, breaking backwards compatibility. The first goal was to remove features which have become
obsolete in the four or so years since FOF 3's release. The second goal was to add features which are useful but might
 break the way FOF renders components, thus requiring a major version update. The third objective is to move towards
 Joomla! 4 compatibility. The third goal is something which will happen over the course of FOF 4's lifetime as it's
  nowhere near stable by the time we started writing FOF 4.

FOF 4 uses a different library path and namespace than its predecessor. This allows you to run both versions of the
framework side by side and perform upgrades to your components at your own pace.

This document discusses the differences between FOF 3 and FOF 4 and gives you useful information for migrating your extensions from FOF 3 to FOF 4. There is no migration path from FOF 2 to FOF 4.

## Namespaces and includes

You need to replace the `FOF30` namespace with `FOF40`. This should be a fairly straightforward search and replace, _as
 long as you do it case-sensitive_.

The other obvious change is that the code to include FOF 4.x to your extension has changed. You should use something
like this instead:

```php
if (!defined('FOF40_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof40/include.php'))
{
    die('You need to have the Akeeba Framework-on-Framework (FOF) version 4 package installed on your site to use this
     component. Please visit https://www.akeeba.com/download/fof4.html to download it and install it on your site.');

    return;
}
```

## The Utils package has been refactored and partially deprecated

The Utils package has grown over the lifetime of FOF 3 into an unwieldy mess of unrelated features. In an attempt to
restore sanity the Utils package is being refactored to only include helper classes used internally by FOF. The 
remaining, very useful features are being refactored into their own, semantically coherent packages.

### Not changed

The following classes did NOT move:

* FOF40\Utils\ArrayHelper
* FOF40\Utils\Buffer
* FOF40\Utils\Collection
* FOF40\Utils\FilesCheck
* FOF40\Utils\ModelTypeHints
* FOF40\Utils\PhpFunc

### Removed classes

The following classes have been removed from the Utils package.

* FOF40\Utils\StringHelper. Use the function fofStringToBool() instead of StringHelper::toBool().
* FOF40\Utils\FEFHelper\Html. Use FOF40\Html\FEFHelper\BrowseView instead (your code might need rewriting).
* FOF40\Utils\InstallScript. Use FOF40\InstallScript\Component instead.

### Moved classes

The following classes have been moved out of the Utils package and into existing or new packages. The following table
helps you understand what moved where.

| From | To |
| ---- | ---- |
| FOF40\Utils\CacheCleaner | FOF40\JoomlaAbstraction\CacheCleaner |
| FOF40\Utils\ComponentVersion | FOF40\JoomlaAbstraction\ComponentVersion |
| FOF40\Utils\DynamicGroups | FOF40\JoomlaAbstraction\DynamicGroups |
| FOF40\Utils\FEFHelper\BrowseView | FOF40\Html\FEFHelper\BrowseView |
| FOF40\Utils\FEFHelper\Html | (removed) |
| FOF40\Utils\InstallScript\BaseInstaller | FOF40\InstallScript\BaseInstaller |
| FOF40\Utils\InstallScript\Component | FOF40\InstallScript\Component |
| FOF40\Utils\InstallScript\Module | FOF40\InstallScript\Module |
| FOF40\Utils\InstallScript\Plugin | FOF40\InstallScript\Plugin |
| FOF40\Utils\InstallScript | FOF40\InstallScript\Component |
| FOF40\Utils\Ip | FOF40\IP\IPHelper |
| FOF40\Utils\SelectOptions | FOF40\Html\SelectOptions |
| FOF40\Utils\TimezoneWrangler | FOF40\Date\TimezoneWrangler |

The FOF Autoloader adds class aliases for the moved classes. The aliases are a temporary measure and **WILL** be removed
in FOF 5. We strongly recommend you to refactor your code using the new class names (in the "To" column) to ensure
future compatibility.

## Installation and dependencies

Use the [`InstallScript` class](https://github.com/akeeba/fof/wiki/The-InstallScript-class) shipped with FOF 4. During
extension installation/upgrade it will remove your extension from the list of FOF 3 dependent extensions and will add
it to the list of FOF 4 dependent extensions. Likewise, on uninstallation, it will remove your extensions from the lists
of FOF 3 and FOF 4 dependent extensions.

Moreover, during installation/upgrade, it will check if there are any more extensions depending on FOF 3. If not, it
 will try to uninstall it.

**DO NOT** directly copy or remove the FOF files from `JPATH_LIBRARIES/lib_fof40`.

**DO NOT** try to manually uninstall or remove FOF 3.

**DO** include FOF 4 in your `package` type extension and **DO NOT** list it in the package manifest. You need to follow
the example of our extensions. Otherwise, when you are trying to install an outdated version of FOF Joomla! will see the
package failing to install and will stop installing your extension, throwing an error. DO NOT try to circumvent this by
copying files directly. Doing so will put you permanently on a public Hall of Shame.

## FEF

A lot of the work done on FOF 3.4 and 4.0 revolves around using Akeeba Frontend Framework (FEF). You are not required to
use it, and we do not have public documentation for it. If you do choose to use it please make sure that you are
installing it the same way as FOF 4 (see above).

## Common Blade view templates

FOF 4 ships with a ViewTemplates folder which contains common view templates. They use per-renderer extensions (see
below) to provide the same view template for different frontend framework requirements. These are used as wrappers in 
browse and edit views and implement picking and displaying users (the latter two being necessary after the removal of 
XML forms, see below). These can be included by using `any:lib_fof40/Common/templateName` and can be overridden in two 
ways:

* Globally, in the `templates/YOUR_TEMPLATE/html/lib_fof40/Common` folder
* Per component, in the `View/Common/tmpl`, `views/Common/tmpl` or `ViewTemplates/Common` folder per standard FOF
  convention.

If you are not using FEF, you can simply skip these common FEF View Templates.

The common Blade templates are implemented as a last resort fallback in FOF's ViewTemplateFinder. This means that FOF
will look for view template files in the following order: template overrides for your component, your component, the 
other side of your component (only if you're using a magic Factory), common Blade view template's overrides in 
`<template Folder>/html/lib_fof40/Common` and finally FOF itself (`libraries/fof40/ViewTemplates/Common`).

Unlike regular view templates, FOF will only look for `.blade.php` overrides for common Blade view templates. This is on
purpose. You are meant to `@include` them in your own Blade templates and override their sections as you see fit.

## Removal of XML forms

One of the major changes in FOF 4 is the removal of the Form package, a.k.a. XML forms. When we first introduced this
feature, Joomla had a limited number of form fields. There was a very well-defined HTML output, created in the PHP classes implementing the form fields. In the following years, Joomla! went on to using `JLayout` to try to abstract the output of form fields, allowing templates to override them.

The problem with that approach is that we had two features not present in Joomla's forms: header fields and using XML
forms for browse views. Each of these features required us to provide our own HTML output. This was produced inside
the PHP classes implementing the fields because that's what Joomla! itself did at that point. Moreover, because of the
way class hierarchies in Joomla's JForm package worked we had to literally extend each and every field class and add the
extra functionality we needed. When Joomla transitioned to JLayouts, our fields would no longer render correctly in some
templates. Even if we rewrote the entire feature to use JLayouts, there was no incentive for template developers to
provide overrides for our JLayouts so we still had the same issue.

On top of that, using XML forms in real world software proved that they are limited and cumbersome. Very quickly you end
up writing hordes of custom fields which are very difficult (if not impossible) to override. This makes output customisation very painful.

PHP view templates, introduced in Joomla! 1.5 back in 2007, are a much more versatile approach. They do, however, have a
fundamental issue: they are very verbose and make it difficult for frontend developers to understand what is going on.

The best alternative is templates using the Blade syntax. They are far less verbose, make much more sense to frontend  developers, and they are transpiled to PHP. The latter means that you can still use PHP code where needed. The downside is that  displaying them requires the `tokenizer` PHP extension. However, we have a solution to that in the form of precompiled  templates (placed in your component's PrecompiledTemplates folder, following the same structure as the ViewTemplates  folder). If the tokenizer is not available you will fall back to PrecompiledTemplates. The downside is that template  overrides in this case must be pure PHP templates, not Blade. If you are on a bad host which doesn't allow you to use  the tokenizer just switch hosts. There is absolutely no security reason whatosever to disable the tokenizer extension.  The only real reason is that your host doesn't understand how PHP works -- which in itself is a security threat!!!

## Removal of scaffolding

Scaffolding in FOF 3 let you create Controllers, Models, Views and XML forms based on your database schema. This was a quick way to start hashing out a component. However, most of that functionality has been superseded by other FOF features, making this feature obsolete.

Creating a Controller, Model or View class file is not actually necessary. If you want to quickly whip out a component you have two options. One, create empty class files extending the base Controller/DataController, Model/DataModel and View/Html/Json/Csv classes respectively. This is the recommended method. Two, use the Magic factory to have FOF fill in the gaps for you.

This leaves us with view templates. Scaffolding was only really useful in creating XML forms which could kinda sorta be used to represent your data but suffered from all the problems plaguing XML forms, outlined above. We consider using Blade and the built-in common Blade view templates the best way to create a quick interface for your component. Moreover, you get to choose the CSS framework you'd like to implement instead of being forced to use Bootstrap 2 as was the case with XML forms. You win something, you lose something. In our experience the end result is far more flexible without too much additional time spent designing the interface.

## Removal of the LESS package

The third party LESS compiler we were using in FOF had not been updated in ages. This made us rather nervous as to
whether it still works correctly. Moreover, LESS seems to have been gradually abandoned for Sass/SCSS or completely
ditched for modern CSS which allows variable replacements. Moreover, we have seen that an increasing number of
developers introduce a step of precompilation and / or minification of their CSS in their build and release workflow.
Finally, compiling LESS on the fly was _slow_ and had several challenges regarding making the compiled file available
to the browser.

With that in mind we completely removed LESS support from FOF 4. You are advised to compile and minify your CSS before
releasing your extension.

## Automatic template suffixes based on Joomla! version _and_ renderer used

A goal of FOF 4 is the easier implementation of component which work across substantially different Joomla! versions
(e.g. Joomla! 3 and Joomla! 4). The biggest challenge with that is that the HTML you need to output in each case is
probably radically different. For this reason we add automatic suffixes to view templates based on the Joomla! version
and FOF renderer used.

For example, consider that you are trying to load the view template `default.php` on a Joomla! 3.9 site using the `FEF`
renderer. FOF will try to file a view template file from most to least specific:

* default.j39.fef.php
* default.j39.php
* default.j3.fef.php
* default.j3.php
* default.fef.php
* default.php

First one to be found is used.

When trying to understand which view template will be loaded also keep in mind that the suffixes do not override the
path priority. What we mean is that FOF will first look for _template overrides_ in the site's template, then view
templates in the component (in the order `ViewTemplates/view_name/default.php`, `View/view_name/tmpl/default.php`,
`views/view_name/tmpl/default.php`). If you are using a Magic factory the same will be repeated on the other side of the
application (e.g. the backend if you are accessing the component from the site's frontend).

In simple terms, if your client has created a template override for `default.php` it will be loaded instead of the
`default.j39.fef.php` inside your component. This is on purpose. The idea being that a template override is always
most specific as it's done for a specific site which runs a specific Joomla! version and for a specific component whose
renderer you already know.

## Renderer changes

The following changes have taken places in FOF's renderers:

* **AkeebaStrapper** has been removed. This was a transitional renderer which backported Bootstrap 2 styling in Joomla!
  2.5. Joomla! 2.5 is no longer supported and Akeeba Strapper (the library with the custom, namespaced Boostrap 2
  distribution) is obsolete. Therefore this renderer has no reason of existence.
* **Joomla** has been added. This is the new default renderer (if the FEF renderer is unavailable) and works in all 
  Joomla! versions supported by FOF (3.x and 4.x).
* **Joomla3** no longer outputs the wrapper DIV id `akeeba-renderjoomla`. Instead, it outputs the wrapper DIV class
  `akeeba-renderer-joomla` and `akeeba-renderer-joomla3`. Moreover, it will only enable itself on Joomla! 3.x; it will
  be disabled on Joomla! 4.x.
* **Joomla4** has been added. This is currently a tentative renderer since Joomla! 4 has not reached a beta stage and
  its template is still under development. It outputs the wrapper DIV class `akeeba-renderer-joomla` and 
  `akeeba-renderer-joomla4`. Moreover, it will only enable itself on Joomla! 4.x; it will be disabled on Joomla! 3.x.
* **FEF** no longer outputs the wrapper DIV id `akeeba-renderer-fef`. Instead, it only outputs the wrapper DIV class
  `akeeba-renderer-fef`. It extends the `Joomla` renderer, but it will NOT output the `akeeba-renderer-joomla` wrapper
  DIV class; this class is forcibly added to the `remove_wrapper_classes` renderer option.

All renderers support the `remove_wrapper_classes` and `add_wrapper_classes` renderer options. These options now allow
you to also _remove_ the wrapper classes (e.g. `akeeba-renderer-joomla` and `akeeba-renderer-fef`) if you so wish. Just
remember that removing the wrapper class from the FEF renderer will result in unstyled content unless you wrap the
output yourself in a DIV with the class `akeeba-renderer-fef`.

If you wrote a custom renderer extending the now defunct `AkeebaStrapper` renderer please extend the `Joomla` renderer
OR the `RenderBase` class instead.

Finally, keep in mind that the default renderer is automatically detected using the information provided by FOF's Render
classes. If FEF is installed on your site, FOF will automatically prefer the FEF renderer instead of the Joomla! 
renderer. This many NOT be what you want. Always set up the desired renderer in your `fof.xml` file to prevent nasty
surprises.

## Obsolete methods

`StringHelper::toSlug` was removed. Replace calls to it with `\Joomla\CMS\Application\ApplicationHelper::stringURLSafe()`

`StringHelper::toASCII` was removed. Replace calls to it with `\Joomla\CMS\Factory::getLanguage()->transliterate()`

## DataModel changes

The following protected properties have been renamed, dropping their underscore prefix:

* _trackAssets to trackAssets
* _has_tags to has_tags
* _rules to rules
* _behaviorParams to behaviorParams
* _assetKey to assetKey

## Encrypt package backwards-incompatible changes

### Discontinued mcrypt support

The mcrypt PHP extension has been declared deprecated since PHP 7.1. Moreover, it's not been maintained since 2003, 
making it unsuitable for production. PHP recommends replacing it with OpenSSL. To this end we had modified our Encrypt 
package to work with both mcrypt and OpenSSL since FOF 3.0.13 released in August 2016. In FOF 4 we are completely 
removing mcrypt support. This change is transparent as long as you use the Encrypt\Aes class. If you were instantiating
Encrypt\AesAdapter\Mcrypt directly your code will break.

### Changed signature of Encrypt\Aes methods

AES encryption used to allow an optional strength. This behaved differently in mcrypt and OpenSSL implementation so it
was deprecated. Support for the strength has been removed.

Key expansion had also been modified in FOF 3. The very old method (renamed to Legacy Mode) was using an insecure key 
expansion and was deprecated. The new method is much more secure. We removed the legacy method.

As a result of the above we changed the following methods:

* Encrypt\Aes::__construct. The deprecated $key and $strength parameters have been removed.
* Encrypt\Aes::setPassword. The $legacy option has been removed.
* Encrypt\AesAdapter\AdapterInterface::setEncryptionMode. The $strength parameter has been removed. 

Moreover, we have removed the unused $strength parameter from Encrypt\Aes and also modified 
Encrypt\AesAdapter\AdapterInterface::setEncryptionMode accordingly.

## PHP 7.2 and method declarations

Since the minimum version has been raised to PHP 7.2 we are also changing the method declarations to include parameter
type hints for scalar types and method return values including nullable types and the special type "void". When this is
not possible (e.g. a method parameter accepts a string and an array) type checking is implemented in the method, 
throwing an InvalidArgumentException when the type constraints are violated.

These changes imply that any overrides of the methods need to conform to the new, typed declaration. This may cause
backwards incompatible breaks in your code.

Furthermore, some methods have changed their return type for sanity's sake, again causing backwards incompatible
changes. Typically, methods which returned some less-than-sane types (e.g. string or boolean false) have had their 
return type converted to a nullable type (e.g. string or null, denoted by `?string`).

The following methods have had their return type changed:

* FOF40\Platform\FilesystemInterface::pathFind() -- FROM string|bool TO string|null
* FOF40\Download\Download::getFromURL() -- FROM string|bool TO string|null

## Removed HAL support from JSON output

In FOF 3 you could set the useHypermedia property to true to automatically inject HAL metadata to the JSON output. However, the HAL specification has not been updated since 2013. We don't really see it being much used in the wild or supported by frameworks consuming JSON data. A better suited replacement would be JSON-LD (JSON for Linking Data, a W3C standard) but it's not possible to automatically derive the context the format calls for. In fact, writing a FOF wrapper around it would make it far more complicated to use than if we just let you override the JSON output through a Json View class and / or a suitable JSON view template!

As a result we removed the HAL support from FOF and ask you to implement whichever JSON metadata scheme you want yourself.  