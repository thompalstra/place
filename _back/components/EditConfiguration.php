<?php
namespace back\components;

class EditConfiguration{
    public static function options(){
        return [
            'toolbar' => [
                'list' => 'list',
                'font-size' => [
                    '10px' => '10px',
                    '15px' => '15px',
                ]
            ],
            'type' => 'html',
        ];
    }
}

?>
