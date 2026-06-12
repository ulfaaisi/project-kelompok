<?php

namespace App\Contracts;

interface HistoryServiceInterface
{
    public function getUserHistory(int $userId, int $limit = 50): array;
}
