@extends('admin.layouts.master')

@section('title', 'Doluluk Takvimi - Yönetim Paneli')
@section('page-title', 'Doluluk Takvimi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.reservations.index') }}">Rezervasyonlar</a></li>
<li class="breadcrumb-item active">Doluluk Takvimi</li>
@endsection

@push('styles')
<style>
 .occ-calendar-wrap { overflow-x: auto; }
 .occ-calendar-grid {
     display: grid;
     grid-template-columns: 120px repeat(var(--days), 34px);
     min-width: calc(120px + (var(--days) * 34px));
     border: 1px solid #e5e7eb;
     border-radius: 6px;
 }
 .occ-cell {
     border-right: 1px solid #e5e7eb;
     border-bottom: 1px solid #e5e7eb;
     min-height: 34px;
     display: flex;
     align-items: center;
     justify-content: center;
     font-size: 12px;
     background: #fff;
 }
 .occ-cell-header {
     background: #f8fafc;
     font-weight: 600;
 }
 .occ-villa-header,
 .occ-villa-cell {
     position: sticky;
     left: 0;
     z-index: 2;
     justify-content: flex-start;
     padding: 0 10px;
 }
 .occ-villa-header { z-index: 3; }
 .occ-villa-cell { font-weight: 600; }

 .occ-lane {
     grid-column: 1 / -1;
     display: grid;
     grid-template-columns: 240px repeat(var(--days), 34px);
 }
 .occ-lane-inner {
     grid-column: 1 / -1;
     display: grid;
     grid-template-columns: 240px repeat(var(--days), 34px);
     position: relative;
 }
 .occ-lane-inner .occ-cell { background: transparent; }

 .occ-res {
     height: 26px;
     margin: 4px 2px;
     border-radius: 4px;
     color: #fff;
     font-size: 12px;
     padding: 2px 6px;
     display: flex;
     align-items: center;
     overflow: hidden;
     white-space: nowrap;
     text-overflow: ellipsis;
     position: relative;
     z-index: 1;
     pointer-events: auto;
 }
 .occ-res:hover { z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
 .occ-res a { color: inherit; text-decoration: none; width: 100%; }

 .occ-status-0 { background: #ffc107; color: #000; }
 .occ-status-1 { background: #28a745; }
 .occ-status-2 { background: #17a2b8; }
 .occ-status-3 { background: #dc3545; }

 .occ-today { background: #fff3cd; }
</style>
@endpush

@section('content')
@php
    $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
    $todayMonth = now()->startOfMonth()->format('Y-m');
    $todayDay = now()->isSameMonth($currentMonth) ? (int) now()->day : null;
@endphp

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.reservations.calendar', ['month' => $prevMonth]) }}">
                <i class="fas fa-chevron-left"></i>
            </a>
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.reservations.calendar', ['month' => $nextMonth]) }}">
                <i class="fas fa-chevron-right"></i>
            </a>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.reservations.calendar', ['month' => $todayMonth]) }}">Bugün</a>
        </div>

        <h3 class="card-title mb-0">{{ $currentMonth->translatedFormat('F Y') }}</h3>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark"><i class="fas fa-circle"></i> Beklemede</span>
            <span class="badge bg-success"><i class="fas fa-circle"></i> Onaylandı</span>
            <span class="badge bg-info"><i class="fas fa-circle"></i> Tamamlandı</span>
            <span class="badge bg-danger"><i class="fas fa-circle"></i> İptal</span>
        </div>
    </div>

    <div class="card-body">
        <div class="occ-calendar-wrap">
            <div class="occ-calendar-grid" style="--days: {{ $daysInMonth }};">
                <div class="occ-cell occ-cell-header occ-villa-header">Villalar</div>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <div class="occ-cell occ-cell-header {{ $todayDay === $d ? 'occ-today' : '' }}">{{ $d }}</div>
                @endfor

                @foreach($calendarRows as $row)
                    @php $rowIndex = $loop->iteration + 1; @endphp
                    
                    <div class="occ-cell occ-villa-cell" style="grid-row: {{ $rowIndex }};">
                        {{ $row['villa']->baslik }}
                    </div>

                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <div class="occ-cell occ-day-cell {{ $todayDay === $d ? 'occ-today' : '' }}" style="grid-row: {{ $rowIndex }};"></div>
                    @endfor

                    @foreach($row['events'] as $event)
                        <div class="occ-res occ-status-{{ $event['status'] }}"
                             style="grid-column: {{ $event['start_day'] + 1 }} / {{ $event['end_day'] + 2 }}; grid-row: {{ $rowIndex }};"
                             title="{{ $event['title'] }} ({{ $event['start_day'] }}-{{ $event['end_day'] }})">
                            <a href="{{ $event['url'] }}">{{ $event['title'] }}</a>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
