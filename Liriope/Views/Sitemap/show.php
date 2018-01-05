<?php
/**
 * Sitemap/show.php
 * --------------------------------------------------
 * Render the sitemap XML
 */

$urls = $this->get('urls');
$xmlTag = '<?xml version="' . $this->get('version') . '" encoding="' . $this->get('encoding') . '"?>' . "\n";

?>
<?php echo $xmlTag; ?>
<urlset xmlns="<?= $this->get('xmlns'); ?>">
<?php foreach($urls as $url): ?>
    <url>
        <loc><?php echo $url['loc']; ?></loc>
        <lastmod><?php echo $url['lastmod']; ?></lastmod>
        <changefreq><?php echo $url['changefreq']; ?></changefreq>
        <priority><?php echo $url['priority']; ?></priority>
    </url>
<?php endforeach; ?>
</urlset> 
