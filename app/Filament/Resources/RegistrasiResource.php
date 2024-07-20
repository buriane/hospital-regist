<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrasiResource\Pages;
use App\Filament\Resources\RegistrasiResource\RelationManagers;
use App\Models\Registrasi;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\Dokter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Illuminate\Support\Collection;
use App\Models\JadwalDokter;
use Carbon\Carbon;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BadgeColumn;

class RegistrasiResource extends Resource
{
    protected static ?string $model = Registrasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Data Registrasi';

    protected static ?int $navigationSort = -2;

    private static function generateUniqueBookingCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2) . 
                            rand(100, 999) . 
                            substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1));
        } while (Registrasi::where('kode_booking', $code)->exists());

        return $code;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('id_pasien')
                            ->label('Pasien')
                            ->relationship('pasien', 'nama_pasien')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_pasien} - {$record->nomor_rm}")
                            ->disabled(fn ($livewire) => $livewire instanceof Pages\EditRegistrasi),
                        DatePicker::make('tanggal_kunjungan')
                            ->required()
                            ->live()
                            ->disabled(fn ($livewire) => $livewire instanceof Pages\EditRegistrasi),
                        Select::make('id_poliklinik')
                            ->label('Poliklinik')
                            ->relationship('poliklinik', 'nama_poliklinik')
                            ->required()
                            ->live()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_poliklinik}")
                            ->disabled(fn ($livewire) => $livewire instanceof Pages\EditRegistrasi),
                        Select::make('id_dokter')
                            ->label('Dokter')
                            ->options(function (Get $get): Collection {
                                $tanggalKunjungan = $get('tanggal_kunjungan');
                                $idPoliklinik = $get('id_poliklinik');
                                
                                if (!$tanggalKunjungan || !$idPoliklinik) {
                                    return Collection::make();
                                }
                        
                                $availableDoctors = JadwalDokter::query()
                                    ->whereHas('dokter', function ($query) use ($idPoliklinik) {
                                        $query->where('id_poliklinik', $idPoliklinik);
                                    })
                                    ->whereDate('tanggal', $tanggalKunjungan)
                                    ->where('kuota', '>', 0)
                                    ->with('dokter')
                                    ->get()
                                    ->mapWithKeys(function ($jadwal) {
                                        $dokter = $jadwal->dokter;
                                        $label = "{$dokter->nama_dokter} - {$jadwal->jam_mulai} s/d {$jadwal->jam_selesai} (Kuota: {$jadwal->kuota})";
                                        return [$dokter->id_dokter => $label];
                                    });
                        
                                if ($availableDoctors->isEmpty()) {
                                    return Collection::make(['0' => 'Tidak ada jadwal dokter yang tersedia']);
                                }
                        
                                return $availableDoctors;
                            })
                            ->required()
                            ->disabled(fn ($livewire, Get $get): bool => 
                                $livewire instanceof Pages\EditRegistrasi || 
                                !$get('tanggal_kunjungan') || 
                                !$get('id_poliklinik')
                            )
                            ->visible(fn (Get $get): bool => $get('tanggal_kunjungan') !== null && $get('id_poliklinik') !== null)
                            ->afterStateHydrated(function (Select $component, $state) {
                                if ($state === '0') {
                                    $component->disabled();
                                }
                            }),
                        TextInput::make('kode_booking')
                            ->label('Kode Booking')
                            ->default(fn () => self::generateUniqueBookingCode())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'Confirmed' => 'Confirmed',
                                'Canceled' => 'Canceled',
                            ])
                            ->required()
                            ->default('Pending')
                            ->visible(fn ($livewire) => $livewire instanceof Pages\EditRegistrasi),
                        Hidden::make('status')
                            ->default('Pending')
                            ->dehydrated(true)
                            ->required()
                            ->visible(fn ($livewire) => $livewire instanceof Pages\CreateRegistrasi),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pasien.nomor_rm')
                    ->label('Nomor RM')
                    ->searchable(),
                TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable(),
                TextColumn::make('tanggal_kunjungan')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('poliklinik.nama_poliklinik')
                    ->label('Poliklinik')
                    ->searchable(),
                TextColumn::make('dokter.nama_dokter')
                    ->label('Dokter')
                    ->searchable(),
                TextColumn::make('kode_booking')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'Pending' => 'heroicon-o-clock',
                        'Confirmed' => 'heroicon-o-check-circle',
                        'Canceled' => 'heroicon-o-x-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Confirmed' => 'success',
                        'Canceled' => 'danger',
                    })
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('poliklinik')
                    ->relationship('poliklinik', 'nama_poliklinik')
                    ->label('Filter Poliklinik')
                    ->multiple()
                    ->preload()
                    ->placeholder('Pilih Poliklinik')
                    ->searchable(),
                SelectFilter::make('dokter')
                    ->relationship('dokter', 'nama_dokter')
                    ->label('Filter Dokter')
                    ->multiple()
                    ->preload()
                    ->placeholder('Pilih Dokter')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('updateStatusPending')
                        ->label('Set Pending')
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => 'Pending']);
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('updateStatusConfirmed')
                        ->label('Set Confirmed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => 'Confirmed']);
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('updateStatusCanceled')
                        ->label('Set Canceled')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => 'Canceled']);
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListRegistrasis::route('/'),
            'create' => Pages\CreateRegistrasi::route('/create'),
            'edit' => Pages\EditRegistrasi::route('/{record}/edit'),
        ];
    }
}
