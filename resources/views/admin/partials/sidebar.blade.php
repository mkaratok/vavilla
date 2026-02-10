@php
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light ms-2"><b>Yönetim</b> Paneli</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-header">MENÜ</li>
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $currentRoute == 'admin.dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Ana Sayfa</p>
                    </a>
                </li>
                
                <!-- Rezervasyon Yönetimi -->
                <li class="nav-item {{ str_starts_with($currentRoute, 'admin.reservations') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ str_starts_with($currentRoute, 'admin.reservations') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>
                            Rezervasyon Yönetimi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.reservations.create') }}" class="nav-link {{ $currentRoute == 'admin.reservations.create' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rezervasyon Ekle</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reservations.index') }}" class="nav-link {{ $currentRoute == 'admin.reservations.index' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Eklenmiş Rezervasyonlar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reservations.external') }}" class="nav-link {{ $currentRoute == 'admin.reservations.external' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Harici Rezervasyonlar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reservations.calendar') }}" class="nav-link {{ $currentRoute == 'admin.reservations.calendar' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Doluluk Takvimi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Villalar -->
                <li class="nav-item {{ str_starts_with($currentRoute, 'admin.villas') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ str_starts_with($currentRoute, 'admin.villas') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Villalar
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.villas.create') }}" class="nav-link {{ $currentRoute == 'admin.villas.create' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Yeni Villa Ekle</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.villas.index') }}" class="nav-link {{ $currentRoute == 'admin.villas.index' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Eklenmiş Villalar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.villas.ordering') }}" class="nav-link {{ $currentRoute == 'admin.villas.ordering' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anasayfa Sıralama</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Bölge ve Villa Tipleri -->
                <li class="nav-item {{ str_starts_with($currentRoute, 'admin.regions') || str_starts_with($currentRoute, 'admin.villa-types') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ str_starts_with($currentRoute, 'admin.regions') || str_starts_with($currentRoute, 'admin.villa-types') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>
                            Bölge ve Villa Tipleri
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.regions.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.regions') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bölgeler</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.villa-types.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.villa-types') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Villa Tipleri</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Teknik Özellikler -->
                <li class="nav-item {{ str_starts_with($currentRoute, 'admin.features') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ str_starts_with($currentRoute, 'admin.features') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Teknik Özellikler
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.features.create') }}" class="nav-link {{ $currentRoute == 'admin.features.create' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Yeni Özellik Ekle</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.features.index') }}" class="nav-link {{ $currentRoute == 'admin.features.index' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Eklenmiş Özellikler</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Ödeme Yöntemleri -->
                <li class="nav-item {{ str_starts_with($currentRoute, 'admin.payment-methods') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ str_starts_with($currentRoute, 'admin.payment-methods') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Ödeme Yöntemleri
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.payment-methods.create') }}" class="nav-link {{ $currentRoute == 'admin.payment-methods.create' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Yeni Ödeme Yöntemi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.payment-methods.index') }}" class="nav-link {{ $currentRoute == 'admin.payment-methods.index' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ödeme Yöntemleri</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Ek Hizmetler -->
                <li class="nav-item {{ str_starts_with($currentRoute, 'admin.services') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ str_starts_with($currentRoute, 'admin.services') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-concierge-bell"></i>
                        <p>
                            Ek Hizmetler
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.services.create') }}" class="nav-link {{ $currentRoute == 'admin.services.create' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Yeni Hizmet Ekle</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.services.index') }}" class="nav-link {{ $currentRoute == 'admin.services.index' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Eklenmiş Hizmetler</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Site Yönetimi -->
                <li class="nav-header">DEĞERLENDİRME & İÇERİK</li>

                <!-- Hakkımızda -->
                <li class="nav-item">
                    <a href="{{ route('admin.about.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.about') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-info-circle"></i>
                        <p>Hakkımızda</p>
                    </a>
                </li>

                <!-- Galeri -->
                <li class="nav-item">
                    <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.gallery') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Foto Galeri</p>
                    </a>
                </li>
                
                <!-- Anasayfa Hizmetler -->
                <li class="nav-item">
                    <a href="{{ route('admin.services-home.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.services-home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-concierge-bell"></i>
                        <p>Anasayfa Hizmetler</p>
                    </a>
                </li>

                <!-- Yorumlar -->
                <li class="nav-item">
                    <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.reviews') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Misafir Yorumları</p>
                    </a>
                </li>

                <!-- Blog -->
                <li class="nav-item">
                    <a href="{{ route('admin.blog.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.blog') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-blog"></i>
                        <p>Blog Yazıları</p>
                    </a>
                </li>
                
                <li class="nav-header">AYARLAR</li>
                
                <!-- Genel Ayarlar -->
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ $currentRoute == 'admin.settings' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Genel Ayarlar</p>
                    </a>
                </li>
                
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
