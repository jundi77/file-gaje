<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Automatically generated strings for Moodle installer
 *
 * Do not edit this file manually! It contains just a subset of strings
 * needed during the very first steps of installation. This file was
 * generated automatically by export-installer.php (which is part of AMOS
 * {@link http://docs.moodle.org/dev/Languages/AMOS}) using the
 * list of strings defined in /install/stringnames.txt.
 *
 * @package   installer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['admindirname'] = 'Admin directory';
$string['availablelangs'] = 'Available language packs';
$string['chooselanguagehead'] = 'Choose a language';
$string['chooselanguagesub'] = 'Please choose a language for the installation. This language will also be used as the default language for the site, though it may be changed later.';
$string['clialreadyconfigured'] = 'The configuration file config.php already exists. Please use admin/cli/install_database.php to install Moodle for this site.';
$string['clialreadyinstalled'] = 'The configuration file config.php already exists. Please use admin/cli/install_database.php to upgrade Moodle for this site.';
$string['cliinstallheader'] = 'Moodle {$a} command line installation program';
$string['databasehost'] = 'Database host';
$string['databasename'] = 'Database name';
$string['databasetypehead'] = 'Choose database driver';
$string['dataroot'] = 'Data directory';
$string['datarootpermission'] = 'Data directories permission';
$string['dbprefix'] = 'Tables prefix';
$string['dirroot'] = 'Moodle directory';
$string['environmenthead'] = 'Checking your environment ...';
$string['environmentsub2'] = 'Each Moodle release has some minimum PHP version requirement and a number of mandatory PHP extensions.
Full environment check is done before each install and upgrade. Please contact server administrator if you do not know how to install new version or enable PHP extensions.';
$string['errorsinenvironment'] = 'Environment check failed!';
$string['installation'] = 'Installation';
$string['langdownloaderror'] = 'Unfortunately the language "{$a}" could not be downloaded. The installation process will continue in English.';
$string['memorylimithelp'] = '<p>The PHP memory limit for your server is currently set to {$a}.</p>

<p>This may cause Moodle to have memory problems later on, especially
   if you have a lot of modules enabled and/or a lot of users.</p>

<p>We recommend that you configure PHP with a higher limit if possible, like 40M.
   There are several ways of doing this that you can try:</p>
<ol>
<li>If you are able to, recompile PHP with <i>--enable-memory-limit</i>.
    This will allow Moodle to set the memory limit itself.</li>
<li>If you have access to your php.ini file, you can change the <b>memory_limit</b>
    setting in there to something like 40M.  If you don\'t have access you might
    be able to ask your administrator to do this for you.</li>
<li>On some PHP servers you can create a .htaccess file in the Moodle directory
    containing this line:
    <blockquote><div>php_value memory_limit 40M</div></blockquote>
    <p>However, on some servers this will prevent <b>all</b> PHP pages from working
    (you will see errors when you look at pages) so you\'ll have to remove the .htaccess file.</p></li>
</ol>';
$string['paths'] = 'Paths';
$string['pathserrcreatedataroot'] = 'Data directory ({$a->dataroot}) cannot be created by the installer.';
$string['pathshead'] = 'Confirm paths';
$string['pathsrodataroot'] = 'Dataroot directory is not writable.';
$string['pathsroparentdataroot'] = 'Parent directory ({$a->parent}) is not writeable. Data directory ({$a->dataroot}) cannot be created by the installer.';
$string['pathssubadmindir'] = 'A very few webhosts use /admin as a special URL for you to access a
control panel or something.  Unfortunately this conflicts with the standard location for the Moodle admin pages.  You can fix this by
renaming the admin directory in your installation, and putting that  new name here.  For example: <em>moodleadmin</em>. This will fix admin links in Moodle.';
$string['pathssubdataroot'] = '<p>A directory where Moodle will store all file content uploaded by users.</p>
<p>This directory should be both readable and writeable by the web server user (usually \'www-data\', \'nobody\', or \'apache\').</p>
<p>It must not be directly accessible over the web.</p>
<p>If the directory does not currently exist, the installation process will attempt to create it.</p>';
$string['pathssubdirroot'] = '<p>The full path to the directory containing the Moodle code.</p>';
$string['pathssubwwwroot'] = '<p>The full address where Moodle will be accessed i.e. the address that users will enter into the address bar of their browser to access Moodle.</p>
<p>It is not possible to access Moodle using multiple addresses. If your site is accessible via multiple addresses then choose the easiest one and set up a permanent redirect for each of the other addresses.</p>
<p>If your site is accessible both from the Internet, and from an internal network (sometimes called an Intranet), then use the public address here.</p>
<p>If the current address is not correct, please change the URL in your browser\'s address bar and restart the installation.</p>';
$string['pathsunsecuredataroot'] = 'Dataroot location is not secure';
$string['pathswrongadmindir'] = 'Admin directory does not exist';
$string['phpextension'] = '{$a} PHP extension';
$string['phpversion'] = 'PHP version';
$string['phpversionhelp'] = '<p>Moodle requires a PHP version of at least 5.6.5 or 7.1 (7.0.x has some engine limitations).</p>
<p>You are currently running version {$a}.</p>
<p>You must upgrade PHP or move to a host with a newer version of PHP.</p>';
$string['welcomep10'] = '{$a->installername} ({$a->installerversion})';
$string['welcomep20'] = 'You are seeing this page because you have successfully installed and
    launched the <strong>{$a->packname} {$a->packversion}</strong> package in your computer. Congratulations!';
$string['welcomep30'] = 'This release of the <strong>{$a->installername}</strong> includes the applications
    to create an environment in which <strong>Moodle</strong> will operate, namely:';
$string['welcomep40'] = 'The package also includes <strong>Moodle {$a->moodlerelease} ({$a->moodleversion})</strong>.';
$string['welcomep50'] = 'The use of all the applications in this package is governed by their respective licences. The complete <strong>{$a->installername}</strong> package is <a href="https://www.opensource.org/docs/definition_plain.html">open source</a> and is distributed under the <a href="https://www.gnu.org/copyleft/gpl.html">GPL</a> license.';
$string['welcomep60'] = 'The following pages will lead you through some easy to follow steps to
    configure and set up <strong>Moodle</strong> on your computer. You may accept the default
    settings or, optionally, amend them to suit your own needs.';
$string['welcomep70'] = 'Click the "Next" button below to continue with the set up of <strong>Moodle</strong>.';
$string['wwwroot'] = 'Web address';
