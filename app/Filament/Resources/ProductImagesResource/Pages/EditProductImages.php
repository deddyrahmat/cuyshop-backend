<?php

namespace App\Filament\Resources\ProductImagesResource\Pages;

use App\Filament\Resources\ProductImagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductImages extends EditRecord
{
    protected static string $resource = ProductImagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
