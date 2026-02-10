@extends('public.layouts.master')

@section('title', 'Sık Sorulan Sorular - ' . ($settings->siteadi ?? 'Villa Kiralama'))

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Sık Sorulan Sorular</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
                <li class="breadcrumb-item active">S.S.S</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if($faqs->count() > 0)
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                    <div class="accordion-item border-0 shadow-sm mb-3" style="border-radius: 15px; overflow: hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                <strong>{{ $faq->soru }}</strong>
                            </button>
                        </h2>
                        <div id="faq{{ $faq->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                {!! $faq->cevap !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Henüz sık sorulan soru eklenmemiş.
                </div>
                @endif
                
                <div class="text-center mt-5">
                    <p class="text-muted mb-3">Başka sorularınız mı var?</p>
                    <a href="{{ route('contact') }}" class="btn btn-success">
                        <i class="fas fa-envelope me-2"></i>Bize Ulaşın
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
