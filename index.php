<?php

use Elegant\Captcha\Icon\Captcha;
use Elegant\Captcha\Icon\CaptchaBuilder;

require './vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// print_r($_POST['captcha'] ?? '');
// Array ( [0] => 231 [1] => 43 )

$builder = new CaptchaBuilder();
$builder->setIconPack(__DIR__ . '/icons');
$builder->setMainColor('#000');
$builder->setBgColor('#fff');
$builder->addText('Mirror starts: abcdef');
$builder->addText('Mirror ends: ghijkl', true);
$captcha = $builder->build();

print_r($captcha->getPlacement()->getPositions());
echo '<br>';
echo "L: " . $captcha->leastRepeatedIconType();
echo '<br>';
echo "R: " . $captcha->validateCoordinates(175, 37);

$img = $captcha->render();
?>
<html>
    <body>
        <form method="POST">
            <input type="image" name="captcha[]" src="data:image/jpeg;charset=utf-8;base64,<?= base64_encode($img) ?>">
        </form>
    </body>
</html>
