<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach($locations as $loc) :?>
    <sitemap><loc><?= $loc ?></loc></sitemap>
    <?php endforeach ?>
</sitemapindex>
