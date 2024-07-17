<?php
namespace App\Policies;

// use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Entry $entry)
    {
        return $user->can('read entry');
    }

    public function create(User $user)
    {
        return $user->can('create entry');
    }

    public function update(User $user, Entry $entry)
    {
        if ($user->can('update entry')) {
            return true;
        }

        if ($user->can('update own entry') && $entry->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Entry $entry)
    {
        if ($user->can('delete entry')) {
            return true;
        }

        if ($user->can('delete own entry') && $entry->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
