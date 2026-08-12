<?php

namespace App\Services;

use App\Models\DafConfig;
use App\Models\DAFAccount;

class DafFlowService
{
    /**
     * Raw flow config loaded from DB (per sponsor)
     */
    protected $config = [];

    /**
     * Current DAF id
     */
    protected $dafId;

    /**
     * Cached DAF model
     */
    protected $daf;

    /* ============================================
     * CONFIG LOADING
     * ============================================ */

    /**
     * Load sponsor-specific flow config
     */
    public function loadConfig($sponsorId, $dafId)
    {
        $raw = DafConfig::where('sponsor_id', $sponsorId)->value('config');

        $this->config = is_array($raw)
            ? $raw
            : (json_decode($raw, true) ?: []);

        $this->dafId = $dafId;
        $this->daf   = DAFAccount::findOrFail($dafId);
    }

    /**
     * Get raw config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /* ============================================
     * STEP KEY NORMALIZATION
     * ============================================ */

    /**
     * Normalize constant-based keys to config keys
     */
    public function normalizeStepKey($key)
    {
        if (!$key) return null;

        $map = [
            DAFAccount::DAF_DONOR            => 'donor',
            DAFAccount::DAF_ADDITIONAL_DONOR => 'additional_donor',
            DAFAccount::DAF_TYPE             => 'daf_type',
            DAFAccount::DAF_SUCCESSORS       => 'successors',
            DAFAccount::DAF_CONTRIBUTIONS    => 'contributions',
            DAFAccount::DAF_INVESTMENTS      => 'investments',
            DAFAccount::DAF_AUTHORIZATION    => 'authorization',

            // contribution children
            DAFAccount::DAF_CONTRIBUTIONS_CASH       => 'contributions.cash_equivalents',
            DAFAccount::DAF_CONTRIBUTIONS_SECURITIES => 'contributions.securities_or_mutual_funds',
            DAFAccount::DAF_CONTRIBUTIONS_STOCKS     => 'contributions.stocks',
            DAFAccount::DAF_CONTRIBUTIONS_OTHERS     => 'contributions.others',
        ];

        return isset($map[$key]) ? $map[$key] : $key;
    }

    /* ============================================
     * ROUTE RESOLUTION
     * ============================================ */

    /**
     * Resolve route name for a step key
     */
    public function resolveRoute($key)
    {
        $routes = [
            'donor'                                    => 'agency-daf-account-info',
            'additional_donor'                         => 'agency-daf-additional-donor',
            'daf_type'                                 => 'agency-daf-type',
            'successors'                               => 'agency-daf-successors',
            'investments'                              => 'agency-daf-investments',

            'contributions'                            => 'agency-daf-contributions-cash',
            'contributions.cash_equivalents'           => 'agency-daf-contributions-cash',
            'contributions.securities_or_mutual_funds' => 'agency-daf-contributions-securities',
            'contributions.stocks'                     => 'agency-daf-contributions-stocks',
            'contributions.others'                     => 'agency-daf-contributions-others',

            'authorization'                            => 'agency-daf-authorization',
        ];

        return isset($routes[$key]) ? $routes[$key] : null;
    }

    /* ============================================
     * ENABLE FLOW LOGIC
     * ============================================ */

    /**
     * Determine which steps are enabled (order-aware)
     */
    private function getEnabledSteps()
    {
        $enabledSteps = [];
        $unlockNext   = true;

        // Sort config by order ASC
        $steps = $this->config;
        uasort($steps, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        foreach ($steps as $key => $step) {

            if (empty($step['enabled'])) {
                continue;
            }

            if ($unlockNext) {
                $enabledSteps[$key] = true;

                // stop enabling after first UNSAVED step
                if (
                    DAFAccount::getMenuStatus($this->dafId, $key)
                    !== DAFAccount::LINK_SAVED
                ) {
                    $unlockNext = false;
                }
            } else {
                $enabledSteps[$key] = false;
            }
        }

        return $enabledSteps;
    }

    /* ============================================
    * LEFT NAVIGATION BUILDER
    * ============================================ */

    /**
     * Build left navigation menu
     *
     * @return array
     */
    public function buildLeftNavigation()
    {
        $menu = [];

        foreach ($this->config as $key => $step) {

            // Skip disabled steps from config
            if (empty($step['enabled'])) {
                continue;
            }

            // Resolve menu status (enabled + saved)
            $menuStatus = self::getMenuStatus($this->dafId, $key);

            $item = [
                'key'     => $key,
                'name'    => $step['name'] ?? '',
                'order'   => $step['order'] ?? 0,
                'route'   => $this->resolveRoute($key),
                'enabled' => $menuStatus['enabled'],
                'status'  => $menuStatus['status'], // UI only
            ];

            // Child menus inherit parent enabled state
            if (!empty($step['children'])) {

                $children = [];

                foreach ($step['children'] as $childKey => $child) {

                    if (empty($child['enabled'])) {
                        continue;
                    }

                    $children[] = [
                        'key'     => $childKey,
                        'name'    => $child['name'] ?? '',
                        'order'   => $child['order'] ?? 0,
                        'route'   => $this->resolveRoute($key . '.' . $childKey),
                        'enabled' => $menuStatus['enabled'],
                    ];
                }

                usort($children, function ($a, $b) {
                    return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
                });

                if ($children) {
                    $item['children'] = $children;
                }
            }

            $menu[] = $item;
        }

        // Sort menu by order
        usort($menu, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });
        # echo "<pre>"; print_r($menu); echo "</pre>"; die;
        return $menu;
    }

   /* ============================================
    * MENU STATUS (ENABLED + SAVED)
    * ============================================ */

    /**
     * Get menu enable & save status
     *
     * @param int    $dafId
     * @param string $key
     * @return array
     */

    static public function getMenuStatus($dafId, $key)
    {
        $dafInfo = DAFAccount::find($dafId);

        // ✅ ALWAYS initialize defaults
        $arr = [
            'enabled' => DAFAccount::LINK_DISABLED,
            'status' => DAFAccount::LINK_DISABLED,
        ];

        if (!$dafInfo) {
            return $arr;
        }

        // ---------- DONOR ----------
        if ($key === DAFAccount::DAF_DONOR) {

            # $enableed = self::getPreviousEnabledKey($dafInfo, $key);

            if ($dafInfo->donor && $dafInfo->fund_name) {
                $arr['status'] = DAFAccount::LINK_SAVED;
            }
            $arr['enabled'] =  1;
        }

        // ---------- DONOR ----------
        if ($key === DAFAccount::DAF_TYPE) {

            $enableed = self::getPreviousEnabledKey($dafInfo, $key);

            if ($dafInfo->daf_type) {
                $arr['status'] = DAFAccount::LINK_SAVED;
            }
            $arr['enabled'] =  1;
        }

        // ---------- ADDITIONAL DONOR ----------
        if ($key === DAFAccount::DAF_ADDITIONAL_DONOR) {

            $enableed = self::getPreviousEnabledKey($dafInfo, $key);

            $donors = json_decode($dafInfo->donors, true);
            if (isset($donors['donors']) && count($donors['donors'])) {
                $arr['status'] = DAFAccount::LINK_SAVED;
            }
            $arr['enabled'] =  $enableed; 
        }

        // ---------- SUCCESSORS ----------
        if ($key === DAFAccount::DAF_SUCCESSORS) {

            $enableed =  self::getPreviousEnabledKey($dafInfo, $key);

            $successors = json_decode($dafInfo->successors, true);
            if (
                (isset($successors['individuals']) && count($successors['individuals'])) ||
                (isset($successors['organizations']) && count($successors['organizations'])) ||
                (!empty($successors['endowment']['isSelected']))
            ) {
                $arr['status'] = DAFAccount::LINK_SAVED;
            }

            $arr['enabled'] =  $enableed; 
        }

        // ---------- CONTRIBUTIONS ----------
        if ($key === DAFAccount::DAF_CONTRIBUTIONS) {

            $enableed =  self::getPreviousEnabledKey($dafInfo, $key);

            $contributions = json_decode($dafInfo->contributions, true);
            if (self::isContributionExist($contributions)) {
                $arr['status'] = DAFAccount::LINK_SAVED;
            }
            $arr['enabled'] =  $enableed;   
        }

        // ---------- INVESTMENTS ----------
        if ($key === DAFAccount::DAF_INVESTMENTS) {

            $enableed = self::getPreviousEnabledKey($dafInfo, $key);

            if ($dafInfo->investments) {
                $arr['status'] = DAFAccount::LINK_SAVED;
            }
            $arr['enabled'] =  $enableed; 
        }

        // ---------- AUTHORIZATION ----------
        if ($key === DAFAccount::DAF_AUTHORIZATION) {

            $enableed = self::getPreviousEnabledKey($dafInfo, $key);

            if ($dafInfo->authorized) {
                $arr['status'] = DAFAccount::LINK_SAVED;
            }
            $arr['enabled'] =  $enableed; 
        }
        return $arr;
    }

    /**
     * Get previous enabled step key
     */
    static public function getPreviousEnabledKey($dafInfo, $currentKey)
    {
        $enable = DAFAccount::LINK_DISABLED;

        $dafConfig = DafConfig::where('sponsor_id', $dafInfo->sponsor_id)->first();

        if (!$dafConfig || empty($dafConfig->config)) {
            return null;
        }

        $steps = $dafConfig->config;
        $enabledSteps = [];
        
        // 1️⃣ Keep only enabled steps
        foreach ($steps as $stepKey => $step) {
            if (!empty($step['enabled'])) {
                $enabledSteps[$stepKey] = $step;
            }
        }

        // 2️⃣ Sort by order ASC
        uasort($enabledSteps, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        // 3️⃣ Get ordered keys
        $keys = array_keys($enabledSteps);
       
        // 4️⃣ Find current index
        $index = array_search($currentKey, $keys, true);

        // 5️⃣ Return previous key if exists
        if ($index === false || $index === 0) {   
            return null; // no previous
        } 

        $previousKey = $keys[$index - 1];

        if ($previousKey === DAFAccount::DAF_DONOR) {
            # $enable = DAFAccount::LINK_ENABLED;
            if ($dafInfo->donor && $dafInfo->fund_name) {
                $enable = DAFAccount::LINK_ENABLED;
            }
        } elseif ($previousKey === DAFAccount::DAF_TYPE) {

            if ($dafInfo->daf_type) {
                $enable = DAFAccount::LINK_ENABLED;
            }

        } elseif ($previousKey === DAFAccount::DAF_ADDITIONAL_DONOR) {
            
            if ($dafInfo->donor && $dafInfo->fund_name) {
                $enable = DAFAccount::LINK_ENABLED;
            }

        } elseif ($previousKey === DAFAccount::DAF_SUCCESSORS) {

            $successors = json_decode($dafInfo->successors, true);
            if (
                (isset($successors['individuals']) && count($successors['individuals'])) ||
                (isset($successors['organizations']) && count($successors['organizations'])) ||
                (!empty($successors['endowment']['isSelected']))
            ) {
                $enable = DAFAccount::LINK_ENABLED;
            }

        } elseif ($previousKey === DAFAccount::DAF_CONTRIBUTIONS) {

            $contributions = json_decode($dafInfo->contributions, true);
            if (self::isContributionExist($contributions)) {
                $enable = DAFAccount::LINK_ENABLED;
            }

        } elseif ($previousKey === DAFAccount::DAF_INVESTMENTS) {

            if ($dafInfo->investments) {
                $enable = DAFAccount::LINK_ENABLED;
            }

        } elseif ($previousKey === DAFAccount::DAF_AUTHORIZATION) {

            $enable = DAFAccount::LINK_ENABLED;
        }
        return $enable;
    }

    /* ============================================
     * NEXT ROUTE NAVIGATION
     * ============================================ */

    /**
     * Get next route based on menu order
     */
    public static function getNextRouteByOrder($id, $parentKey, $childKey = null)
    {
        $daf  = DAFAccount::findOrFail($id);
        $flow = new self();

        $flow->loadConfig($daf->sponsor_id, $id);
        $menu = $flow->buildLeftNavigation();

        if (empty($menu)) {
            return 'agency-daf-authorization';
        }

        $parentKey = $flow->normalizeStepKey($parentKey);
        $childKey  = $flow->normalizeStepKey($childKey);

        if ($childKey && strpos($childKey, '.') !== false) {
            $childKey = explode('.', $childKey)[1];
        }

        foreach ($menu as $parentIndex => $parent) {

            if ($parent['key'] !== $parentKey) continue;

            // Move inside children
            if ($childKey && !empty($parent['children'])) {
                foreach ($parent['children'] as $childIndex => $child) {

                    if ($child['key'] !== $childKey) continue;

                    return $parent['children'][$childIndex + 1]['route']
                        ?? ($menu[$parentIndex + 1]['route'] ?? 'agency-daf-authorization');
                }
            }

            // First child
            if (!$childKey && !empty($parent['children'])) {
                return $parent['children'][0]['route'];
            }

            // Next parent
            return $menu[$parentIndex + 1]['route'] ?? 'agency-daf-authorization';
        }

        return $menu[0]['route'];
    }

    /**
     * Check if contributions exist
     */
    protected static function isContributionExist($contributions)
    {
        if (!$contributions) return false;
        if ( isset($contributions['securities']) && count($contributions['securities']) ) {
            return true;
        } else if (isset($contributions['cash']) && ($contributions['cash']['wire_amount'] != null || $contributions['cash']['check_amount'] != null)) {
            return true;
        } else if (isset($contributions['credit_card'])) {
            return true;
        } else if (isset($contributions['stocks']) && count($contributions['stocks'])) {
            return true;
        } else if (isset($contributions['others']) && $contributions['others']['is_active'] == true) {
            return true;
        } else {
            return false;
        }
    }               
}
