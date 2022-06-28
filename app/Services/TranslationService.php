<?php namespace App\Services;


use App\Models\Vocab;
use Illuminate\Support\Facades\Http;

class TranslationService
{
    public static function translate(string $englishVocab): null|string
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => 'da711ddc66mshd5dd336f47997eep1856efjsn4c76074eaa1b',
            'X-RapidAPI-Host' => 'translated-mymemory---translation-memory.p.rapidapi.com'
        ])->get('https://translated-mymemory---translation-memory.p.rapidapi.com/api/get', [
            'langpair' => 'en|th',
            'q' => $englishVocab,
            'mt' => '1',
            'onlyprivate' => '0',
            'de' => 'a@b.c'
        ]);
        $decodedResponse = json_decode($response->getBody()->getContents(), true);
        if ($decodedResponse['responseStatus'] != 200) {
            return null;
        }
        if (mb_strlen($decodedResponse['responseData']['translatedText']) > 30) {
            return null;
        }
        $translatedText = $decodedResponse['responseData']['translatedText'];
        return $translatedText;
    }

}
