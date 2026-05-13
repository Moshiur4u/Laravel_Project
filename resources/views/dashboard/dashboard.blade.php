@section('content')
<div class="py-4 container-fluid">
    <div class="mb-4 row">
        <div class="col-md-12">
            <h1 class="mb-4 h3">Pharmacy Management Dashboard</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="mb-4 row">
        <div class="col-md-3">
            <div class="py-2 shadow card border-left-primary h-100">
                <div class="card-body">
                    <div class="mb-1 text-primary font-weight-bold text-uppercase">Total Medicines</div>
                    <div class="mb-0 h3">{{ $total_medicines ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="py-2 shadow card border-left-success h-100">
                <div class="card-body">
                    <div class="mb-1 text-success font-weight-bold text-uppercase">Total Sales</div>
                    <div class="mb-0 h3">${{ $total_sales ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="py-2 shadow card border-left-warning h-100">
                <div class="card-body">
                    <div class="mb-1 text-warning font-weight-bold text-uppercase">Low Stock Items</div>
                    <div class="mb-0 h3">{{ $low_stock ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="py-2 shadow card border-left-danger h-100">
                <div class="card-body">
                    <div class="mb-1 text-danger font-weight-bold text-uppercase">Expired Items</div>
                    <div class="mb-0 h3">{{ $expired_items ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mb-4 row">
        <div class="col-md-6">
            <div class="shadow card">
                <div class="text-white card-header bg-primary">
                    <h6 class="m-0">Sales This Month</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="shadow card">
                <div class="text-white card-header bg-primary">
                    <h6 class="m-0">Medicine Categories</h6>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Low Stock -->
    <div class="row">
        <div class="col-md-6">
            <div class="shadow card">
                <div class="text-white card-header bg-primary">
                    <h6 class="m-0">Recent Transactions</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_transactions ?? [] as $transaction)
                                    <tr>
                                        <td>{{ $transaction->medicine_name ?? 'N/A' }}</td>
                                        <td>{{ $transaction->quantity ?? 0 }}</td>
                                        <td>${{ $transaction->amount ?? 0 }}</td>
                                        <td>{{ $transaction->created_at->format('M d, Y') ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No transactions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="shadow card">
                <div class="text-white card-header bg-warning">
                    <h6 class="m-0">Low Stock Alerts</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Current Stock</th>
                                    <th>Min Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($low_stock_items ?? [] as $item)
                                    <tr class="table-warning">
                                        <td>{{ $item->name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity ?? 0 }}</td>
                                        <td>{{ $item->minimum_level ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">All items well stocked</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-danger {
        border-left: 0.25rem solid #e74c3c !important;
    }
    .text-primary { color: #4e73df; }
    .text-success { color: #1cc88a; }
    .text-warning { color: #f6c23e; }
    .text-danger { color: #e74c3c; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Sales',
                data: [1200, 1900, 1600, 2400],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            }
        }
    });

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Antibiotics', 'Pain Relief', 'Cold & Flu', 'Vitamins', 'Others'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74c3c', '#858796']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'bottom' }
            }
        }
    });
</script>
@endsection
