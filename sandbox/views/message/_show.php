<?php

$message = $module->message;

?>

<div id='message-series'>
  <div class='title-image'>
    <?php if( $hasVideo = $message->hasVideo() && $url = $message->getVideo()) : ?>
    <a href='<?php echo $message->getVideo() ?>' rel='rokbox' title='<?php echo $message->seriesTitle ?>' class='title' <?php echo $message->tracking ?>>
      <span class='play'></span>
    <?php endif ?>
      <img src='<?php echo $message->seriesImage->url ?>' alt='<?php echo $message->seriesTitle ?>'>
    <?php if( $hasVideo && $url ) : ?>
    </a>
    <?php endif ?>
  </div>

  <div class='media-table'>
    <div class='title-text'>
      <h2><?php echo $message->seriesTitle ?></h2>
      <?php if( $message->isCurrent || $message->isComing ) : ?>
      <h3><?php if( $message->isCurrent ) : ?>Current Series<?php elseif( $message->isComing ): ?>Coming Series<?php endif ?></h3>
      <?php endif ?>
    </div>

    <table border='0'>
      <tr>
        <th>Title</th>
        <th>Media</th>
      </tr>
      <?php if( count( $message->messages ) > 0 ): ?>
      <?php foreach( $message->messages as $m ): ?>
      <tr class='message-details'>
        <td class='info'><span class='title'><?php echo $m->title ?></span><br>
          by <?php echo $m->speaker ?>
          <time><?php echo $m->getDate() ?></time>
        </td>
        <?php if( count( $m->media ) > 0 ) : ?>
        <td class='media'>
          <ul class='media-tools'>
            <?php foreach( $m->media as $i ) : ?>
            <?php $tracking = sprintf(GA_PAGEVIEW_CODE, $message->seriesTitle . $m->getDate() .  "/" . strtolower( $i->type ) . ".html"); ?>
            <li class='<?php echo $i->type ?>'>
              <?php if( strtolower( $i->type ) == 'watch' ) : ?>
              <a href='<?php echo $i->url ?>' rel='rokbox' title='<?php echo ucfirst( $i->type ) ?>' <?php echo $tracking ?>>Watch</a>
              <?php endif ?>
              <?php if( strtolower( $i->type ) == 'listen' ) : ?>
              <a href='<?php echo $i->url ?>' rel='rokbox' title='<?php echo ucfirst( $i->type ) ?>' <?php echo $tracking ?>>Listen</a>
              <?php endif ?>
              <?php if( strtolower( $i->type ) == 'download' ) : ?>
              <a href='<?php echo $i->url ?>' alt='Download' title='<?php echo ucfirst( $i->type ) ?>' <?php echo $tracking ?>>Download</a>
              <?php endif ?>
            </li>
            <?php endforeach ?>
          </ul>
        </td>
        <?php endif ?>
      </tr>
      <?php endforeach ?>
      <?php endif ?>
    </table>
  </div><!-- /.media-table -->
</div><!-- /message-series -->
