<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'exiftool' => [
            'driver' => 'local',
            'root' => storage_path('app/public/exiftool'),
            'url' => env('APP_URL').'/storage/exiftool',
            'visibility' => 'public',
        ],

        'File Manager' => [
            'driver' => 'local',
            'root' => public_path('file-manager'),
            'url' => env('APP_URL').'/file-manager',
        ],
        'KeyStore' => [
            'driver' => 'local',
            'root' => public_path('uploads/keystore'),
            'url' => env('APP_URL').'/uploads/keystore',
        ],
        'Profile' => [
            'driver' => 'local',
            'root' => public_path('uploads/profile'),
            'url' => env('APP_URL').'/uploads/profile',
        ],


//        's3' => [
//            'driver' => 's3',
//            'key' => env('AWS_ACCESS_KEY_ID'),
//            'secret' => env('AWS_SECRET_ACCESS_KEY'),
//            'region' => env('AWS_DEFAULT_REGION'),
//            'bucket' => env('AWS_BUCKET'),
//            'url' => env('AWS_URL'),
//            'endpoint' => env('AWS_ENDPOINT'),
//        ],

//        'ftp' => [
//            'driver'   => 'ftp',
//            'host'     => '123.25.85.247',
//            'username' => 'luongnhoa',
//            'password' => 'zxcv@1234',
//            'passive'  => true,
//            'timeout'  => 30,
//            'port'     => 75,
            //Các thông số mở rộng
            // 'port'     => 21,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
//        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
