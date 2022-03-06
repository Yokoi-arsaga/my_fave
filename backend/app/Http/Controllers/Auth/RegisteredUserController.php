<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use \Symfony\Component\HttpFoundation\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validation = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:strict,dns,spoof', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()
                ->letters()
                ->mixedCase()
                ->numbers()
            ],
        ]);

        if ($validation->fails()) {
            return response()->json($validation->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json(['name' => Auth::user()->name]);
    }
}
