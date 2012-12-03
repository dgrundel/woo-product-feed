<?php /*
    Plugin Name: Woo Product Feed
    Plugin URI: http://webpresencepartners.com/
    Description: Free CSV import utility for WooCommerce
    Version: 1
    Author: Daniel Grundel, Web Presence Partners
    Author URI: http://www.webpresencepartners.com
*/

    class WebPres_Woo_Product_Feed {
        
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
                    $output[$line_number][] = $product->post_content;
                    $output[$line_number][] = $product->post_excerpt;
                    
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
                    $output[$line_number][] = is_array($product_categories) ? implode('|',$product_categories) : '';
                    
                    $product_tags = wp_get_object_terms($product->ID, 'product_tag');
                    $output[$line_number][] = is_array($product_tags) ? implode('|',$product_tags) : '';
                    
                    $attachment_query = array(
                        'numberposts' => -1,
                        'post_status' => 'publish',
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
        
    }