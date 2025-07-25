<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateExamToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:exam-token {exam_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a static exam token by encrypting the provided exam name';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $examName = $this->argument('exam_name');

        $secretKey = config('token.INTEGRATION_TOKEN_SECRET');

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt($examName, 'aes-256-cbc', $secretKey, 0, $iv);

        $token = base64_encode($iv . $encryptedData);

        $this->info("Generated Token: " . $token);
    }
}
