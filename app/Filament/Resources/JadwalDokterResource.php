<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalDokterResource\Pages;
use App\Filament\Resources\JadwalDokterResource\RelationManagers;
use App\Models\JadwalDokter;
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

class JadwalDokterResource extends Resource
{
    protected static ?string $model = JadwalDokter::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Data Jadwal Dokter';

    protected static ?int $navigationSort = -1;

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
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_dokter}"),
                        DatePicker::make('tanggal')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $dayName = Carbon::parse($state)->locale('id')->dayName;
                                    $set('hari', ucfirst($dayName));
                                }
                            }),
                        TextInput::make('hari')
                            ->label('Hari')
                            ->disabled()
                            ->dehydrated(true),
                        Grid::make(2)
                            ->schema([
                                TimePicker::make('jam_mulai')
                                    ->required()
                                    ->withoutSeconds(),
                                TimePicker::make('jam_selesai')
                                    ->required()
                                    ->withoutSeconds(),
                            ]),
                        TextInput::make('kuota')
                            ->numeric()
                            ->required()
                            ->rules(['min:1'])
                    ])->columns(1),
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
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_dokter}"),
                    Repeater::make('jadwal')
                        ->schema([
                            DatePicker::make('tanggal')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $dayName = Carbon::parse($state)->locale('id')->dayName;
                                        $set('hari', ucfirst($dayName));
                                    }
                                }),
                            TextInput::make('hari')
                                ->label('Hari')
                                ->disabled()
                                ->dehydrated(true),
                            Grid::make(2)
                                ->schema([
                                    TimePicker::make('jam_mulai')
                                        ->required()
                                        ->withoutSeconds(),
                                    TimePicker::make('jam_selesai')
                                        ->required()
                                        ->withoutSeconds(),
                                ]),
                            TextInput::make('kuota')
                                ->numeric()
                                ->required()
                                ->rules(['min:1'])
                        ])
                        ->columns(2)
                        ->defaultItems(1)
                        ->createItemButtonLabel('Tambah Jadwal')
                ])->columns(1),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dokter.nama_dokter')
                    ->label('Nama Dokter')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date() 
                    ->sortable()
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
                    ->searchable(),
                TextColumn::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kuota')
                    ->label('Kuota')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Filter Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal'],
                            fn (Builder $query, $date): Builder => $query->whereDate('tanggal', $date),
                        );
                    }),
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
                SelectFilter::make('id_dokter')
                    ->label('Filter Dokter')
                    ->relationship('dokter', 'nama_dokter')
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
            'index' => Pages\ListJadwalDokters::route('/'),
            'create' => Pages\CreateJadwalDokter::route('/create'),
            'edit' => Pages\EditJadwalDokter::route('/{record}/edit'),
        ];
    }
}
