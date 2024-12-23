<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailOrangTuaResource\Pages;
use App\Filament\Resources\DetailOrangTuaResource\RelationManagers;
use App\Models\DetailOrangTua;
use App\Models\Siswa;
use App\Models\PendaftaranTrialClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class DetailOrangTuaResource extends Resource
{
    protected static ?string $model = DetailOrangTua::class;
    protected static ?string $navigationLabel = 'Data Orang Tua';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        // Ambil data pendaftaran yang aktif
        $activeRegistration = PendaftaranTrialClass::where('status', 'Aktif')->first();
        $record = $form->getRecord();
        $id = $record ? $record->id : null;

        return $form
            ->schema([
                Fieldset::make('Pilih Siswa pada daftar berikut:')
                ->schema([
                    Forms\Components\Select::make('siswa_id')
                    ->label('')
                    ->inlineLabel()
                    ->relationship('siswa', 'nama_lengkap', function (Builder $query) {
                        if (request()->route()->getName() !== 'filament.admin.resources.detail-orang-tuas.edit') {
                            $query->whereNotIn('id', DetailOrangTua::pluck('siswa_id'));
                        }
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $siswa = \App\Models\Siswa::with('pendaftaranTrialClass')->find($value);

                        if ($siswa) {
                            return '<strong>' . $siswa->nama_lengkap . '</strong>';
                        }

                        return 'Data tidak ditemukan';
                    })
                    ->default(null)
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Ambil data siswa dengan relasi pendaftaranTrialClass
                        $siswa = Siswa::with('pendaftaranTrialClass')->find($state);

                        if ($siswa) {
                            // Set field berdasarkan data relasi
                            $pendaftaran = $siswa->pendaftaranTrialClass;
                            $set('nama_lengkap_ayah', $pendaftaran->nama_lengkap_ayah ?? null);
                            $set('nama_lengkap_ibu', $pendaftaran->nama_lengkap_ibu ?? null);
                            $set('nama_lengkap_wali', $pendaftaran->nama_pengantar ?? null);
                        } else {
                            // Reset jika data pendaftaran tidak ditemukan
                            $set('nama_lengkap_ayah', null);
                            $set('nama_lengkap_ibu', null);
                            $set('nama_lengkap_wali', null);
                        }
                    })
                ])->disabled(function () {
                    return request()->route()->getName() === 'filament.admin.resources.detail-orang-tuas.edit';
                }),


                Tabs::make('Data Orang Tua')
                    ->columnSpan('full')
                    ->tabs([
                        Tabs\Tab::make('Data Ayah')
                        ->badge(1)
                        ->schema([
                            Forms\Components\TextInput::make('nama_lengkap_ayah')
                                ->required()
                                ->label('Nama Lengkap Ayah'),
                            Forms\Components\DatePicker::make('tanggal_lahir_ayah')
                                ->required()
                                ->label('Tanggal Lahir Ayah'),
                            Forms\Components\Select::make('agama_ayah')
                                ->required()
                                ->options([
                                    'Islam' => 'Islam',
                                    'Kristen' => 'Kristen',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
                                ])
                                ->label('Agama Ayah'),
                            Forms\Components\TextInput::make('pend_terakhir_ayah')
                                ->nullable()
                                ->label('Pendidikan Terakhir Ayah'),
                            Forms\Components\Textarea::make('alamat_ayah')
                                ->nullable()
                                ->label('Alamat Domisili Ayah'),
                            Forms\Components\TextInput::make('email_ayah')
                                ->nullable()
                                ->email()
                                ->label('Email Ayah'),
                            Forms\Components\TextInput::make('no_hp_ayah')
                                ->nullable()
                                ->label('Nomor Handphone Ayah'),
                            Forms\Components\TextInput::make('pekerjaan_ayah')
                                ->nullable()
                                ->label('Pekerjaan Ayah'),
                            Forms\Components\TextInput::make('institusi_ayah')
                                ->nullable()
                                ->label('Institusi Ayah'),
                            Forms\Components\Textarea::make('alamat_institusi_ayah')
                                ->nullable()
                                ->label('Alamat Institusi Ayah'),
                        ]),

                        Tabs\Tab::make('Data Ibu')
                        ->badge(2)
                        ->schema([
                            Forms\Components\TextInput::make('nama_lengkap_ibu')
                                ->required()
                                ->label('Nama Lengkap Ibu'),
                            Forms\Components\DatePicker::make('tanggal_lahir_ibu')
                                ->required()
                                ->label('Tanggal Lahir Ibu'),
                            Forms\Components\Select::make('agama_ibu')
                                ->required()
                                ->options([
                                    'Islam' => 'Islam',
                                    'Kristen' => 'Kristen',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
                                ])
                                ->label('Agama Ibu'),
                            Forms\Components\TextInput::make('pend_terakhir_ibu')
                                ->nullable()
                                ->label('Pendidikan Terakhir Ibu'),
                            Forms\Components\Textarea::make('alamat_ibu')
                                ->nullable()
                                ->label('Alamat Domisili Ibu'),
                            Forms\Components\TextInput::make('email_ibu')
                                ->nullable()
                                ->email()
                                ->label('Email Ibu'),
                            Forms\Components\TextInput::make('no_hp_ibu')
                                ->nullable()
                                ->label('Nomor Handphone Ibu'),
                            Forms\Components\TextInput::make('pekerjaan_ibu')
                                ->nullable()
                                ->label('Pekerjaan Ibu'),
                            Forms\Components\TextInput::make('institusi_ibu')
                                ->nullable()
                                ->label('Institusi Ibu'),
                            Forms\Components\Textarea::make('alamat_institusi_ibu')
                                ->nullable()
                                ->label('Alamat Institusi Ibu'),
                        ]),

                        Tabs\Tab::make('Data Wali / Pengantar & Penjemput')
                        ->badge(3)
                        ->schema([
                            Forms\Components\TextInput::make('nama_lengkap_wali')
                                ->required()
                                ->label('Nama Lengkap Wali'),
                            Forms\Components\DatePicker::make('tanggal_lahir_wali')
                                ->required()
                                ->label('Tanggal Lahir Wali'),
                            Forms\Components\TextInput::make('pend_terakhir_wali')
                                ->nullable()
                                ->label('Pendidikan Terakhir Wali'),
                            Forms\Components\Textarea::make('alamat_wali')
                                ->nullable()
                                ->label('Alamat Domisili Wali'),
                            Forms\Components\TextInput::make('no_hp_wali')
                                ->nullable()
                                ->label('Nomor Handphone Wali'),
                            Forms\Components\TextInput::make('status_hubungan_wali')
                                ->nullable()
                                ->label('Status Hubungan Anak dengan Wali'),
                        ]),
                    ])->columns(2)
                    ->visible(fn ($get) => $get('siswa_id')),


                Forms\Components\Section::make('Dokumen Orang Tua')
                ->icon('heroicon-m-paper-clip')
                ->iconPosition('before')->iconColor('warning')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('kartu_keluarga')
                        ->collection('kartu_keluarga')
                        ->nullable()
                        ->minSize(200)
                        ->maxSize(2000)
                        ->visibility('private'),
                    SpatieMediaLibraryFileUpload::make('ktp_orang_tua')
                        ->collection('ktp_orang_tua')
                        ->nullable()
                        ->minSize(200)
                        ->maxSize(2000)
                        ->visibility('private'),
                    SpatieMediaLibraryFileUpload::make('npwp_orang_tua')
                        ->collection('npwp_orang_tua')
                        ->nullable()
                        ->minSize(200)
                        ->maxSize(2000)
                        ->visibility('private'),
                ])->columns(2)
                ->visible(fn ($get) => $get('siswa_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(fn($rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Anak')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap_ayah')
                    ->label('Nama Ayah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap_ibu')
                    ->label('Nama Ibu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap_wali')
                    ->label('Wali/Pengantar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp_wali')
                    ->label('No HP Wali/Pengantar')
                    ->searchable(),
            ])
            ->defaultSort('siswa.nama_lengkap', 'asc')
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
            'index' => Pages\ListDetailOrangTuas::route('/'),
            'create' => Pages\CreateDetailOrangTua::route('/create'),
            'edit' => Pages\EditDetailOrangTua::route('/{record}/edit'),
        ];
    }
}
