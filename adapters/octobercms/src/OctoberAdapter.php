<?php
namespace AGNSTK\Adapters\OctoberCMS;

use AGNSTK\Core\Services\MembershipService;

class OctoberAdapter {
    public function getCurrentUserId() {
        return \Auth::id();
    }

    public function getMembership() {
        $service = new MembershipService();
        return $service->getUserMembership($this->getCurrentUserId());
    }
}
