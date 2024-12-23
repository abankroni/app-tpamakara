<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemaHarianResource\Pages;
use App\Filament\Resources\TemaHarianResource\RelationManagers;
use App\Models\TemaHarian;
use App\Models\SubtemaHarian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class TemaHarianResource extends Resource
{
    protected static ?string $model = TemaHarian::class;
    protected static ?string $navigationLabel = 'Tema Harian';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('tema')
                        ->required()
                        ->label('Tema'),
                ])
                ->columns(2),

                Forms\Components\Section::make('Detail Tema Harian')
                ->description('Lengkapi tema harian dengan menambahkan subtema dan detail kegiatan. Sesuaikan item Subtema sesuai kebutuhan.')
                ->schema([
                    Forms\Components\Repeater::make('subtemas')
                        ->relationship('subtemas')
                        ->schema([
                            Group::make([
                                Forms\Components\TextInput::make('subtema')
                                    ->required()
                                    ->label('Subtema'),
                            ])->columns(2),

                            Group::make([
                                Forms\Components\MarkdownEditor::make('detail_fisik_motorik')
                                    ->required()
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'heading',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ])
                                    ->label('Detail Kegiatan Fisik Motorik'),
                                Forms\Components\MarkdownEditor::make('detail_kognitif')
                                    ->required()
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'heading',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ])
                                    ->label('Detail Kegiatan Kognitif'),
                            ])->columns(2),
                        ])
                        ->columns(1)
                        ->defaultItems(1)
                        ->label(''),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(fn($rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('tema')
                    ->sortable()
                    ->searchable()
                    ->label('Tema'),
                Tables\Columns\TextColumn::make('subtema')
                    ->label('Subtema')
                    ->html() // Ensure HTML rendering
                    ->getStateUsing(function ($record) {
                        // Check if subtemas are available and properly plucked
                        if ($record->subtemas->isNotEmpty()) {
                            // Create a bulleted list
                            return '<ul>' . $record->subtemas->pluck('subtema')->map(function ($subtema) {
                                return '<li>• ' . e($subtema) . ';</li>'; // Escape to prevent XSS
                            })->implode('') . '</ul>';
                        }
                        return 'No subtema available'; // Return a fallback if no subtemas exist
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Buat')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Ubah')
                    ->date('d F Y')
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
            'index' => Pages\ListTemaHarians::route('/'),
            'create' => Pages\CreateTemaHarian::route('/create'),
            'edit' => Pages\EditTemaHarian::route('/{record}/edit'),
        ];
    }
}
