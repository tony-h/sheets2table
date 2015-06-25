=== Sheets2Table ===
Contributors: tony.h
Tags: Google Sheets, CSV, HTML Table, Table
Author URI: http://www.tonyhetrick.com
Donate link: http://dev.tonyhetrick.com/donations/
Requires at least: 3.9.0
Tested up to: 4.2.2
Stable tag: 0.4.1
License: GNU General Public License (GPL) version 3
License URI: https://www.gnu.org/licenses/gpl.html

A simple to use plugin that builds an HTML table from a Google Sheet.

== Description ==
Building an HTML table is not rocket science, but keeping the HTML table data up to date is cumbersome and can be time consuming or error prone when making frequent updates. An easier solution is to keep the data in a format and platform that is designed for data entry and collaboration and then dynamically render that data as HTML. Sheets2Table creates a snapshot of a Google Sheet and then dynamically displays the tabular data as an HTML table in WordPress. Using the FooTable plugin, the table is made responsive.

Sheets2Table provides flexibility by allowing a WordPress admin the ability to save a snapshot of the Google Sheet to a user-defined file name. Using the shortcode configuration tool, the admin can display specific columns and rename the column headers. The admin can create the snapshot manually or set the option so that the current data residing in the Google sheet is displayed on the page.

[Sheets2Table Homepage](https://dev.tonyhetrick.com/sheets2table/) |
[Sheets2Table Documentation](https://dev.tonyhetrick.com/sheets2table-help/) |

= Features =
* Import a CSV file from a Google Sheet only using the document ID
* Create a snapshot of the Google Sheet manually, or automatically
* Automated tool to build shortcodes
* Basic file operations to download or delete CSV files
* Multiple CSV files and shortcodes support for limitless tables and combinations
* Responsive table by installing the  FooTable plugin
* A file-based plugin that does not use database integration

== Installation ==
**Preferred installation**

1. Navigate to the plugins page in your Wordpress admin panel
2. Click on *Plugins > Add New*
3. Search for *Sheets2Table* and press the *Install Now* button

**Manual Installation**

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the *Plugins > Add New > Upload* screen in your WordPress dashboard
3. Activate the plugin through the *Plugins* menu in WordPress

== Screenshots ==
1. [Settings - Get CSV file from Google Sheet] (https://dev.tonyhetrick.com/sheets2table-files/screenshots/screenshot-1.jpg)
2. [Settings - Configure the shortcode] (https://dev.tonyhetrick.com/sheets2table-files/screenshots/screenshot-3.jpg)
3. [Sheets2Table render using default settings and theme styles] (https://dev.tonyhetrick.com/sheets2table-files/screenshots/screenshot-6.jpg)
4. [Sheets2Table render using a custom shortcode and FooTable styles] (https://dev.tonyhetrick.com/sheets2table-files/screenshots/screenshot-7.jpg)

== Frequently Asked Questions ==
**How can the changes in my Google Spreadsheet automatically be displayed in my Wordpress site?**

As of version 0.4.1, a shortcode option now exists that will always get the current data in the Google Sheet.

* Naviage to the Shortcodes tab
* Click on *Customize* for the shortcode you wish to display
* Select the columns to display
* Under Other Options, select *get-latest-data*.

== Changelog ==
= 0.4.1 =
* Added a option to always get the latest tabular data from the Google Sheet instead of having to manually create a snapshot.

= 0.4.0 =
* Initial release

== Upgrade Notice ==
We recommend upgrading to the latest version.