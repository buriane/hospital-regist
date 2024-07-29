<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CutiDokterResource\Pages;
use App\Filament\Resources\CutiDokterResource\RelationManagers;
use App\Models\CutiDokter;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;

class CutiDokterResource extends Resource
{
    protected static ?string $model = CutiDokter::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Data Cuti Dokter';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Manajemen Dokter';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('id_dokter')
                            ->label('Dokter')
                            ->relationship('dokter', 'nama_dokter')
                            ->searchable()
                            ->preload()
                            ->required()
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
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->afterOrEqual('tanggal_mulai')
                            ->validationMessages([
                                'after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state && $get('tanggal_mulai') && $state < $get('tanggal_mulai')) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Gagal membuat cuti dokter')
                                        ->body('Tanggal mulai tidak boleh melebihi tanggal selesai.')
                                        ->send();
                                    
                                    $set('tanggal_selesai', null);
                                }
                            }),
                    ]),
            ]);
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
                TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('dokter')
                    ->relationship('dokter', 'nama_dokter')
                    ->label('Filter Dokter')
                    ->placeholder('Pilih Dokter')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('poliklinik')
                    ->label('Filter Poliklinik')
                    ->relationship('dokter.poliklinik', 'nama_poliklinik')
                    ->multiple()
                    ->preload()
                    ->placeholder('Pilih Poliklinik')
                    ->searchable(),
                Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_mulai', '<=', $date)
                                                                            ->whereDate('tanggal_selesai', '>=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['tanggal'] ?? null) {
                            return 'Tanggal: ' . Carbon::parse($data['tanggal'])->format('d/m/Y');
                        }
                        return null;
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
            'index' => Pages\ListCutiDokters::route('/'),
            'create' => Pages\CreateCutiDokter::route('/create'),
            'edit' => Pages\EditCutiDokter::route('/{record}/edit'),
        ];
    }
}
