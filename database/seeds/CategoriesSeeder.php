<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        $data = [
            ['id' => 1,
                'name'  => 'Business' ,
                'slug'  => 'business',
                ],
            ['id' => 2,
                'name'  => 'City Guides' ,
                'slug'  => 'city-guides',
                ],
            ['id' => 3,
                'name'  => 'Food & Dining' ,
                'slug'  => 'food-dining',
                ],
            ['id' => 4,
                'name'  => 'Health' ,
                'slug'  => 'health',
                ],
            ['id' => 5,
                'name'  => 'Living' ,
                'slug'  => 'living',
            ],
            ['id' => 6,
                'name'  => 'Music' ,
                'slug'  => 'music',
            ],
            ['id' => 7,
                'name'  => 'News' ,
                'slug'  => 'news',
            ],
            ['id' => 8,
                'name'  => 'Photos' ,
                'slug'  => 'photos',
            ],
            ['id' => 9,
                'name'  => 'Shopping' ,
                'slug'  => 'shopping',
            ],
            ['id' => 10,
                'name'  => 'Sports' ,
                'slug'  => 'sports',
            ],
            ['id' => 11,
                'name'  => 'Style' ,
                'slug'  => 'style',
            ],
            ['id' => 12,
                'name'  => 'Tech & Science' ,
                'slug'  => 'tech-science',
            ],
            ['id' => 13,
                'name'  => 'Travel' ,
                'slug'  => 'travel',
            ]
        ];
        DB::table('categories')->insert($data);
    }
}
