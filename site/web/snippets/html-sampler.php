<?php

use Liriope\Toolbox\String;

$lorem = <<<LOREM
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ornare justo in tortor sollicitudin eget aliquam purus
euismod. Vivamus posuere volutpat laoreet. Curabitur nunc erat, sollicitudin ut euismod quis, sagittis in ipsum. Sed
tincidunt gravida pharetra. Duis nisl justo, accumsan nec sagittis at, varius tempus felis. Vestibulum porta odio sed
ipsum condimentum auctor. Morbi eros odio, suscipit id rutrum ac, viverra in tellus. Integer adipiscing nisi in magna
hendrerit mattis. Phasellus id mi id turpis eleifend vestibulum. Vestibulum euismod condimentum augue, in blandit neque
posuere at. Vestibulum eu dui nibh. Donec dolor dui, sagittis a bibendum eu, elementum in lectus. Curabitur euismod
cursus justo pellentesque vehicula. Duis ligula urna, consectetur ac imperdiet non, elementum vel tellus. Proin dictum
dui id velit ornare non ullamcorper purus venenatis.  Vivamus id fermentum sem. Ut varius purus eget purus aliquam
ullamcorper. Etiam eros mauris, gravida nec lobortis non, tincidunt vel risus. Aliquam ut nunc nulla, in pharetra sem.
Praesent a quam pulvinar diam semper hendrerit. Sed condimentum dictum posuere. Ut molestie orci eget elit sollicitudin
posuere.  Suspendisse in quam ac ante viverra placerat. Cras odio metus, ullamcorper quis vulputate auctor, luctus non
leo. Donec quis erat lectus, at semper arcu. Vestibulum vel leo sit amet massa mattis consectetur. Proin rhoncus justo
in nibh pulvinar scelerisque. Curabitur id neque sit amet orci mattis pretium sed ut odio. Nulla metus mauris, laoreet
et laoreet in, fringilla sed velit. In eu leo sed felis tempor aliquet. Mauris dictum sodales magna, et tristique nulla
mattis in. Nullam pulvinar neque enim, ut rutrum orci. Suspendisse semper nunc in est sodales ac tempor sapien
scelerisque. Maecenas quis nunc nisi, nec ultrices dolor. Vivamus condimentum, tellus quis pellentesque scelerisque,
sapien magna elementum ligula, ac pulvinar erat purus nec lorem. Aliquam at purus id mi pellentesque eleifend vitae a
quam.  Nulla facilisi. Cras nec tortor eros. Nulla sed nulla purus, at accumsan risus. Vivamus luctus sollicitudin erat
non porta. Vestibulum sit amet eros vulputate nibh vehicula tempor vitae id metus. Sed erat libero, ullamcorper sed
tincidunt non, vehicula a erat. Vestibulum ac tortor id purus lobortis imperdiet. Proin rutrum consequat ligula, id
ultricies tellus faucibus at. Fusce pellentesque justo ac mauris cursus tempus. Fusce ac gravida velit. Lorem ipsum
dolor sit amet, consectetur adipiscing elit. Integer eget nulla pellentesque diam tincidunt sagittis. Maecenas diam
sapien, faucibus dictum ultricies ac, consequat sit amet sapien. Curabitur malesuada adipiscing mattis. Vestibulum at
tortor in purus rhoncus faucibus.  Maecenas et nisl et lectus mattis aliquam sit amet euismod lacus. Cras sapien sapien,
ultricies id gravida consequat, dictum commodo massa. Nunc et nulla tortor. Nam dignissim nisl et lorem varius eget
interdum mauris consequat. Proin a magna in nulla rhoncus facilisis ut a nisl. Aliquam diam sapien, viverra non
adipiscing nec, bibendum id risus. Sed vitae diam massa. Nulla lacus nunc, commodo quis aliquet at, rhoncus vel augue.
In hac habitasse platea dictumst. Etiam quis leo arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
posuere cubilia Curae; In felis justo, accumsan et porttitor ut, faucibus eu quam. 
LOREM;
$lorem = new String($lorem);

?>

<?php for($i=1; $i<=3; $i++ ) : ?>
<h<?= $i ?>>Heading <?= $i ?></h<?= $i ?>>
<?php endfor ?>

<p><?= $lorem->truncate(500,0) ?></p>

<ul>
  <li>List Item</li>
  <li>List Item
    <ul>
      <li>List Item</li>
      <li>List Item</li>
    </ul>
  </li>
  <li>List Item</li>
  <li>List Item</li>
</ul>

<?php for($i=4; $i<=6; $i++ ) : ?>
<h<?= $i ?>>Heading <?= $i ?></h<?= $i ?>>
<?php endfor ?>

<p><?= $lorem->truncate(200,0) ?></p>

<ol>
  <li>List Item</li>
  <li>List Item
    <ol>
      <li>List Item</li>
      <li>List Item</li>
    </ol>
  </li>
  <li>List Item</li>
  <li>List Item</li>
</ol>
