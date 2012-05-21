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
  ->addChild( 'About NRHC',  'about-us' )
  ->addChild( 'New Here?', 'new-here' )
  ->addChild( 'Connect',   'connect' )
  ->addChild( 'Media',     'media' )
  ->addChild( 'Give',      'give' )
  ;
$menu
  ->find( 'about-us' )
  ->addChild( 'Our Story',       'about-us/our-story' )
  ->addChild( 'Beliefs',         'about-us/beliefs' )
  ->addChild( 'Staff & Contact', 'about-us/contact' )
  ->addChild( 'News',            'news' )
  ;
$menu
  ->find( 'new-here' )
  ->addChild( 'Times & Directions',       'new-here/times-directions' )
  ->addChild( 'What to Expect',           'new-here/what-to-expect' )
  ->addChild( 'Kids & Family Ministries', 'new-here/family-ministries' )
  ->addChild( 'Current Series',           'new-here/current-series' )
  ;
$menu
  ->find( 'connect' )
  ->addChild( 'Discover & Partner', 'connect/discover-partner' )
  ->addChild( 'Serve',              'connect/serve' )
  ->addChild( 'Groups',             'connect/groups' )
  ->addChild( 'Baptism',            'connect/baptism' )
  ->addChild( 'NetWORK',            'connect/network' )
  ;
$menu
  ->find( 'new-here' )
  ->find( 'new-here/family-ministries' )
  ->addChild( 'SafariLand', 'new-here/family-ministries/safariland' )
  ->addChild( 'KidsCamp',   'new-here/family-ministries/kidscamp' )
  ->addChild( 'Students',   'new-here/family-ministries/students' )
  ;
$menu
  ->find( 'connect' )
  ->find( 'connect/serve' )
  ->addChild( 'Serve @ NRHC',      'connect/serve/at-nrhc' )
  ->addChild( 'Serve our city',    'connect/serve/our-city' )
  ->addChild( 'Serve the nations', 'connect/serve/the-nations' )
  ;

?>

<ul id="global-menu" class="menu">
  <li>
    <a href="<?php echo url() ?>" class="home<?php echo ($page->isActive( 'home' )) ? ' active' : '' ?>">
      <img src="/<?php echo theme::$folder . '/images/home.png' ?>" alt="Home">
      <span class="image-title">Home</span>
    </a>
  </li>
  <?php foreach( $menu->getChildren() as $m ): ?>
  <li<?php echo $m->hasChildren() ? ' class="deeper"' : '' ?>>
    <a href="<?php echo url( $m->url ) ?>" <?php echo ($m->isActive()) ? ' class="active"' : '' ?>><?php echo html( $m->label ) ?></a>
    <?php if( $m->hasChildren() ): ?>
    <ul style="display: none;">
      <?php foreach( $m->getChildren() as $n ): ?>
      <li<?php echo $n->hasChildren() ? ' class="deeper"' : '' ?>>
        <a href="<?php echo url( $n->url ) ?>" <?php echo ($n->isActive()) ? ' class="active"' : '' ?>><?php echo html($n->label) ?></a>
        <?php if( $n->hasChildren() ): ?>
        <ul style="display: none;">
          <?php foreach( $n->getChildren() as $o ): ?>
          <li<?php echo $o->hasChildren() ? ' class="deeper"' : '' ?>>
            <a href="<?php echo url( $o->url ) ?>" <?php echo ($o->isActive()) ? ' class="active"' : '' ?>><?php echo html( $o->label ) ?></a>
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
