<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GameLevelingOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameLevelingOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the gameLevelingOrder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GameLevelingOrder  $gameLevelingOrder
     * @return mixed
     */
    public function view(User $user, GameLevelingOrder $gameLevelingOrder)
    {
        return in_array($user->parent_id, [$gameLevelingOrder->parent_user_id, $gameLevelingOrder->take_parent_user_id]);
    }

    /**
     * Determine whether the user can create gameLevelingOrders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the gameLevelingOrder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GameLevelingOrder  $gameLevelingOrder
     * @return mixed
     */
    public function update(User $user, GameLevelingOrder $gameLevelingOrder)
    {
        //
    }

    /**
     * Determine whether the user can delete the gameLevelingOrder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GameLevelingOrder  $gameLevelingOrder
     * @return mixed
     */
    public function delete(User $user, GameLevelingOrder $gameLevelingOrder)
    {
        //
    }
}
