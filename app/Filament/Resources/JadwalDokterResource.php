<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalDokterResource\Pages;
use App\Filament\Resources\JadwalDokterResource\RelationManagers;
use App\Models\JadwalDokter;
use App\Models\Dokter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;
use Carbon\Carbon;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;

class JadwalDokterResource extends Resource
{
    protected static ?string $model = JadwalDokter::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Data Jadwal Dokter';

    protected static ?int $navigationSort = -1;

    protected static ?string $navigationGroup = 'Manajemen Dokter';

    private static function determineShift($jamMulai)
    {
        $hour = Carbon::parse($jamMulai)->hour;
        
        if ($hour >= 14 && $hour < 21) {
            return 'Sore';
        } else {
            return 'Pagi';
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('id_dokter')
                            ->label('Dokter')
                            ->relationship('dokter', 'nama_dokter')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_dokter} - {$record->poliklinik->nama_poliklinik}")
                            ->getSearchResultsUsing(fn (string $search) => 
                                Dokter::where('nama_dokter', 'like', "%{$search}%")
                                    ->orWhereHas('poliklinik', function ($query) use ($search) {
                                        $query->where('nama_poliklinik', 'like', "%{$search}%");
                                    })
                                    ->limit(50)
                                    ->get()
                                    ->map(fn ($dokter) => [
                                        'id' => $dokter->id,
                                        'label' => "{$dokter->nama_dokter} - {$dokter->poliklinik->nama_poliklinik}"
                                    ])
                            ),
                        Grid::make(4)
                            ->schema([
                                Select::make('hari')
                                    ->label('Hari')
                                    ->options([
                                        'Senin' => 'Senin',
                                        'Selasa' => 'Selasa',
                                        'Rabu' => 'Rabu',
                                        'Kamis' => 'Kamis',
                                        'Jumat' => 'Jumat',
                                        'Sabtu' => 'Sabtu',
                                        'Minggu' => 'Minggu',
                                    ])
                                    ->required(),
                                TimePicker::make('jam_mulai')
                                    ->required()
                                    ->withoutSeconds()
                                    ->reactive(),
                                TimePicker::make('jam_selesai')
                                    ->required()
                                    ->withoutSeconds()
                                    ->reactive()
                                    ->afterOrEqual('jam_mulai')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state && $get('jam_mulai') && $state <= $get('jam_mulai')) {
                                            Notification::make()
                                                ->danger()
                                                ->title('Gagal edit jadwal dokter')
                                                ->body('Jam selesai harus lebih dari jam mulai.')
                                                ->send();
                                            $set('jam_selesai', null);
                                        }
                                    }),
                                TextInput::make('kuota')
                                    ->numeric()
                                    ->required()
                                    ->rules(['min:1'])
                            ])
                    ])
                    ->columns(1)
            ]);
    }

    public static function getCreateFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Select::make('id_dokter')
                        ->label('Dokter')
                        ->relationship('dokter', 'nama_dokter')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_dokter} - {$record->poliklinik->nama_poliklinik}")
                        ->getSearchResultsUsing(fn (string $search) => 
                            Dokter::where('nama_dokter', 'like', "%{$search}%")
                                ->orWhereHas('poliklinik', function ($query) use ($search) {
                                    $query->where('nama_poliklinik', 'like', "%{$search}%");
                                })
                                ->limit(50)
                                ->get()
                                ->map(fn ($dokter) => [
                                    'id' => $dokter->id,
                                    'label' => "{$dokter->nama_dokter} - {$dokter->poliklinik->nama_poliklinik}"
                                ])
                        ),
                    Repeater::make('jadwal')
                        ->schema([
                            Select::make('hari')
                                ->label('Hari')
                                ->options([
                                    'Senin' => 'Senin',
                                    'Selasa' => 'Selasa',
                                    'Rabu' => 'Rabu',
                                    'Kamis' => 'Kamis',
                                    'Jumat' => 'Jumat',
                                    'Sabtu' => 'Sabtu',
                                    'Minggu' => 'Minggu',
                                ])
                                ->required(),
                            TimePicker::make('jam_mulai')
                                ->required()
                                ->withoutSeconds()
                                ->reactive(),
                            TimePicker::make('jam_selesai')
                                ->required()
                                ->withoutSeconds()
                                ->reactive()
                                ->afterOrEqual('jam_mulai')
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    if ($state && $get('jam_mulai') && $state <= $get('jam_mulai')) {
                                        Notification::make()
                                            ->danger()
                                            ->title('Gagal membuat jadwal dokter')
                                            ->body('Jam selesai harus lebih dari jam mulai.')
                                            ->send();
                                        $set('jam_selesai', null);
                                    }
                                }),
                            TextInput::make('kuota')
                                ->numeric()
                                ->required()
                                ->rules(['min:1']),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->createItemButtonLabel('Tambah Jadwal')
                ])->columns(1)
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dokter.nama_dokter')
                    ->label('Nama Dokter')
                    ->searchable(),
                TextColumn::make('dokter.poliklinik.nama_poliklinik')
                    ->label('Poliklinik')
                    ->searchable(),
                TextColumn::make('hari')
                    ->label('Hari')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('shift')
                    ->label('Shift')
                    ->getStateUsing(fn ($record) => self::determineShift($record->jam_mulai))
                    ->colors([
                        'warning' => 'Pagi',
                        'primary' => 'Sore',
                    ])
                    ->icons([
                        'heroicon-o-sun' => 'Pagi',
                        'heroicon-o-moon' => 'Sore',
                    ]),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->format('H:i')),
                TextColumn::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->format('H:i')),
                TextColumn::make('kuota')
                    ->label('Kuota')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('id_dokter')
                    ->label('Filter Dokter')
                    ->relationship('dokter', 'nama_dokter')
                    ->multiple()
                    ->preload()
                    ->placeholder('Pilih Dokter')
                    ->searchable(),
                SelectFilter::make('poliklinik')
                    ->label('Filter Poliklinik')
                    ->relationship('dokter.poliklinik', 'nama_poliklinik')
                    ->multiple()
                    ->preload()
                    ->placeholder('Pilih Poliklinik')
                    ->searchable(),
                SelectFilter::make('shift')
                    ->label('Filter Shift')
                    ->placeholder('Pilih Shift')
                    ->options([
                        'Pagi' => 'Pagi',
                        'Sore' => 'Sore',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'], function ($query, $shift) {
                            if ($shift === 'Pagi') {
                                return $query->whereTime('jam_mulai', '<', '12:00:00');
                            } elseif ($shift === 'Sore') {
                                return $query->whereTime('jam_mulai', '>=', '12:00:00');
                            }
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListJadwalDokters::route('/'),
            'create' => Pages\CreateJadwalDokter::route('/create'),
            'edit' => Pages\EditJadwalDokter::route('/{record}/edit'),
        ];
    }
}
