<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->description('Customer details and destination')
                    ->schema([
                        TextInput::make('customer_name')
                            ->required(),
                        TextInput::make('customer_phone')
                            ->tel()
                            ->required(),
                        Select::make('destination_city')
                            ->options([
                                'Abbottabad' => 'Abbottabad',
                                'Bahawalnagar' => 'Bahawalnagar',
                                'Bahawalpur' => 'Bahawalpur',
                                'Burewala' => 'Burewala',
                                'Chiniot' => 'Chiniot',
                                'Chishtian' => 'Chishtian',
                                'Dera Ghazi Khan' => 'Dera Ghazi Khan',
                                'Dera Ismail Khan' => 'Dera Ismail Khan',
                                'Faisalabad' => 'Faisalabad',
                                'Gojra' => 'Gojra',
                                'Gujranwala' => 'Gujranwala',
                                'Gujrat' => 'Gujrat',
                                'Hafizabad' => 'Hafizabad',
                                'Hub' => 'Hub',
                                'Hyderabad' => 'Hyderabad',
                                'Islamabad' => 'Islamabad',
                                'Jhang' => 'Jhang',
                                'Jhelum' => 'Jhelum',
                                'Kabirwala' => 'Kabirwala',
                                'Kaman' => 'Kaman',
                                'Karachi' => 'Karachi',
                                'Kasur' => 'Kasur',
                                'Khanewal' => 'Khanewal',
                                'Khanpur' => 'Khanpur',
                                'Kohat' => 'Kohat',
                                'Kot Abdul Malik' => 'Kot Abdul Malik',
                                'Kotri' => 'Kotri',
                                'Lahore' => 'Lahore',
                                'Larkana' => 'Larkana',
                                'Mandi Bahauddin' => 'Mandi Bahauddin',
                                'Mardan' => 'Mardan',
                                'Mingora' => 'Mingora',
                                'Mirpur Khas' => 'Mirpur Khas',
                                'Multan' => 'Multan',
                                'Muridke' => 'Muridke',
                                'Muzaffarabad' => 'Muzaffarabad',
                                'Muzaffargarh' => 'Muzaffargarh',
                                'Nawabshah' => 'Nawabshah',
                                'Okara' => 'Okara',
                                'Peshawar' => 'Peshawar',
                                'Quetta' => 'Quetta',
                                'Rahim Yar Khan' => 'Rahim Yar Khan',
                                'Rawalpindi' => 'Rawalpindi',
                                'Sadiqabad' => 'Sadiqabad',
                                'Sahiwal' => 'Sahiwal',
                                'Samundri' => 'Samundri',
                                'Sargodha' => 'Sargodha',
                                'Sheikhupura' => 'Sheikhupura',
                                'Shikarpur' => 'Shikarpur',
                                'Sialkot' => 'Sialkot',
                                'Sukkur' => 'Sukkur',
                                'Tando Allahyar' => 'Tando Allahyar',
                                'Turbat' => 'Turbat',
                                'Wah Cantonment' => 'Wah Cantonment',
                            ])
                            ->required()
                            ->searchable(),
                        Textarea::make('customer_address')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Parcel Details')
                    ->description('Weight, COD amount and courier routing')
                    ->schema([
                        TextInput::make('consignment_no')
                            ->disabled()
                            ->placeholder('Auto Generated after booking'),
                        TextInput::make('weight')
                            ->numeric()
                            ->default(0.5)
                            ->required(),
                        TextInput::make('pieces')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('cod_amount')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->label('COD Amount (Rs.)'),
                        TextInput::make('delivery_charges')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'dispatched' => 'Dispatched',
                                'delivered' => 'Delivered',
                                'returned' => 'Returned',
                            ])
                            ->default('pending')
                            ->required(),
                        Select::make('courier_integration_id')
                            ->relationship('courier_integration', 'courier_name')
                            ->label('Select Courier Partner')
                            ->searchable()
                            ->preload(),
                    ])->columns(3),
            ]);
    }
}