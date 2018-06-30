<?php

return [
    'apikey' => "YJStGE4zRC5uFNMU",
    'add' => [
        'filename' => 'api_add.php',
        'message' => 'загрузки',
        'get' => [
            'IBLOCK' => ['type' => 'int', 'require' => true],
            'STEP' => ['type' => 'int', 'require' => true],
            'COUNT' => ['type' => 'int', 'require' => true],
            'PROPERTY' => ['type' => 'string', 'require' => false],
            'current' => ['type' => 'int', 'require' => false],
        ]
    ],
    'delete' => [
        'filename' => 'api_delete.php',
        'message' => 'удаления',
        'get' => [
            'IBLOCK' => ['type' => 'int', 'require' => false],
            'STEP' => ['type' => 'int', 'require' => false],
            'COUNT' => ['type' => 'int', 'require' => false],
            'PROPERTY' => ['type' => 'string', 'require' => false],
            'current' => ['type' => 'int', 'require' => false],
            'element_id' => ['type' => 'int', 'require' => false],
            'iblock_ids' => ['type' => 'list', 'require' => false],
            'process' => ['type' => 'string', 'require' => false],
        ]
    ]
];