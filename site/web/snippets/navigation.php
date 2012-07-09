<?php
// --------------------------------------------------
// Navigation snippet
// --------------------------------------------------
// simply the UL of links, used in the header and footer
// within their individual NAV tags for styling
//

// configure the menu in an array
global $menu;

$menu = new menu();
$menu
  ->addChild( 'Projects',  'projects' )
  ->addChild( 'About Us',  'about-us' )
  ->addChild( 'Features',  'features' )
  ->addChild( 'Blog',      'blog' )
  ->addChild( 'Docs',      'docs' )
  ;
$menu
  ->find( 'about-us' )
  ->addChild( 'Sub page',  'about-us/sub-page' )
  ->addChild( 'Level 3 page', 'about-us/sub-page/lvl3' )
  ;
$menu
  ->find( 'features' )
  ->addChild( 'Slider',  'features/slider' )
  ->addChild( 'Tumblr',  'features/tumblr' )
  ->addChild( 'Gallery', 'gallery' )
  ;

if( $page->root() !== 'home' && !$menu->findActive ) $menu->findDeep( $page->root() )->setActive();

?>

<ul>
  <?php foreach( $menu->getChildren() as $m ): ?>
  <li<?php echo $m->hasChildren() ? ' class="deeper"' : '' ?>>
    <a href="<?php echo url( $m->url ) ?>" <?php echo ($m->isActive()) ? ' class="active"' : '' ?>><?php echo html( $m->label ) ?></a>
    <?php if( $m->hasChildren() ): ?>
    <ul>
      <?php foreach( $m->getChildren() as $n ): ?>
      <li<?php echo $n->hasChildren() ? ' class="deeper"' : '' ?>>
        <a href="<?php echo url( $n->url ) ?>" <?php echo ($n->isActive()) ? ' class="active"' : '' ?>><?php echo html($n->label) ?></a>
        <?php if( $n->hasChildren() ): ?>
        <ul>
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
