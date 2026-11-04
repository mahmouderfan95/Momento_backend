<?php
namespace App\Support;
use Illuminate\Http\Request;

final class FavoriteOwner
{
    public function __construct(
        public readonly ?int $userId,
        public readonly ?string $guestToken,
    ) {}
    public static function fromRequest(Request $request): self
    {
        $user = $request->user('sanctum');

        if ($user) {
            return new self($user->id, null);
        }

        $token = $request->header('X-Guest-Token');

        return new self(null, is_string($token) && strlen($token) >= 16 ? $token : null);
    }
    public function isIdentified(): bool
    {
        return $this->userId !== null || $this->guestToken !== null;
    }

}
