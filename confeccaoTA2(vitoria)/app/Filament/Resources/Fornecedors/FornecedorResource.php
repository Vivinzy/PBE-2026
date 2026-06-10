<?php

namespace App\Filament\Resources\Fornecedors;

use App\Filament\Resources\Fornecedors\Pages\CreateFornecedor;
use App\Filament\Resources\Fornecedors\Pages\EditFornecedor;
use App\Filament\Resources\Fornecedors\Pages\ListFornecedors;
use App\Filament\Resources\Fornecedors\Pages\ViewFornecedor;
use App\Filament\Resources\Fornecedors\Schemas\FornecedorForm;
use App\Filament\Resources\Fornecedors\Schemas\FornecedorInfolist;
use App\Filament\Resources\Fornecedors\Tables\FornecedorsTable;
use App\Models\Fornecedor;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class FornecedorResource extends Resource
{
    protected static ?string $model = Fornecedor::class;
    
    // ✅ CORRETO - usar UnitEnum|string|null
    protected static UnitEnum|string|null $navigationGroup = 'Cadastros Gerais';
    
    protected static ?int $navigationSort = 2;
    
    // ✅ CORRETO - usar BackedEnum|string|null
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static ?string $navigationLabel = 'Fornecedores';
    protected static ?string $modelLabel = 'Fornecedor';
    protected static ?string $pluralModelLabel = 'Fornecedores';
    protected static ?string $recordTitleAttribute = 'Fornecedor';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nome')->required()->label('Nome Completo'),
            TextInput::make('email')->email()->label('E-mail'),
            TextInput::make('telefone')->tel()->label('Telefone/Whatsapp'),
            TextInput::make('documento')->label('CPF ou CNPJ'),
            TextInput::make('endereco')->label('Endereço'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FornecedorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nome')->searchable(),
            TextColumn::make('email')->searchable(),
            TextColumn::make('telefone'),
            TextColumn::make('documento'),
            TextColumn::make('endereco'), // ✅ CORRIGIDO - sem acento
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFornecedors::route('/'),
            'create' => CreateFornecedor::route('/create'),
            'view' => ViewFornecedor::route('/{record}'),
            'edit' => EditFornecedor::route('/{record}/edit'),
        ];
    }
}