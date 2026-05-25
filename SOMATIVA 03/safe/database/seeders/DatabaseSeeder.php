<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\Turma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Usuários ---
        $admin = User::create([
            'rn_re'    => 'ADM0001',
            'name'     => 'Administrador SAFE',
            'email'    => 'admin@safe.edu.br',
            'password' => Hash::make('safe@2024'),
            'role'     => 'admin',
        ]);

        $professor = User::create([
            'rn_re'    => 'RE2023045',
            'name'     => 'Carlos Mendes',
            'email'    => 'carlos.mendes@safe.edu.br',
            'password' => Hash::make('safe@2024'),
            'role'     => 'professor',
        ]);

        $coordenacao = User::create([
            'rn_re'    => 'RE2020010',
            'name'     => 'Fernanda Alves',
            'email'    => 'fernanda.alves@safe.edu.br',
            'password' => Hash::make('safe@2024'),
            'role'     => 'coordenacao',
        ]);

        $portaria = User::create([
            'rn_re'    => 'RE2022077',
            'name'     => 'João Silva',
            'email'    => 'joao.silva@safe.edu.br',
            'password' => Hash::make('safe@2024'),
            'role'     => 'portaria',
        ]);

        // --- Turmas ---
        $turmaDS = Turma::create([
            'nome'           => 'Desenvolvimento de Sistemas — 2º A',
            'curso'          => 'Desenvolvimento de Sistemas',
            'periodo'        => '2º ano',
            'turno'          => 'tarde',
            'horario_inicio' => '13:00',
            'horario_fim'    => '17:00',
            'total_aulas_dia'=> 4,
            'dias_semana'    => [1, 3, 5], // Seg, Qua, Sex
            'professor_id'   => $professor->id,
        ]);

        $turmaBD = Turma::create([
            'nome'           => 'Banco de Dados — 3º B',
            'curso'          => 'Banco de Dados',
            'periodo'        => '3º ano',
            'turno'          => 'manhã',
            'horario_inicio' => '07:30',
            'horario_fim'    => '11:30',
            'total_aulas_dia'=> 4,
            'dias_semana'    => [2, 4], // Ter, Qui
            'professor_id'   => $professor->id,
        ]);

        // --- Alunos ---
        Aluno::create([
            'ra'                    => 'RN2024001',
            'nome'                  => 'Marcos Oliveira',
            'email'                 => 'marcos.oliveira@aluno.safe.edu.br',
            'data_nascimento'       => Carbon::now()->subYears(18)->subMonths(3),
            'telefone'              => '11999990001',
            'responsavel_nome'      => 'Maria Oliveira',
            'responsavel_email'     => 'maria.oliveira@email.com',
            'responsavel_telefone'  => '11999990000',
            'responsavel_whatsapp'  => '5511999990000',
            'tem_empresa'           => false,
            'turma_id'              => $turmaDS->id,
        ]);

        Aluno::create([
            'ra'                    => 'RN2024002',
            'nome'                  => 'Juliana Torres',
            'email'                 => 'juliana.torres@aluno.safe.edu.br',
            'data_nascimento'       => Carbon::now()->subYears(17)->subMonths(6),
            'telefone'              => '11999990002',
            'responsavel_nome'      => 'Roberto Torres',
            'responsavel_email'     => 'roberto.torres@email.com',
            'responsavel_telefone'  => '11999990003',
            'responsavel_whatsapp'  => '5511999990003',
            'tem_empresa'           => false,
            'turma_id'              => $turmaDS->id,
        ]);

        Aluno::create([
            'ra'                    => 'RN2024003',
            'nome'                  => 'Rafael Costa',
            'email'                 => 'rafael.costa@aluno.safe.edu.br',
            'data_nascimento'       => Carbon::now()->subYears(19),
            'telefone'              => '11999990004',
            'tem_empresa'           => true,
            'empresa_nome'          => 'TechSolutions Ltda',
            'turma_id'              => $turmaBD->id,
        ]);

        Aluno::create([
            'ra'                    => 'RN2024004',
            'nome'                  => 'Ana Souza',
            'email'                 => 'ana.souza@aluno.safe.edu.br',
            'data_nascimento'       => Carbon::now()->subYears(16)->subMonths(2),
            'responsavel_nome'      => 'Carla Souza',
            'responsavel_email'     => 'carla.souza@email.com',
            'responsavel_whatsapp'  => '5511999990005',
            'tem_empresa'           => false,
            'turma_id'              => $turmaDS->id,
        ]);

        $this->command->info('✅ Seed concluído! Credenciais padrão (todos): senha = safe@2024');
        $this->command->table(
            ['Perfil', 'RN/RE', 'E-mail'],
            [
                ['Admin',       'ADM0001',   'admin@safe.edu.br'],
                ['Professor',   'RE2023045', 'carlos.mendes@safe.edu.br'],
                ['Coordenação', 'RE2020010', 'fernanda.alves@safe.edu.br'],
                ['Portaria',    'RE2022077', 'joao.silva@safe.edu.br'],
            ]
        );
    }
}