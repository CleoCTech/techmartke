<template>
  <Head title="Dashboard" />

  <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">

    <!-- Welcome -->
    <div class="relative bg-gradient-to-r from-ablue to-blue-700 rounded-xl p-5 sm:p-6 mb-6 overflow-hidden">
      <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
        <svg width="240" height="140" viewBox="0 0 240 140" fill="none">
          <circle cx="200" cy="20" r="80" fill="white"/>
          <circle cx="160" cy="100" r="60" fill="white"/>
        </svg>
      </div>
      <div class="relative">
        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">{{ salutation }}, {{ $page.props.auth.user.name }}</h1>
        <p class="text-blue-100 text-sm">Here's what's happening with your store today</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <!-- Total Products -->
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-5">
        <div class="flex items-center justify-between mb-3">
          <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-ablue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
              <line x1="7" y1="7" x2="7.01" y2="7" />
            </svg>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">{{ totalProducts }}</div>
        <div class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Total Products</div>
      </div>

      <!-- Total Orders -->
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-5">
        <div class="flex items-center justify-between mb-3">
          <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
              <line x1="3" y1="6" x2="21" y2="6" />
              <path d="M16 10a4 4 0 01-8 0" />
            </svg>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">{{ totalOrders }}</div>
        <div class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Total Orders</div>
      </div>

      <!-- Revenue -->
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-5">
        <div class="flex items-center justify-between mb-3">
          <div class="w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="1" x2="12" y2="23" />
              <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
            </svg>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">KSh {{ formatNumber(totalRevenue) }}</div>
        <div class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Revenue</div>
      </div>

      <!-- Pending Orders -->
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-5">
        <div class="flex items-center justify-between mb-3">
          <div class="w-10 h-10 rounded-lg bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">{{ pendingOrders }}</div>
        <div class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Pending Orders</div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
      <Link :href="route('admin.products.create')"
        class="flex items-center gap-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 hover:shadow-md transition-shadow group">
        <div class="w-9 h-9 rounded-lg bg-ablue/10 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4 text-ablue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </div>
        <span class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-ablue transition-colors">Add Product</span>
      </Link>
      <Link :href="route('admin.orders')"
        class="flex items-center gap-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 hover:shadow-md transition-shadow group">
        <div class="w-9 h-9 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
            <rect x="9" y="3" width="6" height="4" rx="1" />
          </svg>
        </div>
        <span class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-green-600 transition-colors">View Orders</span>
      </Link>
      <Link :href="route('admin.vip')"
        class="flex items-center gap-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 hover:shadow-md transition-shadow group">
        <div class="w-9 h-9 rounded-lg bg-yellow-500/10 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
          </svg>
        </div>
        <span class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-yellow-600 transition-colors">VIP Members</span>
      </Link>
      <Link :href="route('admin.inquiry')"
        class="flex items-center gap-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 hover:shadow-md transition-shadow group">
        <div class="w-9 h-9 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
          </svg>
        </div>
        <span class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-purple-600 transition-colors">Messages</span>
      </Link>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-12 gap-6 mb-6">

      <!-- Recent Orders -->
      <div class="col-span-full lg:col-span-8 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
          <h2 class="font-semibold text-slate-800 dark:text-slate-100">Recent Orders</h2>
          <Link :href="route('admin.orders')" class="text-sm font-medium text-ablue hover:text-blue-700">View all</Link>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm" v-if="recentOrders.length > 0">
            <thead>
              <tr class="border-b border-slate-200 dark:border-slate-700">
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Order #</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden sm:table-cell">Customer</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Total</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden md:table-cell">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in recentOrders" :key="order.id" class="border-b border-slate-100 dark:border-slate-700/50 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                <td class="px-5 py-3">
                  <Link :href="route('admin.orders.show', order.id)" class="font-medium text-ablue hover:text-blue-700">
                    #{{ order.id }}
                  </Link>
                </td>
                <td class="px-5 py-3 text-slate-600 dark:text-slate-300 hidden sm:table-cell">
                  {{ order.user?.name || 'Guest' }}
                </td>
                <td class="px-5 py-3 text-right font-medium text-slate-800 dark:text-slate-100">
                  KSh {{ formatNumber(order.total_amount) }}
                </td>
                <td class="px-5 py-3 text-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="statusClass(order.status)">
                    {{ order.status }}
                  </span>
                </td>
                <td class="px-5 py-3 text-right text-slate-500 dark:text-slate-400 hidden md:table-cell">
                  {{ formatDate(order.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
          <div v-else class="px-5 py-12 text-center">
            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
              <line x1="3" y1="6" x2="21" y2="6" />
              <path d="M16 10a4 4 0 01-8 0" />
            </svg>
            <p class="text-slate-500 dark:text-slate-400 text-sm">No orders yet</p>
            <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">Orders will appear here once customers start shopping</p>
          </div>
        </div>
      </div>

      <!-- Low Stock Alerts -->
      <div class="col-span-full lg:col-span-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
          <h2 class="font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
            </svg>
            Low Stock
          </h2>
          <Link :href="route('admin.products')" class="text-sm font-medium text-ablue hover:text-blue-700">Manage</Link>
        </div>
        <div class="p-3">
          <div v-if="lowStockProducts.length > 0" class="space-y-2">
            <div v-for="variant in lowStockProducts.slice(0, 5)" :key="variant.id"
              class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-700/30">
              <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ variant.product?.name || 'Product' }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ variant.sku }}</p>
              </div>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold ml-3 shrink-0"
                :class="variant.stock_quantity <= 2 ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400'">
                {{ variant.stock_quantity }} left
              </span>
            </div>
            <div v-if="lowStockProducts.length > 5" class="text-center pt-2">
              <Link :href="route('admin.products')" class="text-xs font-medium text-ablue hover:text-blue-700">
                +{{ lowStockProducts.length - 5 }} more items — View all
              </Link>
            </div>
          </div>
          <div v-else class="py-8 text-center">
            <svg class="w-10 h-10 text-green-300 dark:text-green-600 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
              <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <p class="text-slate-500 dark:text-slate-400 text-sm">All stocked up!</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Trade-In Requests -->
    <div class="grid grid-cols-12 gap-6 mb-6">
      <div class="col-span-full bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 1l4 4-4 4" /><path d="M3 11V9a4 4 0 014-4h14" /><path d="M7 23l-4-4 4-4" /><path d="M21 13v2a4 4 0 01-4 4H3" />
            </svg>
            <h2 class="font-semibold text-slate-800 dark:text-slate-100">Trade-In Requests</h2>
            <span v-if="tradeIns?.pending" class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 text-[10px] font-bold rounded-full px-2 py-0.5">
              {{ tradeIns.pending }} pending
            </span>
          </div>
          <Link :href="route('admin.trade-in')" class="text-sm font-medium text-ablue hover:text-blue-700">View all</Link>
        </div>

        <!-- Stats row -->
        <div class="grid grid-cols-3 border-b border-slate-200 dark:border-slate-700">
          <div class="px-5 py-4 text-center border-r border-slate-200 dark:border-slate-700">
            <div class="text-2xl font-bold" :class="tradeIns?.pending ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600'">{{ tradeIns?.pending || 0 }}</div>
            <div class="text-xs text-slate-500 mt-1">Pending Review</div>
          </div>
          <div class="px-5 py-4 text-center border-r border-slate-200 dark:border-slate-700">
            <div class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ tradeIns?.total || 0 }}</div>
            <div class="text-xs text-slate-500 mt-1">Total Requests</div>
          </div>
          <div class="px-5 py-4 text-center">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">KSh {{ formatNumber(tradeIns?.totalValue || 0) }}</div>
            <div class="text-xs text-slate-500 mt-1">Total Value</div>
          </div>
        </div>

        <!-- Recent list -->
        <div v-if="tradeIns?.recent?.length" class="divide-y divide-slate-100 dark:divide-slate-700/50">
          <Link
            v-for="t in tradeIns.recent"
            :key="t.id"
            :href="`/admin/trade-in/${t.uuid}`"
            class="flex items-center justify-between gap-3 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition"
          >
            <div class="flex items-center gap-3 min-w-0 flex-1">
              <div class="w-8 h-8 rounded-lg bg-green-50 dark:bg-green-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="5" y="2" width="14" height="20" rx="2" /><line x1="12" y1="18" x2="12" y2="18" />
                </svg>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">
                  {{ t.product?.name || t.custom_device_name || 'Unknown device' }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ t.customer_name }} · {{ t.customer_phone }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold capitalize"
                :class="{
                  'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400': t.status === 'pending',
                  'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400': t.status === 'quoted',
                  'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400': t.status === 'accepted' || t.status === 'completed',
                  'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400': t.status === 'rejected',
                }">
                {{ t.status }}
              </span>
              <span class="text-sm font-bold text-slate-800 dark:text-slate-200">KSh {{ formatNumber(t.estimated_value || 0) }}</span>
            </div>
          </Link>
        </div>
        <div v-else class="px-5 py-8 text-center">
          <p class="text-sm text-slate-400">No trade-in requests yet</p>
        </div>
      </div>
    </div>

    <!-- Search Analytics -->
    <div class="grid grid-cols-12 gap-6 mb-6">

      <!-- Search Stats Cards -->
      <div class="col-span-full">
        <div class="flex items-center gap-2 mb-4">
          <svg class="w-5 h-5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          <h2 class="font-semibold text-slate-800 dark:text-slate-100 text-lg">Search Analytics</h2>
          <span class="text-xs bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400 px-2 py-0.5 rounded-full font-medium">AI Search</span>
        </div>
      </div>

      <!-- Mini stat cards -->
      <div class="col-span-6 sm:col-span-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ formatNumber(searchAnalytics.totalSearches) }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Total Searches</div>
      </div>
      <div class="col-span-6 sm:col-span-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ searchAnalytics.todaySearches }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Today</div>
      </div>
      <div class="col-span-6 sm:col-span-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ searchAnalytics.byDevice?.mobile || 0 }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Mobile Searches</div>
      </div>
      <div class="col-span-6 sm:col-span-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ searchAnalytics.zeroResultSearches?.length || 0 }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Zero-Result Queries</div>
      </div>

      <!-- Top Searches + Zero Results side by side -->
      <div class="col-span-full lg:col-span-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
          <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-sm">Top Searches</h3>
        </div>
        <div class="p-3">
          <div v-if="searchAnalytics.topSearches?.length" class="space-y-1.5">
            <div v-for="(item, i) in searchAnalytics.topSearches" :key="i"
              class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/30">
              <div class="flex items-center gap-3 min-w-0">
                <span class="w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 shrink-0">{{ i + 1 }}</span>
                <span class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ item.query }}</span>
              </div>
              <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-full shrink-0 ml-2">{{ item.count }}×</span>
            </div>
          </div>
          <p v-else class="text-sm text-slate-400 text-center py-6">No searches yet</p>
        </div>
      </div>

      <!-- Zero Result Searches (demand signals) -->
      <div class="col-span-full lg:col-span-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
          <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
            </svg>
            Unmet Demand (0 results)
          </h3>
        </div>
        <div class="p-3">
          <div v-if="searchAnalytics.zeroResultSearches?.length" class="space-y-1.5">
            <div v-for="(item, i) in searchAnalytics.zeroResultSearches" :key="i"
              class="flex items-center justify-between p-2.5 rounded-lg bg-amber-50/50 dark:bg-amber-500/5">
              <span class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ item.query }}</span>
              <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-500/20 px-2 py-0.5 rounded-full shrink-0 ml-2">{{ item.count }}×</span>
            </div>
          </div>
          <div v-else class="py-6 text-center">
            <svg class="w-8 h-8 text-green-300 dark:text-green-600 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 11-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <p class="text-sm text-slate-400">All searches returned results!</p>
          </div>
        </div>
      </div>

      <!-- Recent Searches Feed -->
      <div class="col-span-full bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
          <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-sm">Recent Searches (Live)</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-200 dark:border-slate-700">
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Query</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Type</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Results</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden sm:table-cell">Device</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden md:table-cell">Time</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="search in searchAnalytics.recentSearches" :key="search.id"
                class="border-b border-slate-100 dark:border-slate-700/50 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                <td class="px-5 py-3 font-medium text-slate-800 dark:text-slate-200 max-w-[200px] truncate">{{ search.query }}</td>
                <td class="px-5 py-3 text-center">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold"
                    :class="{
                      'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400': search.search_type === 'smart',
                      'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400': search.search_type === 'budget',
                      'bg-slate-100 text-slate-600 dark:bg-slate-600 dark:text-slate-300': search.search_type === 'keyword',
                    }">
                    {{ search.search_type }}
                  </span>
                </td>
                <td class="px-5 py-3 text-center">
                  <span :class="search.results_count === 0 ? 'text-red-500 font-bold' : 'text-slate-600 dark:text-slate-300'">
                    {{ search.results_count }}
                  </span>
                </td>
                <td class="px-5 py-3 text-center hidden sm:table-cell">
                  <span class="text-xs text-slate-400">{{ search.device_type || '-' }}</span>
                </td>
                <td class="px-5 py-3 text-right text-slate-500 dark:text-slate-400 text-xs hidden md:table-cell">
                  {{ formatDate(search.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Search Type Breakdown -->
      <div class="col-span-full sm:col-span-6 lg:col-span-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-sm mb-4">Search Type Breakdown</h3>
        <div class="space-y-3">
          <div v-for="(count, type) in searchAnalytics.byType" :key="type" class="flex items-center gap-3">
            <div class="flex-1">
              <div class="flex justify-between text-xs mb-1">
                <span class="font-medium text-slate-600 dark:text-slate-300 capitalize">{{ type }}</span>
                <span class="text-slate-400">{{ count }}</span>
              </div>
              <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all"
                  :class="{
                    'bg-indigo-500': type === 'smart',
                    'bg-blue-500': type === 'budget',
                    'bg-slate-400': type === 'keyword',
                  }"
                  :style="{ width: (count / searchAnalytics.totalSearches * 100) + '%' }"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Device Breakdown -->
      <div class="col-span-full sm:col-span-6 lg:col-span-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-sm mb-4">Device Breakdown</h3>
        <div class="space-y-3">
          <div v-for="(count, device) in searchAnalytics.byDevice" :key="device" class="flex items-center gap-3">
            <div class="flex-1">
              <div class="flex justify-between text-xs mb-1">
                <span class="font-medium text-slate-600 dark:text-slate-300 capitalize">{{ device }}</span>
                <span class="text-slate-400">{{ count }}</span>
              </div>
              <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all"
                  :class="{
                    'bg-green-500': device === 'mobile',
                    'bg-blue-500': device === 'desktop',
                    'bg-purple-500': device === 'tablet',
                  }"
                  :style="{ width: (count / searchAnalytics.totalSearches * 100) + '%' }"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Daily Volume (simple bar chart) -->
      <div class="col-span-full lg:col-span-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-sm mb-4">Daily Search Volume (14d)</h3>
        <div v-if="Object.keys(searchAnalytics.dailyVolume || {}).length" class="flex items-end gap-1 h-24">
          <div
            v-for="(count, date) in searchAnalytics.dailyVolume"
            :key="date"
            class="flex-1 bg-indigo-500 dark:bg-indigo-400 rounded-t min-h-[4px] transition-all hover:bg-indigo-600 relative group cursor-pointer"
            :style="{ height: Math.max(4, (count / Math.max(...Object.values(searchAnalytics.dailyVolume))) * 96) + 'px' }"
            :title="`${date}: ${count} searches`"
          >
            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none">
              {{ count }}
            </div>
          </div>
        </div>
        <p v-else class="text-sm text-slate-400 text-center py-6">Not enough data yet</p>
      </div>
    </div>

    <!-- Bottom Row: Traffic -->
    <div class="grid grid-cols-12 gap-6">
      <WebTrafficStats class="col-span-full" />
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import WebTrafficStats from '@/Components/Dashboard/WebTrafficStats.vue'

const page = usePage();

const props = defineProps({
  totalProducts: { type: Number, default: 0 },
  totalOrders: { type: Number, default: 0 },
  totalRevenue: { type: Number, default: 0 },
  recentOrders: { type: Array, default: () => [] },
  lowStockProducts: { type: Array, default: () => [] },
  pendingOrders: { type: Number, default: 0 },
  tradeIns: { type: Object, default: () => ({ pending: 0, total: 0, totalValue: 0, recent: [] }) },
  searchAnalytics: { type: Object, default: () => ({
    totalSearches: 0, todaySearches: 0, byType: {}, byDevice: {},
    topSearches: [], zeroResultSearches: [], recentSearches: [], dailyVolume: {},
  }) },
});

const salutation = ref('');

const setSalutation = () => {
  const hour = new Date().getHours();
  if (hour >= 5 && hour < 12) salutation.value = 'Good morning';
  else if (hour >= 12 && hour < 18) salutation.value = 'Good afternoon';
  else salutation.value = 'Good evening';
};

const formatNumber = (num) => {
  if (!num) return '0';
  return Number(num).toLocaleString('en-KE');
};

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-KE', { month: 'short', day: 'numeric', year: 'numeric' });
};

const statusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400',
    processing: 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400',
    completed: 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400',
    cancelled: 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
    delivered: 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400',
  };
  return classes[status] || 'bg-slate-100 text-slate-800 dark:bg-slate-500/20 dark:text-slate-400';
};

onMounted(() => {
  setSalutation();
});
</script>
