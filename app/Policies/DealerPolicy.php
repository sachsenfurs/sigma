<?php

namespace App\Policies;

use App\Enums\Approval;
use App\Models\Ddas\Dealer;
use App\Models\User;
use App\Settings\DealerSettings;

class DealerPolicy
{
    /**
     * Overrides
     */

    public function before(User $user): bool|null {
        if($user->permissions()->contains('manage_dealers_den'))
            return true;

        return null;
    }


    /**
     * Helper functions
     */

    private function isWithinDeadline(): bool {
        return app(DealerSettings::class)->signup_deadline->isAfter(now());
    }

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, Dealer $dealer): bool {
        return $dealer->user_id === $user->id;
    }

    public function create(User $user): bool {
        if(!$this->isWithinDeadline())
            return false;

        return $user->dealers()->count() == 0;
    }

    public function update(User $user, Dealer $dealer): bool {
        return $dealer->user_id === $user->id && $dealer->approval != Approval::APPROVED;
    }

    public function delete(User $user, Dealer $dealer): bool {
        return false;
    }

    public function restore(User $user, Dealer $dealer): bool {
        return false;
    }

    public function forceDelete(User $user, Dealer $dealer): bool {
        return false;
    }

    public function associate(User $user): bool {
        return false;
    }

    public function attach(User $user): bool {
        return false;
    }

    public function deleteAny(User $user): bool {
        return false;
    }

    public function detach(User $user, Dealer $dealer): bool {
        return false;
    }

    public function detachAny(User $user): bool {
        return false;
    }

    public function disassociate(User $user, Dealer $dealer): bool {
        return false;
    }

    public function disassociateAny(User $user): bool {
        return false;
    }

    public function forceDeleteAny(User $user): bool {
        return false;
    }

    public function reorder(User $user): bool {
        return false;
    }

    public function replicate(User $user, Dealer $dealer): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
