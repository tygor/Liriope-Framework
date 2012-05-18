<?php
// --------------------------------------------------
// Menu snippet
// --------------------------------------------------
// simply the UL of links, used in the header and footer
// within their individual NAV tags for styling
//

// configure the menu in an array
global $menu;
$menu = new menu();
$menu
  ->addChild( 'Projects', 'projects' )
  ->addChild( 'About Us', 'about-us' )
  ->addChild( 'Features', 'features' )
  ->addChild( 'Docs', 'docs' )
  ->addChild( 'Blog', 'blog' );
$menu
  ->find( 'about-us' )
  ->addChild( 'Dummy', 'about-us/dummy1' )
  ->addChild( 'Dummy', 'about-us/dummy2' )
  ->addChild( 'Dummy', 'about-us/dummy3' )
  ->addChild( 'Vision', 'about-us/vision' );
$menu
  ->find( 'about-us' )
  ->find( 'about-us/vision' )
  ->addChild( 'Glasses', 'about-us/vision/glasses' );

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
    <a href="<?php echo url( $m->url ) ?>" <?php echo ($m->isActive()) ? ' class="active"' : '' ?>><?php echo $m->label ?></a>
    <?php if( $m->hasChildren() ): ?>
    <ul style="display: none;">
      <?php foreach( $m->getChildren() as $n ): ?>
      <li<?php echo $n->hasChildren() ? ' class="deeper"' : '' ?>>
        <a href="<?php echo url( $n->url ) ?>" <?php echo ($n->isActive()) ? ' class="active"' : '' ?>><?php echo $n->label ?></a>
        <?php if( $n->hasChildren() ): ?>
        <ul style="display: none;">
          <?php foreach( $n->getChildren() as $o ): ?>
          <li<?php echo $o->hasChildren() ? ' class="deeper"' : '' ?>>
            <a href="<?php echo url( $o->url ) ?>" <?php echo ($o->isActive()) ? ' class="active"' : '' ?>><?php echo $o->label ?></a>
          </li>
          <?php endforeach ?>
         </ul>
         <?php endif ?>
      </li>
      <?php endforeach ?>
     </ul>
     <?php endif ?>
  </li>
  <?php endforeach ?>
</ul>
