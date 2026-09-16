<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseRequestResource\Pages;
use App\Models\PurchaseRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestResource extends Resource
{
    protected static ?string $model = PurchaseRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Manajemen Pengadaan';
    protected static ?string $navigationLabel = 'Purchase Requests';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengajuan')
                    ->schema([
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode PR')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Otomatis saat disimpan'),
                        
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Permintaan')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required(),
                            
                        Forms\Components\Select::make('priority')
                            ->label('Prioritas')
                            ->options([
                                'low' => 'Rendah (Low)',
                                'normal' => 'Normal',
                                'high' => 'Tinggi (High)',
                                'urgent' => 'Mendesak (Urgent)',
                            ])
                            ->default('normal')
                            ->required(),
                            
                        Forms\Components\DatePicker::make('needed_date')
                            ->label('Dibutuhkan Tanggal')
                            ->required(),
                            
                        Forms\Components\Hidden::make('requester_id')
                            ->default(fn () => Auth::id()),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi / Alasan Pengadaan')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Daftar Barang (Items)')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('item_name')
                                    ->label('Nama Barang')
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('description')
                                    ->label('Spesifikasi'),
                                    
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => $set('subtotal', $state * $get('estimated_price'))),
                                    
                                Forms\Components\TextInput::make('unit')
                                    ->label('Satuan')
                                    ->default('pcs')
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('estimated_price')
                                    ->label('Harga Estimasi (Satuan)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => $set('subtotal', $state * $get('quantity'))),
                                    
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal Estimasi')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->prefix('Rp'),
                                    
                                Forms\Components\Select::make('supplier_id')
                                    ->label('Rekomendasi Supplier')
                                    ->relationship('supplier', 'name'),
                            ])
                            ->columns(3)
                            ->itemLabel(fn (array $state): ?string => $state['item_name'] ?? null)
                            ->collapsible()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode PR')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('requester.name')
                    ->label('Pemohon')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'pending',
                        'success' => fn ($state) => in_array($state, ['approved', 'purchased', 'received']),
                        'danger' => fn ($state) => in_array($state, ['rejected', 'cancelled']),
                    ]),
                    
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioritas')
                    ->colors([
                        'secondary' => 'low',
                        'primary' => 'normal',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ]),
                    
                Tables\Columns\TextColumn::make('needed_date')
                    ->label('Dibutuhkan')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Pengajuan')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPurchaseRequests::route('/'),
            'create' => Pages\CreatePurchaseRequest::route('/create'),
            'edit' => Pages\EditPurchaseRequest::route('/{record}/edit'),
        ];
    }
}
