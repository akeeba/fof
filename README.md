# Framework on Framework [![Build Status](https://travis-ci.org/akeeba/fof.png)](https://travis-ci.org/akeeba/fof)

Hi! This is the development branch of FOF 4, which was rewritten to provide the minimum required compatibility with Joomla 4, with the express intention of allowing your users to migrate their sites to Joomla 4 and native Joomla 4 versions of your components.

[Documentation](https://github.com/akeeba/fof/wiki)

## DEPRECATED

FOF as a whole has been deprecated and will become End of Life on August 17th, 2023 when Joomla 3 itself becomes End of Life.

We strongly recommend that you do **NOT** use FOF for any new software development.

If you are starting a new software project, or want to upgrade an existing project, we recommend that you use the native Joomla MVC which has improved by leaps and bounds starting with Joomla 4.0. Even though there is no official documentation, I have written documentation for Joomla MVC myself. You can find it at https://www.dionysopoulos.me/book.html

## What is FOF? 

FOF stands for Framework on Framework. It is a rapid application development (RAD) framework for Joomla!.

Unlike other PHP frameworks you may have used, FOF is not standalone. It is designed to sit on top of the Joomla! API,
abstracting most of the differences between different Joomla releases so you can write better code, faster, focusing
only on what matters: your business logic.

FOF uses the MVC pattern as its fundamental building block, just like Joomla. Unlike Joomla 3, it extends the concept by
adding one Container per component (for the purists: it's really a Service Locator but let's not split hairs). Moreover,
it abstracts the main entry point of your component through a Dispatcher. Two further important differences are the
concept of automatically generated Events for most public methods in Controllers, Models and Views as well as the
support of Blade templates, in a form that's similar (but not identical) to the Blade template language used by Laravel.
When used right, FOF lets you separate your application into independent, logically organised layers of code which
promote separation of concerns and maintainability. FOF is not about code purism and doesn't care about it; it's about
empowering developers to write smaller, more robust code in a smaller amount of time than with traditional Joomla MVC.

FOF has been in continuous development since 2011. Many of the features and concepts it introduced, such as the service
locator and the data-driven Model, have already been ported into Joomla 4 itself — at least to an extent. Still, FOF 
itself is a major asset to developers as it offers abstraction of Joomla API changes and features which haven't and 
probably won't make it into Joomla itself.

All of the extensions written by Akeeba Ltd make use of FOF. That is to say, as long as we exist as a company you can be
fairly certain FOF will be maintained and developed further. 

## Requirements

FOF 4 requires Joomla 3.9 or later and PHP 7.2 or later. It will not work on older Joomla and PHP versions.

At a maximum, FOF 4 supports Joomla up to version 4.4 and PHP up to version 8.2. 

We will not support any newer versions of Joomla or PHP.

## FOF 2.x, 3.x, 4.x and Joomla 3

Joomla 3 includes a very, **VERY** old version of FOF we have stopped developing in 2015 and declared End of Life in 
2016. Please don't use that! That's what FOF looked liked a decade or so ago. This repository has a far better, much 
newer version. And, yes, both versions can run side by side.

This warrants an explanation of the extensions you see in the Extensions, Manage page with FOF in their name:

* **FOF** (type: Library, version 2.4.3). This is the ancient version of FOF included in Joomla. It's installed in 
  `libraries/fof`. It cannot and MUST NOT be removed. If you delete it your site will break – this is still used by some
  core Joomla components, including Two Factor Authentication. 
* **F0F** (type: Library). Note that this is F-zero-F. That's an old version of FOF 2.x, renamed so it can run next to
  Joomla's even more outdated version. [It was a rather complicated affair](https://www.akeeba.com/news/1558-info-about-fof-and-f0f.html).
  It's installed in `libraries/f0f` (f-zero-f). It should no longer be necessary but please do check first if you have
  any very old extension still using it.
* **file_fof30** (type: File). This is the current version of FOF 3. It's installed in  `libraries/fof30`. Do NOT remove
  it manually. It will be uninstalled automatically when the last extension using it is removed. 
* **FOF** (type: Library, version 3.x.y). This was the old package type of FOF 3. We switched to a file package in 2018
  to address Joomla bricking your sites if it failed to fully update FOF. While we try to remove the leftover entry from
  Joomla's Extensions, Manage page it's not always possible. If you see this entry please DO NOT try to remove it, you 
  will break your site.
* **User - FOF Token Management** (type: Plugin). This will be shipped with our extensions in 2020 to manage token
  authentication for JSON API calls in Joomla 3. Please do not remove if you're using any Akeeba-branded extension.
  Also, cool fact: this code has already been contributed to Joomla 4 for its brand new API application, meaning all
  developers can use it automatically, without having to use FOF for their extensions.

## FOF and Joomla 4

Joomla 4, thankfully, no longer includes the ancient FOF 2.x version Joomla 3 shipped with. You can use the latest 
version of FOF 4 with Joomla 4.

**Important!** The only reason to migrate a component to FOF 4 is to allow your clients to run the same version of your software on both Joola 3 and 4, allowing them to upgrade their sites. FOF 4 is not considered stable or generally usable on Joomla 4.1 and later versions. You should simultaneously offer a Joomla 4 or later only version of your software written using the core Joomla MVC. That version should be what you will be maintaining in the future.
