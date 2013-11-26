<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php wp_title(''); ?></title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <div class="wrapper">
            <?php echo $content_for_layout; ?>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>