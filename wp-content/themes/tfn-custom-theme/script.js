jQuery(document).ready(function($) {
    // Scroll to top button
    var btn = $('<button>', {
        class: 'btn btn-primary scroll-to-top',
        html: '<i class="fas fa-arrow-up"></i>',
        css: {
            position: 'fixed',
            bottom: '20px',
            right: '20px',
            display: 'none'
        }
    });

    $('body').append(btn);

    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            btn.fadeIn();
        } else {
            btn.fadeOut();
        }
    });

    btn.click(function() {
        $('html, body').animate({scrollTop:0}, '300');
    });

    // Add any other custom JavaScript here
});
