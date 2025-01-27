<?php

use Elegant\Captcha\Icon\CaptchaBuilder;

require './vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$builder = new CaptchaBuilder();
$builder->setIconPack(__DIR__ . '/../resources/icons');
$builder->setMainColor('#000');
$builder->setBgColor('#fff');
$builder->addText('Text on Top');
$builder->addText('Text on Bottom', true);
$captcha = $builder->build();

print_r($captcha->getPlacement()->getPositions());
echo '<br>';

$img = $captcha->render();
?>
<html>
    <body>
        <form method="POST">
            <input type="image" name="captcha[]" src="data:image/jpeg;charset=utf-8;base64,<?= base64_encode($img) ?>">
        </form>
    </body>
</html>
