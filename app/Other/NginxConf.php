<?php

namespace App\Other;

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 25-04-2020
 * Time: 16:15
 */
class NginxConf
{

    static public function create() {

        $ip = '10.128.15.197';
        $items = [
//            [
//                'app' => 'mqa-demo',
//                'server' => 'sageite',
//                'url' => 'mqa-demo.sageite.com',
//                'port' => '8010',
//            ], [
//                'app' => 'mqa-jcf',
//                'server' => 'sageite',
//                'url' => 'mqa-jcf.sageite.com',
//                'port' => '8011',
//            ], [
//                'app' => 'mqa-gmf',
//                'server' => 'sageite',
//                'url' => 'mqa-gmf.sageite.com',
//                'port' => '8012',
//            ], [
//                'app' => 'mqa-hga',
//                'server' => 'sageite',
//                'url' => 'mqa-hga.sageite.com',
//                'port' => '8013',
//            ], [
//                'app' => 'mqa-fig',
//                'server' => 'sageite',
//                'url' => 'mqa-fig.sageite.com',
//                'port' => '8014',
//            ],

            [
                'app' => 'oakwood',
                'server' => 'giftingnetwork',
                'url' => 'oakwood.giftingnetwork.org',
                'port' => '8231',
            ],
//            [
//                'app' => 'uat-jcf',
//                'server' => 'giftingnetwork',
//                'url' => 'uat-jcf.giftingnetwork.org',
//                'port' => '8210',
//            ],
//            [
//                'app' => 'uat-gmf',
//                'server' => 'giftingnetwork',
//                'url' => 'uat-gmf.giftingnetwork.org',
//                'port' => '8211',
//            ],
//            [
//                'app' => 'uat-hga',
//                'server' => 'giftingnetwork',
//                'url' => 'uat-hga.giftingnetwork.org',
//                'port' => '8220',
//            ],
            [
                'app' => 'uat-fig',
                'server' => 'giftingnetwork',
                'url' => 'uat-fig.giftingnetwork.org',
                'port' => '8232',
            ]
        ];


        $cmd = '';
        $cmdFile = './nginx-conf/cmd.txt';
        $response = [];
        foreach($items as $item) {

            $server = $item['server'];
            $app = $item['app'];
            $port = $item['port'];
            $publicUrl = $item['url'];

            $file = './nginx-conf/' . $server . '-' . $app . '.conf';

            $template = <<< EOF
server {
    listen          80;
    listen          [::]:80;
    server_name     $publicUrl;

    access_log      /var/log/nginx/$server/$app/access.log;
    error_log       /var/log/nginx/$server/$app/error.log;

    location / {
        proxy_pass              http://$ip:$port/;
        proxy_redirect          http://$ip:$port/ https://$publicUrl/;
        proxy_redirect          http://$publicUrl:$port/ https://$publicUrl/;

        proxy_set_header        Host             \$host;
        proxy_set_header        X-Real-IP        \$remote_addr;
        proxy_set_header        X-Forwarded-For  \$proxy_add_x_forwarded_for;

        proxy_set_header        X-Forwarded-Proto  https;
        #proxy_set_header        X-Forwarded-Host  \$server_name;
        #proxy_set_header        X-VerifiedViaNginx yes;
    }
}
EOF;

            \Illuminate\Support\Facades\File::put($file, $template);
            $response[] = $template;

            $cmd .= 'mkdir -p /var/log/nginx/' . $server . '/' . $app . '; ';
        }

        \Illuminate\Support\Facades\File::put($cmdFile, $cmd);

        return ['conf' => $response, 'cmd' => $cmd];

    }
}
