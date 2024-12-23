<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanHarianResource\Pages;
use App\Filament\Resources\LaporanHarianResource\RelationManagers;
use App\Models\LaporanHarian;
use App\Models\TemaHarian;
use App\Models\SubtemaHarian;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\CheckboxList;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanHarianResource extends Resource
{
    protected static ?string $model = LaporanHarian::class;
    protected static ?string $navigationLabel = 'Laporan Harian Siswa';
    protected static ?string $navigationGroup = 'Laporan Siswa';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('siswa_id')
                            ->label('Nama Lengkap')
                            ->relationship('siswa', 'nama_lengkap')
                            ->required()
                            ->rules([
                                'required',
                                'exists:siswa,id'
                            ])
                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                $tanggalPelaksanaan = $get('tanggal_pelaksanaan');
                                if ($tanggalPelaksanaan) {
                                    $namaLengkap = Siswa::find($state)->nama_lengkap ?? 'Nama tidak ditemukan';
                                    $exists = LaporanHarian::where('siswa_id', $state)
                                                            ->where('tanggal_pelaksanaan', $tanggalPelaksanaan)
                                                            ->exists();
                                    if ($exists) {
                                        $set('siswa_id', null);

                                        Notification::make()
                                            ->title('Peringatan')
                                            ->body("Laporan sudah ada untuk nama lengkap '<strong>{$namaLengkap}</strong>' dan tanggal pelaksanaan '<strong>{$tanggalPelaksanaan}</strong>'.")
                                            ->warning()
                                            ->send();
                                    }
                                }
                            }),
                        Forms\Components\DatePicker::make('tanggal_pelaksanaan')
                            ->label('Tanggal Pelaksanaan')
                            ->required()
                            ->rules('required|date'),
                        Forms\Components\Select::make('tema_harian_id')
                            ->label('Tema')
                            ->options(TemaHarian::all()->pluck('tema', 'id'))
                            ->required()
                            ->reactive(),
                        Forms\Components\Select::make('subtema_harian_id')
                            ->label('Subtema')
                            ->options(function (callable $get) {
                                $temaId = $get('tema_harian_id');
                                return SubtemaHarian::where('tema_id', $temaId)->pluck('subtema', 'id');
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Fetch related subtema_harian data when a subtema is selected
                                $subtemaHarian = SubtemaHarian::where('tema_id', $state)->first();

                                // If the subtema_harian exists, update the textareas
                                if ($subtemaHarian) {
                                    $set('kegiatan_fisik_motorik', $subtemaHarian->detail_fisik_motorik);
                                    $set('kegiatan_kognitif', $subtemaHarian->detail_kognitif);
                                }
                            }),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Laporan Harian')
                    ->icon('heroicon-m-document-text')->iconColor('warning')
                    ->description('Keterangan Kemampuan: ⭐⭐⭐ = Mandiri , ⭐⭐ = Cukup Mandiri , dan ⭐ = Harus Dibantu/Diarahkan')
                    ->schema([
                        Group::make([
                            Forms\Components\MarkdownEditor::make('kegiatan_fisik_motorik')
                                ->label('Kegiatan Fisik Motorik')
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
                                ->nullable()
                                ->columnSpan(4),
                            Forms\Components\ToggleButtons::make('kemampuan_fisik_motorik')
                                ->label('Kemampuan Fisik Motorik')
                                ->options([
                                    '3' => '⭐⭐⭐',
                                    '2' => '⭐⭐',
                                    '1' => '⭐',
                                ]),
                        ])->columns(5),
                        Group::make([
                            Forms\Components\MarkdownEditor::make('kegiatan_kognitif')
                                ->label('Kegiatan Kognitif')
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
                                ->nullable()
                                ->columnSpan(4),
                            Forms\Components\ToggleButtons::make('kemampuan_kognitif')
                                ->label('Kemampuan Kognitif')
                                ->options([
                                    '3' => '⭐⭐⭐',
                                    '2' => '⭐⭐',
                                    '1' => '⭐',
                                ]),
                        ])->columns(5),
                        Group::make([
                            Forms\Components\MarkdownEditor::make('sosial_emosi')
                            ->label('Sosial Emosi')
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
                            ->nullable(),
                        Forms\Components\MarkdownEditor::make('catatan_khusus')
                            ->label('Catatan Khusus')
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
                            ->nullable(),
                        ])->columns(2),
                    ]),

                Forms\Components\Section::make('Kebutuhan Harian')
                    ->icon('heroicon-m-pencil-square')->iconColor('warning')
                    ->schema([
                        Forms\Components\Textarea::make('snack')
                            ->label('Snack')
                            ->rows(5)
                            ->columnSpan(2)
                            ->nullable(),
                        Forms\Components\Textarea::make('makan_siang')
                            ->label('Makan Siang')
                            ->rows(5)
                            ->columnSpan(2)
                            ->nullable(),
                        Forms\Components\Radio::make('tidur_siang')
                            ->label('Tidur Siang')
                            ->options([
                                'Tidak tidur' => 'Tidak tidur',
                                'Tidur kurang dari 1 jam' => 'Tidur kurang dari 1 jam',
                                'Tidur 1 jam' => 'Tidur 1 jam',
                                'Tidur lebih dari 1 jam' => 'Tidur lebih dari 1 jam',
                            ])
                            ->nullable(),
                    ])->columns(5),

                Forms\Components\Section::make('Keterangan Guru & Koordinator')
                    ->schema([
                        Forms\Components\Select::make('guru_id')
                            ->label('Nama Guru')
                            ->relationship('guru', 'nama_guru', function (Builder $query) {
                                $query->gurus(); // Hanya menampilkan guru
                            }) // Asumsi ada relasi di model Guru
                            ->required(),
                        Forms\Components\Select::make('koordinator_id')
                            ->label('Koordinator')
                            ->relationship('koordinator', 'nama_guru', function (Builder $query) {
                                $query->koordinators(); // Hanya menampilkan koordinator
                            }) // Asumsi ada relasi di model Guru
                            ->required(),
                        Forms\Components\ToggleButtons::make('status_approval')
                            ->label('Status Approval')
                            ->default('Menunggu Review')
                            ->options([
                                'Menunggu Review' => 'Menunggu Review',
                                'Disetujui' => 'Disetujui',
                                'Dikembalikan' => 'Dikembalikan',
                            ])
                            ->inline()
                            //->disabled(fn () => auth()->user()->hasRole('guru'))  // Hanya koordinator yang bisa mengubah
                            //->hidden(fn () => auth()->user()->hasRole('guru'))    // Guru tidak melihat status approval
                            ->required(),
                    ])->columns(3),
            ])->columns(1);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(fn($rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('tanggal_pelaksanaan')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtemaHarian.subtema')
                    ->label('Subtema')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Lengkap')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.nama_guru')
                    ->label('Nama Guru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_approval')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu Review' => 'warning',
                        'Disetujui' => 'success',
                        'Dikembalikan' => 'danger',
                    }),
            ])
            ->defaultSort('tanggal_pelaksanaan', 'desc', 'siswa.nama_lengkap', 'asc')
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
            'index' => Pages\ListLaporanHarians::route('/'),
            'create' => Pages\CreateLaporanHarian::route('/create'),
            'edit' => Pages\EditLaporanHarian::route('/{record}/edit'),
        ];
    }
}
