<?php
namespace App\Services\Front;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AuthenticationFailedException;
class AuthService
{
    public function __construct(protected GuestDataMerger $merger){}
    public function register(array $data, ?string $guestToken): array
    {
        $deviceName = $data['device_name'] ?? 'default';
        unset($data['device_name'], $data['password_confirmation']);

        $user = DB::transaction(fn () => User::query()->create($data + ['status' => 'active']));

        $this->safeMerge($user, $guestToken);

        return [
            'user'  => $user,
            'token' => $this->issueToken($user, $deviceName),
        ];
    }
    public function login(array $credentials, ?string $guestToken): array
    {
        /** @var User|null $user */
        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationFailedException('بيانات الدخول غير صحيحة');
        }

        if ($user->status !== 'active') {
            throw new AuthenticationFailedException('هذا الحساب غير مفعّل حاليًا');
        }

        $deviceName = $credentials['device_name'] ?? 'default';

        $this->safeMerge($user, $guestToken);

        return [
            'user'  => $user,
            'token' => $this->issueToken($user, $deviceName),
        ];
    }
    private function issueToken(User $user, string $deviceName): string
    {
        $user->tokens()->where('name', $deviceName)->delete();

        return $user->createToken($deviceName, ['customer'], now()->addDays(30))->plainTextToken;
    }

    private function safeMerge(User $user, ?string $guestToken): void
    {
        try {
            $this->merger->merge($user, $guestToken);
        } catch (Throwable $e) {
            Log::error('guest data merge failed: ' . $e->getMessage(), ['user_id' => $user->id]);
        }
    }
}
