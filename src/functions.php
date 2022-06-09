<?php

function createTransformationMatrix($coords, $width, $height)
{
    return [
        0, 0, $coords[0][0], $coords[0][1],
        $width, 0, $coords[1][0], $coords[1][1],
        $width, $height, $coords[2][0], $coords[2][1],
        0, $height, $coords[3][0], $coords[3][1],
    ];
}


function printCoords($coords)
{
    foreach ($coords as $coord) {
        echo sprintf("%d:%d<br>", $coord[0], $coord[1]);
    }
}
