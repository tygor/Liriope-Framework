<?php
/**
 * Hidden.php
 * a hidden page that has no controller
 * but does have this view file
 */
?>
<?php snippet( 'default/header'); ?>

<article>
  <h1>Heading 1</h1>
  <h2>Heading 2</h2>
  <h3>Heading 3</h3>
  <h4>Heading 4</h4>
  <h5>Heading 5</h5>
  <h6>Heading 6</h6>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam eget vestibulum eros. Sed quis metus in turpis vehicula adipiscing. In metus est, vulputate quis euismod ac, pretium non est. Aliquam varius, felis eget aliquet suscipit, mi orci condimentum ipsum, laoreet laoreet magna dui id lorem. Duis feugiat commodo leo ut condimentum. In ante neque, scelerisque sit amet commodo ut, egestas in odio. In fermentum nulla sit amet ante placerat eleifend. Pellentesque vel posuere mauris.</p>
  <ul>
    <li>Item
      <ul>
        <li>Item</li>
        <li>Item</li>
        <li>Item
          <ul>
            <li>Item</li>
            <li>Item
              <ul>
                <li>Item</li>
              </ul>
            </li>
            <li>Item</li>
            <li>Item</li>
          </ul>
        </li>
        <li>Item</li>
      </ul>
    </li>
    <li>Item</li>
    <li>Item</li>
    <li>Item</li>
  </ul>
  <p>Lorem ipsum dolor sit amet, <del datetime="2012-01-03T19:44:00Z" cite="google.com">consectetur</del> <ins datetime="2012-01-03T19:44:00Z" cite="google.com">adipiscing</ins> elit. Nam eget <abbr title="A nonsense word">vestibulum</abbr> eros. Sed quis metus in turpis vehicula adipiscing. In metus est, vulputate quis euismod ac, pretium non est. Aliquam varius, felis eget aliquet suscipit, mi orci condimentum ipsum, laoreet laoreet magna dui id lorem. Duis feugiat commodo leo ut condimentum. In ante neque, scelerisque sit amet commodo ut, egestas in odio. In fermentum nulla sit amet ante placerat eleifend. Pellentesque vel posuere mauris.</p>
  <dl>
    <dt>Definition List Title</dt>
    <dd>And the definition goes here</dd>
    <dt>Definition List Title</dt>
    <dd>And the definition goes here</dd>
    <dt>Definition List Title</dt>
    <dd>And the definition goes here</dd>
  </dl>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam eget vestibulum eros. Sed quis metus in turpis vehicula adipiscing. In metus est, vulputate quis euismod ac, pretium non est. Aliquam varius, felis eget aliquet suscipit, mi orci condimentum ipsum, laoreet laoreet magna dui id lorem. Duis feugiat commodo leo ut condimentum. In ante neque, scelerisque sit amet commodo ut, egestas in odio. In fermentum nulla sit amet ante placerat eleifend. Pellentesque vel posuere mauris.</p>
  <ol>
    <li>Item
      <ol>
        <li>Item</li>
        <li>Item</li>
        <li>Item
          <ol>
            <li>Item</li>
            <li>Item
              <ol>
                <li>Item</li>
              </ol>
            </li>
            <li>Item</li>
            <li>Item</li>
          </ol>
        </li>
        <li>Item</li>
      </ol>
    </li>
    <li>Item</li>
    <li>Item</li>
    <li>Item</li>
  </ol>
</article>

<?php snippet( 'default/footer'); ?>
