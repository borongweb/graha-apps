<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sketch;
use Filament\Forms\Get;
use App\Models\Consumer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\File;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ConsumerExporter;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Collection;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\ConsumerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Hydrat\TableLayoutToggle\Facades\TableLayoutToggle;
use App\Filament\Resources\ConsumerResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use RuelLuna\CanvasPointer\Forms\Components\CanvasPointerField;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use App\Filament\Resources\ConsumerResource\Api\Transformers\ConsumerTransformer;

class ConsumerResource extends Resource
{
    protected static ?string $model = Consumer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Konsumen';

    public static function getPluralLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return "Daftar Konsumen";
        } else
            return "Daftar Konsumen";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Konsumen')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autocomplete('off')
                            ->label('Nama Konsumen')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_telp')
                            ->label('No. HP/WA')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('register_on')
                            ->label('Tanggal Daftar')
                            ->default(Carbon::now())
                            ->date()
                            ->required(),
                        Forms\Components\Textarea::make('information')
                            ->label('Keterangan')
                            ->required(),
                        Forms\Components\FileUpload::make('file_id')
                            ->required()
                            ->image()
                            ->maxSize(500)
                            ->imageEditor()
                            ->directory('marketing/id')
                            ->label('Unggah KTP/KK/NPWP (maksimal 500kb)'),
                        Forms\Components\FileUpload::make('file_payment')
                            ->image()
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('payment-',),
                            )
                            ->maxSize(500)
                            ->imageEditor()
                            ->directory('marketing/payment')
                            ->label('Unggah Bukti Pembayaran (maksimal 500kb)')
                            ->live(),
                        TextInput::make('kavling')
                            ->unique()
                            ->hidden(fn(Get $get): bool => ! $get('file_payment')),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $livewire = $table->getLivewire();
        return $table
            ->striped()
            ->columns(
                $livewire->isGridLayout()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->contentGrid(
                fn() => $livewire->isListLayout()
                    ? null
                    : [
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 3,
                    ]
            )
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Booking' => 'Booking',
                        'Belum Booking' => 'Belum Booking',
                    ])
                    ->indicator('Status'),
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('register_on', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('register_on', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->modalHeading(fn(Consumer $record) => $record->name),
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Konsumen'),
                        TextInput::make('kavling'),
                        TextInput::make('information')
                            ->required()
                            ->label('Keterangan'),
                    ]),
                Tables\Actions\Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(function (Consumer $record) {
                        if ($record->file_id != null && Storage::disk('public')->exists($record->file_id)) {
                            Storage::disk('public')->delete($record->file_id);
                        }
                        if ($record->file_payment != null && Storage::disk('public')->exists($record->file_payment)) {
                            Storage::disk('public')->delete($record->file_payment);
                        }
                        $record->delete();
                    })
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Consumer $record) {
                            $record->each(function ($record) {
                                if ($record->file_id != null && Storage::disk('public')->exists($record->file_id)) {
                                    Storage::disk('public')->delete($record->file_id);
                                }
                                if ($record->file_payment != null && Storage::disk('public')->exists($record->file_payment)) {
                                    Storage::disk('public')->delete($record->file_payment);
                                }
                                $record->delete();
                            });
                        })
                ])
            ]);
    }


    public static function getListTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Nama Konsumen')
                ->searchable(),
            Tables\Columns\TextColumn::make('user.name')
                ->label('Nama Marketing')
                ->searchable(),
            Tables\Columns\TextColumn::make('kavling')
                ->default('-')
                ->searchable(),
            Tables\Columns\TextColumn::make('information')
                ->label('Keterangan')
                ->searchable(),
            Tables\Columns\TextColumn::make('register_on')
                ->label('Daftar Pada')
                ->date()
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Booking' => 'success',
                    'Belum Booking' => 'danger',
                }),
        ];
    }
    public static function getGridTableColumns(): array
    {
        return [
            // Make sure to stack your columns together
            Tables\Columns\Layout\Stack::make([

                Tables\Columns\TextColumn::make('status')->badge(),

                // You may group columns together using the Split layout, so they are displayed side by side
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->description(__('Konsumen'), position: 'above')
                        ->weight(FontWeight::Bold)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('user.name')
                        ->description(__('Nama Marketting'), position: 'above')
                        ->weight(FontWeight::Bold)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('kavling')
                        ->description(__('Kavling'), position: 'above')
                        ->weight(FontWeight::Bold)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('information')
                        ->description(__('Keterangan'), position: 'above')
                        ->weight(FontWeight::Bold)
                        ->searchable(),
                ]),

            ])->space(3)->extraAttributes([
                'class' => 'pb-2',
            ]),
        ];
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
            'index' => Pages\ListConsumers::route('/'),
            'create' => Pages\CreateConsumer::route('/create'),
            // 'edit' => Pages\EditConsumer::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('Nama Konsumen'),
                TextEntry::make('user.name')->label('Nama Marketing'),
                TextEntry::make('no_telp')->label('No. HP/WA'),
                TextEntry::make('kavling')->label('Kavling'),
                ImageEntry::make('file_id')->label('Identitas'),
                ImageEntry::make('file_payment')->label('Bukti Pembayaran'),
                TextEntry::make('information')->label('Keterangan')->columnSpan(2),
            ])->columns(3);
    }

    public static function getApiTransformer(){
        return ConsumerTransformer::class;
    }
}