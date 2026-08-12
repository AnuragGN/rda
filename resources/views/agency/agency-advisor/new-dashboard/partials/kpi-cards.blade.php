<div class="row g-3 mb-3 kpi-row align-items-stretch">
  @php
    $colors = ['teal'=>'#0891b2','green'=>'#059669','red'=>'#dc2626','amber'=>'#d97706'];
    $colorKeys = ['teal','green','red','amber'];
    $gradients = [
      'teal'  => 'linear-gradient(135deg,rgba(8,145,178,.15),rgba(8,145,178,.05))',
      'green' => 'linear-gradient(135deg,rgba(5,150,105,.15),rgba(5,150,105,.05))',
      'red'   => 'linear-gradient(135deg,rgba(220,38,38,.15),rgba(220,38,38,.05))',
      'amber' => 'linear-gradient(135deg,rgba(217,119,6,.15),rgba(217,119,6,.05))',
    ];
  @endphp
  @foreach($kpis as $i => $kpi)
  @php $c = $colorKeys[$i] ?? 'teal'; $hex = $colors[$c]; $grad = $gradients[$c]; @endphp
  <div class="col-6 col-xl-3">
    <a href="" style="text-decoration:none;display:flex;flex-direction:column;width:100%">
    <div class="kpi-card" style="border-top-color:{{ $hex }};cursor:pointer">
      <div class="kpi-top">
        <div>
          <div class="kpi-val">{{ $kpi['value'] }}</div>
          <div class="kpi-label">{{ $kpi['label'] }}</div>
        </div>
        <div class="kpi-icon" style="background:{{ $grad }}">
          <i class="fa-solid {{ $kpi['icon'] }}" style="color:{{ $hex }}"></i>
        </div>
      </div>
      <!-- <div class="kpi-trend {{ $kpi['trendDir'] }}">
        <i class="fa-solid fa-arrow-trend-{{ $kpi['trendDir'] === 'up' ? 'up' : 'down' }}"></i>
        {{ $kpi['trend'] }} vs prior period
      </div> -->
    </div>
    </a>
  </div>
  @endforeach
</div>
