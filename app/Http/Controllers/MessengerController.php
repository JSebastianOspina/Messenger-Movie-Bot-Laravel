<?php

namespace App\Http\Controllers;

use App\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerController extends Controller
{
    public function verifyMessenger(Request $request)
    {
        return response($request->hub_challenge, 200);
    }

    public function handleIncomingMessage(Request $request)
    {
        Log::debug($request);
        $message = $request->entry[0]['messaging'][0];
        $recipient = $this->getRecipient($message);
        $sender = $this->getSender($message);
        //$received_message = $this->getReceivedMessage($message);
        if (isset($message['postback'])) { // It is a postBack
            $received_message = $this->getPostBack($message); //get postback info
            $payload = $received_message['payload'];
            if (str_contains($payload, 'genre:')) { //Its askin for genre change
                $genre = explode(':', $payload)[1];
                $this->recommendMovie($sender, $genre);
            } else {
                $this->showGenres($sender);
            }
        } elseif (isset($message['message'])) { // It is a text message
            $received_message = $this->getReceivedMessage($message);
            $this->recommendMovie($sender, 27);

            //$this->showGenres($sender);
        }
        //$this->recommendMovie($sender, $received_message);
        return response('', 200);
    }


    private function sendMessage(string $sender, string $received_message)
    {
        $this->recommendMovie($sender, $received_message);
    }

    private function showGenres(string $sender)
    {
        $page_access_token = env('MESSENGER_TOKEN');
        $movies = new Movies();
        $response = Http::post("https://graph.facebook.com/v10.0/me/messages?access_token={$page_access_token}",
            [
                'recipient' => [
                    'id' => $sender
                ]
                ,
                'message' => [
                    'attachment' => [
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'list',
                            'top_element_style' => 'large',
                            'elements' => $this->buildGenres(),
                            'buttons' => [
                                [
                                    "type" => "postback",
                                    "payload" => "genre: ",
                                    "title" => "Ver mas"
                                ]
                            ]
                        ]
                    ]

                ]
            ]);
        Log::debug($response);
    }

    private function buildGenres()
    {
        $formated_genres = [];
        $movies = new Movies();
        foreach ($movies->genres_ids as $genre) {
            $formated_genres[] = [
                'title' => $genre['name'],
                'subtitle' => 'Peliculas de ' . $genre['name'],
                'image_url' => $genre['poster'],
                'buttons' => [[
                    "type" => "postback",
                    "title" => $genre['name'],
                    "payload" => "genre:".$genre['id']
                ]]

            ];

        }

        return $formated_genres;
    }


    private function recommendMovie($sender, $genre_id): void
    {
        $aux = new Movies();
        $movie = $aux->getMovieByGenre($genre_id);

        $page_access_token = env('MESSENGER_TOKEN');
        $response = Http::post("https://graph.facebook.com/v10.0/me/messages?access_token={$page_access_token}",
            [
                'recipient' => [
                    'id' => $sender
                ]
                ,
                'message' => [
                    //'text' => $genre

                    'attachment' => [
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'generic',
                            'elements' => [
                                [
                                    'title' => $movie['title'],
                                    'subtitle' => $movie['overview'],
                                    'image_url' => $movie['poster'],
                                    'buttons' => [
                                        [
                                            "type" => "postback",
                                            "payload" => "genre: " . $genre_id,
                                            "title" => "¡Dame otra pelicula!"
                                        ],
                                        [
                                            "type" => "postback",
                                            "payload" => "Cambiar genero",
                                            "title" => "Cambiar genero"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]

                ]
            ]);

    }

    private function getRecipient(array $message): string
    {
        return $message['recipient']['id'];
    }

    private function getSender($message): string
    {
        return $message['sender']['id'];

    }

    private function getReceivedMessage($message)
    {
        return $message['message']['text'];
    }

    private function getPostBack($message)
    {
        return $message['postback'];
    }
}
