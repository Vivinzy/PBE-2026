<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlunoResource\Pages;
use App\Models\Aluno;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AlunoResource extends Resource
{
    protected static ?string $model          = Aluno::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Alunos';
    protected static ?int    $navigationSort  = 1;

    public static function canAccess(): bool
    {
        return in_array(Auth::user()?->role, ['coordenacao', 'admin', 'professor']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados do aluno')
                ->schema([
                    Forms\Components\TextInput::make('ra')
                        ->label('RA (Registro do Aluno)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),

                    Forms\Components\TextInput::make('nome')
                        ->label('Nome completo')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('data_nascimento')
                        ->label('Data de nascimento')
                        ->required()
                        ->maxDate(now()),

                    Forms\Components\TextInput::make('telefone')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\Select::make('turma_id')
                        ->label('Turma')
                        ->options(fn () => self::turmasOptions())
                        ->required()
                        ->searchable(),
                ])->columns(2),

            Forms\Components\Section::make('Dados do responsável')
                ->description('Obrigatório para alunos menores de 18 anos.')
                ->schema([
                    Forms\Components\TextInput::make('responsavel_nome')
                        ->label('Nome do responsável')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('responsavel_email')
                        ->label('E-mail do responsável')
                        ->email()
                        ->helperText('Será usado para envio de notificações automáticas (Mailpit em dev).'),

                    Forms\Components\TextInput::make('responsavel_telefone')
                        ->label('Telefone do responsável')
                        ->tel(),

                    Forms\Components\TextInput::make('responsavel_whatsapp')
                        ->label('WhatsApp do responsável')
                        ->tel()
                        ->helperText('Formato: 5511999999999 (com DDI+DDD)'),
                ])->columns(2),

            Forms\Components\Section::make('Empresa / Estágio')
                ->schema([
                    Forms\Components\Toggle::make('tem_empresa')
                        ->label('Aluno em estágio/empresa?')
                        ->live(),

                    Forms\Components\TextInput::make('empresa_nome')
                        ->label('Nome da empresa')
                        ->visible(fn (Forms\Get $get) => $get('tem_empresa'))
                        ->required(fn (Forms\Get $get) => $get('tem_empresa')),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ra')
                    ->label('RA')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('turma.nome')
                    ->label('Turma')
                    ->searchable(),

                Tables\Columns\TextColumn::make('data_nascimento')
                    ->label('Nascimento')
                    ->date('d/m/Y'),

                Tables\Columns\IconColumn::make('menor')
                    ->label('Menor')
                    ->state(fn (Aluno $r) => $r->isMenor())
                    ->boolean(),

                Tables\Columns\IconColumn::make('tem_empresa')
                    ->label('Empresa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('empresa_nome')
                    ->label('Empresa')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('ativo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('turma_id')
                    ->label('Turma')
                    ->options(Turma::pluck('nome', 'id')),

                Tables\Filters\TernaryFilter::make('tem_empresa')
                    ->label('Em empresa'),
            ])
            ->defaultSort('nome');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAlunos::route('/'),
            'create' => Pages\CreateAluno::route('/create'),
            'edit'   => Pages\EditAluno::route('/{record}/edit'),
        ];
    }

    protected static function turmasOptions(): array
    {
        $user = Auth::user();
        if ($user->isProfessor()) {
            return Turma::where('professor_id', $user->id)->pluck('nome', 'id')->toArray();
        }
        return Turma::pluck('nome', 'id')->toArray();
    }
}