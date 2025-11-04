@props(['status' => 'draft', 'class' => ''])
@php
    $statusColors = [
        // Quotation status
        'draft' => 'bg-secondary',
        'sent' => 'bg-info',
        'approved' => 'bg-success',
        'rejected' => 'bg-danger',
        'expired' => 'bg-warning',

        // Prospect status
        'Prospecting' => 'bg-secondary',
        'Qualification' => 'bg-info',
        'Proposal / Quotation' => 'bg-warning',
        'Negotiation' => 'bg-purple',
        'Closing' => 'bg-success',
        'LOST' => 'bg-danger',
    ];
    
    if (isset($statusColors[$status])) {
        $color = $statusColors[$status];
    } else {
        $prospectStatus = App\Models\ProspectStatus::where('name', $status)->first();
        $color = $prospectStatus && $prospectStatus->color ? $prospectStatus->color : 'bg-secondary';
    }
    
    $displayText = ucfirst(str_replace('_', ' ', $status));

@endphp



<span class="badge {{ $color }} text-white {{ $class }}">
    {{ $displayText }}
</span>
