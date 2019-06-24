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

FOF uses the MVC pattern as its fundamental building block, just like Joomla. Unlike Joomla, it extends the concept by
adding one Container per component (for the purists: it's really a Service Locator but let's not split hairs). Moreover,
it abstracts the main entry point of your component through a Dispatcher. Two further important differences are the
concept of automatically generated Events for most public methods in Controllers, Models and Views as well as the
support of Blade templates, in a form that's similar (but not identical) to the Blade template language used by Laravel.
When used right, FOF lets you separate your application into independent, logically organised layers of code which
promote seperation of concerns and maintainability. FOF is not about code purism and doesn't care about it; it's about
empowering developers to write smaller, more robust code in a smaller amount of time than with traditional Joomla MVC.

FOF has been in continuous development since 2011. Many of the features and concepts it introduced, such as the service
locator and the data-driven Model, have already been ported into Joomla 4 itself. Still, FOF proper is a major asset to
developers as it offers abstraction of Joomla API changes and features which haven't and probably won't make it into
Joomla itself.

All of the extensions written by Akeeba Ltd make use of FOF. That is to say, as long as we exist as a company you can be
fairly certain FOF will be maintained and developed further. 

## Requirements

FOF 4 requires Joomla 3.8 or later and PHP 7.0 or later. It will not work on older Joomla and PHP versions.

## How to contribute

Thank you for your interest in improving FOF! No matter how small or how big your contribution is, it has the potential to make a great positive impact to hundreds of people. In this short how-to we are going to answer your questions on making this positive change.

### Which code should I be using?

Always use the `development` branch in our [GitHub repository](https://github.com/akeeba/fof).

Before starting to fix / improve something make sure it's not already taken care of in this branch. The core value of FOF is having developers use their time productively.

### Contributing to the documentation

[The main documentation of FOF](https://github.com/akeeba/fof/wiki) is located in the [github wiki](https://github.com/akeeba/fof/wiki) in the Git repository.

Make sure you read the "How do I submit a Pull Request" section to find out how to contribute your changes to the project.

### Code, docblocks and code style

First make sure that you have checked out the latest development branch. Changes happen on a daily basis, often many times a day. Checking out the latest development branch will ensure you're not trying to improve something already taken care of.

If you are going to touch docblocks and code style only please, please be careful not to make any accidental code changes. If you're dealing with docblocks it's easy for people with commit access to spot any issues; if they see a change in code lines they will know that they have to skip that when committing. Code style changes are actually much tougher as the committer has to go through the original and modified file line-by-line to make sure nothing got inadvertently changes. In order to help them please do small changes at any one time, ideally up to 100 lines of code or less. If you want to make many changes in many files break your work into smaller chunks. If unsure on what to do, create an issue first.

If you are working on a code change it's always a good idea to first discuss this on the list with Nicholas. He's the lead architect of FOF and he's the most qualified to tell you if your intended change is something that can be included and in which version. Usually changes are included right away, unless there are backwards compatibility issues.

Once you have made your changes please sure you read the "How do I submit a Pull Request" section to find out how to contribute your changes to the project.

#### Unit Testing

Unit Testing is an especially sensitive coding area. We'd recommend to first take a look at the [Unit Testing introductory presentation](http://prezi.com/qqv6dqkoqvl3/php-unit-testing-a-practical-approach/) by FOF contributor Davide Tampellini. It will get you up to speed with how testing works.

All tests are stored in the [Tests](https://github.com/akeeba/fof/tree/development/Tests) directory of the Git repository. As you saw in the presentation the folder structure mirrors that of the fof directory of the Git repository.

Once you have made your changes please sure you read the "How do I submit a Pull Request" section to find out how to contribute your changes to the project.

### How do I submit a Pull Request (PR)?

First things first, you need a GitHub user account. If you don't have one already... what are you waiting for? Just go to github.com, create your free account and log in.

You will need to fork our Git repository. You can do this very easily by going to https://github.com/akeeba/fof and click the Fork button towards the upper right hand corner of the page. This will fork the FOF repository under your GitHub account.

Make sure you clone the repository (the one under *your* account) in your computer. If you're not a heavy Git user don't worry, you can use the GitHub application on your Mac or Windows computer. If you're a Linux user you can just use the command line or your favourite Git client application.

Before making any changes you will need to create a new branch. In the GitHub for Mac application you need to first go into your repository and click the branch name at the bottom right corner of the window. Initially you need to click on "development" to ensure that you are seeing the development, not the master, branch. Then click on it again and type in the name of the new branch, then press Enter. You can now make all of your changes in this branch.

After you're done with your changes you need to publish your branch back to GitHub. Easy peasy! If you're using the GitHub application you need just two steps. First commit all your changed files, which adds them to your local branch. Then click on the Sync Branch button. When it stops spinning everything is uploaded to GitHub and you're ready to finally do your Pull Request!

Now go to github.com, into the forked FOF repository under your user account. Click on the branch dropdown and select your branch. On its left you'll see a green icon with the tooltip "Compare & Review". Click it. Just fill in the title and description –hopefully giving as much information as possible about what you did and why– and your PR is now created! If you need to explain something in greater detail just send a list message.

## Build instructions

### Prerequisites

In order to build the installation package of this library you need to have
the following tools:

* A command line environment. bash under Linux / Mac OS X works best. On Windows you will need to run most tools using an elevated privileges (administrator) command prompt.
* The PHP CLI binary in your path
* Command line Subversion and Git binaries(*)
* PEAR and Phing installed, with the Net_FTP and VersionControl_SVN PEAR packages installed
* libxml and libxslt tools if you intend to build the documentation PDF files

You will also need the following path structure on your system:
* `fof` This repository, a.k.a. MAIN directory
* `buildfiles` [Akeeba Build Tools](https://github.com/akeeba/buildfiles). The name of the directory is important. This is where the master Phing script, also used by FOF to build packages, is located in.

### Useful Phing tasks

All of the following commands are to be run from the MAIN/build directory. Best used with a bash or zsh shell, you 
should also be able to use PowerShell on Windows.

You are advised to NOT distribute the library installation packages you have built yourselves with your components. It
is best to only use the official library packages released by Akeeba Ltd.

1. Creating a dev release installation package

   This creates the installable ZIP packages of the component inside the
   MAIN/release directory. It also takes care of initializing the repository
   so you can symlink it to an existing Joomla! installation 

		phing git
		
    Please note that the generated ZIP file is written to the `release` directory inside the repository's root.


1. Symlink to a Joomla! installation

   This symlinks to fof folder to your site's libraries/fof40 folder, allowing you to test your changes on a Joomla!
   installation.

		phing relink -Dsite=/path/to/site
    
    Where /path/to/site is the full path to your site's root folder. It must be in the same drive (Windows), volume 
    (macOS) or mount point (Linux, BSD, etc). 