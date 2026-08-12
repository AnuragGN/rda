<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// use Symfony\Component\HttpFoundation\Request;

//create table activity_log(
//  id serial primary key,
//  name varchar(64),
//  action varchar(64)  NOT NULL,
//  url varchar(255),
//  description  varchar(255),
//  target_string_id varchar(64),
//  target_id integer,
//  target_type varchar(64),
//  data text,
//  ip varchar(64),
//  agent  varchar(255),
//  auth_user_id integer,
//  created_on timestamp without time zone DEFAULT now() NOT NULL,
//  updated_on timestamp without time zone DEFAULT now() NOT NULL,
//  FOREIGN KEY (auth_user_id) REFERENCES auth_user(auth_user_id))


class LogActivity extends Model
{
    /* @var string */
    protected $table = 'activity_log';

    /* @var boolean */
    public $timestamps = false;

    protected $suppressExceptions = true;

    const NAME_AUTH = 'auth';
    const NAME_FUND = 'fund'; // funds, statement & history
    const NAME_GRANT = 'grant'; // grant-recommendation - cart & make-a-grant
    const NAME_AGENCY_TRANSACTION = 'agency-transaction'; // fund-transfer - cart & make-a-grant
    const NAME_GIFT = 'gift'; // gift, make-a-grant
    const NAME_DONATION = 'donation'; // transaction by card or bank
    const NAME_TRANSACTION = 'transaction'; // transaction by card or bank
    const NAME_TRANSACTION_API_TEST = 'transaction-api-test'; // transaction by card or bank
    const NAME_DONATION_API_TEST = 'donation-api-test'; // transaction by card or bank
    const NAME_TRANSACTION_API = 'transaction-api'; // transaction by card or bank
    const NAME_DONATION_API = 'donation-api'; // transaction by card or bank
    const NAME_EMAIL = 'email'; // transaction by card or bank
    const NAME_SMS = 'sms'; // send sms

    const ACTION_LOGIN = 'login';
    const ACTION_2FA = '2fa';
    const ACTION_2FA_SENT = '2fa-sent';
    const ACTION_2FA_RESEND = '2fa-resend';
    const ACTION_LOGOUT = 'logout';
    const ACTION_CONFIRM = 'confirm';
    const ACTION_CHANGE_PASSWORD = 'change-password';
    const ACTION_CHANGE_EMAIL = 'change-email'; // change email
    const ACTION_USER_AGREEMENT_ACCEPTED = 'agreement-accepted';
    const ACTION_PASSWORD_MIGRATED = 'password-migrated-to-bcrypt';
    const ACTION_PASSWORD_REHASHED = 'password-rehashed'; // cost/driver update
    const ACTION_PASSWORD_SET = 'password-changed';

    const ACTION_CREATE = 'create'; // show create view
    const ACTION_VIEW = 'view'; // read a record
    const ACTION_EDIT = 'edit'; // edit record view
    const ACTION_SAVE = 'save'; // edited or created
    const ACTION_DELETE = 'delete'; // deleted a record
    const ACTION_LIST = 'list'; // list records
    const ACTION_CONTRIBUTE = 'contribute'; // contribute to fund
    const ACTION_PAYMENT = 'payment'; //
    const ACTION_REFUND = 'refund'; //
    const ACTION_SEND_EMAIL = 'send-email';
    const ACTION_SEND_SMS = 'send-sms';

    const ACTION_RESEND_ACCOUNT_ACTIVATION_LINK = 'resend-account-activation-link';

    const DESCRIPTION_SUCCESS = 'success';
    const DESCRIPTION_FAILED = 'failed';
    const DES_BAD_INPUT = 'bad-input';
    const DESCRIPTION_FUND_LIST = 'fund-list';
    const DESCRIPTION_GIFT_HISTORY = 'gift-history';
    const DESCRIPTION_GRANT_HISTORY = 'grant-history';
    const DESCRIPTION_DISBURSEMENTS_PENDING = 'disbursements-pending';
    const DESCRIPTION_GRANTS_PENDING = 'grants-pending';
    const DES_CARD_TRANSACTION_INIT = 'card-transaction-init';
    const DES_CARD_TRANSACTION_RESP = 'card-transaction-response';
    const DES_BANK_TRANSACTION_INIT = 'bank-transaction-init';
    const DES_BANK_TRANSACTION_RESP = 'bank-transaction-response';
    const DES_REFUND_RESP = 'refund-response';
    const DESCRIPTION_PASSWORD_MISMATCH = 'password-mismatch';
    const DES_UNEXPECTED_ERROR = 'unexpected-error';
    const DES_USER_AGREEMENT = 'user-agreement';
    const ST_LOGIN_SSO = 'sso';
    // $log['url'] = \Request::full();
    // $log['method'] = request()->method();

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'subject', 'url', 'method', 'ip', 'agent', 'user_id'
//    ];


    /**
     * OBSOLETE: DO NOT USE THIS CONSTRUCTOR i.e. new LogActivity()
     * Use  LogActivity::safe() or LogActivity::required()
     * TODO: Refactor code and remove this constructor
     *
     * @param string|null $name
     * @param string $action
     * @param array $attributes
     */
    public function __construct($name = null, string $action = 'unknown', array $attributes = [])
    {
        if (is_array($name)) {
            // Eloquent hydration: first arg is actually $attributes
            parent::__construct($name);
            return;
        }
        parent::__construct($attributes);

        $this->name = $name;
        $this->action = $action;
    }

    /**
     * Best-effort logging.
     * Logging failures will NOT throw exceptions.
     */
    public static function safe(string $name, string $action)
    {
        return (new static($name, $action))->suppressExceptions(true);
    }

    /**
     * Required logging.
     * Logging failures WILL throw exceptions.
     */
    public static function required(string $name, string $action): self
    {
        return (new static($name, $action))->suppressExceptions(false);
    }

    protected function suppressExceptions(bool $value): self
    {
        $this->suppressExceptions = $value;
        return $this;
    }

    public function add()
    {
        /** @var Contact $sessionContact */
        $sessionContact = Contact::sessionContact();
        $contactTypeId = $sessionContact ? $sessionContact->getContactTypeId() : null;

        try {
            $this->ip = request()->ip();
            $this->url = \substr(url()->full(), 0, 250);
            $agent = request()->header('user-agent');
            $this->agent = \substr($agent, 0, 255);
            $this->auth_user_id = User::getSessionUserId();
            $this->contact_type_id = $contactTypeId;
            $this->save();
        } catch (\Throwable $e) {
            if ($this->suppressExceptions) {
                // fallback logging
                logger()->warning(
                    'LogActivity add failed', [
                        'name'   => $this->name,
                        'action' => $this->action,
                        'error'  => $e->getMessage(),
                    ]);
                return;
            }
            throw $e; // only if explicitly enabled
        }
    }

    public static function activities()
    {
        // Allow `limit` query params, defaulting to 15
        $limit = (int) request()->query('limit', 15);
        $limit = $limit < 1 ? 15 : min($limit, 100);

        return LogActivity::getQueryFromRequest()
            ->orderBy('created_on', 'DESC')
            ->paginate($limit)
            ->withQueryString();
    }

    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function action(string $action)
    {
        $this->action = $action;
        return $this;
    }

    public function description(string $description)
    {
        // Str::limit supports multibyte chars (avoid \substr for user input)
        $this->description = Str::limit($description, 255, '');
        return $this;
    }

    public function data($data)
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $json = json_encode(['_serialization_error_' => json_last_error_msg()]);
        }
        $this->data = $json;
        return $this;
    }

    public function onModel(Model $model)
    {
        if ($model) {
            if ($model->isModelIdInteger()) {
                $this->target_id = $model->getModelId();
            } else {
                $this->target_string_id = $model->getModelId();
            }
            $this->target_type = $model->getModelType();
        }
        return $this;
    }

    public function onSessionUser()
    {
        $user = auth()->user();
        if ($user) $this->onModel($user);
        return $this;
    }

    public function getUserInfo()
    {
        $user = User::getById($this->auth_user_id);
        return $user ? $user->auth_user_id . " : " . $user->username : null;
    }

    /**
     * @param array|null $params
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getQueryFromRequest(?array $params = null)
    {
        $params = $params ?? request()->query();

        // Whitelist allowed filterable columns
        $allowed = [
            'id',
            'name',
            'action',
            'description',
            'target_id',
            'target_type',
            'contact_type_id',
            'auth_user_id',
        ];

        $query = LogActivity::query();

        foreach ($allowed as $column) {
            if (!array_key_exists($column, $params)) {
                continue; // only use if present
            }

            $value = $params[$column];
            if ($value === '' || $value === null) {
                continue; // ignore empty
            }

            // If multiple values provided (?action[]=login&action[]=logout), use whereIn
            if (is_array($value)) {
                // $value = array_values(array_filter($value, static fn($v) => $v !== '' && $v !== null));
                if (count($value)) {
                    $query->whereIn($column, $value);
                }
                continue;
            }

            // Cast numeric columns to int when appropriate
            if (in_array($column, ['id', 'target_id', 'contact_type_id', 'auth_user_id'], true)) {
                if (is_numeric($value)) {
                    $value = (int) $value;
                } else {
                    continue; // skip invalid numeric input
                }
            }

            $query->where($column, $value);
        }

        // Optional: created_on range support if provided
        // Example usage: ?created_from=2025-01-01&created_to=2025-01-31
        $from = $params['created_from'] ?? null;
        $to   = $params['created_to']   ?? null;
        if ($from) {
            $query->whereDate('created_on', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_on', '<=', $to);
        }

        return $query; // return Builder (prepared query)
    }
    
    public static function getClientLastLogin($contactId)
    {
        return self::where('target_id', $contactId)
            ->where('target_type', 'user')
            ->where('action', 'login')
            ->latest('id') // cleaner than orderBy desc
            ->first();
    }
}
