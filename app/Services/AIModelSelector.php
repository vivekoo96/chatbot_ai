<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AIModelSelector
{
    /**
     * Get the appropriate AI model based on input type
     */
    public function selectModel(array $input): string
    {
        // Check for image input
        if ($this->hasImage($input)) {
            $model = config('addons.recommended_models.image', 'gpt-4o-mini');
            Log::info('AI Model Selected: Image', ['model' => $model]);
            return $model;
        }

        // Check for voice input
        if ($this->hasVoice($input)) {
            $model = config('addons.recommended_models.voice', 'gpt-4o-mini-audio-preview');
            Log::info('AI Model Selected: Voice', ['model' => $model]);
            return $model;
        }

        // Default to text model
        $model = config('addons.recommended_models.text', 'gpt-4o-mini');
        Log::info('AI Model Selected: Text', ['model' => $model]);
        return $model;
    }

    /**
     * Check if input contains an image
     */
    protected function hasImage(array $input): bool
    {
        // Check for image file upload
        if (isset($input['image']) && !empty($input['image'])) {
            return true;
        }

        // Check for image path
        if (isset($input['image_path']) && !empty($input['image_path'])) {
            return true;
        }

        // Check for base64 image
        if (isset($input['image_data']) && !empty($input['image_data'])) {
            return true;
        }

        return false;
    }

    /**
     * Check if input contains voice/audio
     */
    protected function hasVoice(array $input): bool
    {
        // Check for audio file upload
        if (isset($input['audio']) && !empty($input['audio'])) {
            return true;
        }

        // Check for voice flag
        if (isset($input['is_voice']) && $input['is_voice']) {
            return true;
        }

        // Check for audio path
        if (isset($input['audio_path']) && !empty($input['audio_path'])) {
            return true;
        }

        return false;
    }

    /**
     * Get model capabilities
     */
    public function getModelCapabilities(string $model): array
    {
        $capabilities = [
            'gpt-4o-mini' => [
                'type' => 'text',
                'supports_text' => true,
                'supports_image' => true,
                'supports_voice' => false,
                'cost_per_1k_tokens' => 0.15,
            ],
            'gpt-4o' => [
                'type' => 'text',
                'supports_text' => true,
                'supports_image' => true,
                'supports_voice' => false,
                'cost_per_1k_tokens' => 0.60,
            ],
            'gpt-4o-mini-audio-preview' => [
                'type' => 'voice',
                'supports_text' => true,
                'supports_image' => false,
                'supports_voice' => true,
                'cost_per_minute' => 0.20,
            ],
        ];

        return $capabilities[$model] ?? $capabilities['gpt-4o-mini'];
    }

    /**
     * Validate if user has enough quota for the selected model
     */
    public function validateQuota($user, string $modelType): array
    {
        $addonService = app(AddonService::class);

        switch ($modelType) {
            case 'image':
                $planLimit = $user->plan->max_image_uploads_per_month ?? 0;
                $used = \App\Models\UserImageUpload::getCurrentMonthCount($user->id);
                $addonBalance = $addonService->getAvailableQuantity($user, 'images');
                $available = ($planLimit - $used) + $addonBalance;

                return [
                    'has_quota' => $available > 0,
                    'available' => $available,
                    'type' => 'images',
                ];

            case 'voice':
                $planLimit = $user->plan->max_voice_minutes_per_month ?? 0;
                $used = \App\Models\UserVoiceUsage::getCurrentMonthMinutes($user->id);
                $addonBalance = $addonService->getAvailableQuantity($user, 'voice');
                $available = ($planLimit - $used) + $addonBalance;

                return [
                    'has_quota' => $available > 0,
                    'available' => $available,
                    'type' => 'voice minutes',
                ];

            case 'text':
            default:
                $planLimit = $user->plan->max_messages_per_month ?? 0;
                $used = $user->messages_this_month ?? 0;
                $addonBalance = $addonService->getAvailableQuantity($user, 'messages');
                $available = ($planLimit - $used) + $addonBalance;

                return [
                    'has_quota' => $available > 0,
                    'available' => $available,
                    'type' => 'messages',
                ];
        }
    }

    /**
     * Consume quota after using the model
     */
    public function consumeQuota($user, string $modelType, int $amount = 1): bool
    {
        $addonService = app(AddonService::class);

        switch ($modelType) {
            case 'image':
                // Try to consume from add-ons first
                if ($addonService->consumeAddon($user, 'images', $amount)) {
                    return true;
                }
                // Otherwise it will be counted against plan limit
                return true;

            case 'voice':
                // Try to consume from add-ons first
                if ($addonService->consumeAddon($user, 'voice', $amount)) {
                    return true;
                }
                // Otherwise it will be counted against plan limit
                return true;

            case 'text':
            default:
                // Try to consume from add-ons first
                if ($addonService->consumeAddon($user, 'messages', $amount)) {
                    return true;
                }
                // Otherwise it will be counted against plan limit
                return true;
        }
    }
}
