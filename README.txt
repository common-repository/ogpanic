=== Plugin Name ===
Contributors: rakuraku
Donate link: https://ogpanic.com
Tags: og-image, image, ogp, open graph protocol, facebook, twitter, google, open, open graph, share, sharing, social, social network, linkedlin, pinterest, affiliates, meta, meta tag, meta tags, tag, tags
Requires at least: 5.0.1
Tested up to: 5.3
Stable tag: 1.0.14
License: GPLv3

OGPanic generates beautiful og-images automatically from your post's title, featured image and etc.

== Description ==

[OGPanic](https://ogpanic.com) is an enhancement for WordPress posts when the links are shared on social media. Social medias like Facebook and Twitter using The [Open Graph protocol](http://ogp.me/) to display featured image and title of the post.

[OGPanic](https://ogpanic.com) goes one step further: It adds the title and category info to the featured image and make your posts stand out when shared on social medias.


== Installation ==

Install and activate the plugin. It will automatically render the og:image tag by using the post's setting.

Additional metadata `og:image:width` and `og:image:height` is added.

This plugin depends on [Open Graph Protocol](https://www.wordpress.org/plugins/open-graph-protocol-framework/) plugin. If you haven't installed that, install it with og-panic.

The following tags will be added by `Open Graph Protocol`

- `og:title` : The page's title is used, this provides the title for posts, pages, archives etc.
- `og:type` : The type will be `article` in general, `website` for the front page and `blog` for the blog homepage.
- `og:url` : The URL of the current page.
- `og:site_name` : The name of the site.
- `og:description` : Uses the full excerpt if available, otherwise derives it from the content. For author and archive pages, the type of page and title is used.
- `og:locale` : The current locale.
- `og:locale:alternate` : Indicates additional locales available if [WPML](http://wpml.org/) is installed.


== Frequently Asked Questions ==

= How to setup the API endpoint and token =

You can get it from https://ogpanic.com/ by filling the form on that page.

== Screenshots ==

1. Choose an theme and you are ready to go.

== Changelog ==

= 1.0.14 =
* Two new themes added

= 1.0.13 =
* Bump version to 1.0.13

= 1.0.12 =
* [NEW] Choose different theme in post editor
* [NEW] Disable OGPanic for certain post in post editor
* [FIX] Fixed warning messages when there's no OGPanic setting

= 1.0.11 =
* Update translations

= 1.0.7 =
* Add link to settings from `Installed Plugins` page
* Correctly sanitize and escape options
