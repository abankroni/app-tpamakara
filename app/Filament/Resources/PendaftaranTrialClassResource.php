<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranTrialClassResource\Pages;
use App\Models\PendaftaranTrialClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PendaftaranTrialClassResource extends Resource
{
    protected static ?string $model = PendaftaranTrialClass::class;
    protected static ?string $navigationLabel = 'Pendaftaran Trial Class';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Siswa')
                ->schema([
                    Forms\Components\TextInput::make('nama_lengkap')
                        ->required()
                        ->label('Nama Lengkap'),
                    Forms\Components\TextInput::make('nama_panggilan')
                        ->required()
                        ->label('Nama Panggilan'),
                    Forms\Components\Radio::make('jenis_kelamin')
                        ->required()
                        ->inline()
                        ->inlineLabel(false)
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->label('Jenis Kelamin'),
                    Forms\Components\TextInput::make('tempat_lahir')
                        ->required()
                        ->label('Tempat Lahir'),
                    Forms\Components\DatePicker::make('tanggal_lahir')
                        ->required()
                        ->label('Tanggal Lahir'),
                ])->columns(2),

                Forms\Components\Section::make('Data Orang Tua')
                ->schema([
                    Forms\Components\TextInput::make('nama_lengkap_ayah')
                        ->required()
                        ->label('Nama Lengkap Ayah'),
                    Forms\Components\TextInput::make('nama_lengkap_ibu')
                        ->required()
                        ->label('Nama Lengkap Ibu'),
                    Forms\Components\TextArea::make('alamat_domisili')
                        ->nullable()
                        ->label('Alamat Domisili'),
                    Forms\Components\TextInput::make('no_handphone')
                        ->required()
                        ->label('No Handphone')
                        ->tel(),
                    Forms\Components\TextInput::make('nama_pengantar')
                        ->nullable()
                        ->label('Nama Pengantar'),
                ])->columns(2),

                Forms\Components\Section::make('Program Kelas')
                ->schema([
                    Forms\Components\Select::make('program_kelas_id')
                        ->required()
                        ->relationship('programKelas', 'nama_kelas')
                        ->label('Program Kelas'),
                    Forms\Components\DatePicker::make('tanggal_daftar')
                        ->required()
                        ->label('Tanggal Daftar'),
                    Forms\Components\ToggleButtons::make('status')
                        ->required()
                        ->default('Menunggu')
                        ->options([
                            'Menunggu' => 'Menunggu',
                            'Aktif' => 'Aktif',
                            'Tidak aktif' => 'Tidak aktif',
                        ])
                        ->colors([
                            'Menunggu' => 'warning',
                            'Aktif' => 'success',
                            'Tidak aktif' => 'danger'
                        ])
                        ->inline()
                        ->label('Status'),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(fn($rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap Anak')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_panggilan')
                    ->label('Panggilan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('programKelas.nama_kelas')
                    ->label('Program Kelas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu' => 'warning',
                        'Aktif' => 'success',
                        'Tidak aktif' => 'danger',
                    })
                    ->sortable(),
            ])
            ->defaultSort('nama_lengkap', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftaranTrialClasses::route('/'),
            'create' => Pages\CreatePendaftaranTrialClass::route('/create'),
            'edit' => Pages\EditPendaftaranTrialClass::route('/{record}/edit'),
        ];
    }
}
