<?php
/*
Plugin Name: WP Faker Seed
Description: Generate fake data for testing purposes
Version: 1.2
Author: AJ Javadi
*/

// Make sure to run 'composer require fakerphp/faker' in your plugin directory or WordPress mu-plugins directory before using this script

// Adjust this path if necessary, depending on where you installed Faker
require_once __DIR__ . '/vendor/autoload.php';

use Faker\Factory;

// Add an admin menu item to trigger the data generation
add_action('admin_menu', 'wp_faker_seed_menu');

function wp_faker_seed_menu() {
    add_management_page('WP Faker Seed', 'WP Faker Seed', 'manage_options', 'wp-faker-seed', 'wp_faker_seed_page');
}

function wp_faker_seed_page() {
    echo '<div class="wrap">';
    echo '<h1>WP Faker Seed</h1>';
    echo '<p>Click the button below to generate fake data.</p>';
    echo '<form method="post">';
    echo '<input type="hidden" name="wp_faker_seed_action" value="generate">';
    echo '<input type="submit" class="button button-primary" value="Generate Fake Data">';
    echo '</form>';
    echo '</div>';

    if (isset($_POST['wp_faker_seed_action']) && $_POST['wp_faker_seed_action'] == 'generate') {
        wp_faker_seed_data();
        echo '<div class="updated"><p>Fake data has been generated!</p></div>';
    }
}

function wp_faker_seed_data() {
    $faker = Factory::create();

    // Generate Users with profiles
    for ($i = 0; $i < 20; $i++) {
        $user_id = wp_create_user(
            $faker->userName,
            $faker->password,
            $faker->email
        );
        
        $user_meta = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'description' => $faker->text(200),
            'user_location' => $faker->city . ', ' . $faker->country,
            'user_occupation' => $faker->jobTitle,
            'user_website' => $faker->url,
            'user_twitter' => '@' . $faker->userName,
            'user_facebook' => 'https://facebook.com/' . $faker->userName,
            'user_linkedin' => 'https://linkedin.com/in/' . $faker->userName,
        ];

        foreach ($user_meta as $meta_key => $meta_value) {
            update_user_meta($user_id, $meta_key, $meta_value);
        }

        wp_update_user([
            'ID' => $user_id,
            'display_name' => $user_meta['first_name'] . ' ' . $user_meta['last_name'],
        ]);
    }

    // Generate Posts
    $users = get_users();
    $categories = ['Technology', 'Lifestyle', 'Travel', 'Food', 'Business', 'Health'];
    foreach ($categories as $category) {
        wp_insert_term($category, 'category');
    }

    for ($i = 0; $i < 100; $i++) {
        $post_date = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');
        $post_id = wp_insert_post([
            'post_title' => $faker->sentence,
            'post_content' => $faker->paragraphs(rand(3, 10), true) . 
                              "\n\n" . $faker->imageUrl(640, 480, null, true) . 
                              "\n\n" . $faker->paragraphs(rand(2, 5), true),
            'post_status' => 'publish',
            'post_author' => $faker->randomElement($users)->ID,
            'post_date' => $post_date,
            'post_date_gmt' => get_gmt_from_date($post_date),
            'post_type' => 'post',
        ]);

        // Set random categories
        $post_categories = $faker->randomElements($categories, rand(1, 3));
        wp_set_post_categories($post_id, array_map('get_cat_ID', $post_categories));

        // Set tags
        $tags = $faker->words(rand(3, 8));
        wp_set_post_tags($post_id, $tags);

        // Generate Comments for each post
        $comment_count = rand(0, 15);
        for ($j = 0; $j < $comment_count; $j++) {
            $comment_date = $faker->dateTimeBetween($post_date)->format('Y-m-d H:i:s');
            wp_insert_comment([
                'comment_post_ID' => $post_id,
                'comment_author' => $faker->name,
                'comment_author_email' => $faker->email,
                'comment_author_url' => $faker->url,
                'comment_content' => $faker->paragraph(rand(1, 3)),
                'comment_date' => $comment_date,
                'comment_approved' => 1,
                'user_id' => rand(0, 1) ? $faker->randomElement($users)->ID : 0, // 50% chance of being a registered user
            ]);
        }
    }

    // Generate Pages
    for ($i = 0; $i < 10; $i++) {
        wp_insert_post([
            'post_title' => $faker->words(3, true),
            'post_content' => $faker->paragraphs(rand(3, 7), true),
            'post_status' => 'publish',
            'post_author' => $faker->randomElement($users)->ID,
            'post_type' => 'page',
        ]);
    }
}
