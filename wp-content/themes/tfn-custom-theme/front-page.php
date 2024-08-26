<?php get_header(); ?>

<main id="main" class="site-main">
    <section class="hero bg-light py-5">
        <div class="container">
            <h1 class="display-4 typing-animation">Three First Names & Associates</h1>
            <p class="lead typing-animation-delayed">Your trusted partners in professional services</p>
        </div>
    </section>

    <?php
    // Function to get page content by title
    function get_page_content_by_title($title) {
        $query = new WP_Query(
            array(
                'post_type' => 'page',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'title' => $title
            )
        );
        
        if ($query->have_posts()) {
            $query->the_post();
            $content = get_the_content();
            wp_reset_postdata();
            return apply_filters('the_content', $content);
        }
        
        return null;
    }
    ?>

    <section class="about py-5">
        <div class="container">
            <h2 class="mb-4">About Us</h2>
            <?php
            $about_content = get_page_content_by_title('About Us');
            if ($about_content) {
                echo $about_content;
            } else {
                echo '<p>About Us content not found.</p>';
            }
            ?>
        </div>
    </section>

    <section class="services py-5 bg-light">
        <div class="container">
            <h2 class="mb-4">Our Services</h2>
            <?php
            $services_content = get_page_content_by_title('Services');
            if ($services_content) {
                echo $services_content;
            } else {
                echo '<p>Services content not found.</p>';
            }
            ?>
        </div>
    </section>

    <section class="contact py-5">
        <div class="container">
            <h2 class="mb-4">Contact Us</h2>
            <?php
            $contact_content = get_page_content_by_title('Contact');
            if ($contact_content) {
                echo $contact_content;
            } else {
                echo '<p>Contact content not found.</p>';
            }
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
