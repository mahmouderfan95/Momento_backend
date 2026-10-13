<?php
namespace App\Services\Admin;
use App\Enums\StatusEnum;
use App\Exceptions\AuthenticationFailedException;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $credentials) : array
    {
        $user = Admin::query()->where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationFailedException();
        }
        if ($user->status !== StatusEnum::ACTIVE->value) {
            throw new AuthenticationFailedException('هذا الحساب غير مفعّل حاليًا');
        }
        $deviceName = $credentials['device_name'] ?? 'default';
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken(
            $deviceName,
            ['*'],
            now()->addDays(30)
        );
        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }
    public function logout(Admin $user): void
    {
        $currentToken = $user->currentAccessToken();
        $currentToken->delete();
    }
}
