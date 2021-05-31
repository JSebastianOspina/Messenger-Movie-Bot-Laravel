<?php

namespace App;

use Illuminate\Support\Facades\Http;

class Movies
{
    private $name;
    private $gender;
    private $url = 'https://api.themoviedb.org/3';
    public $genres_ids = [
        [
            'id' => 28,
            'name' => "Acción",
            'poster' => 'https://image.tmdb.org/t/p/original/jIIjsExCEv6lsfQGHTXDPk3Q0M5.jpg'
        ],
        ['id' =>
            12,
            'name' => "Aventura",
            'poster' => 'https://image.tmdb.org/t/p/original/3f4ETSwknZs74lmUYC7ENIMRBMP.jpg'

        ],
        ['id' =>
            16,
            'name' => "Animación",
            'poster' => 'https://image.tmdb.org/t/p/original/8UoKkpLVnl2LrK0GG1z9JuKCart.jpg'
        ],
        ['id' =>
            35,
            'name' => "Comedia",
            'poster' => 'https://image.tmdb.org/t/p/original/PxbO8UzZ17dMcqs0YwAnKuwfHA.jpg'
        ],
/*        ['id' =>
            80,
            'name' => "Crimen",
            'poster' => 'https://image.tmdb.org/t/p/original/yJTk4eqQd9Yo5REpFbTSOMkbSgn.jpg'
        ],*/
        /*  ['id' =>
              99,
              'name' => "Documental",
              'poster' => 'https://image.tmdb.org/t/p/original/yJTk4eqQd9Yo5REpFbTSOMkbSgn.jpg'
          ],*/
        ['id' =>
            18,
            'name' => "Drama",
            'poster' => 'https://image.tmdb.org/t/p/original/qzA87Wf4jo1h8JMk9GilyIYvwsA.jpg'
        ],

/*        ['id' =>
            14,
            'name' => "Fantasía",
            'poster' => 'https://image.tmdb.org/t/p/original/yJTk4eqQd9Yo5REpFbTSOMkbSgn.jpg'
        ],*/

        ['id' =>
            27,
            'name' => "Terror",
            'poster' => 'https://image.tmdb.org/t/p/original/4U1SBHmwHkNA0eHZ2n1CuiC1K1g.jpg'
        ],
/*        ['id' =>
            10402,
            'name' => "Música",
            'poster' => 'https://image.tmdb.org/t/p/original/yJTk4eqQd9Yo5REpFbTSOMkbSgn.jpg'
        ],*/
        /*['id' =>
            9648,
            'name' => "Misterio",
            'poster' => 'https://image.tmdb.org/t/p/original/yJTk4eqQd9Yo5REpFbTSOMkbSgn.jpg'
        ],*/
        ['id' =>
            10749,
            'name' => "Romance",
            'poster' => 'https://image.tmdb.org/t/p/original/xGDbQI7Gtgurt9W5ez6Tim2lpS2.jpg'
        ],
        ['id' =>
            878,
            'name' => "Ciencia ficción",
            'poster' => 'https://image.tmdb.org/t/p/original/lMFgbCskJgsJdPH81SwVZOc1hNs.jpg'
        ],

        ['id' =>
            53,
            'name' => "Suspense",
            'poster' => 'https://image.tmdb.org/t/p/original/ddO5a3tMPpQutSDQO1bESgLWadB.jpg'
        ],
        /* ['id' =>
             10752,
             'name' => "Bélica",
             'poster' => 'https://image.tmdb.org/t/p/original/yJTk4eqQd9Yo5REpFbTSOMkbSgn.jpg'
         ],
         ['id' =>
             37,
             'name' => "Western",
             'poster' => 'https://image.tmdb.org/t/p/original/yJTk4eqQd9Yo5REpFbTSOMkbSgn.jpg'

         ]*/
    ];
    private $lang;

    /**
     * Movies constructor.
     * @param string $lang
     */
    public function __construct(string $lang = 'es')
    {
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */

    function getGenreId(string $gender_name)
    {
        $key = array_search($gender_name, array_column($this->genres_ids, 'name'), true);
        return $this->genres_ids[$key]['id'];
    }

    function getGenres()
    {
        $path = '/genre/movie/list';
        $full_url = $this->url . $path;
        return Http::get($full_url, [
            'api_key' => env('THEMOVIEDB_KEY'),
            'language' => 'es'
        ]);
    }

    public function getMovieByGenre(string $genre_id): array
    {
        $path = '/discover/movie';
        $full_url = $this->makeUrl($path);

        $movies = Http::get($full_url, [
            'api_key' => env('THEMOVIEDB_KEY'),
            'with_genres' => $genre_id,
            'sort_by' => 'popularity.desc',
            'language' => $this->lang
        ])['results'];
        return $this->formatMovie($movies[array_rand($movies)]);

    }

    function makeUrl($path): string
    {
        return $this->url . $path;
    }

    function getPoster($poster_path)
    {
        return 'https://image.tmdb.org/t/p/original' . $poster_path;
    }

    private function formatMovie(array $movie): array
    {
        return [
            'title' => $movie['title'],
            'lang' => $movie['original_language'],
            'overview' => $movie['overview'],
            'poster' => $this->getPoster($movie['poster_path'])
        ];
    }

}
