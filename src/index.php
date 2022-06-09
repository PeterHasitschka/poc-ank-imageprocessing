<?php
include "./functions.php";
ini_set('memory_limit', '1024M');

$defaultSecondary = "https://upload.wikimedia.org/wikipedia/commons/thumb/1/10/Userbox_creeper.svg/1024px-Userbox_creeper.svg.png";

?>



<?php
$coords = [
    [341, 1005],
    [686, 902],
    [682, 1281],
    [305, 1368],
];
?>

<h1>Image Distortion & Overlay - PoC für Projekt ANK</h1>

<p>Tip: Try using another sencondary image by providing its URL in the 'image' paramter</p>


<h2>Base-Image</h2>
<?php
$handle = fopen("./base.png", "r");
$base = new Imagick();
$base->readImageFile($handle);
?>
<img src="data:image/jpg;base64,<?= base64_encode($base->getImageBlob()); ?>" width="400" />





<h2>Corner-Coordinates (Defined)</h2>
<p>Clockwise from top-left</p>
<?php
printCoords($coords);
?>
<div style="position:relative; width: fit-content">
    <img src="data:image/jpg;base64,<?= base64_encode($base->getImageBlob()); ?>" width="400" />

    <?php
    $w = $base->getImageWidth();
    $h = $base->getImageHeight();
    ?>
    <?php foreach ($coords as $coord) : ?>
        <div style="
        position: absolute;
        transform:translate(-50%,-50%);
        border-radius:50%;
        width:10px;
        height:10px;
        background:red;
        left:<?= $coord[0] / $w * 100; ?>%;
        top:<?= $coord[1] / $h * 100; ?>%;"></div>
    <?php endforeach; ?>
</div>





<h2>Secondary Image</h2>
<?php

$secondaryImgUrl = $_GET['image'] ?? $defaultSecondary;
$handle = fopen($secondaryImgUrl, "r");
$secondary = new Imagick();
$secondary->readImageFile($handle);
?>
<img src="data:image/jpg;base64,<?= base64_encode($secondary->getImageBlob()); ?>" width="400" />

<h2>Secondary after scale</h2>
<?php
$secondary->scaleImage($base->getImageWidth(), $base->getImageHeight());
?>
<img src="data:image/jpg;base64,<?= base64_encode($secondary->getImageBlob()); ?>" width="400" />


<h2>Applied Transformation</h2>

<?php

$transformationMatrix = createTransformationMatrix($coords, $secondary->getImageWidth(), $secondary->getImageHeight());

$secondary->setImageFormat('png');
$secondary->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
$secondary->setImageMatte(true);

$secondary->setimagebackgroundcolor(new ImagickPixel('transparent'));
$secondary->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_BACKGROUND);
$secondary->distortImage(Imagick::DISTORTION_BILINEAR, $transformationMatrix, TRUE);
?>
<img src="data:image/jpg;base64,<?= base64_encode($secondary->getImageBlob()); ?>" width="400" style="border: 1px solid red" />




<h2>Composed Image</h2>
<?php
$base->compositeImage($secondary, Imagick::COMPOSITE_SRCOVER, 0, 0);
?>
<img src="data:image/jpg;base64,<?= base64_encode($base->getImageBlob()); ?>" width="400" />