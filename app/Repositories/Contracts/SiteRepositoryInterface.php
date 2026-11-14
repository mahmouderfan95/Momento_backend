<?php
namespace App\Repositories\Contracts;
use App\Models\Setting;

interface SiteRepositoryInterface
{
    public function get(): ?Setting;
    public function save(array $data): Setting;
}
