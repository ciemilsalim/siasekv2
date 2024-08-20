<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use function Laravel\Prompts\textarea;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BarangResource\Pages;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\JenisBarang;
use App\Models\Ruang;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Static_;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    
    protected static ?string $navigationGroup = 'Sarana Prasarana';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'kode_barang';

    // protected static int $globalSearchResultsLimit = 2;

    public static function getGloballySearchableAttributes(): array
    {
        return ['kode_barang', 'nama_barang', 'keterangan'];
    } 

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Nama Barang' => $record->nama_barang,
            'Jenis' => $record->jenis_barang->jenis_barang,
            'keterangan' => $record->keterangan
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['jenis_barang']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } 
    
    protected static ?string $navigationLabel = 'Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('kode_barang')
                                    // ->live()
                                    ->required(),
                                    // ->unique(),
                                    // ->default(Barang::getNextKodeBarang())
                                    // ->readOnly(),
                                    TextInput::make('nama_barang')
                                        ->required()
                                        ->columnSpan('full'),
                                    Select::make('jenis_barang_id')
                                        ->relationship('jenis_barang','jenis_barang')
                                        ->createOptionForm([
                                            TextInput::make('jenis_barang')
                                            ->required()
                                        ])
                                        ->options(JenisBarang::all()->pluck('jenis_barang', 'id'))
                                        ->searchable()
                                        ->required(),
                                    TextInput::make('merk_barang')
                                        ->required(),
                                    Select::make('kondisi_barang')
                                            ->options([
                                                'baik' => 'Baik',
                                                'rusak ringan' => 'Rusak Ringan',
                                                'rusak berat' => 'Rusak Berat'
                                            ])
                                            ->required(),
                                    TextInput::make('tahun_pembelian')
                                            ->required(),
                            ])->columns(2),
                        
                    ]),
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Toggle::make('status_barang')
                                    ->label('Barang Aktif')
                                    ->helperText('Aktifkan Jika barang masih digunakan')
                                    ->required(),
                                Toggle::make('status_kepemilikan')
                                        ->label('Status Milik')
                                        ->helperText('aktifkan jika barang milik sekolah')
                                        ->required(),
                                Select::make('ruang_id')
                                        ->relationship('ruang', 'nama_ruang')
                                        ->createOptionForm([
                                            TextInput::make('nama_ruang')
                                            ->required()
                                        ])
                                        ->label('Tempat Penyimpanan')
                                        ->options(Ruang::all()->pluck('nama_ruang', 'id'))
                                        ->searchable()
                                        ->columnSpan('full')
                                        ->required(),                               
                                Textarea::make('keterangan')
                                        ->required()
                                        ->columnSpan('full'),
                            ])->columns(2),
                    ]),
                Section::make([
                    FileUpload::make('foto_barang')
                        ->label('Gambar')
                        ->image()
                        ->imageEditor()
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')
                    ->label('Kode Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jenis_barang.jenis_barang')
                    ->label('Jenis')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('kondisi_barang')
                    ->label('Kondisi')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('status_kepemilikan')
                    ->label('Status Milik')
                    ->toggleable()
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('status_kepemilikan')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Barang milik sekolah')
                    ->falseLabel('Barang bukan milik sekolah')
                    ->native(false),
                
                SelectFilter::make('jenis_barang')
                    ->relationship('jenis_barang', 'jenis_barang')
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
