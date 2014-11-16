Magento-Store-Codes
===================

When Magento sites add a second store on the same domain there is a problem with legacy requests to old URLs.
This is bad SEO and a bad experience for customers too, who will see an unhelpful 404 error page.
**Mage-Store-Codes** (aka. Knectar_Storecodes) solves this by automatically forwarding unrecognised URLs to the default store.

Installation
------------
Simply copy/merge this `app` directory with your own, then clear Magento's cache.  Composer users can enter this command:

    composer config repositories.firegento composer http://packages.firegento.com
    composer require knectar/storecodes:dev-master

Usage
-----
As before, enable store codes in "System > Configuration > Web > Url Options > Add Store Code to Urls".
Magento Store Codes will start working immediately, detecting 404 errors and turning them into 302 redirects.
CMS pages are handled slightly differently and can be used to create a landing page.
For example, create a new CMS page with the URL key "home" and enable it for "All Store Views".
Any other CMS page with the same URL key but restricted to individual store views will become that store's home page.

For example these three addresses all have the URL key of "home" but show different content;

- `http://www.example.com/` - Landing page, with "All Store Views".
- `http://www.example.com/primary/` - Primary store home page.
- `http://www.example.com/secondary/` - Secondary store home page.

Magento Store Codes also adds a new option in the same location, "&hellip;and default store view".
Change this to "No" to simply serve the default store at old URLs without any redirection.
Non-default stores will continue to use their individual codes in URLs.
A landing page is no longer possible in this mode.

The above example would now look like this;

- `http://www.example.com/` - Primary store home page.
- `http://www.example.com/secondary/` - Secondary store home page.
