<?php

namespace App\Filament\Editor\Resources;

use App\Filament\Editor\Resources\ProductResource\Pages;
use App\Filament\Editor\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource as ResourcesProductResource;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends ResourcesProductResource
{
}
