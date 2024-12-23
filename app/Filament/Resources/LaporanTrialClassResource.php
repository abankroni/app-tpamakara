<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanTrialClassResource\Pages;
use App\Filament\Resources\LaporanTrialClassResource\RelationManagers;
use App\Models\LaporanTrialClass;
use App\Models\PendaftaranTrialClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Barryvdh\DomPDF\Facade\Pdf;


class LaporanTrialClassResource extends Resource
{
    protected static ?string $model = LaporanTrialClass::class;
    protected static ?string $navigationLabel = 'Laporan Trial Class';
    protected static ?string $navigationGroup = 'Laporan Siswa';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('pendaftaran_id')
                            ->label('Nama Lengkap')
                            ->relationship('pendaftaranTrialClass', 'nama_lengkap', function (Builder $query) {
                                $query->whereIn('program_kelas_id', [1, 2]) // Filter program_kelas_id untuk 1 atau 2
                                    ->where('status', 'Aktif');
                            })
                            ->required()
                            ->rules([
                                'required',
                                'exists:pendaftaran_trial_class,id', // Pastikan nama tabel dan kolom sesuai
                            ])
                            ->afterStateUpdated(function (callable $set, $state) use ($form) {
                                $tanggalPelaksanaan = $form->getState()['tanggal_pelaksanaan'] ?? null;
                                if ($tanggalPelaksanaan) {
                                    $namaLengkap = PendaftaranTrialClass::find($state)->nama_lengkap ?? 'Nama tidak ditemukan';
                                    // Mengecek apakah laporan sudah ada untuk pendaftaran_id dan tanggal pelaksanaan yang sama
                                    $exists = LaporanTrialClass::where('pendaftaran_id', $state)
                                                            ->where('tanggal_pelaksanaan', $tanggalPelaksanaan)
                                                            ->exists();
                                    if ($exists) {
                                        $set('pendaftaran_id', null);

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
                    ])->columns(2),

                Forms\Components\Section::make('Detail Laporan Trial Class')
                    ->icon('heroicon-m-document-text')->iconColor('warning')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('aspek_motorik')
                            ->label('Aspek Motorik')
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
                        Forms\Components\MarkdownEditor::make('aspek_kognitif')
                            ->label('Aspek Kognitif')
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
                        Forms\Components\MarkdownEditor::make('aspek_sosial_emosi')
                            ->label('Aspek Sosial Emosi')
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
                        Forms\Components\MarkdownEditor::make('aspek_kemandirian')
                            ->label('Aspek Kemandirian')
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

                Forms\Components\Section::make('Kesimpulan Akhir')
                    ->icon('heroicon-m-bookmark')->iconColor('warning')
                    ->schema([
                        Forms\Components\ToggleButtons::make('kesimpulan')
                            ->label('Rekomendasi')
                            ->options([
                                'dapat bergabung' => 'Dapat Begabung',
                                'belum dapat bergabung' => 'Belum Dapat Bergabung',
                            ])
                            ->inline()
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\Toggle::make('catatan')
                            ->default(false)
                            ->reactive()
                            ->label('Tambah Catatan'),
                        Forms\Components\Textarea::make('detail_catatan')
                            ->label('Detail Catatan')
                            ->rows(3)
                            ->columnSpan(2)
                            ->nullable()
                            ->visible(fn ($get) => $get('catatan')),
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
            ])
            ->columns(1);
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
                Tables\Columns\TextColumn::make('pendaftaranTrialClass.nama_lengkap')
                    ->label('Nama Lengkap')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kesimpulan')
                    ->label('Rekomendasi')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'dapat bergabung' => 'success',
                        'belum dapat bergabung' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('guru.nama_guru')
                    ->label('Nama Guru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_approval')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu Review' => 'warning',
                        'Disetujui' => 'success',
                        'Dikembalikan' => 'danger',
                    }),
            ])
            ->defaultSort('tanggal_pelaksanaan', 'desc', 'pendaftaranTrialClass.nama_lengkap', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('preview_pdf')
                    ->label('PDF')
                    ->url(fn ($record) => route('laporan-trial-class.preview', ['id' => $record->id]))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->status_approval === 'Disetujui')
                    ->icon('heroicon-o-eye')
                    ->color('info'),
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
            'index' => Pages\ListLaporanTrialClasses::route('/'),
            'create' => Pages\CreateLaporanTrialClass::route('/create'),
            'edit' => Pages\EditLaporanTrialClass::route('/{record}/edit'),
        ];
    }
}
