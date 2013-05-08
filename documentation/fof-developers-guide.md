Introduction
============

What is FOF?
------------

FOF (Framework on Framework) is a rapid application development
framework for Joomla!. Unlike other frameworks it is not standalone. It
extends the Joomla! Platform instead of replacing it, featuring its own
forked and extended version of the MVC classes, keeping a strong
semblance to the existing Joomla! MVC API. This means that you don't
have to relearn writing Joomla! extensions. Instead, you can start being
productive from the first day you're using it. Our goal is to always
support the officially supported LTS versions of Joomla! and not break
backwards compatibility without a clear deprecation and migration path.

FOF is currently used by Akeeba products such as Akeeba Backup, Admin
Tools, Akeeba Subscriptions, Akeeba Ticket System and Akeeba DocImport
components.

Free Software means collaboration
---------------------------------

The reason of existence of FOSS (Free and Open Source Software) is
collaboration between developers. FOF is no exception; it exists because
and for the community of Joomla! developers. It is provided free of
charge and with all of the freedoms of the GPL for you to benefit. And
in true Free Software spirit, the community aspect is very strong.
Participating is easy and fun.

If you want to discuss FOF there is [a Google Groups mailing
list](https://groups.google.com/forum/?hl=en&fromgroups#!forum/frameworkonframework).
This is a peer discussion group where developers working with FOF can
freely discuss.

If you have a feature proposal or have found a bug, but you're not sure
how to code it yourself, please report it on the list.

If you have a patch feel free to fork [this project on
GitHub](https://github.com/akeeba/fof) (you only need a free account to
do that) and send a pull request. Please remember to describe what you
intended to achieve to help me review your code faster.

If you've found a cool hack (in the benign sense of the word, not the
malicious one...), something missing from the documentation or have a
tip which can help other developers feel free to edit the Wiki. We're
all grown-ups and professionals, I believe there is no need of policing
the wiki edits. If you're unsure about whether a wiki edit is
appropriate, please ask on the list.

Preface to this documentation
-----------------------------

FOF is a rapid application development framework for the Joomla! CMS.
Instead of trying to completely replace Joomla!’s own API (formerly
known as the Joomla! Platform) it builds upon it and extends it both in
scope and features. In the end of the day it enables agony-free
extension development for the Joomla! CMS.

In order to exploit the time-saving capabilities of the FOF framework to
the maximum you need to understand how it's organized, the conventions
used and how its different pieces work together. This documentation
attempts to provide you with this knowledge.

As with every piece of documentation we had to answer two big questions:
where do we start and how do we structure the content. The first
question was easy to answer. Having given the presentation of the FOF
framework countless times we have developed an intuitive grasp of how to
start presenting it: from the abstract to the concrete.

The second question was harder to answer. Do we write a dry reference to
the framework or more of a story-telling documentation which builds up
its reader’s knowledge? Since we are all developers we can read the code
(and DocBlocks), meaning that the first option is redundant. Therefore
we decided to go for the second option.

As a result this documentation does not attempt to be a complete
reference, a development gospel, the one and only source of information
on FOF. On the contrary, this documentation aims to be the beginning of
your journey, much like a travel guide. What matters the most is the
journey itself, writing your own extensions based on FOF. As you go on
writing software you will be full of questions. Most of them you’ll
answer yourself. Some of them will be already answered in the wiki. A
few of them you’ll have to ask on the mailing list. In the end of the
day you will be richer in knowledge. If you do dig up a golden nugget of
knowledge, please do consider writing a wiki page. This way we’ll all be
richer and enjoy our coding trip even more.

Have fun and code on!

Key features
------------

Some of the key features / highlights of FOF:

**Convention over configuration, Rails style**. Instead of having to
painstakingly code every single bit of your component, it's sufficient
to use our naming conventions, inspired by Ruby on Rails conventions.
For example, if you have com\_example, the foobar view will read from
the \#\_\_example\_foobars table which has a unique key named
example\_foobar\_id. The default implementation of controllers, models,
tables and views will also cater for the majority of use cases,
minimising the code you'll need to write.

**HMVC today, without relearning component development**. There's a lot
of talk about the need to re-engineer the MVC classes in Joomla! to
support HMVC. What if we could give you HMVC support using the existing
MVC classes, today, without having to relearn how to write components?
Yes, it's possible with FOF. It has been possible since September 2011,
actually. And for those who mumble their words and spread FUD, yes, it
IS HMVC by any definition. The very existence of the FOFDispatcher class
proves the point.

**Easy reuse of view template files without ugly include()**. More often
than not you want to reuse view template files across views. The
"traditional" way was by using include() or require() statements. This
meant, however, that template overrides ceased working. Not any more!
Using FOFView's loadAnyTemplate() you can load any view template file
from the front- or back-end of your component or any other component,
automatically respecting template overrides.

**Automatic language loading and easy overrides**. Are you sick and
tired of having to load your component's language files manually? Do you
end up with a lot of untranslated strings when your translators don't
catch up with your new features? Yes, that sucks. It's easy to overcome.
FOF will automatically handle language loading for you.

**Media files override (works like template overrides)**. So far you
knew that you can override Joomla!'s view template files using template
overrides. But what about CSS or Javascript files? This usually required
the users to "hack core", i.e. modify your views' PHP files, ending up
in an unmaintainable, non-upgradeable and potentially insecure solution.
Not any more! Using FOF's FOFTemplateUtils::addCSS and
FOFTemplateUtils::addJS you can load your CSS and JS files directly from
the view template file. Even better? You can use the equivalent of
template overrides to let your users and template designers override
them with their own implementations. They just have to create the
directory templates/your\_template/media/com\_example to override the
files normally found in media/com\_example. So easy!

**Automatic JSON and CSV views with no extra code (also useful for web
services)**. Why struggle to provide a remote API for your component?
FOF makes the data of each view accessible as JSON feeds opening a new
world of possibilities for Joomla! components (reuse data in mobile
apps, Metro-style Windows 8 tiles, browser extensions, mash-up web
applications, ...). The automatic CSV views work on the same principle
but output data in CSV format, suitable for painlessly data importing to
spreadsheets for further processing. Oh, did we mention that we already
have an almost RESTful web services implementation?

**No code view templates**. Don't you hate it that you have to write a
different view template (in PHP and HTML) for each Joomla! version and,
worse, each template out there? Don't you hate it having to teach
non-developers how to not screw up your component with every update you
publish? We feel your pain. That's why FOF supports the use of XML files
as view templates, rendering them automatically to HTML. Not just forms;
everything, including browse (multiple items) and single item views.
Even better, you get to choose if you want to use traditional PHP/HTML
view templates, XML view templates or a combination of both, even in the
same view!

**No code routing, ACL and overall application configuration**. Since
FOF 2.1 you can define your application's routing, access control
integration and overall configuration without routing any code, just by
using a simple to understand XML file. It's now easier than ever to have
Joomla! extensions with truly minimal (or no) PHP code.

Getting started with FOF
------------------------

### Download and install FOF

You can download FOF as an installable Joomla! library package from [our
repository](https://www.akeebabackup.com/download/fof.html). You can
install it like any other extension under Joomla! 2.x and later.

**Using the latest development version**

You can clone a read-only copy of the Git repository of FOF in your
local machine. Make sure you symlink or copy the fof directory to your
dev site's libraries/fof directory. Alternatively, we publish dev
releases in the [dev release
repository](https://www.akeebabackup.com/download/fof-dev.html). These
are installable packages but please note that they may be out of date
compared to the Git HEAD. Dev releases are not published automatically
and may be several revisions behind the current Git master branch.

### Using it in your extension

The recommended method for including FOF in your component, module or
plugin is using this short code snippet right after your
defined('\_JEXEC') or die() statement (Joomla! 2.x and later):

    if (!defined('FOF_INCLUDED')) {
        include_once JPATH_LIBRARIES . '/fof/include.php';

    }

Alternatively, you can use the one-liner:

    require_once JPATH_LIBRARIES . '/fof/include.php';

From that point onwards you can use FOF in your extension.

### Installing FOF with your component

Unfortunately, Joomla! doesn't allow us to version checking before
installing a library package. This means that it's your responsibility
to check that there is no newer version of FOF installed in the user's
site before attempting to install FOF with your extension. In the
following paragraphs we are going to demonstrate one way to do that for
Joomla! 2.x / 3.x component packages.

Include a directory called fof in your installation package. The
directory should contain the files of the installation package's fof
directory. Then, in your script.mycomponent.php file add the following
method:

    /**

    -   Check if FoF is already installed and install if not \*
    -   @param object \$parent class calling this method \*
    -   @return array Array with performed actions summary \*/ private
        function \_installFOF($parent) {     $src =
        \$parent-\>getParent()-\>getPath('source');

        // Load dependencies JLoader::import('joomla.filesystem.file');
        JLoader::import('joomla.utilities.date'); $source = $src . '/fof';

        if (!defined('JPATH\_LIBRARIES')) {
        $target = JPATH_ROOT . '/libraries/fof'; } else {     $target =
        JPATH\_LIBRARIES . '/fof'; } \$haveToInstallFOF = false;

        if (!is\_dir($target)) {     $haveToInstallFOF = true; } else {
        \$fofVersion = array();

            if (file_exists($target . '/version.txt'))
            {
                $rawData = JFile::read($target . '/version.txt');
                $info    = explode("\n", $rawData);
                $fofVersion['installed'] = array(
                    'version'   => trim($info[0]),
                    'date'      => new JDate(trim($info[1]))
                );
            }
            else
            {
                $fofVersion['installed'] = array(
                    'version'   => '0.0',
                    'date'      => new JDate('2011-01-01')
                );
            }

            $rawData = JFile::read($source . '/version.txt');
            $info    = explode("\n", $rawData);
            $fofVersion['package'] = array(
                'version'   => trim($info[0]),
                'date'      => new JDate(trim($info[1]))
            );

            $haveToInstallFOF = $fofVersion['package']['date']->toUNIX() > $fofVersion['installed']['date']->toUNIX();

        }

        \$installedFOF = false;

        if ($haveToInstallFOF) {     $versionSource = 'package';
        $installer = new JInstaller;     $installedFOF =
        $installer->install($source); } else { \$versionSource =
        'installed'; }

        if (!isset($fofVersion)) {     $fofVersion = array();

            if (file_exists($target . '/version.txt'))
            {
                $rawData = JFile::read($target . '/version.txt');
                $info    = explode("\n", $rawData);
                $fofVersion['installed'] = array(
                    'version'   => trim($info[0]),
                    'date'      => new JDate(trim($info[1]))
                );
            }
            else
            {
                $fofVersion['installed'] = array(
                    'version'   => '0.0',
                    'date'      => new JDate('2011-01-01')
                );
            }

            $rawData = JFile::read($source . '/version.txt');
            $info    = explode("\n", $rawData);
            $fofVersion['package'] = array(
                'version'   => trim($info[0]),
                'date'      => new JDate(trim($info[1]))
            );
            $versionSource = 'installed';

        }

        if (!($fofVersion[$versionSource]['date'] instanceof JDate)) {
        $fofVersion[$versionSource]['date'] = new JDate; }

        return array( 'required' =\>
        $haveToInstallFOF,     'installed' => $installedFOF, 'version'
        =\> $fofVersion[$versionSource]['version'], 'date' =\>
        $fofVersion[$versionSource]['date']-\>format('Y-m-d'), ); }
        

You need to call it from inside your postflight() method. For example:

    /**

    -   Method to run after an install/update/uninstall method \*
    -   @param object \$type type of change (install, update or
        discover\_install)
    -   @param object \$parent class calling this method \*
    -   @return void \*/ function postflight($type, $parent) {
        $fofInstallationStatus = $this-\>\_installFOF(\$parent); }
        

> **Important**
>
> Due to a bug/feature in Joomla! 1.6 and later, your component's
> manifest file must start with a letter before L, otherwise Joomla!
> will assume that lib\_fof.xml is your extension's XML manifest and
> install FOF instead of your extension. We suggest using the
> com\_yourComponentName.xml convention, e.g. com\_todo.xml. There is a
> patch pending in Joomla!'s tracker for this issue, hopefully it will
> be accepted sometime soon.

### Sample applications

FOF comes with two sample applications which are used to demonstrate its
features, [To-Do](https://github.com/akeeba/todo-fof-example) and
[Contact Us](https://github.com/akeeba/contactus). These were conceived
and developed in different points of FOF's development. As a result they
are always in a state of flux and will definitely not expose all of
FOF's features.

Another good way to learn some FOF tricks is by reading the source code
of existing FOF-based components. Just remember that we are all real
world developers and sometimes our code is anything but kosher ;)

The big picture
===============

Overview of a component
-----------------------

FOF is an MVC framework in heart and soul. It tries to stick as close as
possible to the MVC conventions put forward by the Joomla! CMS since
Joomla! 1.5, cutting down on unnecessary code duplication. The main
premise is that your code will be DRY – not as the opposite of “wet”,
but as in Don’t Repeat Yourself. Simply put: if you ever find yourself
trying to copy code from a base class and paste it into a specialized
class, you are doing it wrong.

In order to achieve this code isolation, FOF uses a very flexible
structure for your components. A component's structure looks like this:

![](images/component-structure.png)

The Dispatcher is the entry point of your component. Some people would
call this a "front Controller" and this is actually what it is. It's
different than what we typically call a Controller in the sense that the
Dispatcher is the only part of your component which is supposed to
interface the underlying application (e.g. the Joomla! CMS) and gets to
decide which Controller and task to execute based on the input data
(usually this is the request data). No matter if you call it an entry
point, front controller, dispatcher or Clint Eastwood its job is to
figure out what needs to run and run it. We simply chose the name
"Dispatcher" because, like all conventions, we had to call it something.
So, basically, the Dispatcher will take a look at the input data, figure
out which Controller and task to run, create an instance of it, push it
the data and tell it to run the task. The Controller is expected to
return the rendered data or a redirection which the Dispatcher will
dully pass back to its caller.

Oh, wait, what is a Controller anyway?! Right below the Dispatcher you
will see a bunch of stuff grouped as a "triad". The "triad" is commonly
called "view" (with a lowercase v). Each triad does something different
in your component. For example, one triad may allow you to handle
clients, another triad allow you to handle orders and so on. Your
component can have one or more triads. A triad usually contains a
Controller, a Model and a View, hence the name ("triad" literally means
"a bunch of three things"). The only mandatory member is the Controller.
A triad may be reusing the Model and View from another triad – which is
another reason why DRY code rocks– or it may even be view-less. So, a
triad may actually be a bunch of one, two or three things, as long as it
includes a Controller. Just to stop you from being confused or thinking
about oriental organised crime and generally make your life easier we
decided to call these "views" (with a lowercase v) instead of "triads".
See? Now it is so much better.

FOF views follow the "fat Model - thin Controller" paradigm. This means
that the Controller is a generally minimalist piece of code and the
Model is what gets to do all the work. Knowing this very important bit
of information, let's take a look at the innards of a view.

In the very beginning we have the Controller. The Controller has one or
more tasks. Each task is an action of your component, like showing a
list of records, showing a single record, publish a record, delete a
record and so on. With a small difference. The Controller's tasks do not
perform the actual work. They simply spawn an instance of the Model and
push it the necessary data it needs. This is called "setting the state"
of the Model. In most cases the Controller will also call a Model's
method which does something. It's extremely important to note that the
Controller will work with *any* Model that implements that method and
that the Model is oblivious to the Controller. Then the Controller will
create an instance of the View class, pass it the instance of the Model
and tell it to go render itself. It will take the output of the View and
pass it back to the Dispatcher.

Which brings us to the Model. The Model is the workhorse of the view. It
implements the business logic. All FOF Models are passive Models which
means that they are oblivious to the presence of the Controller and
View. Actually, they are completely oblivious to the fact that they are
part of a triad. That's right, Models can be used standalone, outside
the context of the view or component they are designed to live in. The
Model's methods will act upon the state variables which have already
been set (typically, by the Controller) and will only modify the state
variables or return the output directly. Models must never have to deal
with input data directly or talk to specific Controllers and/or Views.
Models are decoupled from everything, that's where their power lies.

Just a small interlude here. Right below the Model we see a small thing
called a "Table". This is a strange beast. It's one part data adapter,
one part model and one part controller, but nothing quite like any of
this. The Table is used to create an object representing a single
record. It is typically used to check the validity of a record before
saving it to the database and post-process a record when reading it from
the database (e.g. unserialise a field which contains serialised or JSON
data).

The final piece of our view is the View class itself. It will ask the
Model for the raw data and transform it into a suitable representation.
Typically this means getting the raw records from the Model and create
the HTML output, but that's not the only use for a View. A View could
just as well render the data as a JSON stream, a CSV file, or even
produce a graphic, audio or video file. It's what transforms the raw
data into something useful, i.e. it's your presentation layer. Most
often it will do so by loading view templates which are .php files which
transform raw data to a suitable representation. If you are using the
XML forms feature of FOF, the View will ask the Model to return the form
definition and ask FOF's renderer to render this to HTML instead. Even
though the actual rendering is delegated to the Renderer (not depicted
above), it's still the View that's responsible for the final leg of the
rendered data: passing it back to its caller. Yes, the View will
actually neither output its data directly to the browser, nor talk to
the underlying application. It returns the raw data back to its caller,
which is almost always the Controller. Again, we have to stress that the
View is oblivious to both the Controller and the Model being used. A
properly written View is fully decoupled from everything else and will
work with any data provider object implementing the same interface as a
Model object and a caller which is supposed to capture its output for
further consumption.

> **Important**
>
> All classes comprising a view are fully decoupled. None is aware of
> the internal workings of another object in the same or a different
> view. This allows you to exchange objects at will, promoting code
> reuse. Even though it sounds like a lot of work it's actually less
> work and pays dividends the more features you get to add to your
> components.

There's another bit mentioned below the triad, the Toolbar. The Toolbar
is something which conceptually belongs to the component and only has to
do with views being rendered as HTML. It's what renders the title in the
back-end, the actions toolbar in the front- or back-end and the
navigation links / menu in the back-end. In case you missed the subtle
reference: FOFToolbar allows you to render an actions toolbar even in
the front-end of your component, something that's not possible with
plain old Joomla!. You will simply need to add some CSS to do it.

Finally we mention the Helpers. The Helpers are pure static classes
implementing every bit of functionality that's neither OOP, nor can it
be categorised in any other object already mentioned. For example,
methods to render drop-down selection lists. In so many words, "Helper"
is a polite way of saying "non-OOP cruft we'd rather not talk about".
Keep your Helpers to a minimum as they're a royal pain in the rear to
test.

Please do keep in mind that this is just a generic overview of how an
FOF-based component works. The real power comes from the fact that you
don't need to know the internal workings of FOF to use it, you don't
need to copy and paste code from it (woe is the developer who does that)
and quite possibly you don't even need to write any code. At all. It's
all discussed later on.

Diving into the FOF classes
---------------------------

As it was casually mentioned in the previous section, the real power of
FOF comes from the fact that it doesn't require you to write much or any
code. All you have to do is to follow some conventions and understand
what are the default actions carried out by FOF. This sections attempts
to explain it all, one class at a time.

### FOFDispatcher

**Instantiating and using the Dispatcher**

Following Joomla conventions, at the root of both the front- and
back-end of an extension is the entry point for that end of the
extension, named following the extension’s name.

For example, for com\_foobar, we would create foobar.php in the root of
each end. These files are not dispatchers, but they are the points in
the extension that include the FOF library, and create an instance of
FOF's dispatcher.

FOF is included with:

    // Load FOF

    include\_once JPATH\_LIBRARIES . '/fof/include.php'; if
    (!defined('FOF\_INCLUDED')) { JError::raiseError('500', 'FOF is not
    installed'); }

Creating an instance of FOF's dispatcher for com\_foobar would be done
with:

    FOFDispatcher::getTmpInstance('com_foobar')->dispatch();

**What FOFDispatcher does**

**Convention over Configuration**

**Specialising FOFDispatcher**

**FOFDispatcher methods you should know about**

#### Calling a Dispatcher from anywhere (a.k.a. HMVC)

#### Web services

### FOFController

**What FOFController does**

**Convention over Configuration**

**Specialising FOFController**

**FOFController methods you should know about**

### FOFModel

**What FOFModel does**

**Convention over Configuration**

**Specialising FOFModel**

**FOFModel methods you should know about**

### FOFTable

**What FOFTable does**

**Convention over Configuration**

**Specialising FOFTable**

**FOFTable methods you should know about**

### FOFView

**What FOFView does**

**Convention over Configuration**

**Specialising FOFView**

**FOFView methods you should know about**

#### FOFView subclasses

#### View templates

**Naming of view template files**

**Including media files**

**Reusing view templates**

#### Template overrides

**Overriding view templates**

**Overriding media files**

### FOFToolbar

**What FOFToolbar does**

**Convention over Configuration**

**Specialising FOFToolbar**

**FOFToolbar methods you should know about**

XML Forms
=========

The different form types
------------------------

### Browse forms

### Read forms

### Edit forms

Header field types reference
----------------------------

### accesslevel

### field

### fieldsearchable

### fieldselectable

### fieldsql

### filtersearchable

### filterselectable

### filtersql

### language

### ordering

### published

### rowselect

Form field types reference
--------------------------

### accesslevel

### button

### cachehandler

### calendar

### captcha

### checkbox

### editor

### email

### groupedlist

### hidden

### image

### imagelist

### integer

### language

### list

### media

### model

### ordering

### password

### plugins

### published

### radio

### selectrow

### sessionhandler

### spacer

### sql

### tel

### text

### textarea

### timezone

### url

### user

Configuring the MVC and associated classes
==========================================

All MVC and associated classes in FOF (Dispatcher, Controller, Model,
View, Table, Toolbar) come with a default behavior, for example where to
look for model files, how to handle request data and so on. While this
is fine most of the times –as long as you follow FOF’s conventions– this
is not always desirable.

For example, if you are building a CCK (something like K2) you may want
to look for view templates in a non-standard directory in order to
support alternative “themes”. Or, maybe, if you're building a contact
component you only want to expose the add view to your front-end users
so that they can file a contact request but not view other people's
contact requests. You get the idea.

The traditional approach to development prescribes overriding classes,
even to the extent of copying and pasting code. If you've ever attended
one of my presentations you've probably figured that I consider copying
and pasting code a mortal sin. You may have also figured that, like all
developers, I am lazy and dislike writing lots of code. Naturally, FOF
being a RAD tool it provides an elegant solution to this problem. The
`$config` array and its sibling, the `fof.xml` file.

What is the `$config` array?
----------------------------

You may have observed that FOF’s MVC classes can be passed an optional
array parameter `$config`. This is a hash array with configuration
options. It is being passed from the Dispatcher to the Controller and
from there to the Model, View and Table classes. Essentially, this is
your view (MVC triad) configuration. Setting its options allows you to
modify FOF’s internal workings without writing code.

The various possible settings are explained in “The configuration
settings” section below.

What’s the deal with the `fof.xml` file?
----------------------------------------

The `$config` array is a great idea but has a major drawback: you have
to create one or several `.php` files with specialized classes to use
it. Remember the FOF promise about not having to write code unless
absolutely necessary? Yep, this doesn’t stick very well with that
promise. So the `fof.xml` file was born in FOF 2.1.

The `fof.xml` file is a simple XML file placed inside your component's
**back-end** directory, e.g. `administrator/com_example/fof.xml`. It
contains configuration overrides for the front-end, back-end and CLI
parts of your FOF component.

### A sample `fof.xml` file

    <?xml version="1.0" encoding="UTF-8"?>

    \
