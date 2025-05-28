<?php

namespace App\Http\Controllers;

use App\Actions\SaveUserAction;
use App\Http\Requests\CreateOrUpdateUserRequest;
use App\Models\School;
use App\Models\User;
use App\Transformers\SchoolTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search", "role"]);
        $users = User::filter($filters)->latest()->paginate(30)->withQueryString();
        $usersData = fractal($users, new UserTransformer())->toArray();

        return Inertia::render(
            'Users/Index',
            [
                "users" => $usersData,
                "title" => "Users Management",
                "roles" => User::ROLES,
                "filters" => $filters,
            ]
        );
    }

    public function create(): Response
    {
        $schools = School::all();
        $schoolData = fractal($schools, new SchoolTransformer())->toArray()['data'];
        return Inertia::render(
            'Users/Create',
            [
                "roles" => User::ROLES,
                "title" => "Add User",
                "user_model" => new User(),
                "schools" => $schoolData,
            ]
        );
    }

    public function store(CreateOrUpdateUserRequest $request, SaveUserAction $saveUserAction): RedirectResponse
    {
        $user = new User();
        $user = $saveUserAction->execute($user, $request->validated());

        return redirect()->route("dashboard.users.show", ["user" => $user])->with(
            "success",
            "user {$user->name} has been create!"
        );
    }

    public function show(User $user): Response
    {
        $user = User::where("id", $user->id)->first();
        $userData = fractal($user, new UserTransformer())->toArray();
        return Inertia::render('Users/Show', ["userModel" => $userData, "title" => "View : {$user->name}"]);
    }

    public function edit(User $user): Response
    {
        $schools = School::all();
        $schoolData = fractal($schools, new SchoolTransformer())->toArray()['data'];
        $roles = User::ROLES;
        $user = User::where("id", "$user->id")->first();
        $user = $user->load('roles');
        $userData = fractal($user, new UserTransformer())->toArray();
        return Inertia::render(
            'Users/Edit',
            [
                "roles" => $roles,
                "title" => "Edit {$user->name}",
                "userModel" => $userData,
                "schools" => $schoolData,
            ]
        );
    }

    public function update(
        CreateOrUpdateUserRequest $request,
        User $user,
        SaveUserAction $saveUserAction
    ): RedirectResponse {
        $request->validated();
        $user = User::where("id", "$user->id")->first();
        $user = $saveUserAction->execute($user, $request->validated());

        return redirect()->route("dashboard.users.show", ["user" => $user])->with(
            "success",
            "user {$user->name} has been update!"
        );
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->delete()) {
            return redirect()->route("dashboard.users.index")->with("success", "user {$user->name} has been destroy!");
        } else {
            return redirect()->route("dashboard.users.index")->with("error", "Can't create user {$user->name}");
        }
    }


}
