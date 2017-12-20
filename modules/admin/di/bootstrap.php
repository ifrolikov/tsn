<?php

require_once __DIR__ . '/DiAliases.php';
\Yii::$container->setDefinitions(require __DIR__ . '/definitions.php');
\Yii::$container->setSingletons(require __DIR__ . '/singletons.php');
