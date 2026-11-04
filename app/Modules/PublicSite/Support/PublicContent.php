<?php

namespace App\Modules\PublicSite\Support;

class PublicContent
{
    public static function championship(): array
    {
        return [
            'name' => 'National Targetball Championship 2025',
            'edition' => '12th Junior, 4th Mix 11th Sub Junior & 4th Mix Sub Junior',
            'dates' => '27–30 October 2025',
            'venue' => 'Tatya Tope Stadium, T.T. Nagar, Bhopal',
            'organiser' => 'Madhya Pradesh Targetball Association',
        ];
    }

    public static function gameFacts(): array
    {
        return [
            ['value' => '6', 'label' => 'Players a side', 'copy' => 'Two teams of six contest possession on a marked court.'],
            ['value' => '30 × 20', 'label' => 'Metres', 'copy' => 'The court is 30 metres along the base line and 20 metres deep.'],
            ['value' => '4', 'label' => 'Diamond zones', 'copy' => 'Diamond-shaped zones frame each end line, with two goal circles per end.'],
            ['value' => '2012', 'label' => 'Founded', 'copy' => 'Formalised by Dr. Sonu Sharma in Mathura, drawing on a traditional Braj game.'],
        ];
    }

    public static function milestones(): array
    {
        return [
            ['year' => '2012', 'title' => 'The modern game takes shape', 'copy' => 'Dr. Sonu Sharma formalises Targetball in Mathura on 25 May, drawing on a traditional game played in the Braj region.'],
            ['year' => '2013', 'title' => 'First national competition', 'copy' => 'The game moves beyond demonstration matches with its first national-level event in October.'],
            ['year' => '2014', 'title' => 'Women’s competition added', 'copy' => 'A women’s game is introduced, widening the competitive pathway beyond the men’s draw.'],
            ['year' => '2025', 'title' => 'Nationals come to Bhopal', 'copy' => 'Madhya Pradesh hosts the National Targetball Championship at Tatya Tope Stadium from 27 to 30 October.'],
        ];
    }

    public static function gallery(): array
    {
        return [
            ['name' => 'gallery-01', 'alt' => 'State teams marching at the opening of the 2025 National Targetball Championship in Bhopal', 'caption' => 'Opening march past, Tatya Tope Stadium'],
            ['name' => 'gallery-02', 'alt' => 'Uttar Pradesh squad lined up at the 2025 National Targetball Championship', 'caption' => 'Uttar Pradesh contingent'],
            ['name' => 'gallery-03', 'alt' => 'Tamil Nadu squad with their state flag at the national championship', 'caption' => 'Tamil Nadu contingent'],
            ['name' => 'gallery-04', 'alt' => 'Lamp lighting ceremony at the 2025 National Targetball Championship', 'caption' => 'Opening ceremony'],
            ['name' => 'gallery-05', 'alt' => 'Association officials at the national championship in Bhopal', 'caption' => 'Association delegates'],
            ['name' => 'gallery-06', 'alt' => 'Ceremony stage at the national championship in Bhopal', 'caption' => 'Championship stage, Bhopal'],
            ['name' => 'gallery-08', 'alt' => 'Maharashtra squad at the 2025 National Targetball Championship', 'caption' => 'Maharashtra contingent'],
            ['name' => 'gallery-10', 'alt' => 'Kerala squad at the 2025 National Targetball Championship', 'caption' => 'Kerala contingent'],
            ['name' => 'gallery-11', 'alt' => 'Indian team celebrating with the trophy at an international targetball event', 'caption' => 'India on the international stage'],
            ['name' => 'gallery-12', 'alt' => 'Indian women’s targetball team with their trophy', 'caption' => 'Indian women’s team'],
        ];
    }

    public static function championshipStory(): array
    {
        return [
            'date' => '2025-10-27',
            'title' => 'Madhya Pradesh hosts the National Targetball Championship 2025',
            'excerpt' => 'Tatya Tope Stadium in Bhopal welcomed state contingents for four days of junior, sub-junior and mixed competition, organised by the Madhya Pradesh Targetball Association.',
            'body' => [
                'The National Targetball Championship 2025 brought the 12th Junior, 4th Mix 11th Sub Junior and 4th Mix Sub Junior competitions to Tatya Tope Stadium, T.T. Nagar, Bhopal.',
                'The championship ran from 27 to 30 October 2025 and was organised by the Madhya Pradesh Targetball Association. State squads, technical officials and association delegates took part across the four-day programme.',
                'Official results and participation records are published by the championship technical committee. This portal will carry association notices and verified updates as they are released.',
            ],
        ];
    }
}
