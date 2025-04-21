<?php
namespace App\Repositories;
use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\AuthenticationInterface;
use Illuminate\Support\Facades\Auth;

Class ProfileRepository extends BaseRepository {

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getProfile(){
        return User::with('profile')->where('id', Auth::user()->id)->first();
    }

    public function updateProfile(array $data){

        $user = User::where('id', Auth::user()->id)->first();

        $user->update([
            'name' => $data['name']
        ]);

        if ($user->userDetail) {
            $user->userDetail->update([
                'dob' => $data['dob'],
                'location' => $data['location'],
                'address' => $data['address']
            ]);
        } else {
            // Create content if it doesn't exist
            $user->userDetail()->create([
                'dob' => $data['dob'],
                'location' => $data['location'],
                'address' => $data['address']
            ]);
        }



        return $user;

    }
}
