woo-product-feed
================

A simple, public CSV product feed for your woocommerce product catalog.

Once installed and activated, visit:

```
http://yourblog.com/?woo_product_feed
```

**Options**

Products are pulled from the database using WordPress' built-in get_posts() method.

You can read the docs for this method here:

- http://codex.wordpress.org/Template_Tags/get_posts

You can set the following args via GET or POST params:

- numberposts
- offset
- category
- orderby
- order
- include
- exclude
- meta_key
- meta_value
- post_parent

**Examples**

Get the first 5 products:

```
http://yourblog.com/?woo_product_feed?numberposts=5
```

Get products in category with ID 47:

```
http://yourblog.com/?woo_product_feed?category=47
```

Get products ordered by product name (post_title), A to Z (ascending):

```
http://yourblog.com/?woo_product_feed?orderby=title&order=asc
```