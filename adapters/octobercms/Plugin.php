<?php
namespace AGNSTK\Adapters\OctoberCMS;

use System\Classes\PluginBase;

class Plugin extends PluginBase {
    public function registerComponents() {
        return [
            'MembershipComponent' => 'AGNSTK\Adapters\OctoberCMS\OctoberAdapter'
        ];
    }
}
