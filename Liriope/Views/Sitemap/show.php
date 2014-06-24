<?php
/**
 * Sitemap/show.php
 * --------------------------------------------------
 * Render the sitemap XML
 */

$xmlTag = '<?xml version="' . $this->get('version') . '" encoding="' . $this->get('encoding') . '"?>' . "\n";

?>
<?php echo $xmlTag; ?>
<urlset xmlns="<?= $this->get('xmlns'); ?>">
    <url>
        <loc>http://www.example.com/</loc>
        <lastmod>2005-01-01</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
</urlset> 
