<?php
return [
    'urlClass' => [
        'class' => '\common\components\UrlHelper',
        [
            '/blog/<category:(.*)>/<item:(.*)>' => '/blog/view',
            '/blog/<category:(.*)>' => '/blog/index',
            '/blog' => '/blog/all',
            '/chatroom/<channel:(.*)>' => '/chat/index',
        ],
    ],
    'defaultRoute' => '/default/index',
    'errorRoute' => '/default/error',
    'defaultLayout' => 'main.php',
];
?>
