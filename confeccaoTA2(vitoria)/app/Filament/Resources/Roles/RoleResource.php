<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRole;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Schemas\RoleInfolist;
use App\Filament\Resources\Roles\Tables\RolesTable;
use Spatie\Permission\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;       
use Filament\Forms\Components\TextInput;    
use Filament\Table\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    // public static function canAcess(): bool
    // {
    //     return auth()->user()?->hasRole('Admin') ?? false;
    // }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }
 
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Cargos e Funções';

    public static function form(Schema $schema): Schema
    {
    // return PermissionForm::configure($schema);
        return $schema
            ->schema([
                Select::make('permissions')           
                    ->label('Permissões de Acesso')
                    ->multiple()                      
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->columnSpanFull(),               

                TextInput::make('name')
                    ->label('Nome da Regra')          
                    ->required(),

                    TextInput::make('guard_name')
                    ->label('Guard Name')
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome da Regra')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('guard_name')
                    ->label('Guard Name')
                    ->searchable()
                    ->sortable(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view'   => ViewRole::route('/{record}'),
            'edit'   => EditRole::route('/{record}/edit'),
        ];
    }
}