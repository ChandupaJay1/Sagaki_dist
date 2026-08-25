import sys

with open('resources/views/index.blade.php', 'r') as f:
    content = f.read()

# 1. Update Total Revenue
content = content.replace('Rs. 0.00', 'Rs. {{ number_format($totalRevenue ?? 0, 2) }}')

# 2. Dropdown Replacement Template
dropdown_content1 = '''<div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-light border-0 px-3 rounded-pill fw-semibold text-capitalize"
                                             data-bs-toggle="dropdown" aria-expanded="false">
                                             {{ $filter }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="?filter=daily" class="dropdown-item py-2 {{ $filter == 'daily' ? 'active' : '' }}">Daily</a>
                                             <a href="?filter=weekly" class="dropdown-item py-2 {{ $filter == 'weekly' ? 'active' : '' }}">Weekly</a>
                                             <a href="?filter=monthly" class="dropdown-item py-2 {{ $filter == 'monthly' ? 'active' : '' }}">Monthly</a>
                                             <a href="?filter=yearly" class="dropdown-item py-2 {{ $filter == 'yearly' ? 'active' : '' }}">Yearly</a>
                                        </div>
                                   </div>'''

dropdown_content2 = '''<div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-link text-decoration-none text-muted fw-bold text-capitalize"
                                             data-bs-toggle="dropdown" aria-expanded="false">{{ $filter }}</a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="?filter=daily" class="dropdown-item py-2 {{ $filter == 'daily' ? 'active' : '' }}">Daily</a>
                                             <a href="?filter=weekly" class="dropdown-item py-2 {{ $filter == 'weekly' ? 'active' : '' }}">Weekly</a>
                                             <a href="?filter=monthly" class="dropdown-item py-2 {{ $filter == 'monthly' ? 'active' : '' }}">Monthly</a>
                                             <a href="?filter=yearly" class="dropdown-item py-2 {{ $filter == 'yearly' ? 'active' : '' }}">Yearly</a>
                                        </div>
                                   </div>'''

# Replace Dropdown 1 (Revenue)
content = content.replace('''<div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-light border-0 px-3 rounded-pill fw-semibold"
                                             data-bs-toggle="dropdown" aria-expanded="false">
                                             Monthly
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>''', dropdown_content1)

# Replace Dropdown 2 (Daily Delivery)
content = content.replace('''<div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-link text-decoration-none text-muted fw-bold"
                                             data-bs-toggle="dropdown" aria-expanded="false">
                                             Weekly
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>''', dropdown_content2)

# Replace Dropdown 3 (Orders Overview)
content = content.replace('''<div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-link text-decoration-none text-muted fw-bold"
                                             data-bs-toggle="dropdown" aria-expanded="false">Weekly</a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>''', dropdown_content2)

# Replace Dropdown 4 (Recent Deliveries)
content = content.replace('''<div class="dropdown">
                                        <a href="#"
                                             class="dropdown-toggle btn btn-sm btn-light border-0 px-3 rounded-pill fw-semibold"
                                             data-bs-toggle="dropdown" aria-expanded="false">
                                             Daily
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>''', dropdown_content1)

# Now, rewrite the Recent Deliveries table.
import re
table_replacement = '''<tbody>
                                                  @forelse($recentDeliveries as $delivery)
                                                  <tr>
                                                       <td class="ps-4 fw-medium text-dark-emphasis">{{ \Carbon\Carbon::parse($delivery->date)->format('Y-m-d') }}</td>
                                                       <td class="text-muted small">{{ $delivery->payment_method ?? 'Unknown' }}</td>
                                                       <td>
                                                            @if(strtolower($delivery->status) == 'success' || strtolower($delivery->status) == 'paid')
                                                                <span class="badge-soft bg-success-subtle text-success">
                                                                    <i class="ri-checkbox-circle-fill me-1"></i> {{ ucfirst($delivery->status) }}
                                                                </span>
                                                            @elseif(strtolower($delivery->status) == 'pending')
                                                                <span class="badge-soft bg-warning-subtle text-warning">
                                                                    <i class="ri-time-fill me-1"></i> {{ ucfirst($delivery->status) }}
                                                                </span>
                                                            @else
                                                                <span class="badge-soft bg-danger-subtle text-danger">
                                                                    <i class="ri-close-circle-fill me-1"></i> {{ ucfirst($delivery->status) }}
                                                                </span>
                                                            @endif
                                                       </td>
                                                       <td class="pe-4 text-end fw-bold text-dark-emphasis">{{ number_format($delivery->total_amount, 2) }}</td>
                                                  </tr>
                                                  @empty
                                                  <tr>
                                                      <td colspan="4" class="text-center py-4 text-muted">No recent deliveries found.</td>
                                                  </tr>
                                                  @endforelse
                                             </tbody>'''

content = re.sub(r'<tbody>.*?</tbody>', table_replacement.replace('\\', '\\\\'), content, flags=re.DOTALL)

with open('resources/views/index.blade.php', 'w') as f:
    f.write(content)
print('Blade file updated.')
