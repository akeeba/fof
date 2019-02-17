# Migrating from FOF 3 to FOF 4

FOF 4 is a major version, breaking backwards compatibility. The first goal was to remove features which have become
obsolete in the four or so years since FOF 3's release. The second goal was to add features which are useful but might
 break the way FOF renders components, thus requiring a major version update. The third objective is to move towards
 Joomla! 4 compatibility. The third goal is something which will happen over the course of FOF 4's lifetime as it's
  nowhere near stable by the time we started writing FOF 4.

FOF 4 uses a different library path and namespace than its predecessor. This allows you to run both versions of the
framework side by side and perform upgrades to your components at your own pace.

This document discusses the differences between FOF 3 and FOF 4 and gives you tips migrating your extensions from FOF 3
 to FOF 4. There is no migration path from FOF 2 to FOF 4.

## Namespaces and includes

You need to replace the `FOF30` namespace with `FOF40`. This should be a fairly staightforward search and replace, _as
 long as you do it case sensitive_.

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

## Installation and dependencies

Use the [`InstallScript` class](https://github.com/akeeba/fof/wiki/The-InstallScript-class) shipped with FOF 4. During
extension installation/upgrade it will remove your extension from the list of FOF 3 dependent extensions and will add
it to the list of FOF 4 dependent extensions. Likewise, on uninstallation, it will remove your extensions from the lists
of FOF 3 and FOF 4 dependent extensions.

Moreover, during installation/upgrade, it will check if there are any more extensions depending on FOF 3. If not, it
 will try to uninstall it.

**DO NOT** directly copy or remove the FOF files from JPATH_LIBRARIES/lib_fof40.

**DO NOT** try to manually uninstall or remove FOF 3.

**DO** include FOF 4 in your `package` type extension and **DO NOT** list it in the package manifest. You need to follow
the example of our extensions. Otherwise, when you are trying to install an outdated version of FOF Joomla! will see the
package failing to install and will stop installing your extension, throwing an error. DO NOT try to circumvent this by
copying files directly. Doing so will put you permanently on a public Hall of Shame.

## FEF

A lot of the work done on FOF 3.4 and 4.0 revolves around using Akeeba Frontend Framework (FEF). You are not required to
use it and we do not have public documentation for it. If you do choose to use it please make sure that you are
installing it the same way as FOF 4 (see above).

## Common Blade view templates

FOF 4 ships with a ViewTemplates folder which contains common view templates. They use per-renderer extensions (see
below) to provide the same view template for different frontend framework requirements. These are used as wrappers in 
browse and edit views and implement picking and displaying users (the latter two being necessary after the removal of 
XML forms, see below). These can be included by using `any:lib_fof40/Common/templateName` and can be overriden in two 
ways:

* Globally, in the `templates/YOUR_TEMPLATE/html/lib_fof40/Common` folder
* Per component, in the `View/Common/tmpl`, `views/Common/tmpl` or `ViewTemplates/Common` folder per standard FOF
  convention.

If you are not using FEF you can simply skip these common FEF View Templates.

The common Blade templates are implemented as a last resort fallback in FOF's ViewTemplateFinder. This means that FOF
will look for view template files in the following order: template overrides for your component, your component, the 
other side of your component (only if you're using a magic Factory), common Blade view template's overrides in 
`<template Folder>/html/lib_fof40/Common` and finally FOF itself (`libraries/fof40/ViewTemplates/Common`).

Unlike regular view templates, FOF will only look for `.blade.php` overrides for common Blade view templates. This is on
purpose. You are meant to `@include` them in your own Blade templates and override their sections as you see fit.

## Removal of XML forms

One of the major changes in FOF 4 is the removal of the Form package, a.k.a. XML forms. When we first introduced this
feature Joomla had a limited number of form fields and a very well defined HTML output, created in the PHP classes
implementing the form fields. In the following years, Joomla! went on to using JLayouts to try and abstract the output
of form fields, allowing templates to override them.

The problem with that approach is that we had two features not present in Joomla's forms: header fields and using XML
forms for browse views. Each of these features required us to provide our own HTML output. This was produced inside
the PHP classes implementing the fields because that's what Joomla! itself did at that point. Moreover, because of the
way class hierarchies in Joomla's JForm package worked we had to literally extend each and every field class and add the
extra functionality we needed. When Joomla transitioned to JLayouts our fields would no longer render correctly in some
templates. Even if we rewrote the entire feature to use JLayouts there was no incentive for template developers to
provide overrides for our JLayouts so we still had the same issue.

On top of that, using XML forms in real world software proved that they are limited and cumbersome. Very quickly you end
up writing hordes of custom fields which are very difficult (if not impossible) to override. This makes output
customisation very painful.

PHP view templates, introduced in Joomla! 1.5 back in 2007, are a much more versatile approach but they have a
fundamental issue: they are very verbose and make it difficult for frontend developers to understand what is going on.

The best alternative is templates using the Blade syntax. They are far less verbose, make much more sense to frontend
developers and they are transpiled to PHP, meaning that you can still use PHP code where needed. The downside is that
displaying them requires the `tokenizer` PHP extension. However, we have a solution to that in the form of precompiled
templates (placed in your component's PrecompiledTemplates folder, following the same structure as the ViewTemplates
folder). If the tokenizer is not available you will fall back to PrecompiledTemplates. The downside is that template
overrides in this case must be pure PHP templates, not Blade. If you are on a bad host which doesn't allow you to use
the tokenizer just switch hosts. There is absolutely no security reason whatosever to disable the tokenizer extension.
The only real reason is that your host doesn't understand how PHP works -- which in itself is a security threat!!!

## Removal of the LESS package

The third aprty LESS compiler we were using in FOF had not been updated in ages. This made us rather nervous as to
whether it still works correctly. Moreover, LESS seems to have been gradually abandoned for Sass/SCSS or completely
ditched for modern CSS which allows variable replacements. Moreover, we have seen that an increasing number of
developers introduce a step of precompilation and / or minification of their CSS in their build and release workflow.
Finally, compiling LESS on the fly was _slow_ and had several challenges regarding makign the compiled file available
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
  `akeeba-renderer-fef`. It extends the `Joomla` renderer but it will NOT output the `akeeba-renderer-joomla` wrapper
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