<?php

namespace App\Http\Controllers;

use App\Helpers\GConst;
use App\Models\ChatbotTAndC;
use App\Models\Contact;
use App\Models\ContactType;
use App\Models\ContactTypeContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Firebase\JWT\JWT;

class ChatbotController extends Controller
{
    /**
     * Chatbot UI page — existing session se user context uthata hai
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Session mein chatbot_user nahi hai, ya roles invalid hain to rebuild karo
        $chatbotUser = $request->session()->get('chatbot_user');
        $validRoles  = ['donor', 'advisor', 'admin'];
        $hasValidRole = !empty($chatbotUser['roles']) &&
            count(array_intersect($chatbotUser['roles'], $validRoles)) > 0;

        if (! $chatbotUser || ! $hasValidRole) {
            $this->buildAndStoreChatbotContext($request);
        }

        // Session role ke hisaab se layout decide karo
        $sessionRole = $request->session()->get(\App\Helpers\GConst::SESSION_ROLE);
        $layout = match($sessionRole) {
            \App\Helpers\GConst::SESSION_ROLE_AGENCY => 'agency.layouts.main',
            \App\Helpers\GConst::SESSION_ROLE_DONOR  => 'donor.layouts.main',
            default                                   => 'donor.layouts.main',
        };

        $activeTAndC = ChatbotTAndC::getActive();

        // No active T&C record — show fallback state, hide chat
        if (! $activeTAndC) {
            return view('chatbot.index', [
                'chatbotUser'   => $request->session()->get('chatbot_user'),
                'layout'        => $layout,
                'fallbackState' => true,
                'activeTAndC'   => null,
            ]);
        }

        // T&C already accepted in this session — show chat, no modal
        if ($request->session()->get('chatbot_tc_accepted') === true) {
            return view('chatbot.index', [
                'chatbotUser'   => $request->session()->get('chatbot_user'),
                'layout'        => $layout,
                'fallbackState' => false,
                'activeTAndC'   => null,
            ]);
        }

        // T&C not yet accepted — pass record to trigger the gate modal
        return view('chatbot.index', [
            'chatbotUser'   => $request->session()->get('chatbot_user'),
            'layout'        => $layout,
            'fallbackState' => false,
            'activeTAndC'   => $activeTAndC,
        ]);
    }

    /**
     * T&C acceptance — session flag set karke chatbot index pe redirect karta hai
     */
    public function acceptTerms(Request $request): RedirectResponse
    {
        $request->session()->put('chatbot_tc_accepted', true);

        return redirect()->route('chatbot.index');
    }

    /**
     * Generate a short-lived JWT for document proxy authentication.
     * Frontend uses this token to access /api/documents/:fileId on MCP server.
     */
    public function getDocumentToken(Request $request): JsonResponse
    {
        $chatbotUser = $request->session()->get('chatbot_user');

        if (!$chatbotUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $secret = config('services.node_ai.proxy_secret');
        if (!$secret) {
            return response()->json(['error' => 'Service not configured'], 503);
        }

        $token = JWT::encode([
            'auth_user_id' => $chatbotUser['auth_user_id'],
            'contact_id'   => $chatbotUser['contact_id'],
            'iat'          => time(),
            'exp'          => time() + 3600, // 1 hour
        ], $secret, 'HS256');

        return response()->json(['token' => $token]);
    }

    /**
     * Chat message Node AI service ko forward karta hai
     */
    public function send(Request $request): JsonResponse
    {
        // T&C gate enforcement
        if ($request->session()->get('chatbot_tc_accepted') !== true) {
            return response()->json([
                'reply' => 'Terms & Conditions not accepted.',
            ], 403);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $chatbotUser = $request->session()->get('chatbot_user');

        if (! $chatbotUser) {
            return response()->json([
                'reply' => 'Session expired. Please refresh the page.',
            ], 401);
        }

        try {
            $response = Http::timeout(90)->post(config('services.node_ai.url') . '/chat', [
                'message' => $data['message'],
                'user'    => [
                    'auth_user_id' => $chatbotUser['auth_user_id'],
                    'contact_id'   => $chatbotUser['contact_id'],
                    'roles'        => $chatbotUser['roles'],
                ],
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException) {
            return response()->json([
                'reply' => 'The AI service is not available right now. Please try again later.',
            ], 503);
        }

        if ($response->failed()) {
            return response()->json([
                'reply' => $response->json('reply') ?? 'The AI service could not process the request.',
            ], $response->status());
        }

        return response()->json([
            'reply' => $response->json('reply'),
        ]);
    }

    /**
     * Existing rda session se chatbot context build karke session mein store karta hai.
     * Login ke baad session mein session_role aur session_contact_id already hote hain.
     */
    private function buildAndStoreChatbotContext(Request $request): void
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        if (! $user) {
            return;
        }

        // rda session se contact_id uthao (GConst::SESSION_CONTACT_ID)
        $contactId = $request->session()->get(GConst::SESSION_CONTACT_ID);
        $contact   = $contactId
            ? Contact::getByContactId($contactId)
            : Contact::getByUser($user);

        if (! $contact) {
            return;
        }

        // rda session role ko chatbot roles mein map karo
        $sessionRole = $request->session()->get(GConst::SESSION_ROLE);
        $roles       = $this->mapSessionRoleToChatbotRoles($sessionRole, $contact);

        $displayName = trim($contact->first_name . ' ' . $contact->last_name);
        if ($displayName === '') {
            $displayName = $user->username;
        }

        $request->session()->put('chatbot_user', [
            'auth_user_id' => (int) $user->auth_user_id,
            'contact_id'   => (int) $contact->contact_id,
            'roles'        => $roles,
            'display_name' => $displayName,
            'username'     => $user->username,
        ]);
    }

    /**
     * rda session_role → chatbot roles array
     * rda roles:  donor | agency | support_staff | admin | daf
     * chatbot roles: donor | advisor | admin
     *
     * Note: In latest_new_ffp_db, contact_type_id = 18 is the advisor type.
     * We rely on session_role set by LoginController::doLogin() which already
     * checked ContactType::isAgency() — so 'agency' session role = advisor.
     */
    private function mapSessionRoleToChatbotRoles(string $sessionRole = null, Contact $contact = null): array
    {
        $roles = [];

        switch ($sessionRole) {
            case GConst::SESSION_ROLE_DONOR:
                $roles[] = 'donor';
                break;

            case GConst::SESSION_ROLE_AGENCY:
                // Agency = Fund Advisor in chatbot context
                // $roles[] = 'fund_advisor';
                $roles[] = 'advisor';
                break;

            case GConst::SESSION_ROLE_SUPPORT_STAFF:
            case GConst::SESSION_ROLE_ADMIN:
                $roles[] = 'admin';
                break;

            case GConst::SESSION_ROLE_DAF:
                $roles[] = 'donor';
                break;

            default:
                // Fallback: check contact_type_contact table directly
                // latest_new_ffp_db contact_type mapping:
                //   contact_type_id = 10  → Donor
                //   contact_type_id = 18  → Agency Fund Holder (THE advisor type)
                //   contact_type_id = 19  → Support Staff
                if ($contact) {
                    $contactTypeIds = \App\Models\ContactTypeContact::where('contact_id', $contact->contact_id)
                        ->pluck('contact_type_id')
                        ->toArray();

                    if (in_array(18, $contactTypeIds)) {
                        // $roles[] = 'fund_advisor';
                        $roles[] = 'advisor';
                    }
                    if (in_array(10, $contactTypeIds)) {
                        $roles[] = 'donor';
                    }
                    if (in_array(19, $contactTypeIds)) {
                        $roles[] = 'admin';
                    }
                }
                break;
        }

        return array_values(array_unique($roles));
    }
}
