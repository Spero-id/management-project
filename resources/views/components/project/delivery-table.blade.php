@props(['projectId'])

<div class="table-responsive">
    <x-datatable 
        id="delivery-items-table" 
        title="Status pengiriman barang" 
        url="{{ route('project-order.delivery-datatable', ['project_id' => $projectId]) }}" 
        :columns="['brand', 'model_type', 'qty', 'delivered', 'remaining', 'status']"
    />
</div>
