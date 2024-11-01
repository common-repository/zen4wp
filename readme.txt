=== Zen Cart for WordPress (zen4wp) ===
Tags: WordPress and Zen Cart, Zen Cart and WordPress Integration, Zen Cart, ZenCart, wp4zen, zen4wp, WordPress integration, Zen Cart integration, ecommerce, featured products, new products, special products, bestsellers, zencart, zen cart
Contributors: lat9, DivaVocals
Minimum version: 3.5
Requires at least: 3.5
Tested up to: 4.8.x
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This series of WordPress widgets provide methods to seamlessly link your Zen Cart products to your WordPress blog.

== Description ==

If you are interested integrating Zen Cart and WordPress, it's probably because you want to mention the products from your Zen Cart store in your WordPress blog. Wouldn't it be nice to be able to put links to your products into the relevant blog posts and pages? Wouldn't it be nice to have the ability to display your New or Featured Products on the home page of your WordPress blog and have those listings update automatically as your Zen Cart store changes?

WordPress is known as one of the best open-source Content Management Systems (CMS) for creating blogs while Zen Cart is a one of the best open-source frameworks to build an on-line shopping cart. Each has its own outstanding features, but the combination of them will create a great product which we are calling "Zen Cart for WordPress (zen4wp)". 

Basically, Zen4WP is a WordPress plugin that can be used to integrate Zen Cart **INSIDE** WordPress so that shopowners can display Zen Cart content **DIRECTLY** on their WordPress site via a series of widgets and shortcodes (***shortcodes are a Premium Feature***).

= Prerequisites for Zen Cart for WordPress (zen4wp) =

   * A Zen Cart store installed and operable. Tested with Zen Cart v1.5.x.
   * WordPress installed and operable. Tested with WordPress v3.5.x - v4.5.x.
   * The Zen Cart and WordPress installations share a common mySQL database. (use table prefixes to separate WordPress tables from Zen Cart tables)
   * The Zen Cart and WordPress installations must share the same domain.
   * Update the admin configuration options with the correct filepath to the WordPress installation.

Note: If your store/blog configuration does NOT match these prerequisites, then Zen Cart for WordPress (zen4wp) will not function

= Want more??? Get Zen Cart for WordPress Premium (zen4wp) =

[Zen Cart for WordPress Premium (zen4wp)](http://overthehillweb.com/shop/premium-modules-plugins-addons/wordpress-premium-plugins/zen-cartr-for-wordpress-zen4wp) adds more widgets (including the shopping cart, categories, manufacturers, reviews, and testimonials widget) and shortcodes.

To use the premium (paid) features, you must first install the (free) base plugin (Zen Cart for WordPress) 

= Zen Cart for WordPress Features =
This list identifies each of the features provided by zen4wp and the zen4wp level that includes the feature.  Premium - Option 1 includes all Base features;  Premium - Option 2 includes all Base and Premium - Option 1 features.

* ***zen4wp_best_sellers*** - Displays an ordered-list that contains the current best-sellers from the Zen Cart store, each list-item is a link back to the associated product's detailed information page. You configure the number to display and whether the purchase-count is included. <br />***(Base)***

* ***zen4wp_featured*** - Displays a collection of featured-products from the Zen Cart store, including the product's name, image and price with a link back to the product's detailed information page. You configure the total number and the number-per-row for each instance. <br />***(Base)***

* ***zen4wp_new*** - Displays a collection of new-products from the Zen Cart store, including the product's name, image and price with a link back to the product's detailed information page. You configure the total number and the number-per-row for each instance. The Zen Cart configuration controls what products are considered new. <br />***(Base)***

* ***zen4wp_specials*** - Displays a collection of products-on-special from the Zen Cart store, including the product's name, image and price with a link back to the product's detailed information page. You configure the total number and the number-per-row for each instance. <br />***(Base)***

* ***zen4wp_categories*** - Displays a list of top-level category links from the Zen Cart store, including each category's name. This widget is hideCategories-aware, i.e. if a top-level category is hidden, its link will not be shown. <br />***(Premium - Option 1)***

* ***zen4wp_manufacturers*** - Displays a list of links to the index-listing page for each of the manufacturers from the Zen Cart store. <br />***(Premium - Option 1)***

* ***zen4wp_reviews*** - Displays a collection of product reviews from the Zen Cart store, including the review's text and rating and the product's image with a link back to the review. You configure the total number and the number-per-row for each instance as well as the number of characters of the reviews text to include. <br />***(Premium - Option 1)***

* ***[zenprod id=nn]*** - Generates an HTML link to the specified product in the Zen Cart store, if the product ID specified is a valid product ID, using the product's name as the anchor-text. If the ID value is not valid, the string The product with an ID of "nn" was not found. is output. <br />***(Premium - Option 1)***

* ***[zenprod id=nn name=ww image=xx price=yy]*** - Generates an HTML block for the specified product in the Zen Cart store, if the product ID specified is a valid product ID, optionally including the product's name, image and price. If the ID value is not valid, the string The product with an ID of "nn" was not found. is output. <br />***(Premium - Option 1)***

* ***[zenprod id=nn name=ww image=xx price=yy cart=zz]*** - Generates an HTML block for the specified product in the Zen Cart store, if the product ID specified is a valid product ID, optionally including the product's name, image, price and add-to-cart button. If the ID value is not valid, the string The product with an ID of "nn" was not found. is output. <br />***(Premium - Option 1)***

* ***[zencat id=xx]*** - Generates an HTML link to the specified category in the Zen Cart store, if the category ID specified is a valid category ID, using the category's name as the anchor-text. If the ID value is not valid, the string The category with an ID of "xx" was not found. is output. <br />***(Premium - Option 1)***

* ***[zenmfgr id=xx]*** - Generates an HTML link to the specified manufacturer's index page in the Zen Cart store, if the manufacturers ID specified is a valid manufacturers ID, using the manufacturer's name as the anchor-text. If the ID value is not valid, the string The manufacturer with an ID of "xx" was not found. is output. <br />***(Premium - Option 1)***


* ***zen4wp_shopping_cart*** - If enabled via the Zen Cart admin, this widget shows the number of items and current total amount associated with a customer's Zen Cart shopping cart. You configure whether the Zen Cart login/logoff and shopping_cart page links are shown, the format of the cart (sidebox vs. header) and whether the cart widget displays when the cart is empty. Requires a SQL patch and a piece of code running on the Zen Cart store. <br />***(Premium - Option 2)***

* ***zen4wp_testimonials*** - If the testimonials_manager plugin is installed, displays a collection of testimonials from the Zen Cart store, modelled after the Testimonials sidebox. You configure the total number and the number-per-row for each instance as well as the number of characters of the testimonials text to include. <br />***(Premium - Option 2)***

== Other Modules/Plugins ==
For a COMPLETE integration solution, see also: [WordPress for Zen Cart Basic (wp4zen)](http://overthehillweb.com/shop/free-modules-plugins-addons/zen-cart-free-add-ons/wordpress-for-zen-cartr-basic-wp4zen)
WordPress for Zen Cart (wp4zen) is a Zen Cart module that allows shopowners to display WordPress content on their Zen Cart site simply using sideboxes.

These admin configurable sideboxes are meant to link your WordPress blog to your Zen Cart store without having to implement complicated WordPress blog embedding solutions/add-ons. These sideboxes pull content links directly from the WordPress blog. All a store owner has to do is to style their WordPress blog to match their store (or vice versa).

Some may find these sideboxes are a LOT less troublesome than blog embedding solutions.

See [WordPress for Zen Cart Basic (wp4zen)](http://overthehillweb.com/shop/free-modules-plugins-addons/zen-cart-free-add-ons/wordpress-for-zen-cartr-basic-wp4zen) in action here:

* http://eyeitalia.com
* http://tablelegworld.com
* http://overthehillweb.com

== Installation ==

1. Unzip and copy the files from the 'WordPress Plugin' folder to your WordPress environment in your 'wp-content/plugins' directory
2. Click the 'Activate' link on your 'Plugins' page (in the WordPress administration page) for each Zen4WP plugin you wish to activate.
3. Go to options page (admin menu 'Settings -> Set Zen4Wp Options) and set these settings to the value of the like-named entry in your Zen Cart's '/includes/configure.php' file; be sure not to have errors or warning messages.

== Frequently Asked Questions ==

= Where can I see this plugin in action? =
* http://eyeitalia.com
* http://tablelegworld.com
* http://lakesidefasteners.ca
* http://overthehillweb.com

= Why does Zen Cart for WordPress require the same database that Zen Cart uses? Could I configure a different database for the blog? I don't think it's that desirable to mix the product and order data with blog data? = 
Having Zen Cart look up info in WordPress tables in a database Zen Cart already has access to is a world of difference from having to establish credentials and communication with a different database. The two table systems can be separated with prefixes if necessary; the capability is built into WordPress and Zen Cart.

= How does one set this up for WordPress and Zen Cart to share a database? Zen Cart is in my root and WordPress is installed in a sub-directory. Do I need to reinstall WordPress in a different manner? =
Doesn't matter which one you install first (Zen Cart or WordPress), but during the installation when you are asked for your database information you use the SAME database information for both. (DB name, DB Password, DB Username) You also need to make sure you are using a DB prefix for BOTH installs. For example in Zen Cart you could use zen_ and for WordPress you could use wp_.

= Okay it sounds like I need to wipe-out and reinstall WordPress manually as it was installed with Simple Scripts and I never had the option of selecting or naming a database. My Zen Cart install was a manual install about a year ago. Will the default database settings for this install conform to these rules? There's no way that I'd want to re-install Zen Cart now that the site is built and customized. =
The default Zen Cart database prefix is '  ' (i.e. none) and the default WordPress prefix is 'wp_'. As long as the prefixes are DIFFERENT, you should be good-to-go! (YES they are "different" if you used the Zen Cart default of NONE and the WordPress default)

= Does it matter whether or not WordPress is in the main site root or does Zen Cart have to be in the main site root? =
IN GENERAL you need WordPress in a subdirectory of Zen Cart OR Zen Cart in a sub-directory of WordPress. You don't install them BOTH in the root of your domain.. Bottomline is this plugin works REGARDLESS as to which is in the root folder (WordPress or Zen Cart).

= When someone goes to the shop page I don't want it to look like the Zen Cart pages. Will Zen Cart for WordPress (zen4wp) & WordPress for Zen Cart (wp4zen) make my Zen Cart site look like my WordPress site? =

Zen Cart for WordPress (zen4wp) & WordPress for Zen Cart (wp4zen) isn't going to change the look of Zen Cart or WordPress. The look and feel of Zen Cart is still controlled by the Zen Cart template and the look and feel of WordPress is still controlled by your WordPress theme. You WILL still need to modify your Zen Cart template to make it MATCH your WordPress site. (which is EXACTLY what was done for sites that are using Zen4WP - like  http://www.eyeitalia.com)

Zen Cart for WordPress (zen4wp) & WordPress for Zen Cart (wp4zen) are not theming or templating add-ons.. They are CONTENT add-ons. They will allow you to display Zen Cart products, reviews, testimonial, shopping cart, etc in your WordPress site. They will allow you to display your blogs post categories, recent posts or tag cloud widgets, etc in Zen Cart. What your WordPress site and Zen cart site LOOK like is still controlled by the respective WordPress theme and Zen Cart template. Making them LOOK alike is a LOT easier today than it has been historically. (As you can see on the http://www.eyeitalia.com site)

= I found a plugin for WordPress which says it "integrates osCommerce into any WordPress theme". This plugin looks like it embeds osCommerce inside WordPress.  Does Zen Cart for WordPress (zen4wp) work the same way? Will I be able to embed Zen Cart inside my WordPress site/theme? =

Simply put, no. Unlike other plugins, Zen Cart for WordPress (zen4wp) is not going to display a whole Zen Cart store inside WordPress. Zen Cart for WordPress (zen4wp) is NOT an embedding plugin (ALA WordPress on Zen Cart or other "blog embedding" solutions). Zen Cart for WordPress (zen4wp) allows you to display specific Zen Cart content on your WordPress site using a series of widgets (which can be displayed inside any WordPress sidebar) and shortcodes (which can be displayed inside any page or post or text widget).. Unlike embedding plugins, Zen Cart for WordPress (zen4wp) allows you to use the FULL power of WordPress and the FULL power of Zen Cart. This means that you will be able to use any plugin/module for Zen Cart or WordPress without limits (like having to implement some gnarly code just to get your Zen Cart module to work inside WordPress).

= Where can I get more information? = 
To learn more, go to http://zencart-wordpress-integration.com/

== Changelog ==
= v1.2.2, 2013-09-23 =
* Initial release
= v1.2.3, 2013-10-03 =
* Incorrect display of some sale prices, especially Salemaker sales and some free products. Changed zen4wp_functions_prices.php.
= v1.3.0, 2014-03-30 =
* Provided enhancements to the [zenprod] Shortcode.
= v1.3.1, 2014-09-18 =
* Best Sellers widget: Throwing "printf() too few args" on line 97
* Correct various PHP notify errors (a couple of which were variable "misspellings").
* Duplicated definition: ZEN_TABLE_PRODUCT_TYPES
= v1.4.0, 2016-05-28 =
* Update zen4wp functions to use Zen Cart 1.5.4 as their change-basis.
* Incorporate changes into the widgets to make them compatible with WordPress 4.5.2 and later
