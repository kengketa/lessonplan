<?php

namespace App\Console\Commands;

use App\Models\Vocab;
use App\Services\TranslationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;


class addTranslationToVocabs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vocabs:translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add Thai translate by api';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $vocabs = Vocab::whereNull('vocab_th')->get();
        foreach ($vocabs as $vocab) {
            $translatedText = TranslationService::translate($vocab->vocab_en);
            $vocab->vocab_th = $translatedText;
            $vocab->save();
            $this->info($vocab->vocab_en.':'.$translatedText);
        }
    }
}
