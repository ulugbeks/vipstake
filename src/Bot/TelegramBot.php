<?php


namespace App\Bot;



use http\Client;
use http\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelegramBot

{
    protected $botId = '6970171176:AAEYqxh-YKP4TJ8BxLlFGn1MlqG_mi862wM';
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function sendMessage($text, $chatId)
    {
        $query = [
            'text' => $text,
            'chat_id' => $chatId,
            'parse_mode' => "HTML",
        ];

        try{
            $this->client->request('GET', 'https://api.telegram.org/bot' . $this->botId . '/sendmessage', ['query' => $query]);
        } catch (\Exception $e){
            throw new \Exception($e);
        }
    }
}
