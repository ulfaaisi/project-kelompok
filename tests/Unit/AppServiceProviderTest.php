<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Contracts\AuthServiceInterface;
use App\Contracts\FavoriteServiceInterface;
use App\Contracts\GenreServiceInterface;
use App\Contracts\HistoryServiceInterface;
use App\Contracts\MovieServiceInterface;
use App\Providers\AppServiceProvider;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

final class AppServiceProviderTest extends TestCase
{
    public function test_all_service_contracts_are_registered_in_container(): void
    {
        $container = new Container();
        $provider = new AppServiceProvider($container);

        $provider->register();

        self::assertTrue($container->bound(AuthServiceInterface::class));
        self::assertTrue($container->bound(FavoriteServiceInterface::class));
        self::assertTrue($container->bound(GenreServiceInterface::class));
        self::assertTrue($container->bound(HistoryServiceInterface::class));
        self::assertTrue($container->bound(MovieServiceInterface::class));
    }
}
