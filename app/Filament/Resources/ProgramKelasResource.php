<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramKelasResource\Pages;
use App\Filament\Resources\ProgramKelasResource\RelationManagers;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramKelasResource extends Resource
{
    protected static ?string $model = Program::class;
    protected static ?string $navigationLabel = 'Program Kelas';
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\TextInput::make('nama_kelas')
                    ->required()
                    ->label('Nama Program'),
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Program'),
                Forms\Components\TextInput::make('durasi')
                    ->required()
                    ->numeric()
                    ->suffix('Hari')
                    ->label('Durasi'),
                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->prefix('IDR')
                    ->label('Harga'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(fn($rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Nama Program')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('durasi')
                    ->label('Durasi (Hari)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR',true)
                    ->sortable(),
            ])
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
            'index' => Pages\ListProgramKelas::route('/'),
            'create' => Pages\CreateProgramKelas::route('/create'),
            'edit' => Pages\EditProgramKelas::route('/{record}/edit'),
        ];
    }
}
