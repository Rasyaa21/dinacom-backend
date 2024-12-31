<?php

namespace App\Repositories\Contracts;

interface TrashInterface
{
    public function scanImage(string $imagePath);
    public function storeData(array $data);
    public function DetailTrash($id);
    public function delete($id);
    public function getDataByUserAndCategory($category_id);
    public function getAllDataByUserId();
    public function getGroupDataByUserId(string $type);
}
