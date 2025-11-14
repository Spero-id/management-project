@props(['delivery' => []])

<div class="table-responsive">
    {{-- <x-datatable :url="" /> --}}
    <x-datatable id="projects-table" title="Projects" url="{{ route('prospect-status.datatable') }}" :columns="['name', 'persentage', 'color']"
         />
    {{-- <x-datatable :url="route('prospect-status.datatable')" :columns="[ 'name', 'persentage', 'color']" /> --}}
    {{-- <table class="table table-vcenter card-table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Brand</th>
                <th>Model / Type</th>
                <th>QTY</th>
                <th>EAD (Estimation Arrival Date)</th>
                <th>Status</th>
                <th>ROBOT</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($delivery as $index => $item)
                <tr>
                    <td class="text-muted">{{ $index + 1 }}</td>
                    <td class="fw-bold">
                        <span class="badge bg-blue text-white">{{ $item['brand'] ?? '-' }}</span>
                    </td>
                    <td>{{ $item['model_type'] ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge bg-blue-lt">{{ $item['qty'] ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="text-muted">{{ $item['ead'] ?? '-' }}</span>
                    </td>
                    <td>
                        @php
                            $statusClasses = [
                                'indent' => 'bg-primary',
                                'ready stock' => 'bg-success',
                                'order sebagian' => 'bg-warning',
                                'not stok' => 'bg-danger',
                            ];
                            $statusClass = $statusClasses[strtolower($item['status'] ?? '')] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }} text-white">
                            {{ ucfirst($item['status'] ?? '-') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="progress progress-sm" role="progressbar">
                            @php
                                $robotValue = (int)str_replace('%', '', $item['robot'] ?? '0');
                            @endphp
                            <div class="progress-bar"
                                style="width: {{ $robotValue }}%">
                            </div>
                        </div>
                        <span class="text-muted small">{{ $item['robot'] ?? '0%' }}</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-list flex-nowrap">
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-primary"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path
                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-danger"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path
                                        d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        No delivery data available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table> --}}
</div>
