<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistroMovimentoResource\Pages;
use App\Models\Aluno;
use App\Models\RegistroMovimento;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RegistroMovimentoResource extends Resource
{
    protected static ?string $model = RegistroMovimento::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-on-rectangle';
    protected static ?string $navigationLabel = 'Movimentos';
    protected static ?string $modelLabel = 'Registro de Movimento';
    protected static ?string $pluralModelLabel = 'Registros de Movimentos';
    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['coordenacao', 'admin', 'professor', 'portaria']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados do movimento')
                ->schema([
                    Forms\Components\Select::make('aluno_id')
                        ->label('Aluno')
                        ->options(Aluno::where('ativo', true)->pluck('nome', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($aluno = Aluno::find($state)) {
                                $set('turma_id', $aluno->turma_id);
                                $set('menor_de_idade', $aluno->isMenor());
                                $set('tem_empresa', $aluno->tem_empresa);
                                $set('empresa_nome', $aluno->empresa_nome);
                            }
                        }),

                    Forms\Components\Select::make('turma_id')
                        ->label('Turma')
                        ->options(fn () => self::turmasDisponiveis())
                        ->required(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(['entrada' => 'Entrada', 'saida' => 'Saída'])
                        ->required()
                        ->live(),

                    Forms\Components\DateTimePicker::make('horario')
                        ->label('Horário')
                        ->required()
                        ->default(now())
                        ->seconds(false),
                ])->columns(2),

            Forms\Components\Section::make('Faltas')
                ->schema([
                    Forms\Components\CheckboxList::make('faltas_aulas')
                        ->label('Aulas perdidas')
                        ->options([
                            1 => '1ª aula',
                            2 => '2ª aula',
                            3 => '3ª aula',
                            4 => '4ª aula',
                            5 => '5ª aula',
                        ])
                        ->columns(5)
                        ->helperText('Selecione as aulas que o aluno irá perder com este movimento.'),
                ]),

            Forms\Components\Section::make('Motivo e observações')
                ->schema([
                    Forms\Components\TextInput::make('motivo')
                        ->label('Motivo')
                        ->maxLength(255),

                    Forms\Components\Textarea::make('observacao')
                        ->label('Observação')
                        ->rows(2),
                ])->columns(2),

            Forms\Components\Section::make('Empresa (estágio)')
                ->schema([
                    Forms\Components\Toggle::make('tem_empresa')
                        ->label('Aluno em empresa/estágio?')
                        ->live(),

                    Forms\Components\TextInput::make('empresa_nome')
                        ->label('Nome da empresa')
                        ->visible(fn (Forms\Get $get) => $get('tem_empresa'))
                        ->required(fn (Forms\Get $get) => $get('tem_empresa')),
                ])->columns(2),

            Forms\Components\Section::make('Controle de menor de idade')
                ->schema([
                    Forms\Components\Toggle::make('menor_de_idade')
                        ->label('Menor de idade?')
                        ->live(),

                    Forms\Components\Toggle::make('responsavel_autorizado')
                        ->label('Responsável autorizou?')
                        ->visible(fn (Forms\Get $get) => $get('menor_de_idade') && $get('tipo') === 'saida'),

                    Forms\Components\TextInput::make('responsavel_presente_nome')
                        ->label('Nome do responsável presente')
                        ->visible(fn (Forms\Get $get) => $get('menor_de_idade') && $get('tipo') === 'saida'),
                ])->columns(3)
                ->visible(fn (Forms\Get $get) => $get('tipo') === 'saida'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aluno.nome')
                    ->label('Aluno')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('turma.nome')
                    ->label('Turma')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'entrada',
                        'danger'  => 'saida',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('horario')
                    ->label('Horário')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dia_semana')
                    ->label('Dia'),

                Tables\Columns\TextColumn::make('faltas_formatada')
                    ->label('Faltas'),

                Tables\Columns\IconColumn::make('tem_empresa')
                    ->label('Empresa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('empresa_nome')
                    ->label('Nome da empresa')
                    ->placeholder('—'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pendente',
                        'success' => 'confirmado',
                        'danger'  => 'negado',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->options(['entrada' => 'Entrada', 'saida' => 'Saída']),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pendente'   => 'Pendente',
                        'confirmado' => 'Confirmado',
                        'negado'     => 'Negado',
                    ]),

                Tables\Filters\SelectFilter::make('turma_id')
                    ->label('Turma')
                    ->options(Turma::pluck('nome', 'id')),

                Tables\Filters\Filter::make('hoje')
                    ->label('Somente hoje')
                    ->query(fn (Builder $q) => $q->whereDate('horario', today()))
                    ->default(),
            ])
            ->defaultSort('horario', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                // Professor só vê movimentos das suas turmas
                if ($user->isProfessor()) {
                    $turmaIds = Turma::where('professor_id', $user->id)->pluck('id');
                    $query->whereIn('turma_id', $turmaIds);
                }
            });
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRegistroMovimentos::route('/'),
            'create' => Pages\CreateRegistroMovimento::route('/create'),
            'edit'   => Pages\EditRegistroMovimento::route('/{record}/edit'),
        ];
    }

    protected static function turmasDisponiveis(): array
    {
        $user = Auth::user();
        if ($user->isProfessor()) {
            return Turma::where('professor_id', $user->id)->pluck('nome', 'id')->toArray();
        }
        return Turma::pluck('nome', 'id')->toArray();
    }
}