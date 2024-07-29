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
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Set;
use App\Models\CutiDokter;
use App\Models\JadwalKhususDokter;

class RegistrasiResource extends Resource
{
    protected static ?string $model = Registrasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Data Registrasi';

    protected static ?int $navigationSort = -2;

    protected static ?string $navigationGroup = 'Manajemen Pasien';

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
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y')
                            ->afterStateUpdated(function ($state, callable $set, $livewire) {
                                $tomorrow = Carbon::tomorrow()->startOfDay();
                                $maxDate = Carbon::tomorrow()->addDays(6)->endOfDay();
                                
                                $selectedDate = $state ? Carbon::parse($state) : null;
                                
                                if ($selectedDate && ($selectedDate->lt($tomorrow) || $selectedDate->gt($maxDate))) {
                                    $set('tanggal_kunjungan', $tomorrow->format('Y-m-d'));
                                    
                                    Notification::make()
                                        ->title('Tanggal tidak valid')
                                        ->body('Tanggal kunjungan harus antara ' . $tomorrow->format('d/m/Y') . ' dan ' . $maxDate->format('d/m/Y') . '.')
                                        ->warning()
                                        ->send();
                                }
                            })
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
                            ->options(function (Get $get, $livewire): Collection {
                                $tanggalKunjungan = $get('tanggal_kunjungan');
                                $idPoliklinik = $get('id_poliklinik');
                                
                                if (!$tanggalKunjungan || !$idPoliklinik) {
                                    return Collection::make();
                                }
                        
                                $hariKunjungan = Carbon::parse($tanggalKunjungan)->locale('id')->dayName;
                        
                                $specialSchedules = JadwalKhususDokter::where('tanggal', $tanggalKunjungan)
                                    ->whereHas('dokter', function ($query) use ($idPoliklinik) {
                                        $query->where('id_poliklinik', $idPoliklinik);
                                    })
                                    ->with('dokter')
                                    ->get();
        
                                if ($specialSchedules->isNotEmpty()) {
                                    return $specialSchedules->mapWithKeys(function ($jadwal) use ($livewire) {
                                        $dokter = $jadwal->dokter;
                                        $label = "{$dokter->nama_dokter} - {$jadwal->jam_mulai} s/d {$jadwal->jam_selesai}";
                                        
                                        if (!$livewire instanceof Pages\EditRegistrasi) {
                                            $label .= " (Kuota: {$jadwal->kuota})";
                                        }
                        
                                        return [$dokter->id_dokter => $label];
                                    });
                                }
        
                                $query = JadwalDokter::query()
                                    ->whereHas('dokter', function ($query) use ($idPoliklinik) {
                                        $query->where('id_poliklinik', $idPoliklinik);
                                    })
                                    ->where('hari', $hariKunjungan)
                                    ->with('dokter');
                        
                                if (!$livewire instanceof Pages\EditRegistrasi) {
                                    $query->where('kuota', '>', 0);
                                }
                        
                                $dokterCuti = CutiDokter::where('tanggal_mulai', '<=', $tanggalKunjungan)
                                    ->where('tanggal_selesai', '>=', $tanggalKunjungan)
                                    ->pluck('id_dokter')
                                    ->toArray();
                        
                                $availableDoctors = $query->get()
                                    ->mapWithKeys(function ($jadwal) use ($livewire, $dokterCuti) {
                                        $dokter = $jadwal->dokter;
                                        $label = "{$dokter->nama_dokter} - {$jadwal->jam_mulai} s/d {$jadwal->jam_selesai}";
                                        
                                        if (!$livewire instanceof Pages\EditRegistrasi) {
                                            $label .= " (Kuota: {$jadwal->kuota})";
                                        }
                        
                                        return [$dokter->id_dokter => $label];
                                    });
                        
                                if ($livewire instanceof Pages\EditRegistrasi) {
                                    return $availableDoctors->isEmpty() 
                                        ? Collection::make(['0' => 'Tidak ada jadwal dokter yang tersedia'])
                                        : $availableDoctors;
                                } else {
                                    $availableDoctors = $availableDoctors->reject(function ($label, $id) use ($dokterCuti) {
                                        return in_array($id, $dokterCuti);
                                    });
                        
                                    return $availableDoctors->isEmpty() 
                                        ? Collection::make(['0' => 'Tidak ada jadwal dokter yang tersedia'])
                                        : $availableDoctors;
                                }
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
                            })
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                if ($state) {
                                    $tanggalKunjungan = $get('tanggal_kunjungan');
                                    $hariKunjungan = Carbon::parse($tanggalKunjungan)->locale('id')->dayName;
                                    $jadwalDokter = JadwalDokter::where('id_dokter', $state)
                                        ->where('hari', $hariKunjungan)
                                        ->first();
                        
                                    if ($jadwalDokter && $jadwalDokter->kuota <= 0) {
                                        Notification::make()
                                            ->title('Kuota Habis')
                                            ->body('Maaf, kuota untuk dokter ini pada hari tersebut sudah habis.')
                                            ->danger()
                                            ->send();
                        
                                        $set('id_dokter', null);
                                    }
                                }
                            }),
                        TextInput::make('kode_booking')
                            ->label('Kode Booking')
                            ->default(fn () => self::generateUniqueBookingCode())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        ViewField::make('qr_code')
                            ->view('filament.components.qrcode')
                            ->visible(fn (Get $get): bool => $get('kode_booking') !== null)
                            ->afterStateHydrated(function (Get $get, Set $set) {
                                $kodeBooking = $get('kode_booking');
                                if ($kodeBooking) {
                                    $set('qr_code', ['kodeBooking' => $kodeBooking]);
                                }
                            })
                            ->label('QR Code'),
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
                TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable(),
                TextColumn::make('tanggal_kunjungan')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('poliklinik.nama_poliklinik')
                    ->label('Poliklinik')
                    ->searchable()
                    ->wrap()
                    ->extraAttributes(['style' => 'width: 200px;']),
                TextColumn::make('dokter.nama_dokter')
                    ->label('Dokter')
                    ->searchable()
                    ->wrap()
                    ->extraAttributes(['style' => 'width: 200px;']),
                TextColumn::make('dokter_schedule')
                    ->label('Jam Praktik')
                    ->searchable()
                    ->getStateUsing(function (Registrasi $record): string {
                        $hariKunjungan = Carbon::parse($record->tanggal_kunjungan)->locale('id')->dayName;
                        $jadwal = JadwalDokter::where('id_dokter', $record->id_dokter)
                            ->where('hari', $hariKunjungan)
                            ->first();
                    
                        if ($jadwal) {
                            $jamMulai = Carbon::parse($jadwal->jam_mulai)->format('H:i');
                            $jamSelesai = Carbon::parse($jadwal->jam_selesai)->format('H:i');
                            return "{$jamMulai} - {$jamSelesai}";
                        }
                    
                        return 'N/A';
                    }),
                TextColumn::make('kode_booking')
                    ->label('Kode Booking')
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
                Filter::make('tanggal_kunjungan')
                    ->form([
                        DatePicker::make('tanggal_kunjungan')
                            ->label('Filter Tanggal Kunjungan'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal_kunjungan'],
                            fn (Builder $query, $date): Builder => $query->whereDate('tanggal_kunjungan', $date),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['tanggal_kunjungan'] ?? null) {
                            return 'Tanggal Kunjungan: ' . Carbon::parse($data['tanggal_kunjungan'])->toFormattedDateString();
                        }
                        return null;
                    }),
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
                Tables\Actions\Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-s-eye')
                    ->color('warning')
                    ->modalHeading('Detail Registrasi')
                    ->modalContent(function (Registrasi $record): View {
                        return view('filament.pages.actions.registrasi', ['record' => $record]);
                    })
                    ->modalSubmitAction(false),
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
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(false);
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
