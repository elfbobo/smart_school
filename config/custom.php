<?php
/**
 * Created by PhpStorm.
 * User: shiwenbin
 * Date: 2018/10/26
 * Time: 15:19
 */
return [
    'perPage' => 20, //默认每页显示条数
    'super_manager' => 'admin', //超级管理员账号
    'actions' => [
        [
            'e_name' => 'index',
            'c_name' => '列表',
            'is_check' => true,
        ],
        [
            'e_name' => 'create',
            'c_name' => '新增',
            'is_check' => true,
        ],
        [
            'e_name' => 'edit',
            'c_name' => '编辑',
            'is_check' => true,
        ],
        [
            'e_name' => 'destroy',
            'c_name' => '删除',
            'is_check' => true,
        ],
        [
            'e_name' => 'show',
            'c_name' => '详情',
            'is_check' => false,
        ],
        [
            'e_name' => 'import',
            'c_name' => '导入',
            'is_check' => false,
        ],
        [
            'e_name' => 'export',
            'c_name' => '导出',
            'is_check' => false,
        ],
        [
            'e_name' => 'print',
            'c_name' => '打印',
            'is_check' => false,
        ],
    ],

    //每种文件类型支持的后缀格式
    'allow_ext' => [
        'image' => [
            'png',
            'jpg',
            'jpeg',
            'gif'
        ],
        'video' => [
            'avi',
            'flv',
            'rmvb',
            'mp4',
            'mov'
        ],
        'audio' => [
            'mp3',
            'ogg',
            'wav',
            'wma'
        ],
        'word' => [
            'doc',
            'docx',
            'txt',
        ],
        'excel' => [
            'xls',
            'xlsx',
            'csv'
        ],
        'ppt' => [
            'pptx',
            'ppt'
        ],
        'pdf' => [
            'pdf'
        ]
    ]
];