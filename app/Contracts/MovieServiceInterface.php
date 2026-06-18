<?php

namespace App\Contracts;

interface MovieServiceInterface
{
    public function getRecommendation(array $filters, int $userId): ?array;

    public function getDetail(int $movieId): array;
}
