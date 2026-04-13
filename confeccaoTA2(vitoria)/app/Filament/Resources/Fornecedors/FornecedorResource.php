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
    protected static string|UnitEnum|null $navigationGroup = 'Cadastros Gerais';
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    //Nome que vai aparecer no menu lateral
    protected static ?string $navigationLabel = 'Fornecedores';

    //Nome singular (ex: usado no botão "Criar Usuario")
    protected static ?string $modelLabel = 'Fornecedor';

    //Nome plural (ex: usado no título da tabela "Usuarios")
    protected static ?string $pluralModelLabel = 'Fornecedores';

    protected static ?string $recordTitleAttribute = 'Fornecedor';

    public static function form(Schema $schema): Schema
    {
        // return FornecedorForm::configure($schema);
        return $schema
        ->components([
            TextInput::make('nome')->required()->Label('Nome Completo'),
            TextInput::make('email')->email()->label('E-mail'),
            TextInput::make('telefone')->tel()->label('Telefone/Whatsapp'),
            TextInput::make('documento')->label('CPF ou CNPJ'),
            TextInput::make('endereço')->label('Endereço'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FornecedorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return FornecedorsTable::configure($table);
        return $table->columns([
            TextColumn::make('nome')->searchable(),
            TextColumn::make('email')->searchable(),
            textColumn::make('telefone'),
            TextColumn::make('documento'),
            TextColumn::make('endereço'),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
