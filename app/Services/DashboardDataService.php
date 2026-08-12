<?php

namespace App\Services;

class DashboardDataService
{
    public function getSponsors(): array
    {
        return [
            ['id'=>'cgf',  'name'=>'Charitable Gifting Fund',             'aum'=>2150000,'funds'=>8,'gifts'=>45000,'grants'=>128500,'ytd'=>312000],
            ['id'=>'bsmhf','name'=>'Bon Secours Mercy Health Foundation', 'aum'=>1980000,'funds'=>6,'gifts'=>38500,'grants'=>104200,'ytd'=>278000],
            ['id'=>'fftc', 'name'=>'Foundation For The Carolinas',        'aum'=>2340000,'funds'=>9,'gifts'=>52000,'grants'=>143800,'ytd'=>341500],
            ['id'=>'jcfsd','name'=>'JCF San Diego',                       'aum'=>1760000,'funds'=>5,'gifts'=>29000,'grants'=>87600, 'ytd'=>214000],
            ['id'=>'pbfnc','name'=>'Provision - Baptist Fdn of NC',       'aum'=>1420000,'funds'=>4,'gifts'=>21500,'grants'=>63400, 'ytd'=>168000],
            ['id'=>'decho','name'=>'Dechomai Foundation',                  'aum'=>1650000,'funds'=>5,'gifts'=>31000,'grants'=>79800, 'ytd'=>195500],
        ];
    }

    public function getFunds(): array
    {
        return [
            ['id'=>'f1', 'sponsorId'=>'cgf',  'name'=>'Smith Family Fund',         'donors'=>['Robert Smith','Linda Smith'],              'balance'=>980000,  'type'=>'daf',        'status'=>'active'],
            ['id'=>'f2', 'sponsorId'=>'cgf',  'name'=>'Green Future Endowment',    'donors'=>['Sarah Williams'],                          'balance'=>1170000, 'type'=>'endowment',  'status'=>'active'],
            ['id'=>'f3', 'sponsorId'=>'bsmhf','name'=>'Johnson Charitable Fund',   'donors'=>['Michael Johnson','Patricia Johnson'],       'balance'=>1200000, 'type'=>'daf',        'status'=>'active'],
            ['id'=>'f4', 'sponsorId'=>'fftc', 'name'=>'Heritage Education Fund',   'donors'=>['David Park','Amy Park'],                   'balance'=>935000,  'type'=>'scholarship','status'=>'active'],
            ['id'=>'f5', 'sponsorId'=>'jcfsd','name'=>'Chen Family Foundation',    'donors'=>['Robert Chen','Linda Chen'],                'balance'=>920000,  'type'=>'daf',        'status'=>'active'],
            ['id'=>'f6', 'sponsorId'=>'jcfsd','name'=>'Torres Giving Fund',        'donors'=>['Michael Torres'],                          'balance'=>840000,  'type'=>'daf',        'status'=>'pending'],
            ['id'=>'f7', 'sponsorId'=>'cgf',  'name'=>'Williams Impact Fund',      'donors'=>['Jennifer Williams','Mark Williams'],        'balance'=>540000,  'type'=>'other',      'status'=>'active'],
            ['id'=>'f8', 'sponsorId'=>'fftc', 'name'=>'Park Scholarship Fund',     'donors'=>['David Park'],                              'balance'=>430000,  'type'=>'scholarship','status'=>'active'],
            ['id'=>'f9', 'sponsorId'=>'pbfnc','name'=>'Nguyen Community Fund',     'donors'=>['Jennifer Nguyen','Thomas Nguyen'],         'balance'=>380000,  'type'=>'daf',        'status'=>'active'],
            ['id'=>'f10','sponsorId'=>'cgf',  'name'=>'Anderson Legacy Fund',      'donors'=>['Thomas Anderson'],                         'balance'=>720000,  'type'=>'endowment',  'status'=>'active'],
            ['id'=>'f11','sponsorId'=>'bsmhf','name'=>'Martinez Arts Endowment',   'donors'=>['Carlos Martinez','Elena Martinez'],        'balance'=>610000,  'type'=>'endowment',  'status'=>'active'],
            ['id'=>'f12','sponsorId'=>'decho','name'=>'Lee Education Trust',        'donors'=>['Kevin Lee','Susan Lee'],                   'balance'=>290000,  'type'=>'scholarship','status'=>'closed'],
            ['id'=>'f13','sponsorId'=>'decho','name'=>'Patel Charitable Fund',      'donors'=>['Raj Patel','Priya Patel'],                 'balance'=>760000,  'type'=>'daf',        'status'=>'active'],
            ['id'=>'f14','sponsorId'=>'pbfnc','name'=>'Grace Community Endowment', 'donors'=>['William Moore','Grace Moore'],             'balance'=>520000,  'type'=>'endowment',  'status'=>'active'],
            ['id'=>'f15','sponsorId'=>'fftc', 'name'=>'Carolinas Impact Fund',     'donors'=>['James Harris','Carol Harris','Tom Reed'],  'balance'=>975000,  'type'=>'daf',        'status'=>'active'],
        ];
    }

    public function getGrants(): array
    {
        return [
            ['id'=>'g1', 'fundId'=>'f1', 'sponsorId'=>'cgf',  'org'=>'Habitat for Humanity',       'amount'=>5000, 'stage'=>'submitted',  'date'=>'Apr 22, 2025'],
            ['id'=>'g2', 'fundId'=>'f2', 'sponsorId'=>'cgf',  'org'=>'Red Cross',                  'amount'=>2500, 'stage'=>'approved',   'date'=>'Apr 10, 2025'],
            ['id'=>'g3', 'fundId'=>'f3', 'sponsorId'=>'bsmhf','org'=>'Local Food Bank',             'amount'=>1000, 'stage'=>'submitted',  'date'=>'Apr 18, 2025'],
            ['id'=>'g4', 'fundId'=>'f4', 'sponsorId'=>'fftc', 'org'=>"Children's Hospital",         'amount'=>10000,'stage'=>'approved',   'date'=>'Apr 05, 2025'],
            ['id'=>'g5', 'fundId'=>'f5', 'sponsorId'=>'jcfsd','org'=>'Environmental Defense Fund',  'amount'=>3500, 'stage'=>'submitted',  'date'=>'Apr 20, 2025'],
            ['id'=>'g6', 'fundId'=>'f6', 'sponsorId'=>'jcfsd','org'=>'YMCA of Greater San Diego',   'amount'=>2000, 'stage'=>'paid',       'date'=>'Mar 28, 2025'],
            ['id'=>'g7', 'fundId'=>'f7', 'sponsorId'=>'cgf',  'org'=>'United Way',                  'amount'=>7500, 'stage'=>'paid',       'date'=>'Mar 15, 2025'],
            ['id'=>'g8', 'fundId'=>'f8', 'sponsorId'=>'fftc', 'org'=>'Scholarship America',         'amount'=>4000, 'stage'=>'submitted',  'date'=>'Apr 14, 2025'],
            ['id'=>'g9', 'fundId'=>'f9', 'sponsorId'=>'pbfnc','org'=>'Community Health Center',     'amount'=>1500, 'stage'=>'cancelled',  'date'=>'Apr 02, 2025'],
            ['id'=>'g10','fundId'=>'f10','sponsorId'=>'cgf',  'org'=>'Nature Conservancy',          'amount'=>8000, 'stage'=>'approved',   'date'=>'Apr 08, 2025'],
            ['id'=>'g11','fundId'=>'f11','sponsorId'=>'bsmhf','org'=>'Arts Council',                 'amount'=>3000, 'stage'=>'submitted',  'date'=>'Apr 21, 2025'],
            ['id'=>'g12','fundId'=>'f12','sponsorId'=>'decho','org'=>'Public Library Foundation',    'amount'=>1200, 'stage'=>'paid',       'date'=>'Mar 10, 2025'],
            ['id'=>'g13','fundId'=>'f1', 'sponsorId'=>'cgf',  'org'=>'Meals on Wheels',             'amount'=>2200, 'stage'=>'submitted',  'date'=>'Apr 16, 2025'],
            ['id'=>'g14','fundId'=>'f3', 'sponsorId'=>'bsmhf','org'=>'Boys & Girls Club',           'amount'=>5500, 'stage'=>'approved',   'date'=>'Apr 12, 2025'],
            ['id'=>'g15','fundId'=>'f13','sponsorId'=>'decho','org'=>'Sierra Club Foundation',       'amount'=>4500, 'stage'=>'cancelled',  'date'=>'Mar 25, 2025'],
        ];
    }

    public function getTickets(): array
    {
        return [
            ['id'=>'t1', 'title'=>'Grant Disbursement Delay — Smith Family Fund','category'=>'Raise Cash',          'status'=>'open',       'priority'=>'high',  'date'=>'Apr 22, 2025','sponsorId'=>'cgf'],
            ['id'=>'t2', 'title'=>'Contribution Receipt Request',                 'category'=>'Meeting Notes',       'status'=>'in_progress','priority'=>'medium','date'=>'Apr 19, 2025','sponsorId'=>'cgf'],
            ['id'=>'t3', 'title'=>'Fund Statement Discrepancy',                   'category'=>'Rebalance Portfolio', 'status'=>'hold',       'priority'=>'high',  'date'=>'Apr 17, 2025','sponsorId'=>'bsmhf'],
            ['id'=>'t4', 'title'=>'New Donor Onboarding Assistance',              'category'=>'Advisor Onboarding',  'status'=>'open',       'priority'=>'low',   'date'=>'Apr 15, 2025','sponsorId'=>'fftc'],
            ['id'=>'t5', 'title'=>'Investment Allocation Update Request',          'category'=>'Rebalance Portfolio', 'status'=>'closed',     'priority'=>'medium','date'=>'Apr 12, 2025','sponsorId'=>'jcfsd'],
            ['id'=>'t6', 'title'=>'Incorrect Tax Receipt Issued',                  'category'=>'Events',              'status'=>'open',       'priority'=>'high',  'date'=>'Apr 10, 2025','sponsorId'=>'pbfnc'],
            ['id'=>'t7', 'title'=>'Wire Transfer Confirmation Missing',            'category'=>'Raise Cash',          'status'=>'in_progress','priority'=>'medium','date'=>'Apr 08, 2025','sponsorId'=>'decho'],
            ['id'=>'t8', 'title'=>'Donor Portal Login Issue',                      'category'=>'Advisor Onboarding',  'status'=>'closed',     'priority'=>'low',   'date'=>'Apr 05, 2025','sponsorId'=>'cgf'],
            ['id'=>'t9', 'title'=>'Quarterly Report Not Generated',                'category'=>'Meeting Notes',       'status'=>'hold',       'priority'=>'medium','date'=>'Mar 28, 2025','sponsorId'=>'fftc'],
            ['id'=>'t10','title'=>'Fund Beneficiary Update',                       'category'=>'Events',              'status'=>'closed',     'priority'=>'low',   'date'=>'Mar 20, 2025','sponsorId'=>'decho'],
        ];
    }

    public function getRecommendations(): array
    {
        return [
            ['id'=>'r1','org'=>'Habitat for Humanity',      'amount'=>5000, 'donor'=>'Robert Smith',   'fund'=>'Smith Family Fund',       'sponsorId'=>'cgf',  'date'=>'Apr 22, 2025','status'=>'submitted'],
            ['id'=>'r2','org'=>'Red Cross',                 'amount'=>2500, 'donor'=>'Sarah Williams', 'fund'=>'Green Future Endowment',  'sponsorId'=>'cgf',  'date'=>'Apr 08, 2025','status'=>'approved'],
            ['id'=>'r3','org'=>'Local Food Bank',           'amount'=>1000, 'donor'=>'Michael Johnson','fund'=>'Johnson Charitable Fund', 'sponsorId'=>'bsmhf','date'=>'Apr 15, 2025','status'=>'submitted'],
            ['id'=>'r4','org'=>"Children's Hospital",       'amount'=>10000,'donor'=>'David Park',     'fund'=>'Heritage Education Fund', 'sponsorId'=>'fftc', 'date'=>'Apr 01, 2025','status'=>'paid'],
            ['id'=>'r5','org'=>'Environmental Defense Fund','amount'=>3500, 'donor'=>'Robert Chen',    'fund'=>'Chen Family Foundation',  'sponsorId'=>'jcfsd','date'=>'Apr 20, 2025','status'=>'submitted'],
            ['id'=>'r6','org'=>'YMCA San Diego',            'amount'=>2000, 'donor'=>'Michael Torres', 'fund'=>'Torres Giving Fund',      'sponsorId'=>'jcfsd','date'=>'Apr 12, 2025','status'=>'approved'],
            ['id'=>'r7','org'=>'United Way',                'amount'=>7500, 'donor'=>'Thomas Anderson','fund'=>'Anderson Legacy Fund',    'sponsorId'=>'cgf',  'date'=>'Apr 18, 2025','status'=>'cancelled'],
            ['id'=>'r8','org'=>'Arts Council',              'amount'=>3000, 'donor'=>'Carlos Martinez','fund'=>'Martinez Arts Endowment', 'sponsorId'=>'bsmhf','date'=>'Apr 21, 2025','status'=>'submitted'],
        ];
    }

    public function getDafApplications(): array
    {
        return [
            ['id'=>'d1','name'=>'Robert & Linda Chen','sponsorId'=>'cgf',  'sponsor'=>'Charitable Gifting Fund',             'date'=>'Mar 15, 2025','status'=>'approved'],
            ['id'=>'d2','name'=>'Michael Torres',     'sponsorId'=>'jcfsd','sponsor'=>'JCF San Diego',                       'date'=>'Apr 02, 2025','status'=>'submitted'],
            ['id'=>'d3','name'=>'Sarah Williams',     'sponsorId'=>'cgf',  'sponsor'=>'Charitable Gifting Fund',             'date'=>'Apr 10, 2025','status'=>'pending'],
            ['id'=>'d4','name'=>'David & Amy Park',   'sponsorId'=>'fftc', 'sponsor'=>'Foundation For The Carolinas',        'date'=>'Feb 28, 2025','status'=>'approved'],
            ['id'=>'d5','name'=>'Jennifer Nguyen',    'sponsorId'=>'pbfnc','sponsor'=>'Provision - Baptist Fdn of NC',       'date'=>'Apr 18, 2025','status'=>'review'],
            ['id'=>'d6','name'=>'Thomas Anderson',    'sponsorId'=>'decho','sponsor'=>'Dechomai Foundation',                  'date'=>'Apr 05, 2025','status'=>'approved'],
            ['id'=>'d7','name'=>'Carlos Martinez',    'sponsorId'=>'bsmhf','sponsor'=>'Bon Secours Mercy Health Foundation', 'date'=>'Apr 20, 2025','status'=>'submitted'],
            ['id'=>'d8','name'=>'Kevin & Susan Lee',  'sponsorId'=>'decho','sponsor'=>'Dechomai Foundation',                  'date'=>'Mar 22, 2025','status'=>'pending'],
        ];
    }

    public function getActivityEvents(): array
    {
        return [
            ['id'=>'a1', 'type'=>'contribution',   'desc'=>'Contribution of $50,000 received',                    'fund'=>'Smith Family Fund',       'donor'=>'Robert Smith',   'ts'=>'Apr 28, 2025 9:15 AM', 'sponsorId'=>'cgf'],
            ['id'=>'a2', 'type'=>'grant_disbursed','desc'=>'Grant of $2,500 disbursed to Red Cross',              'fund'=>'Green Future Endowment',  'donor'=>'Sarah Williams', 'ts'=>'Apr 27, 2025 3:42 PM', 'sponsorId'=>'cgf'],
            ['id'=>'a3', 'type'=>'ticket_opened',  'desc'=>'New ticket: Fund Statement Discrepancy',              'fund'=>'Johnson Charitable Fund', 'donor'=>'Michael Johnson','ts'=>'Apr 27, 2025 11:20 AM','sponsorId'=>'bsmhf'],
            ['id'=>'a4', 'type'=>'recommendation', 'desc'=>'Grant recommendation submitted to YMCA',              'fund'=>'Torres Giving Fund',      'donor'=>'Michael Torres', 'ts'=>'Apr 26, 2025 2:05 PM', 'sponsorId'=>'jcfsd'],
            ['id'=>'a5', 'type'=>'balance_updated','desc'=>'Fund balance updated after Q1 rebalancing',           'fund'=>'Heritage Education Fund', 'donor'=>'David Park',     'ts'=>'Apr 26, 2025 9:00 AM', 'sponsorId'=>'fftc'],
            ['id'=>'a6', 'type'=>'ticket_resolved','desc'=>'Ticket resolved: Investment Allocation',              'fund'=>'Johnson Charitable Fund', 'donor'=>'Michael Johnson','ts'=>'Apr 25, 2025 4:30 PM', 'sponsorId'=>'bsmhf'],
            ['id'=>'a7', 'type'=>'contribution',   'desc'=>'Contribution of $25,000 received',                    'fund'=>'Chen Family Foundation',  'donor'=>'Robert Chen',    'ts'=>'Apr 25, 2025 10:15 AM','sponsorId'=>'jcfsd'],
            ['id'=>'a8', 'type'=>'grant_disbursed','desc'=>"Grant of \$10,000 disbursed to Children's Hospital",  'fund'=>'Heritage Education Fund', 'donor'=>'David Park',     'ts'=>'Apr 24, 2025 1:00 PM', 'sponsorId'=>'fftc'],
            ['id'=>'a9', 'type'=>'recommendation', 'desc'=>'Grant recommendation submitted to Nature Conservancy','fund'=>'Anderson Legacy Fund',    'donor'=>'Thomas Anderson','ts'=>'Apr 24, 2025 9:45 AM', 'sponsorId'=>'cgf'],
            ['id'=>'a10','type'=>'ticket_opened',  'desc'=>'New ticket: Wire Transfer Confirmation Missing',      'fund'=>'Torres Giving Fund',      'donor'=>'Michael Torres', 'ts'=>'Apr 23, 2025 3:15 PM', 'sponsorId'=>'jcfsd'],
            ['id'=>'a11','type'=>'balance_updated','desc'=>'Monthly statement generated and sent',                 'fund'=>'Smith Family Fund',       'donor'=>'Robert Smith',   'ts'=>'Apr 23, 2025 8:00 AM', 'sponsorId'=>'cgf'],
            ['id'=>'a12','type'=>'contribution',   'desc'=>'Contribution of $15,000 received',                    'fund'=>'Martinez Arts Endowment', 'donor'=>'Carlos Martinez','ts'=>'Apr 22, 2025 2:30 PM', 'sponsorId'=>'bsmhf'],
            ['id'=>'a13','type'=>'grant_disbursed','desc'=>'Grant of $7,500 disbursed to United Way',             'fund'=>'Anderson Legacy Fund',    'donor'=>'Thomas Anderson','ts'=>'Apr 22, 2025 11:00 AM','sponsorId'=>'cgf'],
            ['id'=>'a14','type'=>'ticket_resolved','desc'=>'Ticket resolved: Donor Portal Login Issue',           'fund'=>'Smith Family Fund',       'donor'=>'Robert Smith',   'ts'=>'Apr 21, 2025 4:00 PM', 'sponsorId'=>'cgf'],
            ['id'=>'a15','type'=>'recommendation', 'desc'=>'Grant recommendation submitted to Arts Council',      'fund'=>'Martinez Arts Endowment', 'donor'=>'Carlos Martinez','ts'=>'Apr 21, 2025 10:30 AM','sponsorId'=>'bsmhf'],
            ['id'=>'a16','type'=>'contribution',   'desc'=>'Contribution of $80,000 received',                    'fund'=>'Green Future Endowment',  'donor'=>'Sarah Williams', 'ts'=>'Apr 20, 2025 9:00 AM', 'sponsorId'=>'cgf'],
            ['id'=>'a17','type'=>'ticket_opened',  'desc'=>'New ticket: Incorrect Tax Receipt Issued',            'fund'=>'Nguyen Community Fund',   'donor'=>'Jennifer Nguyen','ts'=>'Apr 19, 2025 2:45 PM', 'sponsorId'=>'pbfnc'],
            ['id'=>'a18','type'=>'balance_updated','desc'=>'Portfolio rebalanced — Q1 2025',                      'fund'=>'Heritage Education Fund', 'donor'=>'David Park',     'ts'=>'Apr 18, 2025 3:00 PM', 'sponsorId'=>'fftc'],
            ['id'=>'a19','type'=>'grant_disbursed','desc'=>'Grant of $1,200 disbursed to Public Library',         'fund'=>'Lee Education Trust',     'donor'=>'Kevin Lee',      'ts'=>'Apr 17, 2025 1:30 PM', 'sponsorId'=>'decho'],
            ['id'=>'a20','type'=>'recommendation', 'desc'=>'Grant recommendation submitted to Habitat for Humanity','fund'=>'Smith Family Fund',    'donor'=>'Robert Smith',   'ts'=>'Apr 16, 2025 10:00 AM','sponsorId'=>'cgf'],
            ['id'=>'a21','type'=>'contribution',   'desc'=>'Contribution of $35,000 received',                    'fund'=>'Johnson Charitable Fund', 'donor'=>'Michael Johnson','ts'=>'Apr 15, 2025 9:30 AM', 'sponsorId'=>'bsmhf'],
            ['id'=>'a22','type'=>'ticket_opened',  'desc'=>'New ticket: Contribution Receipt Request',            'fund'=>'Green Future Endowment',  'donor'=>'Sarah Williams', 'ts'=>'Apr 14, 2025 2:00 PM', 'sponsorId'=>'cgf'],
            ['id'=>'a23','type'=>'balance_updated','desc'=>'Annual fee deducted from fund balance',               'fund'=>'Patel Charitable Fund',   'donor'=>'Raj Patel',      'ts'=>'Apr 13, 2025 8:00 AM', 'sponsorId'=>'decho'],
            ['id'=>'a24','type'=>'grant_disbursed','desc'=>'Grant of $4,000 disbursed to Scholarship America',    'fund'=>'Park Scholarship Fund',   'donor'=>'David Park',     'ts'=>'Apr 12, 2025 11:45 AM','sponsorId'=>'fftc'],
            ['id'=>'a25','type'=>'recommendation', 'desc'=>'Grant recommendation submitted to YMCA',              'fund'=>'Torres Giving Fund',      'donor'=>'Michael Torres', 'ts'=>'Apr 11, 2025 3:30 PM', 'sponsorId'=>'jcfsd'],
        ];
    }

    public function getAumData(): array
    {
        return [
            ['month'=>'Dec 2025','total'=>14200000,'cgf'=>2450000,'bsmhf'=>2120000,'fftc'=>2580000,'jcfsd'=>1920000,'pbfnc'=>1490000,'decho'=>3640000],
            ['month'=>'Jan 2026','total'=>14450000,'cgf'=>2480000,'bsmhf'=>2150000,'fftc'=>2620000,'jcfsd'=>1950000,'pbfnc'=>1510000,'decho'=>3740000],
            ['month'=>'Feb 2026','total'=>14680000,'cgf'=>2510000,'bsmhf'=>2180000,'fftc'=>2660000,'jcfsd'=>1970000,'pbfnc'=>1530000,'decho'=>3830000],
            ['month'=>'Mar 2026','total'=>14900000,'cgf'=>2540000,'bsmhf'=>2210000,'fftc'=>2700000,'jcfsd'=>1990000,'pbfnc'=>1550000,'decho'=>3910000],
            ['month'=>'Apr 2026','total'=>15150000,'cgf'=>2570000,'bsmhf'=>2240000,'fftc'=>2740000,'jcfsd'=>2010000,'pbfnc'=>1570000,'decho'=>4020000],
            ['month'=>'May 2026','total'=>15400000,'cgf'=>2600000,'bsmhf'=>2270000,'fftc'=>2780000,'jcfsd'=>2030000,'pbfnc'=>1590000,'decho'=>4130000],
        ];
    }

    public function getPerfData(): array
    {
        return [
            ['month'=>'Apr 2024','all'=>1.2,'f1'=>1.4,'f2'=>0.9,'f3'=>1.5,'f4'=>1.1,'f5'=>1.0,'benchmark'=>1.8],
            ['month'=>'May 2024','all'=>2.1,'f1'=>2.3,'f2'=>1.8,'f3'=>2.4,'f4'=>1.9,'f5'=>1.7,'benchmark'=>2.5],
            ['month'=>'Jun 2024','all'=>1.5,'f1'=>1.7,'f2'=>1.2,'f3'=>1.8,'f4'=>1.3,'f5'=>1.1,'benchmark'=>1.6],
            ['month'=>'Jul 2024','all'=>3.2,'f1'=>3.5,'f2'=>2.9,'f3'=>3.6,'f4'=>3.0,'f5'=>2.8,'benchmark'=>3.0],
            ['month'=>'Aug 2024','all'=>2.8,'f1'=>3.0,'f2'=>2.5,'f3'=>3.1,'f4'=>2.6,'f5'=>2.4,'benchmark'=>2.4],
            ['month'=>'Sep 2024','all'=>1.9,'f1'=>2.1,'f2'=>1.6,'f3'=>2.2,'f4'=>1.7,'f5'=>1.5,'benchmark'=>2.2],
            ['month'=>'Oct 2024','all'=>4.1,'f1'=>4.4,'f2'=>3.8,'f3'=>4.5,'f4'=>3.9,'f5'=>3.7,'benchmark'=>3.8],
            ['month'=>'Nov 2024','all'=>3.5,'f1'=>3.8,'f2'=>3.2,'f3'=>3.9,'f4'=>3.3,'f5'=>3.1,'benchmark'=>3.3],
            ['month'=>'Dec 2024','all'=>2.2,'f1'=>2.4,'f2'=>1.9,'f3'=>2.5,'f4'=>2.0,'f5'=>1.8,'benchmark'=>2.6],
            ['month'=>'Jan 2025','all'=>1.8,'f1'=>2.0,'f2'=>1.5,'f3'=>2.1,'f4'=>1.6,'f5'=>1.4,'benchmark'=>2.0],
            ['month'=>'Feb 2025','all'=>2.9,'f1'=>3.2,'f2'=>2.6,'f3'=>3.3,'f4'=>2.7,'f5'=>2.5,'benchmark'=>2.7],
            ['month'=>'Mar 2025','all'=>3.4,'f1'=>3.7,'f2'=>3.1,'f3'=>3.8,'f4'=>3.2,'f5'=>3.0,'benchmark'=>3.1],
            ['month'=>'Apr 2025','all'=>2.6,'f1'=>2.9,'f2'=>2.3,'f3'=>3.0,'f4'=>2.4,'f5'=>2.2,'benchmark'=>2.8],
        ];
    }

    public function getKpis(): array
    {
        $sponsors = $this->getSponsors();
        $totalAum = array_sum(array_column($sponsors, 'aum'));
        $funds = $this->getFunds();
        $allDonors = array_unique(array_merge(...array_column($funds, 'donors')));
        $totalDonors = count($allDonors);
        $tickets = $this->getTickets();
        $openTickets = count(array_filter($tickets, fn($t) => in_array($t['status'], ['open'])));
        $grants = $this->getRecommendations();
        $pendingGrants = array_sum(array_column(
            array_filter($grants, fn($g) => in_array($g['status'], ['submitted','approved'])),
            'amount'
        ));

        return [
            ['label'=>'Total AUM',      'value'=>'$'.number_format($totalAum/1000000,2).'M','icon'=>'fa-dollar-sign',       'color'=>'teal',  'trend'=>'+8.3%','trendDir'=>'up',   'route'=>'institutional'],
            ['label'=>'Total Clients',   'value'=>(string)$totalDonors,                       'icon'=>'fa-users',             'color'=>'green', 'trend'=>'+4.3%','trendDir'=>'up',   'route'=>'clients'],
            ['label'=>'Open Tickets',   'value'=>(string)$openTickets,                        'icon'=>'fa-ticket',            'color'=>'red',   'trend'=>'+16.7%','trendDir'=>'down','route'=>'service-requests','query'=>['status'=>'open']],
            ['label'=>'Pending Grants', 'value'=>'$'.number_format($pendingGrants/1000,0).'K','icon'=>'fa-hand-holding-heart','color'=>'amber', 'trend'=>'-5.1%','trendDir'=>'down', 'route'=>'recommendations'],
        ];
    }
}




