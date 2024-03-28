<?php

namespace App\Providers;

use App\Repository\BlockRepository;
use App\Repository\BlockRepositoryInterface;
use App\Repository\BookingRepository;
use App\Repository\BookingRepositoryInterface;
use App\Repository\RoomRepository;
use App\Repository\RoomRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(BlockRepositoryInterface::class, BlockRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
