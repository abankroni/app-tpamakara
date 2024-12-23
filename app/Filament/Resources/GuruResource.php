<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;
    protected static ?string $navigationLabel = 'Data Guru';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_guru')
                    ->label('Nama Guru')
                    ->required(),

                Forms\Components\Radio::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->inline()
                    ->inlineLabel(false)
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                Forms\Components\Select::make('agama')
                    ->label('Agama')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('pend_terakhir')
                    ->label('Pendidikan Terakhir')
                    ->required(),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email(),

                Forms\Components\TextInput::make('no_handphone')
                    ->label('Nomor Handphone')
                    ->required()
                    ->tel(),

                Forms\Components\Select::make('peran')
                    ->label('Peran')
                    ->default('Guru')
                    ->options([
                        'Guru' => 'Guru',
                        'Koordinator' => 'Koordinator',
                    ])
                    ->required(),

                Forms\Components\ToggleButtons::make('status_guru')
                    ->label('Status Guru')
                    ->default('Aktif')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Tidak aktif' => 'Tidak aktif',
                    ])
                    ->colors([
                        'Aktif' => 'success',
                        'Tidak aktif' => 'danger'
                    ])
                    ->inline()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(fn($rowLoop) => $rowLoop->index + 1),
                //Tables\Columns\TextColumn::make('id')
                //    ->label('ID')
                //    ->sortable()
                //    ->searchable(),
                Tables\Columns\TextColumn::make('nama_guru')
                    ->label('Nama Lengkap')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_handphone')
                    ->label('No HP')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_guru')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Tidak aktif' => 'danger',
                    })
                    ->sortable(),
            ])
            ->defaultSort('nama_guru', 'asc')
            ->filters([
                //
            ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}
