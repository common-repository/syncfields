=== SyncFields ===
Contributors: pjfc 
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=M4YCYHUN95Q8J
Tags: sync, usermeta, woocommerce, custom fields, database
Requires at least: 3.8.6
Tested up to: 6.5
Stable tag: trunk
License: GPL2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Take control. Map and Sync user (meta)fields between WordPress and any plugin, (like WooCommerce, user registration plugins, etc).

== Description ==

= What does it do? =
With this plugin you can map and synchronize fields in the WordPress user and usermeta database.

Note:
This plugin syncs one way only!

The plugin adds a new menu in the backend called 'SyncFields', which allows you to control the plugin settings.

= Give me an example please =
When you install plugins such as WooCommerce, the Wordpress usermeta database table is populated with new fields. You can map these fields and choose to automatically sync these with other usermeta fields, or with the Wordpress regular user fields.
For example, you can choose to Sync WooCommerce billing_country usermeta field with the WordPress main Country user field. And lot's of other ways to sync data within WordPress, and keep it synced automatically.

= Does it work with plugin xyz...? =
Yes, it works with ANY plugin which creates usermeta fields. The plugin automatically finds new fields every time you install a new plugin.

= To create a new Sync between two database fields: =
- Click on 'Add new mapping'. A list of available fields are collected. (This can take some time).
- Choose the Primary Field.
- Choose a field the primary field will sync with.
- Click on 'Create New Field'

Once you created a mapping between two fields, the data will remain synced for all users. (The way it works is that every time data is changed in a field the sync will trigger automatically).

= To delete a field mapping: =
- Click the 'delete' button in the applicable mapping row.

= To edit a field mapping: =
- Click the 'edit' button in the applicable mapping row.

= Caution: =
Make sure database fields which you intend to sync are of the same type. So for example do not sync an email field with a Country field, this will result in invalid data in the synced fields.
If in doubt do NOT sync fields with each other, it may break your site in extreme cases.

= Tested at plugintests.com =
Wonder if it will slow down your site? Wonder it creates PHP errors?
It doesn't. Check this out: https://plugintests.com/plugins/wporg/syncfields/latest

== Installation ==

= For an automatic installation through WordPress: =
1.  Go to the 'Add New' plugins screen in your WordPress admin area
2.  Search for 'SyncFields'
3.  Click 'Install Now' and activate the plugin
4.  Go the 'Export User Data' menu, under 'Users'

= For a manual installation via FTP: =
1.  Upload the synfields directory to the /wp-content/plugins/ directory
2.  Activate the plugin through the 'Plugins' screen in your WordPress admin area
3.  Go the 'SyncFields' menu

= To upload the plugin through WordPress, instead of FTP: =
1.  Upload the downloaded zip file on the 'Add New' plugins screen (see the 'Upload' tab) in your WordPress admin area and activate.
2.  Go the 'SyncFields' menu

== Frequently Asked Questions ==

= I'm missing usermeta fields =
So you  activated a new plugin, and expected new usermeta fields to appear in SyncFields. But they didn't.

Why does this happen:
Most usermeta fields only get created in the WordPress database once they are needed for the first time. This typically is when a user first populates a field, and not once a plugin gets activated!

What can you do about it:
If you miss a specific usermeta field, create a test customer and use the field you want to appear. 
For example: to make WooCommerce billing_country usermeta field appear, create a testcustomer and purchase a test product whilst populating the checkout fields including the billing country for the test customer. At that moment new usermeta fields for WooCommerce are created for the first time in the WordPress database (Tip: use the 'Cash on Delivery' payment method so you don't have to set up any real payment methods yet to place the test order). After this, these usermeta fields are created, and therefore available to SyncFields.

For details please check this:
https://wordpress.org/support/topic/adding-fields-6

= Does this plugin work with Headless /JamStack WordPress sites? =
Yes it works.

= What is Cron Scheduling? =
If you want to run a regular Sync on a Cron schedule, please use this option.
It normally should not be necessary because syncs are saved upon every commit.

= Where can I find the documentation =
The plugin is documented in the backend. If you have any questions, feel free to pose them in the Support section here https://wordpress.org/support/plugin/syncfields

== Screenshots ==
1. The main admin screen of the plugin.
2. The 'Add New Mapping' screen.
3. The 'Edit Mapping' screen.

== Changelog ==
- Version 2.1: Confirmed compatability with Wordpress 6.5
- Version 2.0: Confirmed compatability with Wordpress 6.1
- Version 1.9.91: Confirmed compatability with Wordpress 6.0
- Version 1.9.9: Confirmed compatibility with latest Wordpress
- Version 1.9.8: Confirmed compatibility with latest Wordpress
- Version 1.9.7: Added FAQ, plugin works with Headless /JamStack WordPress sites
- Version 1.9.6: Confirmed compatibility with Wordpress 5.5.1
- Version 1.9.5: Confirmed compatibility with Wordpress 5.3
- Version 1.9.4: Added security code suggested by Jeff Starr. Confirmed compatibility with Wordpress 5.2
- Version 1.9.3: Confirmed compatibility with Wordpress 5.1
- Version 1.9.2: Confirmed compatibility with Wordpress 5.0 (first release with Gutenberg)
- Version 1.9.1: Small edits, and confirmed compatibility with Wordpress 4.9.8
- Version 1.9: Confirmed compatibility with Wordpress 4.9.8
- Version 1.8: Small edits, and confirmed compatibility with Wordpress 4.9.4
- Version 1.7: Confirmed compatibility with Wordpress 4.9
- Version 1.6: Confirmed compatibility with Wordpress 4.8
- Version 1.5.2: Confirmed compatibility with Wordpress 4.6
- Version 1.5.1: Clarified plugin does one-way sync
- Version 1.5: Corrected typos
- Version 1.4: Backend updates to improve the flow
- Version 1.3: Edited the FAQ
- Version 1.2: Added to the FAQ about missing usermeta fields
- Version 1.1.1 : Added background image
- Version 1.0 : Stable release for production
- Version 0.6.1 : Small changes
- Version 0.5.4 to 0.5.9 : Edits in explanation and screenname to make things more consistent.
- Version 0.5 : Stable enough to release to the public for further scrutiny :-)
- Version 0.4 : Stable enough to test in a production environment with WooCommerce. Beta testers feedback encompassed.
- Version 0.3 : Debugging.
- Version 0.2 : Added automatic sync functionality.
- Version 0.1 : Initial rough thoughts, bugs to squash..
