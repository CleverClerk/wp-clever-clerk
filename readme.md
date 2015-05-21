=== Plugin Name ===
Contributors: mrgenixus
Tags: clever-clerk
Requires at least: 4.2.2
Tested up to: 4.2.2
Stable tag: 4.2
License: MIT
License URI: http://opensource.org/licenses/MIT

This is currently able to pull in your hotel's tours as markup using a short tag

== Description ==

enables use of short tag `[clever_clerk data-clever_clerk='tours']` to include a list of tours.

please contact cleverclerk to add additional data from api to plugin.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Development

dependencies

  ```
    npm install -g browserify watchify
  ```

building the javascript
  
  ```
    npm install
  ```

  ```
    browserify src/*.js -t jadeify -t uglifyify -o build/cleverclerk.min.js
  ```

working in development

  ```
    watchify src/*.js -t jadeify -t uglifyify -o build/cleverclerk.min.js
  ```


