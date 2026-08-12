<?php

namespace App\Services\AI;

use App\Services\AI\Prompts\{
    ExplainQuestionPrompt,
    DraftAnswerPrompt,
    PolishPrompt,
    TranslatePrompt,
    ReadnessPrompt
};

class AITextService
{
    protected $client;

    public function __construct(AIClient $client)
    {
        $this->client = $client;
    }

    /**
     * Explain a question (no length limit)
     */
    public function explainQuestion($question)
    {
        return $this->client->ask(
            ExplainQuestionPrompt::build($question)
        );
    }

    /**
     * Draft answer with optional length limit
     */
    public function draftAnswer($question, $limitType = null, $limitValue = null)
    {
        $output = $this->client->ask(
            DraftAnswerPrompt::build($question)
        );

        return $this->applyLimit($output, $limitType, $limitValue);
    }

    /**
     * Polish answer with optional length limit
     */
    public function polish($text, $limitType = null, $limitValue = null)
    {
        $output = $this->client->ask(
			PolishPrompt::build($text, $limitType, $limitValue)
        );

        return $this->applyLimit($output, $limitType, $limitValue);
    }

    /**
     * Translate text (no limit by default)
     */
    public function translate($text, $language)
    {
        return $this->client->ask(
            TranslatePrompt::build($text, $language)
        );
    }

    /**
     * Translate text (no limit by default)
     */
    public function aiReadiness($qaList)
    {
        return $this->client->ask(
            ReadnessPrompt::build($qaList)
        );
    }

    /**
     * Apply HARD limits (chars / words)
     */
    private function applyLimit($text, $limitType, $limitValue)
    {
        if (empty($limitType) || empty($limitValue)) {
            return $text;
        }

        // Character limit
        if ($limitType === 'chars') {
            return mb_strlen($text) > $limitValue
                ? mb_substr($text, 0, $limitValue)
                : $text;
        }

        // Word limit
        if ($limitType === 'words') {
            $words = preg_split('/\s+/u', trim($text));
            return implode(' ', array_slice($words, 0, $limitValue));
        }

        return $text;
    }
}
