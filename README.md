# Framework on Framework [![Build Status](https://travis-ci.org/akeeba/fof.png)](https://travis-ci.org/akeeba/fof)

## WORK IN PROGRESS!

Hi! This is the development branch of FOF 4, the upcoming version of our RAD framework which is being rewritten to cater
for Joomla! 4 and newer versions of PHP. It's currently in active development. Things may be broken from time to time.
Please only use it to explore the feasibility of porting your extensions from FOF 3 to FOF 4. Take a look at the 
MIGRATION_3_TO_4.md document for backwards incompatible changes and information about migrating your component to FOF 4. 

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

## FOF 2.x, 3.x, 4.x and Joomla 3

Joomla 3 includes a very, **VERY** old version of FOF we have stopped developing in 2015 and declared End of Life in 
2016. Please don't use that! That's what FOF looked liked a decade or so ago. This repository has a far better, much 
newer version. And, yes, both versions can run side by side.

This warrants an explanation of the extensions you see in the Extensions, Manage page with FOF in their name:

* **FOF** (type: Library, version 2.4.3). This is the ancient version of FOF included in Joomla. It's installed in 
  `libraries/fof`. It cannot and MUST NOT be removed. If you delete it your site will break – this is still used by some
  core Joomla components, including Two Factor Authentication. 
* **F0F** (type: Library). Note that this is F-zero-F. That's an old version of FOF 2.x, renamed so it can run next to
  Joomla's even more outdated version. [It was a rather complicated affair](https://www.akeebabackup.com/news/1558-info-about-fof-and-f0f.html).
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

**Important!** We only work towards full compatibility with _stable_ versions of Joomla. Using FOF with pre-release
versions of Joomla (alpha, beta, RC) may result in issues. If you have identified the issue to be coming from FOF and
not your extensions feel free to file a Pull Request or an issue in this repository. Please be as specific and detailed
as possible. 

## Using FOF for your extensions

If you want to use FOF to build your extensions and include it with them please read our Wiki for more information. Be a
good fellow developer and take care NOT to overwrite newer versions of FOF or use a modified version under the FOF40
namespace (which would cause all extensions using FOF proper to fail loading). 

Developers caught violating this clause and causing problems with their fellow developers and our clients will face consequences. At first, they will be given a warning. Failure to comply will result in public shaming and active measures in FOF-based software and FOF itself including but not limited to blacklisting your namespace prefixes and forced removal of your extensions when FOF proper is installed on a site.

If you want to use a custom version of FOF or pin your extensions to an older version of FOF you can always create a custom distribution if you abide by the following simple rules:

* Change the namespace prefix from `FOF40\` to something else, e.g. `AcmeCorp\FOF40\` (where `AcmeCorp\` is the usual namespace prefix used in your software).
* Change the installation path from `JPATH_LIBRARIES . '/fof40'` to a custom location e.g. `JPATH_LIBRARIES . '/acmecorp_fof40'` or `JPATH_ADMINISTRATOR . '/components/com_acme/fof40'`.
* If you install it as a package you MUST change its name and publisher. You CAN NOT use the strings "Akeeba" or "FOF" in your name, description or publisher names.
* Do NOT use the `#__akeeba_common` table to handle version dependencies (you need to modify `script.fof.php`).
* Notify your clients that your software is using a _modified_ version of the Akeeba FOF framework and that your modified version is neither published nor supported by Akeeba Ltd.
* Per the software license, you cannot remove our copyright from the files but you MUST add your own copyright, stating that the file is derivative work.
* Per the software license, your customised copy of FOF must be published under the same license: GNU General Public License version 3 or, at your option, any later version.

These changes will take you all of half an hour and ensure that you are not going to be interfering with any other developer's software.