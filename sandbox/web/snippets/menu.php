<?php
// --------------------------------------------------
// Menu snippet
// --------------------------------------------------
// simply the UL of links, used in the header and footer
// within their individual NAV tags for styling
//

// configure the menu in an array
$menu = new menu();
$menu->addChild( 'Projects', 'projects' )->addChild( 'About Us', 'about-us' );
$menu->addChild( 'Features', 'features' )->addChild( 'Blog', 'blog' )->addChild( 'Docs', 'docs' );
$menu->find( 'About Us' )->addChild( 'Vision', 'vision' );

?>

<ul id="global-menu" class="menu">
  <li>
    <a href="<?php echo url() ?>" class="home<?php echo ($page->isActive( 'home' )) ? ' active' : '' ?>">
      <img src="<?php echo theme::$folder . '/images/home.png' ?>" alt="Home">
      <span class="image-title">Home</span>
    </a>
  </li>
  <?php foreach( $menu->getChildren() as $m ): ?>
  <li<?php echo $m->hasChildren() ? ' class="deeper"' : '' ?>>
    <a href="<?php echo $m->url ?>" <?php echo ($page->isActive( $m->url )) ? ' class="active"' : '' ?>><?php echo $m->label ?></a>
    <?php if( $m->hasChildren() ): ?>
    <ul style="display: none;">
      <?php foreach( $m as $n ): ?>
      <li<?php echo $n->hasChildren() ? ' class="deeper"' : '' ?>>
        <a href="<?php echo $m->url . '/' . $n->url ?>" <?php echo ($page->isActive( $n->url )) ? ' class="active"' : '' ?>><?php echo $n->label ?></a>
      </li>
      <?php endforeach ?>
     </ul>
     <?php endif ?>
  </li>
  <?php endforeach ?>
</ul>
