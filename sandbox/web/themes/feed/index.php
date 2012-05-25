<?php echo "<?xml version=\"1.0\" ?>\n" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?php echo $site->title() ?></title>
    <description><?php echo $site->description() ?></description>
    <lastBuildDate><?php echo date('r', time()) ?></lastBuildDate>
    <link><?php echo $site->url() ?></link>
    <atom:link href="<?php echo $site->url() ?>" rel="self" type="application/rss+xml" />
    <?php echo $page->render() ?>
  </channel>
</rss>
