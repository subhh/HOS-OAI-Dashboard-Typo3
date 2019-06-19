# HOS-OAI-Dashboard-Typo3

Typo3-Plugin for the Hamburg Open Science OAI Dashboard

## Installation
The extension can be installed like any other extension of TYPO3, either via direct uploading in the Extension-Mananger or via composer mode.
The directory name hast to be subhh_oa_dashboard.

## Adding the static template of the extension

Go Module WEB > Template:

![static_template](/Documentation/Images/web_template.png)

Edit the whole template Record:

![static_template](/Documentation/Images/edit_template.png)

Go to tab includes and add the static template:

![static_template](/Documentation/Images/static_template.png)


## Configurating the host address of the REST API

Go to module ADMIN TOOLS > Settings:

![static_template](/Documentation/Images/admin_settings.png)

Click on "Configure Extensions" and choose "subhh_oa_dashboard > Rest api host":

![static_template](/Documentation/Images/extension_configuration.png)

Edit the REST API host: 

![static_template](/Documentation/Images/rest_api_host.png)

## Adding the viewport meta tag 

For proper responsive behavior, the viewport meta tag must be included. 

To do this, add the following entry to the Typoscript site template:

*page.meta.viewport = width = device-width, initial-scale = 1.0*


## Adding needed plugins

There are 2 plugins you need to add to the page where the repository data should be displayed:

HOS OA Dashboard - Repository List: Shows a scrollable menue containing a list of all available repositories.   
  
HOS OA Dashboard - Repository Details: Displays the data charts of the currently selected repository.