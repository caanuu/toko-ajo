<nav class="sidebar" id="sidebarMenu">
    <div class="py-3">
        <div class="nav-section text-info">DASHBOARD</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> <span>Ringkasan</span>
        </a>

        <div class="nav-section text-success">MASTER</div>
        <a href="{{ route('categories.index') }}" class="nav-link {{ Request::is('categories*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> <span>Kategori</span>
        </a>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}">
            <i class="fas fa-truck"></i> <span>Supplier</span>
        </a>
        <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('products*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> <span>Produk</span>
        </a>

        <div class="nav-section text-warning" style="color: #fd7e14 !important;">TRANSAKSI</div>
        <a href="{{ route('purchases.index') }}" class="nav-link {{ Request::is('purchases*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> <span>Pembelian</span>
        </a>
        <a href="{{ route('sales.index') }}" class="nav-link {{ Request::is('sales*') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i> <span>Penjualan</span>
        </a>
        <a href="{{ route('adjustments.index') }}" class="nav-link {{ Request::is('adjustments*') ? 'active' : '' }}">
            <i class="fas fa-sliders-h"></i> <span>Penyesuaian Stok</span>
        </a>

        <div class="nav-section text-purple" style="color: #6f42c1;">LAPORAN</div>
        <a href="{{ route('stock-card.index') }}" class="nav-link {{ Request::is('stock-card*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> <span>Kartu Stok</span>
        </a>
        <a href="{{ route('purchases.index') }}" class="nav-link {{ Request::is('purchases*') && !Request::is('purchases/create') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> <span>Pembelian</span>
        </a>
        <a href="{{ route('sales.index') }}" class="nav-link {{ Request::is('sales*') && !Request::is('sales/create') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> <span>Penjualan</span>
        </a>
        <a href="{{ route('min-stock.index') }}" class="nav-link {{ Request::is('min-stock*') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle"></i> <span>Stok Minimum</span>
        </a>

        <div class="nav-section text-secondary">PENGATURAN</div>
        <a href="{{ route('settings.index') }}" class="nav-link {{ Request::is('settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> <span>Pengaturan</span>
        </a>
    </div>
</nav>
