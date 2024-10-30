=== LH Signup ===
Contributors: shawfactor
Donate link: https://lhero.org/portfolio/lh-signup/
Tags: automate, duplicate, copy, copy site, copier, clone, multisite, network
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.16
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

LH Signup Allows you to easily allow your users to create new sites on the front end of your website. It allows you to create white label websites ready for your user when they create a site  

== Description ==

This plugin ONLY works with WordPress Multisite, will NOT work in single site mode, and MUST be Network Activated. You will find it's menu in your network administration dashboard (wp-content/network)

By activating and configuring this plugin and adding the shortcode [lh_signup_form] to a page on the front end of your primary site it allows selected user roles to sign up for a new site within your multisite network in a simple and visually appealing way. This is perfect for creating a white labelled site for new sign ups.

**Like this plugin? Please consider [leaving a 5-star review](https://wordpress.org/support/view/plugin-reviews/lh-signup/).**

**Love this plugin or want to help the LocalHero Project? Please consider [making a donation](https://lhero.org/portfolio/lh-signup/).**

== Installation ==

1. Log in to your WordPress network as a multisite super admin and go to /wp-admin/network
1. Use the Dashboard > Plugins > Add New tools to install LH Signup from the WP.org repository or install by uploading the zip file
1. Paste the shortcode [lh_signup_form] into the a page on your main site and save
1. Access the LH Signup's options in the Network Dashboard (/wp-admin/network), under Network->Settings->Network Signup Options. Configure the options, including the page you created above.
1. Optionally add a welcome email to the user who has created the new site.
1. Also optionally you can also configure a preview of each template you have allowed (in the previous step) by going to that sites Wp-admin->Settings->General and adding a description and screenshot in the Site Signup Configuration section

== Frequently Asked Questions ==

= Why can't I select the main site as a template?  =

Because that would be a very bad idea for performance and security purposes.

= Will this look good with my theme?  =

The plugin does not contain any inherent styling. This is a deliberate decision so that it should look okay in most themes. If there is somthing that does not look right it is trivial to style the form as you want it by using a child theme.

= How can I customise the words and labels of signup form?  =

The shortcode that creates the signup form has the following attributes

site_slug_label - the label of the slug input
site_slug_placeholder - the placeholder attribute of the slug input
site_slug_title - the title attribute of the slug input
site_title_label - the label of title input
site_title_placeholder - the placeholder attribute of the title input
site_template_label - the label of the template input
form_submit_button_value - the text value of the form submit button

All these have logical defaults adding these attributes is optional

= What is something does not work?  =

LH Signup, and all [https://lhero.org](LocalHero) plugins are made to WordPress standards. Therefore they should work with all well coded plugins and themes. However not all plugins and themes are well coded (and this includes many popular ones). 

If something does not work properly, firstly deactivate ALL other plugins and switch to one of the themes that come with core, e.g. twentyfifteen, twentysixteen etc.

If the problem persists please leave a post in the support forum: [https://wordpress.org/support/plugin/h-signup/](https://wordpress.org/support/plugin/lh-signup/). I look there regularly and resolve most queries.

= What if I need a feature that is not in the plugin?  =

Please contact me for custom work and enhancements here: [https://shawfactor.com/contact/](https://shawfactor.com/contact/)

== Changelog ==

= 1.01 =
* Initial release

**1.01 April 07, 2016** 
* Changed sanitize function

**1.02 April 27, 2016** 
* Removed irrrlevant code that should never have been included

**1.03 July 10, 2016** 
* array fix and settings link

**1.04 March 30, 2017** 
* used isset

**1.05 March 01, 2021** 
* major overhaul

**1.06 July 14, 2022** 
* Various fixes

**1.07 July 17 2022** 
Minor improvements

*1.08 July 18 2022** 
fixes to settings

*1.09 July 18 2022** 
plugin conflict fix

*1.10 July 26 2022** 
Allow previews of site templates

*1.11 July 27 2022** 
Fixed minor javascript error

*1.12 July 29 2022** 
Better validation

*1.13 August 03 2022** 
Better translations and shortcode attributes

*1.14 November 26 2022** 
Configurable site creation email functionality added

*1.15 November 27 2022** 
Wpautop email content

*1.16 November 28 2022** 
Stripslashes