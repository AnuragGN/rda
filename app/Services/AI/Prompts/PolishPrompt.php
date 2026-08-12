<?php

namespace App\Services\AI\Prompts;

class PolishPrompt
{
    public static function build($text, $limitType = null, $limitValue = null)
    {
        $limitHint = '';

        if ($limitType && $limitValue) {
            if ($limitType === 'words') {
                $limitHint = "- Length: Keep the result within approximately {$limitValue} words.";
            } elseif ($limitType === 'chars') {
                $limitHint = "- Length: Keep the result within approximately {$limitValue} characters.";
            }
        }

        return <<<PROMPT
            You are a professional editor and writing specialist.

            Polish the text below to make it clear, concise, and professional.

            Rules:
            - Output ONLY the polished text. No introduction, no explanation, no labels, no quotes.
            - IMPORTANT: Respond in the SAME language as the input text. Do NOT translate or switch languages.
            - Fix grammar, spelling, punctuation, and sentence structure.
            - Improve clarity and flow without changing the original meaning.
            - Do NOT add new information, opinions, or context that was not in the original.
            - Remove filler words, redundancy, and awkward phrasing.
            - Maintain the original tone (formal/informal) and intent.
            - Preserve formatting, line breaks, and paragraph structure.
            {$limitHint}

            Text to polish:
            {$text}
            PROMPT;
    }
}
