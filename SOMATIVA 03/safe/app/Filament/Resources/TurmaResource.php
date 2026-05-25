<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TurmaResource\Pages;
use App\Models\Turma;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TurmaResource extends Resource
{
    protected static ?string $model           = Turma::class;
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-group';
    protected static ?string $navigationLabel = 'Turmas';
    protected static ?int    $navigationSort  = 3;

    public static function canAccess(): bool
    {
        return in_array(Auth::user()?->role, ['coordenacao', 'admin', 'professor']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados da turma')
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome da turma')
                        ->required()
                        ->placeholder('Ex: Desenvolvimento de Sistemas — 2º A'),

                    Forms\Components\TextInput::make('curso')
                        ->required()
                        ->placeholder('Ex: Desenvolvimento de Sistemas'),

                    Forms\Components\TextInput::make('periodo')
                        ->required()
                        ->placeholder('Ex: 2º ano'),

                    Forms\Components\Select::make('turno')
                        ->options([
                            'manhã'  => 'Manhã',
                            'tarde'  => 'Tarde',
                            'noite'  => 'Noite',
                        ])
                        ->required(),

                    Forms\Components\TimePicker::make('horario_inicio')
                        ->label('Início das aulas')
                        ->required()
                        ->seconds(false),

                    Forms\Components\TimePicker::make('horario_fim')
                        ->label('Término das aulas')
                        ->required()
                        ->seconds(false),

                    Forms\Components\Select::make('total_aulas_dia')
                        ->label('Total de aulas por dia')
                        ->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6])
                        ->default(5)
                        ->required(),

                    Forms\Components\CheckboxList::make('dias_semana')
                        ->label('Dias de aula')
                        ->options([
                            1 => 'Segunda-feira',
                            2 => 'Terça-feira',
                            3 => 'Quarta-feira',
                            4 => 'Quinta-feira',
                            5 => 'Sexta-feira',
                            6 => 'Sábado',
                        ])
                        ->columns(3)
                        ->required(),

                    Forms\Components\Select::make('professor_id')
                        ->label('Professor responsável')
                        ->options(User::where('role', 'professor')->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('curso')->searchable(),
                Tables\Columns\TextColumn::make('periodo')->label('Período'),
                Tables\Columns\TextColumn::make('turno'),
                Tables\Columns\TextColumn::make('horario_inicio')->label('Início'),
                Tables\Columns\TextColumn::make('horario_fim')->label('Fim'),
                Tables\Columns\TextColumn::make('professor.name')->label('Professor')->searchable(),
                Tables\Columns\IconColumn::make('ativa')->boolean(),
            ])
            ->modifyQueryUsing(function ($query) {
                if (Auth::user()->isProfessor()) {
                    $query->where('professor_id', Auth::id());
                }
            });
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTurmas::route('/'),
            'create' => Pages\CreateTurma::route('/create'),
            'edit'   => Pages\EditTurma::route('/{record}/edit'),
        ];
    }
}