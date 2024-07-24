<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CutiDokterResource\Pages;
use App\Filament\Resources\CutiDokterResource\RelationManagers;
use App\Models\CutiDokter;
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

class CutiDokterResource extends Resource
{
    protected static ?string $model = CutiDokter::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Data Cuti Dokter';

    protected static ?int $navigationSort = 1;

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
                            ->required(),
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
                    ->label('Nama Dokter'),
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
                    ->label('Cari Dokter')
                    ->searchable()
                    ->preload(),
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
