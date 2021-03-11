# Webform Mautic
---

CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Recommended
 * Features
 * Maintainers


INTRODUCTION
------------

The Webform Mautic module integrates your Webform submissions with Mautic form 
submissions.

The module adds a new handler to be triggered for Webform submissions, which 
allows you to easily send a submission as a POST request to a Mautic form.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/webform_mautic

 * To submit bug reports and feature suggestions, or to track changes:
   https://www.drupal.org/project/issues/webform_mautic


REQUIREMENTS
------------

This module requires following modules outside of Drupal core:

 * Webform - https://www.drupal.org/project/webform


RECOMMENDED
-----------

The module tries to send Mautic cookies (session and device ID) automatically, 
to merge the newly created contact from the submission with the anonymous 
tracked visitor through the tracking pixel seamlessly.

Therefore, Mautic tracking JavaScript is necessary for this functionality to 
work. You can achieve that by adding the Mautic tracking script to your Drupal
site in various ways. Such as:
 * Mautic module - https://www.drupal.org/project/mautic
 * Adding the JavaScript into your template
 * Script Manager module - https://www.drupal.org/project/script_manager
 * or Google Tag Manager

Refer to Mautic's Contact Monitoring for further details.
https://www.mautic.org/docs/en/contacts/contact_monitoring.html


INSTALLATION
------------

 * Install the Webform Mautic module as you would normally install a contributed
   Drupal module. Visit https://www.drupal.org/node/1897420 for further 
   information.


FEATURES
--------

 * Adds a Webform handler to map submissions to Mautic form.
 * Automatically send Mautic cookies (session and device ID), to merge the 
   newly created contact from the submission with the anonymous tracked visitor 
   through the tracking pixel seamlessly.
 * Automatically forward the IP address to Mautic, to provide consistent 
   tracking of leads once they submit the form.



MAINTAINERS
-----------

 * Mohammed Razem - https://www.drupal.org/u/mohammed-j-razem
 * Muath Khraisat - https://www.drupal.org/u/muath-khraisat
 * Sponsored by Vardot  - https://www.vardot.com
