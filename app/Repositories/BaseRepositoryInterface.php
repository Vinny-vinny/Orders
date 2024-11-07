<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{

    public function all(): Collection;
    public function paginate($perPage = 15, $page = null): LengthAwarePaginator;

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function where(array $conditions);

    public function search(string $term);
}
