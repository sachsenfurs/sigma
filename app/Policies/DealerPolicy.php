<?php

namespace App\Policies;

use App\Models\Ddas\Dealer;
use App\Models\Ddas\Enums\Approval;
use App\Models\User;

class DealerPolicy
{
    public function viewAny(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function view(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den') || $dealer->user_id === $user->id;
    }

    public function create(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den') || $user->dealers()->count() == 0;
    }

    public function update(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den') || ($dealer->user_id === $user->id && $dealer->approval != Approval::APPROVED);
    }

    public function delete(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function restore(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function forceDelete(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function associate(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function attach(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function deleteAny(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function detach(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function detachAny(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function disassociate(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function disassociateAny(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function forceDeleteAny(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function reorder(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function replicate(User $user, Dealer $dealer): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }

    public function restoreAny(User $user): bool {
        return $user->permissions()->contains('manage_dealers_den');
    }
}
