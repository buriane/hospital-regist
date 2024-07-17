<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasienResource\Pages;
use App\Filament\Resources\PasienResource\RelationManagers;
use App\Models\Pasien;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Radio;
use Illuminate\Validation\Rule;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class PasienResource extends Resource
{
    protected static ?string $model = Pasien::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Data Pasien';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nomor_rm')
                        ->numeric()
                        ->length(6)
                        ->maxLength(6)
                        ->unique(ignorable: fn ($record) => $record)
                        ->rule(
                            fn ($record) => 
                            Rule::unique('pasiens', 'nomor_rm')->ignore($record)
                        )
                        ->validationAttribute('Nomor RM')
                        ->required()
                        ->label('Nomor Rekam Medis')
                        ->extraInputAttributes(['onInput' => 'if(this.value.length > 6) this.value = this.value.slice(0, 6);']),
                        TextInput::make('nama_pasien')->required(),
                        TextInput::make('tempat_lahir')->required(),
                        DatePicker::make('tanggal_lahir')->required(),
                        Radio::make('jenis_kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])
                                ->required(),
                        TextArea::make('alamat'),
                        TextInput::make('nomor_telepon')->numeric()->required(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->rule(
                                fn ($record) => 
                                Rule::unique('pasiens', 'email')->ignore($record)
                            )
                            ->validationAttribute('Email'),
                        TextInput::make('nomor_kartu')
                            ->numeric()
                            ->unique(ignorable: fn ($record) => $record)
                            ->rule(
                                fn ($record) => 
                                Rule::unique('pasiens', 'nomor_kartu')->ignore($record)
                            )
                            ->validationAttribute('Nomor Kartu'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),                
                TextColumn::make('nomor_rm')->label('Nomor RM')->searchable(),
                TextColumn::make('nama_pasien')->label('Nama Pasien')->searchable(),
                TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('alamat')->label('Alamat')->wrap()->extraAttributes(['style' => 'width: 300px;']),
                TextColumn::make('nomor_telepon')->label('Nomor Telepon'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('nomor_kartu')->label('Nomor Kartu'),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePasiens::route('/'),
        ];
    }
}
