<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class AuthServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_register_creates_user_and_returns_bearer_token(): void
    {
        $user = Mockery::mock('alias:' . User::class);
        $tokenResult = (object) ['plainTextToken' => 'token-register-123'];

        Hash::shouldReceive('make')
            ->once()
            ->with('secret123')
            ->andReturn('hashed-password');

        $user->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Budi',
                'email' => 'budi@example.com',
                'password' => 'hashed-password',
            ])
            ->andReturn($user);

        $user->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn($tokenResult);

        $result = (new AuthService())->register([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        self::assertSame($user, $result['user']);
        self::assertSame('token-register-123', $result['access_token']);
        self::assertSame('Bearer', $result['token_type']);
    }

    public function test_login_returns_null_when_user_is_not_found(): void
    {
        $user = Mockery::mock('alias:' . User::class);
        $query = Mockery::mock();

        $user->shouldReceive('where')
            ->once()
            ->with('email', 'missing@example.com')
            ->andReturn($query);

        $query->shouldReceive('first')->once()->andReturnNull();
        Hash::shouldReceive('check')->never();

        $result = (new AuthService())->login([
            'email' => 'missing@example.com',
            'password' => 'wrong-password',
        ]);

        self::assertNull($result);
    }

    public function test_login_deletes_old_tokens_and_returns_new_token(): void
    {
        $user = Mockery::mock('alias:' . User::class);
        $query = Mockery::mock();
        $tokens = Mockery::mock();
        $tokenResult = (object) ['plainTextToken' => 'token-login-456'];

        $user->password = 'stored-hash';

        $user->shouldReceive('where')
            ->once()
            ->with('email', 'budi@example.com')
            ->andReturn($query);

        $query->shouldReceive('first')->once()->andReturn($user);

        Hash::shouldReceive('check')
            ->once()
            ->with('secret123', 'stored-hash')
            ->andReturnTrue();

        $user->shouldReceive('tokens')->once()->andReturn($tokens);
        $tokens->shouldReceive('delete')->once()->andReturn(1);

        $user->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn($tokenResult);

        $result = (new AuthService())->login([
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        self::assertSame($user, $result['user']);
        self::assertSame('token-login-456', $result['access_token']);
        self::assertSame('Bearer', $result['token_type']);
    }

    public function test_logout_deletes_current_access_token(): void
    {
        $user = Mockery::mock(User::class);
        $token = Mockery::mock();

        $user->shouldReceive('currentAccessToken')->once()->andReturn($token);
        $token->shouldReceive('delete')->once()->andReturnTrue();

        (new AuthService())->logout($user);

        self::assertTrue(true);
    }

    public function test_me_returns_only_public_user_fields(): void
    {
        $user = new User();

        $user->id = 10;
        $user->name = 'Budi';
        $user->email = 'budi@example.com';

        $result = (new AuthService())->me($user);

        self::assertSame([
            'id' => 10,
            'name' => 'Budi',
            'email' => 'budi@example.com',
        ], $result);
    }
}