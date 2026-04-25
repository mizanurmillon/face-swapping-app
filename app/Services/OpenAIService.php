<?php
namespace App\Services\Chat;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    /**
     * Standard text-only chat.
     */
    public function chat(array $messages, string $model = null): array
    {
        $model = $model ?: config('services.openai.model', 'gpt-4o-mini');

        return $this->callOpenAI($model, $messages);
    }

    /**
     * Vision chat — user message includes an image URL + optional text.
     * Automatically uses gpt-4o (vision-capable model).
     */
    public function chatWithImage(string $imageUrl, string $userText, array $priorMessages = []): array
    {
        $model = config('services.openai.vision_model', 'gpt-4o');

        // Build vision user message
        $visionContent = [
            [
                'type'      => 'image_url',
                'image_url' => ['url' => $imageUrl, 'detail' => 'high'],
            ],
        ];

        // Append user text if provided
        if (! empty(trim($userText))) {
            $visionContent[] = [
                'type' => 'text',
                'text' => $userText,
            ];
        }

        // Merge prior system/history messages + new vision message
        $messages   = $priorMessages;
        $messages[] = ['role' => 'user', 'content' => $visionContent];

        return $this->callOpenAI($model, $messages, maxTokens: 1000);
    }

    // Shared HTTP call to OpenAI.
    private function callOpenAI(string $model, array $messages, int $maxTokens = 500): array
    {
        $res = Http::withToken(config('services.openai.key'))
            ->timeout(120)
            ->post(config('services.openai.url', 'https://api.openai.com/v1/chat/completions'), [
                'model'       => $model,
                'temperature' => 0,
                'max_tokens'  => $maxTokens,
                'messages'    => $messages,
            ]);

        if (! $res->ok()) {
            return [
                'ok'     => false,
                'error'  => $res->json('error.message') ?? 'OpenAI request failed',
                'status' => $res->status(),
                'raw'    => $res->json(),
            ];
        }

        return [
            'ok'      => true,
            'content' => $res->json('choices.0.message.content'),
            'raw'     => $res->json(),
        ];
    }
}
