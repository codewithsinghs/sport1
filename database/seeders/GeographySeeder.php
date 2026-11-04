<?php

namespace Database\Seeders;

use App\Modules\Geography\Models\District;
use App\Modules\Geography\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GeographySeeder extends Seeder
{
    public function run(): void
    {
        $state = State::query()->updateOrCreate(
            ['code' => 'MP'],
            [
                'name' => 'Madhya Pradesh',
                'country_code' => 'IN',
                'is_active' => true,
            ]
        );

        foreach ($this->districts() as $name) {
            District::query()->updateOrCreate(
                [
                    'state_id' => $state->getKey(),
                    'slug' => Str::slug($name),
                ],
                [
                    'name' => $name,
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * @return list<string>
     */
    private function districts(): array
    {
        return [
            'Agar Malwa',
            'Alirajpur',
            'Anuppur',
            'Ashoknagar',
            'Balaghat',
            'Barwani',
            'Betul',
            'Bhind',
            'Bhopal',
            'Burhanpur',
            'Chhatarpur',
            'Chhindwara',
            'Damoh',
            'Datia',
            'Dewas',
            'Dhar',
            'Dindori',
            'Guna',
            'Gwalior',
            'Harda',
            'Indore',
            'Jabalpur',
            'Jhabua',
            'Katni',
            'Khandwa',
            'Khargone',
            'Maihar',
            'Mandla',
            'Mandsaur',
            'Mauganj',
            'Morena',
            'Narmadapuram',
            'Narsinghpur',
            'Neemuch',
            'Niwari',
            'Panna',
            'Pandhurna',
            'Raisen',
            'Rajgarh',
            'Ratlam',
            'Rewa',
            'Sagar',
            'Satna',
            'Sehore',
            'Seoni',
            'Shahdol',
            'Shajapur',
            'Sheopur',
            'Shivpuri',
            'Sidhi',
            'Singrauli',
            'Tikamgarh',
            'Ujjain',
            'Umaria',
            'Vidisha',
        ];
    }
}
