<?php

namespace App\Policies;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookmarkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bookmark $bookmark): bool
    {
        // User can view their own bookmarks or public bookmarks
        return $bookmark->user_id === $user->id || !$bookmark->private;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bookmark $bookmark): bool
    {
        return $bookmark->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bookmark $bookmark): bool
    {
        return $bookmark->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bookmark $bookmark): bool
    {
        return $bookmark->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bookmark $bookmark): bool
    {
        return $bookmark->user_id === $user->id;
    }
}
