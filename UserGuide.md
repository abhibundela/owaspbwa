

# Introduction #

This is the user guide for the [Open Web Application Security Project (OWASP)](https://www.owasp.org/) Broken Web Applications Project.  This open source project produces a Virtual Machine (VM) running a variety of web applications with security vulnerabilities.

**NOTE - This document is a work in progress.  Please provide us [feedback](#Getting_Involved.md) on any errors or areas not covered**

# Applications Included #

This project includes open source applications of various types.  Below is is a list of the applications and versions currently on the VM.  A the version number ending in +SVN or +GIT indicates that the application is pulled directly to the VM from the application's public source code repository and the code running may be later than the version number indicated.

The lists below are current as of the 1.2 release.

## Training Applications ##
Applications designed for learning which guide the user to specific, intentional vulnerabilities.
  * [OWASP WebGoat](http://www.owasp.org/index.php/Category:OWASP_WebGoat_Project) version 5.4+SVN (Java)
  * [OWASP WebGoat.NET](https://www.owasp.org/index.php/Category:OWASP_WebGoat.NET) version 2012-07-05+GIT (ASP.NET)
  * [OWASP ESAPI Java SwingSet Interactive](https://www.owasp.org/index.php/ESAPI_Swingset) version 1.0.1+SVN (Java)
  * [OWASP Mutillidae II](http://www.irongeek.com/i.php?page=security/mutillidae-deliberately-vulnerable-php-owasp-top-10) version 2.6.24+SVN (PHP)
  * [OWASP RailsGoat](http://railsgoat.cktricky.com/) (Ruby on Rails)
  * [OWASP Bricks](http://sechow.com/bricks/) version 2.2+SVN (PHP)
  * [OWASP Security Shepherd](https://www.owasp.org/index.php/OWASP_Security_Shepherd) version 2.4+GIT (Java)
  * [Ghost](http://www.gh0s7.net/) (PHP)
  * [Magical Code Injection Rainbow](https://github.com/SpiderLabs/MCIR) version 2014-08-20+GIT (PHP)
  * [bWAPP](http://www.itsecgames.com/) version 1.9+GIT (PHP)
  * [Damn Vulnerable Web Application](http://www.dvwa.co.uk/) version 1.8+GIT (PHP)


## Realistic, Intentionally Vulnerable Applications ##
Applications that have a wide variety of intentional security vulnerabilities, but are designed to look and work like a real application.
  * [OWASP Vicnum](http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project) version 1.5 (PHP/Perl)
  * [OWASP 1-Liner](http://1-liner.org/) (Java/JavaScript)
  * [Google Gruyere](http://google-gruyere.appspot.com/) version 2010-07-15 (Python)
  * [Hackxor](http://hackxor.sourceforge.net/cgi-bin/index.pl) version 2011-04-06 (Java JSP)
  * [WackoPicko](https://github.com/adamdoupe/WackoPicko) version 2011-07-12+GIT (PHP)
  * [BodgeIt](http://code.google.com/p/bodgeit/) version 1.3+SVN (Java JSP)
  * [Cyclone Transfers](https://github.com/fridaygoldsmith/bwa_cyclone_transfers) (Ruby on Rails)
  * [Peruggia](http://peruggia.sourceforge.net/) version 1.2 (PHP)

## Old Versions of Real Applications ##
Open source applications with one or more known security issues.
  * [WordPress](http://wordpress.org/) 2.0.0 (PHP, released December 31, 2005) with plugins:
    * [myGallery](http://www.wildbits.de/mygallery/) version 1.2
    * [Spreadsheet for WordPress](http://timrohrer.com/blog/?page_id=71) version 0.6
  * [OrangeHRM](http://www.orangehrm.com/) version 2.4.2 (PHP, released May 7, 2009)
  * [GetBoo](http://sourceforge.net/projects/getboo/) version 1.04 (PHP, released April 7, 2008)
  * [gtd-php](http://www.gtd-php.com/) version 0.7 (PHP, released September 30, 2006)
  * [Yazd](http://www.forumsoftware.ca/) version 1.0 (Java, released February 20, 2002)
  * [WebCalendar](http://www.k5n.us/webcalendar.php) version 1.03 (PHP, released April 11, 2006)
  * [Gallery2](http://gallery.menalto.com/) version 2.1 (PHP, released March 23, 2006)
  * [TikiWiki](http://owaspbwa/tikiwiki) version 1.9.5 (PHP, released September 5, 2006)
  * [Joomla](http://www.joomla.org/) version 1.5.15 (PHP, released November 4, 2009)
  * [AWStats](http://awstats.sourceforge.net/) version 6.4 (build 1.814, Perl, released February 25,2005)

## Applications for Testing Tools ##
Applications designed for testing automated tools like web application security scanners.
  * [OWASP ZAP-WAVE](http://code.google.com/p/zaproxy/) version 0.2+SVN (Java JSP)
  * [WAVSEP](http://code.google.com/p/wavsep/) version 1.5 (Java JSP)
  * [WIVET](http://code.google.com/p/wivet/) version 3+SVN (PHP)

## Demonstration Pages / Small Applications ##
Little applications or pages with intentional vulnerabilities to demonstrate specific concepts.
  * [OWASP CSRFGuard](http://www.owasp.org/index.php/Category:OWASP_CSRFGuard_Project) Test Application version 2.2 (Java)
  * [Mandiant](http://www.mandiant.com/) Struts Forms (Java/Struts)
  * Simple ASP.NET Forms (ASP.NET/C#)
  * Simple Form with DOM Cross Site Scripting (HTML/JavaScript)

## OWASP Demonstration Applications ##
Demonstration of an OWASP application. Does not contain any intentional vulnerabilties.
  * [OWASP AppSensor Demo Application](http://www.owasp.org/index.php/Category:OWASP_AppSensor_Project) (Java)

# Getting Started #

## Download ##

The VM can be downloaded as a .zip file or as a much smaller [.7z 7-zip Archive](http://www.7-zip.org/).  **BOTH FILES CONTAIN THE EXACT SAME VM!**  I recommend that you download the .7z archive if possible to save bandwidth (and time). 7-zip IS [available for Mac, Linux, and other Operating Systems](http://www.7-zip.org/download.html).

Download from [http://sourceforge.net/projects/owaspbwa/files/](http://sourceforge.net/projects/owaspbwa/files/).

## Virtual Machine Format ##

The Open Web Application Security Project (OWASP) Broken Web Applications Project is distributed as a Virtual Machine in [VMware](http://www.vmware.com) format compatible with their no-cost [VMware Player](http://www.vmware.com/products/player/) and [VMware vSphere Hypervisor (ESXi)](http://www.vmware.com/products/vsphere-hypervisor/overview.html) products (along with their older and commercial products).

See [Converting Virtual Machine Format](#Converting_Virtual_Machine_Format.md) if you want to use the VM with other virtualization software.

The VM requires no installation.  Simply extract the files from the archive and then start the VM in your virtualization software.

## Viewing the VM's Starting Page ##

When the OWASP BWA VM is started, it will provide information on the console with the IP address of the VM and various ways to access and manage it.

It is highly recommended (though not required for most applications) that you add an entry into your 'hosts' file of your virtual host OS to resolve the hostname 'owaspbwa' with the IP address shown in the console on bootup.  The entry will look like below (replace 192.168.15.130 with the IP of your VM).  This document (and the vulnerability list at https://sourceforge.net/p/owaspbwa/tickets/?limit=999&sort=_severity+asc) assume that you have added this entry.

```
192.168.15.130	owaspbwa
```

Once the entry is added, you can view the main page for the VM at http://owaspbwa/.  That page includes links and lots of additional information about each of the applications.

The VM's home page will also be available via SSL at https://owaspbwa/. The server is using a self-signed certificate, however.

## Application Usernames and Passwords ##

In general, applications on OWASP BWA have been configured with an administrative account named 'admin' with a password of 'admin' and a "normal" user account named 'user' with a password of 'user'.  In some cases, however, this was not possible or additional accounts were required.

The full list of credentials for each application can be found on the main OWASP BWA web page (in the VM at http://owaspbwa/).  In order to view the credentials for each application, click the **green plus** next to the application name on that we page to show more information about the application.

# Management #

Once booted, the VM can be administered few a few different mechanisms. Note, these are not considered "in scope" for the vulnerabilities in the VM... they are just there to support management.  Administrative interfaces:
  * SSH
  * Samba shares
  * Console login
  * PHPMyAdmin (at `http://owaspbwa/phpmyadmin/`)

In all cases, use a username of "root" and a password of "owaspbwa".

# Usage #

## Updating Application Code ##

Software for many applications can be updated in place by editing the files (.php, .jsp, .aspx, etc). This can be done on the command line (via the console or SSH), but is more commonly done via the Samba shares at \\owaspbwa\.  Once files are edited, the resulting changes should take effect immediately.

Some applications require compilation and redeployment before changes take effect, however.  For those, the source code can be edited using Samba shares (or on the command line), then a script can be run in a terminal to rebuild and redeploy the application.  The following tables shows information for the applications that require compilation.

| Application | Source Location (Share) | Rebuild Script |
|:------------|:------------------------|:---------------|
| OWASP WebGoat (Java) | \\owaspbwa\owaspbwa\WebGoat-svn\ | owaspbwa-webgoat-rebuild.sh |
| OWASP WebGoat.NET | \\owaspbwa\owaspbwa\webgoat.net-git\ | Cannot currently be rebuilt on VM. Can rebuild on another machine via share. |
| OWASP ESAPI SwingSet Interactive | \\owaspbwa\owaspbwa\owasp-esapi-java-swingset-interactive-svn | owaspbwa-swingset-interactive-rebuild.sh |
| OWASP CSRFGuard Test Applications | \\owaspbwa\owaspbwa\owaspbwa-svn\misc-src\OWASP-CSRFGuard-TestApp-2.2-src and \\owaspbwa\owaspbwa\owaspbwa-svn\misc-src\OWASP-CSRFGuard-TestApp-2.2-Vulnerable-src | owaspbwa-csrfguard-test-apps-rebuild.sh |
| Yazd        | \\owaspbwa\owaspbwa\owaspbwa-svn\misc-src\Yazd\_1.0-src | owaspbwa-yazd-rebuild.sh |

## Enabling and Disabling OWASP ModSecurity Core Rule Set ##

The VM ships with Apache ModSecurity enabled, but no rule sets in use (so nothing is blocked or logged).  The VM provides easy mechanisms to enable and disable the [OWASP !ModSecurity Core Rule Set](https://www.owasp.org/index.php/Category:OWASP_ModSecurity_Core_Rule_Set_Project) as shown below.  The purpose of this feature is to allow users to see firsthand the effectiveness (and limitations) of the Core Rule Set in catching malicious requests.

In order to log (but not block) any requests using the OWASP ModSecurity Core Rule Set, run the following command.  Log messages appear in /var/log/apache2/error.log (shared at \\owaspbwa\var\log\apache2\error.log).

```
owaspbwa-modsecurity-crs-log.sh
```

In order to both block and log requests using the OWASP ModSecurity Core Rule Set, run:

```
owaspbwa-modsecurity-crs-block.sh
```

To disable all ModSecurity rules (returning to the state the VM ships in), run:

```
owaspbwa-modsecurity-crs-off.sh
```

Users can edit the Core Rule Set rules in effect by editing files under /etc/apache2/modsecurity-crs/ (shared at \\owaspbwa\etc\apache2\modsecurity-crs\).  Users are also free to alter the Apache configuration to use an entirely different set of ModSecurity rules.

## Logging ##

The logging configuration for all components on the VM have been left at the default settings, but the user is free to edit the logging configuration in order to see the impact of changes.

Logs can be accessed under the /var/log/ directory (shared at \\owaspbwa\var\log\).  All logs are deleted when the VM is distributed, so all log entries that a user sees are due to their own activity on the system.

# Advanced Topics #

## Converting Virtual Machine Format ##

The OWASP BWA developers believe that the VM should be easily convertible to run under virtualization software other than VMware products.

Users have reported the VM to work in the following virtualization software packages:
  * [VirtualBox](https://www.virtualbox.org/)
  * [Microsoft Hyper-V](https://technet.microsoft.com/en-us/windowsserver/dd448604.aspx)

Users who attempt to use the VM in another virtualization software are encouraged to provide [feedback](#Getting_Involved.md) on the process, whether or not it was successful.

When converting the VM for use on another virtualization product or for use on a VMware server, you may wish to download ans use the VM in Open Virtualization Format (.ova).

## Updating Components From Repositories ##

Various components on the OWASP BWA VM can be updated from their public repositories.  Any such updates carry a risk of breaking the respective application, but have potential to provide additional features without having to wait for the next release of the OWASP BWA VM.

Generally, users who desire this feature should try updating all components first.  If errors are encountered, please report them to the OWASP BWA developers.  Then, revert the VM to an earlier, working state and attempt to update only the OWASP BWA specific content.

### All Components ###

In order to update the files for OWASP BWA, along with code for applications that are pulled from public source code repositories, run the command:

```
owaspbwa-update-all.sh
```

This may result in application errors if there are updates to the application that do not work in the VM.  This is often due to changes in the structure of the database used by the application.  If this occurs, check the application for instructions on how to rebuild the database.  Some applications include a page or link that will rebuild the database automatically.

### OWASP BWA Specific Content ###

Code and files that are not available in any other public repositories are stored in the SVN repository for OWASP BWA at Google Code.  That can be updated by running:

```
owaspbwa-update-owaspbwa-only.sh
```

Running this command may also update the application databases stored on the VM. That update may cause issues with applications that have their own public repository since the application itself is not updated by this command.

# Getting Involved #

## Reporting Bugs ##

All known bugs (not the intentional security issues) in the VM are in the [Google Code issue tracker](https://code.google.com/p/owaspbwa/issues/list) (that is, "Issues" in the menu above).  Please report any additional bugs you discover.

## Known Vulnerabilities ##

The known security vulnerabilities in the applications are tracked at https://sourceforge.net/p/owaspbwa/tickets/?limit=999&sort=_severity+asc. Please submit any additional issues that you discover.

## Google Group ##

This project has a (relatively low traffic) Google Group at http://groups.google.com/group/owaspbwa.  Feel free to join and ask questions about the VM, make suggestions, etc.

## Twitter ##

To get release announcements and other news from the project on Twitter, follow @owaspbwa or visit https://twitter.com/owaspbwa/.