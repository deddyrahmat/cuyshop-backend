<?php

namespace App\Filament\Editor\Resources;

use App\Filament\Editor\Resources\CategoryResource\Pages;
use App\Filament\Editor\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource as ResourcesCategoryResource;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends ResourcesCategoryResource
{
}
