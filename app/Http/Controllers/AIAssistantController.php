<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AI\AITextService;
use Illuminate\Support\Facades\Cache;
use App\Models\Proposal;
use App\Models\SurveyAnswer;
use App\Models\SurveyComponent;
use App\Models\SurveyComponentConfig;
use App\Models\Survey;
use App\Models\LetterOfIntent;
use App\Models\Question;
use App\Helpers\ApplicationData;
use App\Helpers\GnUtils;
use App\Models\LetterOfIntentGeographicArea;
use App\Models\LetterOfIntentPopulationServed;
use App\Models\LetterOfIntentGoal;

class AIAssistantController extends Controller
{	
	public function process(Request $request, AITextService $ai)
	{
		$request->validate([
			'type' => 'required',
			'text' => 'required',
		]);

		$type        = $request->input('type');
		$prompt      = $request->input('text');
		$language    = $request->input('language');
		$limitType   = $request->input('limit_type');
		$limitValue  = (int) $request->input('limit_value');

		$userId = auth()->id() ?? 'guest';

		$keyData = [
			'user'   => $userId,
			'type'   => $type,
			'prompt' => $prompt,
		];

		if (!empty($limitType))  $keyData['limitType']  = $limitType;
		if (!empty($limitValue)) $keyData['limitValue'] = $limitValue;
		if ($type === 'translate' && !empty($language)) $keyData['language'] = $language;

		$cacheKey  = 'ai_' . md5(json_encode($keyData));
		$result    = Cache::get($cacheKey);
		$fromCache = (bool) $result;

		try {
			if (!$fromCache) {
				$result = Cache::remember($cacheKey, config('ai.ai_cache_ttl'), function () use ($type, $prompt, $language, $limitType, $limitValue, $ai) {
					return match ($type) {
						'explain_question' => $ai->explainQuestion($prompt),
						'draft_answer'     => $ai->draftAnswer($prompt, $limitType, $limitValue),
						'polish'           => $ai->polish($prompt, $limitType, $limitValue),
						'translate'        => $ai->translate($prompt, $language),
						default            => null,
					};
				});
			}
		} catch (\Exception $e) {
			$raw     = $e->getMessage();
			$message = 'AI action failed. Please try again.';

			// Extract a clean user-facing message from known API error shapes
			$decoded = null;
			if (preg_match('/\{.*\}/s', $raw, $m)) {
				$decoded = json_decode($m[0], true);
			}

			if (!empty($decoded['error']['message'])) {
				$apiMsg  = $decoded['error']['message'];
				$retry   = '';

				// if (preg_match('/retry in ([\d.]+)s/i', $apiMsg, $m)) {
				// 	$retry = ' Retry in ' . (int) ceil((float) $m[1]) . 's.';
				// }

				if (stripos($apiMsg, 'quota') !== false || str_contains($raw, '429')) {
					$message = 'AI quota exceeded. Please try again later.' . $retry;
				} else {
					$message = 'AI request failed.' . $retry;
				}
			} elseif (str_contains($raw, '429') || stripos($raw, 'quota') !== false) {
				$message = 'AI quota exceeded. Please try again later.';
			}

			return response()->json([
				'error'  => $message,
				'raw'    => app()->environment('local') ? $raw : null,
			], 429);
		}

		if (!$result) {
			return response()->json(['error' => 'Invalid type'], 400);
		}

		return response()->json([
			'result' => $result,
			'meta' => [
				'source' => $fromCache ? 'cache' : 'ai',
				'driver' => config('ai.driver'),
				'model'  => match (config('ai.driver')) {
					'openrouter'  => config('ai.openrouter.model'),
					'ollama'      => config('ai.ollama.model'),
					'huggingface' => config('ai.huggingface.model'),
					'gemini'      => config('ai.gemini.model'),
					'claude'      => config('ai.claude.model'),
					default       => 'unknown',
				},
			],
		]);
	}

	public function calculateAIReadiness(Request $request, AITextService $ai)
	{
		$proposalId  = $request->proposal_id;
		$surveyId    = $request->survey_id;
		$loiQuestions = $request->input('loi_questions', []);
		$loiAnswers   = $request->input('loi_answers', []);
		$answers   	= $request->input('answers', []);

		if (!$proposalId || !$surveyId) {
			return response()->json([
				'status' => false,
				'message' => 'Invalid data'
			]);
		}

		//$answers    = SurveyAnswer::getGroupedAnswers($proposalId, $surveyId);
		$components = SurveyComponent::getFilteredSurveyComponents($surveyId, $proposalId);

		$qaList = [];

		foreach ($components as $component) 
		{
			$name = $component->component->name;

			// ================= NORMAL QUESTIONS =================
			if ($name == ApplicationData::QUESTIONS_COMPONENT) {

				foreach ($component->questions as $q) {

					$question = Question::getQuestionById($q->question_id);
					if (!$question) continue;

					$answerText = isset($answers[$q->question_id]) ? $answers[$q->question_id][0] : null;

					$qaList[] = [
						'question' => trim($question),
						'answer'   => $answerText ?: 'No answer provided'
					];
				}
			}

			// ================= PRE-QUALIFYING =================
			if ($name == ApplicationData::PRE_QUALIFYING_QUESTIONS_COMPONENT) {

				foreach ($component->questions as $q) {

					$question = Question::getQuestionById($q->question_id);
					if (!$question) continue;

					$answerText = isset($answers[$q->question_id]) ? $answers[$q->question_id][0] : null;

					$qaList[] = [
						'question' => trim($question->question),
						'answer'   => $answerText ?: 'No answer provided'
					];
				}
			}

			// ================= LOI =================
			if ($name == ApplicationData::LOI_COMPONENT) {

				$id = $loiAnswers['letter_of_intent_id'] ?? null;

				$geo = $pop = $goals = '';

				if ($id) {
					$geo   = collect(LetterOfIntentGeographicArea::getLOIGeographicAreaIds($id))->implode(', ');
					$pop   = collect(LetterOfIntentPopulationServed::getLOIPopulationServedIds($id))->implode(', ');
					$goals = collect(LetterOfIntentGoal::getLOIGoal($id))->implode(', ');
				}

				foreach ($loiQuestions as $q) {

					$config = $q['config_area'] ?? null;
					if (!$config) continue;

					$value = '';

					switch ($config) {

						case ApplicationData::CONFIG_AREA_COMMUNITY_GOALS:
							$value = $goals; break;

						case ApplicationData::CONFIG_AREA_CONTACT_PERSON:
							$value = $loiAnswers['contact_name'] ?? ''; break;

						case ApplicationData::CONFIG_AREA_GEOGRAPHIC_AREA:
							$value = $geo; break;

						case ApplicationData::CONFIG_AREA_POPULATION_SERVED:
							$value = $pop; break;

						case ApplicationData::CONFIG_AREA_FISCAL_YEAR:
							$value = !empty($loiAnswers['fiscal_year'])
								? GnUtils::customUIDate($loiAnswers['fiscal_year']) : '';
							break;

						case ApplicationData::CONFIG_AREA_GRANT_PERIOD:
							$start = !empty($loiAnswers['project_start']) ? GnUtils::customUIDate($loiAnswers['project_start']) : '';
							$end   = !empty($loiAnswers['project_end']) ? GnUtils::customUIDate($loiAnswers['project_end']) : '';
							$value = trim("$start - $end", ' -');
							break;

						case ApplicationData::CONFIG_AREA_PROJECT_DESCRIPTION:
							$value = strip_tags($loiAnswers['purpose'] ?? '');
							break;

						case ApplicationData::CONFIG_AREA_REQUEST_AMOUNT:
							$value = !empty($loiAnswers['request_amount'])
								? number_format($loiAnswers['request_amount'], 2) : '';
							break;
					}

					$qaList[] = [
						'question' => ucwords(str_replace('_', ' ', strtolower($config))),
						'answer'   => $value ?: 'No answer provided'
					];
				}
			}
		}
		
		// ================= CACHE KEY =================
		$userId = auth()->id() ?? 'guest';
		$hash   = substr(md5(json_encode($qaList)), 0, 12);

		$cacheKey = "ai_readiness_v1_{$userId}_{$proposalId}_{$surveyId}_{$hash}";

		// ================= CHECK CACHE =================
		if (Cache::has($cacheKey)) {

			$cached = Cache::get($cacheKey);

			return response()->json([
				'status'   => true,
				'score'    => $cached['score'],
				'feedback' => $cached['feedback'],
				'cached'   => true,
				'source'   => 'cache', // 👈 directly set
				'model'  => match (config('ai.driver')) {
					'openrouter'  => config('ai.openrouter.model'),
					'ollama'      => config('ai.ollama.model'),
					'huggingface' => config('ai.huggingface.model'),
					'gemini'      => config('ai.gemini.model'),
					'claude'      => config('ai.claude.model'),
					default       => 'unknown',
				},
			]);
		}

		// ================= CALL AI =================
		try {

			$response = $ai->aiReadiness($qaList);

			// 🔥 CLEAN RESPONSE
			$clean = trim($response);
			$clean = preg_replace('/```json|```/', '', $clean);
			$clean = preg_replace('/^[^{]+/', '', $clean);
			$clean = preg_replace('/[^}]+$/', '', $clean);

			$data = json_decode($clean, true);

			if (!$data) {
				\Log::error('AI JSON decode failed', ['raw' => $response]);

				return response()->json([
					'status' => false,
					'message' => 'AI response invalid'
				]);
			}

			$result = [
				'score'    => $data['overall_score'] ?? 0,
				'feedback' => $data['answers'] ?? []
			];

			// ================= STORE CACHE =================
			Cache::put($cacheKey, $result, config('ai.ai_cache_ttl'));

			return response()->json([
				'status'   => true,
				'score'    => $result['score'],
				'feedback' => $result['feedback'],
				'cached'   => false,
				'source'   => 'ai', // 👈 directly set
				'model'  => match (config('ai.driver')) {
					'openrouter'  => config('ai.openrouter.model'),
					'ollama'      => config('ai.ollama.model'),
					'huggingface' => config('ai.huggingface.model'),
					'gemini'      => config('ai.gemini.model'),
					'claude'      => config('ai.claude.model'),
					default       => 'unknown',   
				},
			]);

		} catch (\Exception $e) {

			$message = $e->getMessage();

			$userMessage = 'Something went wrong. Please try again.';

			if (strpos($message, 'quota') !== false || strpos($message, '429') !== false) {
				$userMessage = "AI usage limit reached. Please try again later.";
			}

			return response()->json([
				'status' => false,
				'message' => $userMessage,
				'raw' => app()->environment('local') ? $message : null
			]);
		}
	}
}
