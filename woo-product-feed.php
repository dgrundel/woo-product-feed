<?php /*
    Plugin Name: Woo Product Feed
    Plugin URI: https://github.com/dgrundel/woo-product-feed
    Description: Free public CSV export utility for WooCommerce
    Version: 1
    Author: Daniel Grundel, Web Presence Partners
    Author URI: http://www.webpresencepartners.com
*/

    class WebPres_Woo_Product_Feed {
        
        public function __construct() {
            add_action('init', array(&$this, 'check_request'));
        }
        
        public function check_request() {
            if(isset($_REQUEST['woo_product_feed'])) {
                $this->csv();
                exit();
            }
        }
        
        public function csv() {
            
            $delimiter = ',';
            
            $output = array();
            $line_number = 0;
            
            $product_query = array(
                'numberposts' => -1,
                'post_status' => 'publish',
                'post_type' => 'product');
            $products = get_posts($product_query);
            
            if(is_array($products)) {
                foreach($products as $product) {
                    
                    $output[$line_number][] = $product->post_title;
                    $output[$line_number][] = self::strip_whitespace(nl2br($product->post_content));
                    $output[$line_number][] = self::strip_whitespace(nl2br($product->post_excerpt));
                    
                    $output[$line_number][] = get_post_meta($product->ID, '_weight', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_length', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_width', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_height', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_regular_price', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_sale_price', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_price', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_tax_status', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_tax_class', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_visibility', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_featured', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_sku', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_downloadable', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_virtual', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_stock', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_stock_status', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_backorders', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_manage_stock', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_product_type', true);
                    $output[$line_number][] = get_post_meta($product->ID, '_product_url', true);
                    
                    $product_categories = wp_get_object_terms($product->ID, 'product_cat');
                    $product_category_names = array();
                    if(is_array($product_categories)) {
                        foreach($product_categories as $product_category) {
                            $product_category_names[] = $product_category->name;
                        }
                    }
                    $output[$line_number][] = implode('|',$product_category_names);
                    
                    $product_tags = wp_get_object_terms($product->ID, 'product_tag');
                    $product_tag_names = array();
                    if(is_array($product_tags)) {
                        foreach($product_tags as $product_tag) {
                            $product_tag_names[] = $product_tag->name;
                        }
                    }
                    $output[$line_number][] = implode('|',$product_tag_names);
                    
                    $attachment_query = array(
                        'numberposts' => -1,
                        'post_status' => 'inherit',
                        'post_mime_type' => 'image',
                        'post_type' => 'attachment',
                        'post_parent' => $product->ID);
                    $attachments = get_posts($attachment_query);
                    $product_attachment_urls = array();
                    if(is_array($attachments)) {
                        foreach($attachments as $attachment) {
                            $product_attachment_urls[] = $attachment->guid;
                        }
                    }
                    $output[$line_number][] = implode('|',$product_attachment_urls);
                    
                    $line_number++;
                }
            }
            
            $file_name = "woo-product-export.csv";
            
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename={$file_name}");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $lines = array();
            foreach($output as $line) {
                $lines[] = '"'.implode('","', str_replace('"', '""', $line)).'"';
            }
            echo implode("\n", $lines);
        }
        
        public static function strip_whitespace($content) {
            $content = trim($content);
            
            $content = str_replace("\n", ' ', $content );
            $content = str_replace("\r", ' ', $content );
            
            //remove repeating spaces
            $content = preg_replace('/(?:\s\s+|\n|\t)/', ' ', $content);
            
            return $content;
        }
        
    }
    
    $webpres_woo_product_feed = new WebPres_Woo_Product_Feed();
    