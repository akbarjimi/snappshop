<?php

namespace App\Http\Controllers;

use App\Http\Resources\Top3UsersResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class Top3UsersController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var Collection $top3 */
        $top3 = User::withCount(['transactions' => function ($query) {
            $query->where('transactions.created_at', '>', now()->subMinutes(10));
        }])
            ->orderBy('transactions_count', 'desc')
            ->take(3)->get();

        /** @var User $user */
        foreach ($top3 as $key => $user) {
            $top3[$key] = $user->loadMissing(['transactions' => function ($q) {
                $q->latest('id')->take(10);
            }]);
        }

        return new Top3UsersResource($top3);
    }
}
