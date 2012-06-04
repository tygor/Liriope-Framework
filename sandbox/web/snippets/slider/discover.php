<style>
  #discover-slider {
    height: 405px;
    width: 960px;
    overflow: hidden;
    background-image: url( '/snippets/slider/Discover-Class-WebSlider.jpg' );
    background-color: rgb( 192,152,42 );
  }
  #discover-slider p {
    color: #FFF;
    margin: 0;
  }
  #discover-slider div {
    border-radius: 0px;
    box-shadow: none;
  }
  #discover-slider .sticker {
    position: absolute;
    bottom: -20px;
    right: -20px;
    width: 230px;
    height: 150px;
    overflow: hidden;
    background-image: url( '/snippets/slider/Discover-sticker.png' );
    text-align: center;
    padding-top: 80px;

    -webkit-transform: rotate(10deg);
    -moz-transform: rotate(10deg);
    -o-transform: rotate(10deg);
    -ms-transform: rotate(10deg);
  }
  #discover-slider .sticker .date {
    font-weight: bold;
    font-size: 30px;
  }
  #discover-slider .sticker .time {
    font-weight: normal;
    font-size: 18px;
  }
  #discover-slider .text {
    position: absolute;
    left: 400px;
    top: 225px;
  }
  #discover-slider #headline {
    font-weight: bold;
    font-size: 30px;
  }
  #discover-slider #footnote {
    font-weight: normal;
    font-size: 18px;
  }
</style>

<div id="discover-slider" class="content">
  <div class="sticker">
    <p class="date">June 10</p>
    <p class="time">4:00&ndash;5:30 PM</p>
  </div>
  <div class="text">
    <p id="headline">Learn about NRHC!</p>
    <p id="footnote">Childcare provided.</p>
    <p class="readmore"><a href="<?php echo url( '/news/discover_2012-04-30' ) ?>">Read more&hellip;</a></p>
  </div>
</div>
