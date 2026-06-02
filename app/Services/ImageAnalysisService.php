<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use GuzzleHttp\Client;
use Exception;

class ImageAnalysisService
{
    protected $client;
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->model = config('services.openai.model', 'gpt-4o');
        $this->client = new Client();
    }

    /**
     * Analyze image and extract title, description, and category suggestions
     * 
     * @param UploadedFile $file
     * @return array
     */
    public function analyzeImage(UploadedFile $file): array
    {
        // Jeśli brak klucza API lub mock mode, użyj dummy data
        if (!$this->apiKey || config('ai.image_analysis.mock')) {
            return $this->getMockAnalysis();
        }

        try {
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();

            $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $this->getAnalysisPrompt(),
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => "data:{$mimeType};base64,{$imageData}",
                                        'detail' => 'high',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'max_tokens' => 1000,
                ],
            ]);

            $content = json_decode((string)$response->getBody(), true);
            return $this->parseResponse($content);

        } catch (Exception $e) {
            \Log::error('Image analysis failed: ' . $e->getMessage());
            return [
                'title' => '',
                'description' => '',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the analysis prompt in Polish
     */
    protected function getAnalysisPrompt(): string
    {
        return <<<'PROMPT'
Analizuj to zdjęcie jako profesjonalny sprzedawca. Zwróć JSON z następującą strukturą (TYLKO JSON, bez dodatkowego tekstu):
{
    "title": "krótki, привлекательный tytuł (max 80 znaków)",
    "description": "szczegółowy opis produktu/przedmiotu (2-3 zdania, do 300 znaków)",
    "category": "kategoria: furniture|electronics|clothing|books|sports|home|other",
    "condition": "condition: new|like_new|good|fair|poor",
    "price_suggestion": "przybliżona cena w PLN (bez waluta)"
}

Bądź konkretny, profesjonalny i zwróć TYLKO prawidłowy JSON bez żadnego dodatkowego tekstu.
PROMPT;
    }

    /**
     * Parse OpenAI response
     */
    protected function parseResponse(array $response): array
    {
        try {
            $message = $response['choices'][0]['message']['content'] ?? '';
            
            // Usuń markdown formatting jeśli istnieje
            $message = preg_replace('/^```json\s*|\s*```$/m', '', $message);
            
            $data = json_decode($message, true);

            return [
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
                'category' => $data['category'] ?? 'other',
                'condition' => $data['condition'] ?? 'good',
                'price_suggestion' => $data['price_suggestion'] ?? '',
                'success' => true,
            ];
        } catch (Exception $e) {
            return [
                'title' => '',
                'description' => '',
                'error' => 'Nie udało się przeanalizować odpowiedzi',
            ];
        }
    }

    /**
     * Get mock analysis data (for testing without API key)
     */
    protected function getMockAnalysis(): array
    {
        $sampleTitles = [
            'Laptop Dell XPS 13 - Stan idealny',
            'Stół drewniany do jadalni',
            'Rower górski Trek',
            'iPhone 13 Pro Space Gray',
            'Książka "Clean Code"',
        ];

        $sampleDescriptions = [
            'Profesjonalny sprzęt w doskonałym stanie. Minimalnie użytkowany, bez uszkodzeń. Idealny do pracy i nauki.',
            'Solidny mebel wykonany z drewna dębowego. Wymiary: 150x80 cm. Możliwość negocjacji ceny.',
            'Doskonały do jazdy po trasach. Niedawno przegląd techniczny. Wszystkie komponenty sprawne.',
            'Nieużywany, w oryginalnym opakowaniu. Wszystkie akcesoria w zestawie. Gwarancja producenta.',
            'Pozycja niezbędna dla każdego programisty. Egzemplarz w bardzo dobrym stanie.',
        ];

        $mockConditions = ['new', 'like_new', 'good', 'fair'];
        $mockCategories = ['electronics', 'furniture', 'sports', 'books', 'clothing'];
        $mockPrices = ['299', '1500', '450', '2899', '49'];

        return [
            'title' => $sampleTitles[array_rand($sampleTitles)],
            'description' => $sampleDescriptions[array_rand($sampleDescriptions)],
            'category' => $mockCategories[array_rand($mockCategories)],
            'condition' => $mockConditions[array_rand($mockConditions)],
            'price_suggestion' => $mockPrices[array_rand($mockPrices)] . ' PLN',
            'success' => true,
            'is_mock' => true,
        ];
    }
}
