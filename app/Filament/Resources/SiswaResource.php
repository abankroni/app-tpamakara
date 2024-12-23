<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
use App\Models\PendaftaranTrialClass;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;


class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    protected static ?string $navigationLabel = 'Data Siswa';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        // Ambil data pendaftaran yang aktif
        $activeRegistration = PendaftaranTrialClass::where('status', 'Aktif')->first();
        $record = $form->getRecord();
        $id = $record ? $record->id : null;

        return $form
            ->schema([
                Fieldset::make('Pilih Siswa dari daftar Trial Class:')
                ->schema([
                    Forms\Components\Select::make('pendaftaran_trial_class_id')
                    ->nullable()
                    ->inlineLabel()
                    ->label('')
                    ->relationship('pendaftaranTrialClass', 'nama_lengkap', function (Builder $query) {
                        // Retrieve IDs of already selected 'pendaftaran_trial_class_id' values
                        $usedIds = Siswa::pluck('pendaftaran_trial_class_id')->toArray();

                        $query->whereIn('program_kelas_id', [3, 4]) // Filter for specific 'program_kelas_id'
                            ->where('status', 'Aktif') // Filter for status 'Aktif'
                            ->whereNotIn('id', $usedIds); // Exclude already selected records
                    })
                    ->default(null)
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state, $get) {
                        if ($state) {
                            $pendaftaran = PendaftaranTrialClass::find($state);
                            $set('nama_lengkap', $pendaftaran->nama_lengkap ?? null);
                            $set('nama_panggilan', $pendaftaran->nama_panggilan ?? null);
                            $set('jenis_kelamin', $pendaftaran->jenis_kelamin ?? null);
                            $set('tempat_lahir', $pendaftaran->tempat_lahir ?? null);
                            $set('tanggal_lahir', $pendaftaran->tanggal_lahir ?? null);
                        } else {
                            $set('nama_lengkap', null);
                            $set('nama_panggilan', null);
                            $set('jenis_kelamin', null);
                            $set('tempat_lahir', null);
                            $set('tanggal_lahir', null);
                        }
                    })
                ])->visible(fn () => !in_array(request()->route()->getName(), ['filament.admin.resources.siswas.edit'])),

                Forms\Components\Section::make('Data Diri Siswa')
                ->icon('heroicon-m-user')
                ->iconPosition('before')->iconColor('warning')
                ->schema([
                    Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->label('Nama Lengkap Anak'),
                    Forms\Components\TextInput::make('nama_panggilan')
                        ->required()
                        ->label('Nama Panggilan Anak'),
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
                    Forms\Components\Select::make('agama')
                        ->required()
                        ->options([
                            'Islam' => 'Islam',
                            'Kristen' => 'Kristen',
                            'Katolik' => 'Katolik',
                            'Hindu' => 'Hindu',
                            'Buddha' => 'Buddha',
                            'Konghucu' => 'Konghucu',
                        ])
                        ->label('Agama'),
                    Forms\Components\TextInput::make('urutan_anak_dalam_keluarga')
                        ->required()
                        ->numeric()
                        ->prefix('Anak ke-')
                        ->label('Urutan Anak dalam Keluarga'),
                    Forms\Components\Select::make('program_kelas_id')
                        ->required()
                        ->relationship('programKelas', 'nama_kelas')
                        ->options(fn (callable $get) => Program::where('nama_kelas', 'NOT LIKE', 'Trial%')->pluck('nama_kelas', 'id'))
                        ->label('Program Kelas')
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                            // Ambil durasi dari program yang dipilih
                            $program = Program::find($state);

                            if ($program) {
                                // Ambil durasi dalam hari
                                $durasi = $program->durasi; // Pastikan kolom durasi ada di tabel program_kelas

                                // Ambil tanggal mulai
                                $tanggalMulai = $get('Tanggal_mulai'); // Gunakan $get untuk mendapatkan nilai

                                if ($tanggalMulai) {
                                    // Hitung tanggal berakhir
                                    $tanggalBerakhir = Carbon::parse($tanggalMulai)->addDays($durasi);
                                    $set('tanggal_berakhir', $tanggalBerakhir); // Set tanggal berakhir
                                }
                            } else {
                                // Jika tidak ada program yang dipilih, set tanggal berakhir menjadi null
                                $set('tanggal_berakhir', null);
                            }
                        }),
                    Forms\Components\DatePicker::make('tanggal_mulai')
                        ->required()
                        ->label('Tanggal Mulai Program')
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                            // Jika tanggal mulai diubah, hitung ulang tanggal berakhir
                            $programKelasId = $get('program_kelas_id'); // Ambil ID program kelas yang dipilih
                            $program = Program::find($programKelasId);

                            if ($program && $state) {
                                // Ambil durasi dari program yang dipilih
                                $durasi = $program->durasi; // Pastikan kolom durasi ada di tabel program_kelas

                                // Hitung tanggal berakhir
                                $tanggalBerakhir = Carbon::parse($state)->addDays($durasi);
                                $set('tanggal_berakhir', $tanggalBerakhir); // Set tanggal berakhir
                            }
                        }),
                    Forms\Components\DatePicker::make('tanggal_berakhir')
                        ->required()
                        ->label('Tanggal Berakhir Program'),
                ])->visible(fn ($get) => $get('pendaftaran_trial_class_id'))
                ->columns(2),

                Tabs::make('Tentang Anak')
                ->columnSpan('full')
                ->tabs([
                    Tabs\Tab::make('Kebiasaan Anak')
                    ->icon('heroicon-m-information-circle')
                    ->iconPosition('before')
                    ->schema([
                        Forms\Components\TextArea::make('kebiasaan_makan')
                            ->nullable()
                            ->label('Kebiasaan Makan'),
                        Forms\Components\TextArea::make('kebiasaan_minum')
                            ->nullable()
                            ->label('Kebiasaan Minum'),
                        Forms\Components\Textarea::make('kebiasaan_tidur')
                            ->nullable()
                            ->label('Kebiasaan Tidur'),
                        Forms\Components\TextArea::make('kebiasaan_bakbab')
                            ->nullable()
                            ->label('Kebiasaan BAK atau BAB'),
                    ]),
                    Tabs\Tab::make('Catatan Tentang Anak')
                    ->icon('heroicon-m-pencil-square')
                    ->iconPosition('before')
                    ->schema([
                        Forms\Components\Toggle::make('catatan_khusus_medis')
                            ->default(false) // Set default value to false
                            ->reactive() // Make it reactive
                            ->label('Catatan Khusus Medis'),
                        Forms\Components\TextArea::make('deskripsi_catatan_medis')
                            ->visible(fn ($get) => $get('catatan_khusus_medis'))
                            ->nullable()
                            ->label('Deskripsi Catatan Khusus Medis'),
                        Forms\Components\TextArea::make('penyakit_berat')
                            ->nullable()
                            ->label('Penyakit berat yang pernah dialami anak'),
                        Forms\Components\TextArea::make('keadaan_anak')
                            ->nullable()
                            ->helperText('Contoh: "Alergi, makanan atau minuman yang tidak dikonsumsi".')
                            ->label('Keadaan anak yang perlu diperhatikan khusus oleh pihak TPA Makara'),
                        Forms\Components\TextArea::make('sifat_baik')
                            ->nullable()
                            ->label('Sifat-sifat baik/ positif dari anak yang menonjol'),
                        Forms\Components\TextArea::make('sifat_perlu_perhatian')
                            ->nullable()
                            ->label('Sifat-sifat anak yang saat ini masih perlu mendapatkan perhatian'),
                    ]),
                ])->columns(2)
                ->visible(fn ($get) => $get('pendaftaran_trial_class_id')),


                Forms\Components\Section::make('Dokumen Siswa')
                ->icon('heroicon-m-paper-clip')
                ->iconPosition('before')->iconColor('warning')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('foto_anak')
                        ->label('Foto Anak')
                        ->collection('foto_anak')
                        ->nullable()
                        ->minSize(200)
                        ->maxSize(2000)
                        ->disk('public')
                        ->visibility('public'),
                    SpatieMediaLibraryFileUpload::make('akta_lahir_anak')
                        ->label('Akta Lahir Anak')
                        ->collection('akta_lahir_anak')
                        ->nullable()
                        ->minSize(200)
                        ->maxSize(2000)
                        ->visibility('private'),
                ])->columns(2)
                ->visible(fn ($get) => $get('pendaftaran_trial_class_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(fn($rowLoop) => $rowLoop->index + 1),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('foto_anak')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
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
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->label('Tanggal Berakhir')
                    ->date('d F Y')
                    ->sortable(),
            ])
            ->defaultSort('nama_lengkap', 'asc')
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
