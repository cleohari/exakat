<?php

$fn1 = fn ($a1, $b) =>  1;
$fn2 = fn ($a2, $b) =>  2;

$fn1(1, 2);
$fn1(2, 2);
$fn1(3, 2);

$fn2(1, 2);
$fn2(2, 2);
$fn2(3, 3);
