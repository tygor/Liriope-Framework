<?php foreach( $page->items() as $item ): ?>
<item>
  <title><?php   echo $item->title() ?></title>
  <description><![CDATA[<?php echo html($item->description()) ?>]]></description>
  <guid><?php    echo $item->url() ?></guid>
  <link><?php    echo $item->url() ?></link>
  <pubDate><?php echo date( 'r', $item->time()) ?></pubDate>
</item>
<?php endforeach ?>
