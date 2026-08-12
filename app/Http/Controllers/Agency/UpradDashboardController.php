<?php
namespace App\Http\Controllers\Agency; 
use App\Http\Controllers\Controller;

use App\Services\DashboardDataService;
use Illuminate\Http\Request;

class UpradDashboardController extends Controller
{
    public function __construct(private DashboardDataService $data) {}

    public function index(Request $request)
    {
        $sponsors     = $this->data->getSponsors();
        $funds        = $this->data->getFunds();
        $grants       = $this->data->getGrants();
        $tickets      = $this->data->getTickets();
        $recommendations = $this->data->getRecommendations();
        $dafApps      = $this->data->getDafApplications();
        $activity     = $this->data->getActivityEvents();
        $kpis         = $this->data->getKpis();

        // Build pipeline stages from recommendations
        $stages = ['submitted','approved','cancelled','paid'];
        $stageLabels = ['submitted'=>'Submitted','approved'=>'Approved','cancelled'=>'Cancelled','paid'=>'Paid'];
        $stageColors = ['submitted'=>'#d97706','approved'=>'#059669','cancelled'=>'#dc2626','paid'=>'#0891b2'];
        $pipeline = [];
        foreach ($stages as $s) {
            $items = array_filter($recommendations, fn($r) => $r['status'] === $s);
            $pipeline[$s] = [
                'label'  => $stageLabels[$s],
                'color'  => $stageColors[$s],
                'count'  => count($items),
                'total'  => array_sum(array_column($items, 'amount')),
                'items'  => array_values($items),
            ];
        }
        $maxCount = max(max(array_column($pipeline, 'count')), 1);
        foreach ($pipeline as &$p) {
            $p['pct'] = $maxCount > 0 ? round(($p['count'] / $maxCount) * 100) : 0;
        }
        $pendingTotal = $pipeline['submitted']['total'] + $pipeline['approved']['total'];

        // Group funds by sponsor
        $fundsBySponsor = [];
        foreach ($sponsors as $sp) {
            $fundsBySponsor[$sp['id']] = [
                'sponsor' => $sp,
                'funds'   => array_values(array_filter($funds, fn($f) => $f['sponsorId'] === $sp['id'])),
            ];
        }

        // Sponsor balances from current fund data
        $sponsorBalances = [];
        foreach ($sponsors as $sp) {
            $spFunds = array_filter($funds, fn($f) => $f['sponsorId'] === $sp['id'] && $f['status'] === 'active');
            $sponsorBalances[] = [
                'label'   => $sp['name'],
                'balance' => array_sum(array_column($spFunds, 'balance')),
            ];
        }

        // Ticket stats
        $ticketStats = [
            'open'       => count(array_filter($tickets, fn($t) => $t['status'] === 'open')),
            'in_progress'=> count(array_filter($tickets, fn($t) => $t['status'] === 'in_progress')),
            'hold'       => count(array_filter($tickets, fn($t) => $t['status'] === 'hold')),
            'closed'     => count(array_filter($tickets, fn($t) => $t['status'] === 'closed')),
        ];

        return view('agency.agency-advisor.new-dashboard.index', compact(
            'sponsors','funds','grants','tickets','recommendations',
            'dafApps','activity','kpis','sponsorBalances',
            'pipeline','pendingTotal','fundsBySponsor','ticketStats'
        ));
    }
}
