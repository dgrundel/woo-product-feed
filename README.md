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

http://codex.wordpress.org/Template_Tags/get_posts

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
- post_status