<?php
// Get data from the controller
$stats = $stats ?? [
    'total_orders' => 0,
    'total_revenue' => 0,
    'total_customers' => 0,
    'total_products' => 0,
    'low_stock_count' => 0,
    'pending_orders' => 0,
    'completed_orders' => 0,
    'monthly_sales' => []
];

$recentOrders = $recentOrders ?? [];

// Format numbers
$formattedRevenue = number_format($stats['total_revenue'], 0);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div>
            <a href="/admin/reports/sales" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50 me-1"></i> Generate Sales Report
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $formattedRevenue ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_orders'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_customers'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Products (Low Stock: <?= $stats['low_stock_count'] ?>)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_products'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Cards -->
    <div class="row">
        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pending Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['pending_orders'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-primary stretched-link" href="/admin/orders?status=pending">View Details</a>
                    <div class="small text-primary"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Processing Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Processing Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['processing_orders'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-primary stretched-link" href="/admin/orders?status=processing">View Details</a>
                    <div class="small text-primary"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Shipped Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Shipped Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['shipped_orders'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-primary stretched-link" href="/admin/orders?status=shipped">View Details</a>
                    <div class="small text-primary"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['completed_orders'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-primary stretched-link" href="/admin/orders?status=completed">View Details</a>
                    <div class="small text-primary"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Sales Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Export Options:</div>
                            <a class="dropdown-item" href="/admin/reports/sales/monthly/pdf">Export PDF</a>
                            <a class="dropdown-item" href="/admin/reports/sales/monthly/csv">Export CSV</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/admin/reports/sales">View Detailed Reports</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">View Options:</div>
                            <a class="dropdown-item" href="/admin/reports/products">View All Products Report</a>
                            <a class="dropdown-item" href="/admin/reports/products/low-stock">View Low Stock</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2" id="product-legends">
                            <!-- Product legends will be inserted here by JS -->
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
            <a href="/admin/orders" class="btn btn-sm btn-primary">View All Orders</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentOrders)): ?>
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No recent orders found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td>
                                        <a href="/admin/orders/edit/<?= $order['id'] ?>" class="fw-bold text-decoration-none">
                                            #<?= str_pad($order['id'], 8, '0', STR_PAD_LEFT) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= getStatusColor($order['status']) ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= $order['total_items'] ?></td>
                                    <td><?= number_format($order['total'], 0) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="/admin/orders/edit/<?= $order['id'] ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/admin/orders/view/<?= $order['id'] ?>" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-success order-status-btn" 
                                                    data-order-id="<?= $order['id'] ?>" 
                                                    data-current-status="<?= $order['status'] ?>">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="orderStatusModal" tabindex="-1" aria-labelledby="orderStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderStatusModalLabel">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderStatusForm" action="/admin/orders/update-status" method="post">
                    <input type="hidden" id="order_id" name="order_id" value="">
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 shipping-fields" style="display: none;">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number">
                    </div>
                    
                    <div class="mb-3 shipping-fields" style="display: none;">
                        <label for="tracking_url" class="form-label">Tracking URL</label>
                        <input type="url" class="form-control" id="tracking_url" name="tracking_url">
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="notify_customer" name="notify_customer" value="1" checked>
                        <label class="form-check-label" for="notify_customer">Notify customer</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="orderStatusForm" class="btn btn-primary">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
// Helper function to get status color
function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'processing': 'info',
        'shipped': 'primary',
        'delivered': 'success',
        'cancelled': 'danger',
        'refunded': 'secondary'
    };
    return colors[status] || 'secondary';
}

document.addEventListener('DOMContentLoaded', function() {
    // Charts
    if (typeof Chart !== 'undefined') {
        // Monthly Sales Chart
        const monthlySalesCtx = document.getElementById('monthlySalesChart');
        if (monthlySalesCtx) {
            const monthlyData = <?= json_encode($stats['monthly_sales'] ?? []) ?>;
            
            const labels = monthlyData.map(item => item.month);
            const salesData = monthlyData.map(item => item.total);
            
            new Chart(monthlySalesCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Monthly Sales',
                        lineTension: 0.3,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: salesData
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        },
                        y: {
                            ticks: {
                                maxTicksLimit: 5,
                                callback: function(value) {
                                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            },
                            grid: {
                                color: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                borderDashOffset: [2]
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleColor: "#6e707e",
                            titleFontSize: 14,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: ' + context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Top Products Chart
        const topProductsCtx = document.getElementById('topProductsChart');
        if (topProductsCtx) {
            const productsData = <?= json_encode($stats['top_products'] ?? []) ?>;
            
            const productNames = productsData.map(item => item.name);
            const productSales = productsData.map(item => item.sales);
            
            // Generate random colors
            const backgroundColors = productsData.map(() => {
                return `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.8)`;
            });
            
            new Chart(topProductsCtx, {
                type: 'doughnut',
                data: {
                    labels: productNames,
                    datasets: [{
                        data: productSales,
                        backgroundColor: backgroundColors,
                        hoverBackgroundColor: backgroundColors.map(color => color.replace('0.8', '1')),
                        hoverBorderColor: "rgba(234, 236, 244, 1)"
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10
                        }
                    }
                }
            });
            
            // Generate product legends
            const legendsContainer = document.getElementById('product-legends');
            if (legendsContainer) {
                productNames.forEach((name, index) => {
                    const color = backgroundColors[index];
                    const legend = document.createElement('span');
                    legend.classList.add('me-2', 'mb-2', 'd-inline-block');
                    legend.innerHTML = `
                        <i class="fas fa-circle" style="color: ${color}"></i> ${name.length > 15 ? name.substring(0, 15) + '...' : name}
                    `;
                    legendsContainer.appendChild(legend);
                });
            }
        }
    }
    
    // Order Status Modal
    const orderStatusBtns = document.querySelectorAll('.order-status-btn');
    const orderStatusModal = document.getElementById('orderStatusModal');
    const orderIdInput = document.getElementById('order_id');
    const statusSelect = document.getElementById('status');
    const shippingFields = document.querySelectorAll('.shipping-fields');
    
    orderStatusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const currentStatus = this.dataset.currentStatus;
            
            orderIdInput.value = orderId;
            statusSelect.value = currentStatus;
            
            // Show/hide shipping fields based on status
            toggleShippingFields(currentStatus);
            
            const modal = new bootstrap.Modal(orderStatusModal);
            modal.show();
        });
    });
    
    // Toggle shipping fields based on status
    statusSelect.addEventListener('change', function() {
        toggleShippingFields(this.value);
    });
    
    function toggleShippingFields(status) {
        const showFields = status === 'shipped';
        
        shippingFields.forEach(field => {
            field.style.display = showFields ? 'block' : 'none';
        });
    }
});
</script> 