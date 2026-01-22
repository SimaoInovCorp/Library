<?php

namespace App\Services\Publishers;

use App\Models\Publisher;
use Illuminate\Http\UploadedFile;

class PublisherService
{
    public function create(array $data): Publisher
    {
        $publisher = new Publisher();
        $publisher->name = $data['name'];
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $publisher->logo = $data['logo']->store('publishers', 'public');
        }
        $publisher->save();
        return $publisher;
    }

    public function update(Publisher $publisher, array $data): Publisher
    {
        $publisher->name = $data['name'];
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $publisher->logo = $data['logo']->store('publishers', 'public');
        }
        $publisher->save();
        return $publisher;
    }

    public function delete(Publisher $publisher): void
    {
        $publisher->delete();
    }
}
